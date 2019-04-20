(function ($, ts) {

  // Function to do the needful tweaks on the page.
  var doContributionTabTweaks = function doContributionTabTweaks() {
    // For each "amount" link, replace the link with the plain-text amount.
    $('td.crm-contribution-amount a.crm-expand-row').each(function(idx, el){
      $(el).replaceWith($(el).text());
    });    
  };

  // Tweak the page content now.
  doContributionTabTweaks();

})(CRM.$, CRM.ts('com.joineryhq.metrotweaks'));
