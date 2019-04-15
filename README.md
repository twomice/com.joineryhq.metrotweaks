# Metro Tweaks by Joinery

![Screenshot](/images/screenshot.png)

Custom behaviors for Metro: 

* "Discounted 3% and 5%" auto-calculation on contributions.
* Tweaks to activity date fields per on activity type.
* More ideas in the works ...

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

## Issues and support

Please report any issues at https://github.com/twomice/com.joineryhq.metrotweaks.git/issues
