<?php

require_once 'metrotweaks.civix.php';
use CRM_Metrotweaks_ExtensionUtil as E;

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function metrotweaks_civicrm_buildForm($formName, &$form) {
  _metrotweaks_buildForm_activity($formName, $form);
  _metrotweaks_buildForm_hideContributionFields($formName, $form);
  _metrotweaks_buildForm_unlinkContributionTabAmounts($formName, $form);
}

/**
 * Implements hook_civicrm_alterEntityRefParams().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterEntityRefParams/
 */
function metrotweaks_civicrm_alterEntityRefParams(&$params, $formName) {
  // If this is the "Soft Credit" entityref field on the Contribution form (whether
  // Create or Edit), we want to limit the results to organizations. This hook
  // lets us define that limitation, but it doesn't provide a lot of scope
  // variables to detect the exact situation. Here we make a best effort to
  // match the relevant properties of the $params array such that it only
  // happens for this specific entityRef field.

  // Define a shorthand variable for lowercased scalar members of $params
  // (strtolower() will trigger a warning on any non-scalar values, so we filter for scalar values first).
  $paramsLowerCaseScalars = array_map('strtolower', array_filter($params, 'is_scalar'));
  $paramsApiExtra = ($params['api']['extra'] ?? []);
  if (
    $formName == 'CRM_Contribute_Form_Contribution'
    && $paramsLowerCaseScalars['entity'] === 'contact'
    && $params['create'] == TRUE
    // older civicrm versions use '- none -'; newer versions use '- select Contact -'
    && ($paramsLowerCaseScalars['placeholder'] === '- none -' || $paramsLowerCaseScalars['placeholder'] === '- select contact -')
    // The 'contributor' entityRef has 'extra' => ['email' ...]; currently this is
    // the only known way to identify this field as other-than-soft-credit.
    && (!in_array('email', $paramsApiExtra))
  ) {
    $params['api']['params']['contact_type'] = 'organization';
    $params['placeholder'] = '- select Organization -';
  }
}

/**
 * Do tweaks for the Contributions Tab.
 */
function _metrotweaks_buildForm_unlinkContributionTabAmounts($formName, $form) {
  if ($formName == 'CRM_Contribute_Form_Search') {
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.metrotweaks', 'js/contributionTabTweaks.js');
  }
}

/**
 * Do tweaks for the Activity form.
 */
function _metrotweaks_buildForm_activity($formName, &$form) {
  $activityForms = [
    'CRM_Activity_Form_Activity',
  ];
  if (in_array($formName, $activityForms)) {
    $config = _metrotweaks_get_config();
    if ($activityTypesConfig = $config['activityTypesConfig'] ?? NULL) {
      $settings = [
        'metrotweaks' => [
          'activityTypesConfig' => $activityTypesConfig,
          'defaultActivityTypeId' => $form->_activityTypeId,
          'activityId' => $form->_activityId,
        ],
      ];
      CRM_Core_Resources::singleton()->addSetting($settings);
      CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.metrotweaks', 'js/activityDateTweaks.js');
    }
  }
}

/**
 * Do tweaks to hide specific fields on the Add/Edit Contribution form.
 */
function _metrotweaks_buildForm_hideContributionFields($formName, &$form) {
  $contributionForms = [
    'CRM_Contribute_Form_Contribution',
    'CRM_Contribute_Form_ContributionView',
  ];
  if (in_array($formName, $contributionForms)) {

    $settings = [
      'metrotweaks' => [
        'contributionLabelsToHide' => [],
      ],
    ];

    // Define the labels of rows that should be hidden.
    $contributionLabelsToHide = [
      'Check Number',
      'Fee Amount',
      'Net Amount',
      'Non-deductible Amount',
      'Received Into',
      'Payment Method',
      'Payment Details',
    ];
    // Pass each label through ts(). It happens that ts() in JavaScript is not
    // enough to match with Word Replaements, but doing ts() here in PHP works
    // well in that regard.
    foreach ($contributionLabelsToHide as $label) {
      $settings['metrotweaks']['contributionLabelsToHide'][] = E::ts($label);
    }
    // Pass settings to JS, which will do the hiding.
    CRM_Core_Resources::singleton()->addSetting($settings);
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.metrotweaks', 'js/contributionTweaks.js');
  }
}

/**
 * Implements hook_civicrm_post().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_post
 */
function metrotweaks_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  // On change/create of any contribution:
  if ($objectName == 'Contribution' && in_array($op, ['create', 'edit'])) {
    // Calculate discounts.
    $total_amount = (isset($objectRef->total_amount) ? $objectRef->total_amount : 0);
    $discount3per = $total_amount - ($total_amount * .03);
    $discount5per = $total_amount - ($total_amount * .05);
    $discount3perFieldID = CRM_Core_BAO_CustomField::getCustomFieldID('Discounted_3_', 'Contribution_Discounts');
    $discount5perFieldID = CRM_Core_BAO_CustomField::getCustomFieldID('Discounted_5_', 'Contribution_Discounts');

    // Update the custom fields using customValue api. The alternative is to use
    // the Contribution API, but of course you'd need some variation of static
    // cache to prevent endless loop, and that approach appears to incurr a large
    // performance hit. E.g., importing 100 contributions takes ~12 seconds if
    // we use Contribution API with static cache checking, vs. ~4 seconds if
    // we use the CustomValue API as we're doing here.
    $result = civicrm_api3('CustomValue', 'create', [
      'entity_id' => $objectId,
      'custom_' . $discount3perFieldID => $discount3per,
    ]);
    $result = civicrm_api3('CustomValue', 'create', [
      'entity_id' => $objectId,
      'custom_' . $discount5perFieldID => $discount5per,
    ]);
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function metrotweaks_civicrm_config(&$config) {
  _metrotweaks_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function metrotweaks_civicrm_install() {
  _metrotweaks_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function metrotweaks_civicrm_enable() {
  _metrotweaks_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 */
// function metrotweaks_civicrm_preProcess($formName, &$form) {

// } // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 * function metrotweaks_civicrm_navigationMenu(&$menu) {
 *  _metrotweaks_civix_insert_navigation_menu($menu, 'Mailings', array(
 *    'label' => E::ts('New subliminal message'),
 *    'name' => 'mailing_subliminal_message',
 *    'url' => 'civicrm/mailing/subliminal',
 *    'permission' => 'access CiviMail',
 *    'operator' => 'OR',
 *    'separator' => 0,
 *  ));
 *  _metrotweaks_civix_navigationMenu($menu);
 * }
 */
function _metrotweaks_get_config() {
  return Civi::settings()->get('com.joineryhq.metrotweaks');
}
