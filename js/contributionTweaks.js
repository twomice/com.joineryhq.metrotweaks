(function ($, ts) {

  // Function to do the needful tweaks on the page.
  var doContributionTweaks = function doContributionTweaks() {
    // Hide rows for the appropriate labels, if they exist.
    var labels = CRM.metrotweaks.contributionLabelsToHide;
    for (var i in labels) {
      var label = labels[i];
      $('td.label:contains("' + label + '")').filter(function() {
        return $(this).text() == label;
      }).closest('tr').hide();
    }

    // Hide the 'payment details' fieldset, if found; this appears as a <fieldset>
    // in Edit, and as <tr> in View.
    $('fieldset.payment-details_group').hide();
  };

  // Immediately after any ajax load, tweak the page.
  $(document).ajaxComplete(function() {
    doContributionTweaks();
  });

  // Tweak the page now.
  doContributionTweaks();

})(CRM.$, CRM.ts('com.joineryhq.metrotweaks'));
