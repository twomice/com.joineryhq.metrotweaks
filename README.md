# Metro Tweaks by Joinery

Custom behaviors for Metro: 

* "Discounted 3% and 5%" auto-calculation on contributions (see [Usage: Discounted amount calculation](#discounted-amount-calculation) below): <br/>![3-5-discount.png](/images/3-5-discount.png)
* Tweaks to activity date fields per on activity type (see [Configuration: Activity date tweaks](#activity-date-tweaks) below).
* Soft Credit contact fields are limited to Organization contacts.
* Certain fields are hidden on Contribution View and Edit forms: <br/>![hiddenContribFields.png](/images/hiddenContribFields.png)
* On a contact's Contributions tab, Amount is not a link and thus does not expand to show payments.

The extension is licensed under [GPL-3.0](LICENSE.txt).

## Requirements

* PHP v5.4+
* CiviCRM >= 5.x

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl com.joineryhq.metrotweaks@https://github.com/twomice/com.joineryhq.metrotweaks/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/twomice/com.joineryhq.metrotweaks.git
cv en metrotweaks
```

## Usage

### Discounted amount calculation
This extension creates two read-only custom fields on every contribution, which
are automatically adjusted to always display the appropriately discounted 
contribution value:

* "Discounted 3%": contribution total minus 3%
* "Discounted 5%": contribution total minus 5%

These fields are searchable and thus appear in reports, search criteria, and as tokens.
Field values are automatically updated with any change to the contribution record.

## Configuration

This extension has no browser-based configuration form within CiviCRM. Configuration
is by PHP arrays in code within the civicrm.settings.php file.

### Activity date tweaks
This extension will alter the label of the Date field on activities, and optionally
leave that field without a default value on newly created activities, per activity
type, for configured types. To configure, you'll need to add in your 
civicrm.settings.php file a new config variable:

```php
global $civicrm_setting;
$civicrm_setting['com.joineryhq.metrotweaks']['com.joineryhq.metrotweaks'] = array(
  'activityTypesConfig' => array(
    '1' => array (                      // Numerical activity type ID
      'dateLabel' => 'Foobar Date',     // Date label string. Optional. Omit to leave as-is.
      'nullDefaultDate' => TRUE,        // If true, clear default date
                                        // instead of using current
                                        // date/time
    ),
  ),
);
```

## Support
![screenshot](/images/joinery-logo.png)

Joinery provides services for CiviCRM including custom extension development, training, data migrations, and more. We aim to keep this extension in good working order, and will do our best to respond appropriately to issues reported on its [github issue queue](https://github.com/twomice/com.joineryhq.metrotweaks/issues). In addition, if you require urgent or highly customized improvements to this extension, we may suggest conducting a fee-based project under our standard commercial terms.  In any case, the place to start is the [github issue queue](https://github.com/twomice/com.joineryhq.metrotweaks/issues) -- let us hear what you need and we'll be glad to help however we can.

And, if you need help with any other aspect of CiviCRM -- from hosting to custom development to strategic consultation and more -- please contact us directly via https://joineryhq.com
