<?php
/*
 * Settings metadata file
 */

use CRM_Metrotweaks_ExtensionUtil as E;

return [
  'com.joineryhq.metrotweaks' => [
    'group_name' => 'com.joineryhq.metrotweaks',
    'name' => 'activityTypeProperties',
    'type' => 'Array',
    'html_type' => 'textarea',
    'default' => FALSE,
    'add' => '5.0',
    'title' => E::ts('Activity Type Properties'),
    'is_domain' => 0,
    'is_contact' => 0,
    'description' => E::ts('An array of settings for the Metro Tweaks extension.'),
    'help_text' => NULL,
  ],
];
