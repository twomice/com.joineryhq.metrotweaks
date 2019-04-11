(function ($, ts) {
  /**
   * Storage for most recent value of Time field.
   * @type String
   */
  var defaultTime;
  
  /**
   * Storage for most recent value of Date field.
   * @type String
   */
  var defaultDate;
  
  /**
   * Show/hide date field, and alter label, as needed.
   */
  var metrotweaks_tweakDateField = function metrotweaks_tweakDateField(activityTypeId) {
    var typeConfig;
    if (activityTypeId) {
      // If the activity type is known, go ahead and show the date.
      $('#activity_date_time').closest('tr').show();
    }
    else {
      // Otherwise hide it.
      $('#activity_date_time').closest('tr').hide();
    }
    
    // If this activity type ID has metrotweaks configuration settings:
    if (CRM.metrotweaks.activityTypesConfig[activityTypeId]) {
      // Store those settings in a shorthand variable.
      typeConfig = CRM.metrotweaks.activityTypesConfig[activityTypeId];
    }
    if (typeConfig && typeConfig.dateLabel) {
      // Adjust the date field label, if so configured.
      $('label[for="activity_date_time"]').html(typeConfig.dateLabel);
    }
  }
  
  /**
   * Nullify date/time fields, or restore them to previous values, as needed.
   */
  var metrotweaks_setDateValues = function metrotweaks_setDateValues(activityTypeId) {
    if (CRM.metrotweaks.activityId) {
      // We're ediging an existing activity. Don't attempt to alter date values.
      return;
    }
    var typeConfig;
    // If this activity type ID has metrotweaks configuration settings:
    if (CRM.metrotweaks.activityTypesConfig[activityTypeId]) {
      // Store those settings in a shorthand variable.
      typeConfig = CRM.metrotweaks.activityTypesConfig[activityTypeId];
    }
    if (typeConfig && typeConfig.nullDefaultDate) {
      // Nullify date/time values, if so configured, but first store the most recent
      // values, so we can restore those values if a different activity type is
      // selected.
      defaultTime = $('#activity_date_time').siblings('input[aria-label="Time"]').val();
      defaultDate = $('#activity_date_time').siblings('input[aria-label="Date"]').val();
      // Clear values for hidden datetime field and aria datepicker fields.
      $('#activity_date_time').val('');
      $('#activity_date_time').siblings('input[aria-label="Time"]').val('');
      $('#activity_date_time').siblings('input[aria-label="Date"]').val('');
    }
    else {
      // If not configured for date/time nullification, and if a most recent date/time has
      // been stored, and if there's no existing date/time in the fields, restore
      // those stored values.
      if (!$('#activity_date_time').siblings('input[aria-label="Date"]').val() && defaultDate) {
        $('#activity_date_time').siblings('input[aria-label="Date"]').val(defaultDate);
      }
      if (!$('#activity_date_time').siblings('input[aria-label="Time"]').val() && defaultTime) {
        $('#activity_date_time').siblings('input[aria-label="Time"]').val(defaultTime);
      }
    }
  }
  
  // Set a change handler on the activity type id field.
  $('#activity_type_id').change(function(){
    metrotweaks_tweakDateField(this.value);
    metrotweaks_setDateValues(this.value);
  });
  
  // Initialize the date label and date/time values, based on configuration.
  metrotweaks_tweakDateField(CRM.metrotweaks.defaultActivityTypeId);
  metrotweaks_setDateValues(CRM.metrotweaks.defaultActivityTypeId);
})(CRM.$, CRM.ts('com.joineryhq.metrotweaks'));
