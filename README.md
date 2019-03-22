# Metro Tweaks by Joinery

![Screenshot](/images/screenshot.png)

Custom behaviors for Metro: 

* "Discounted 3% and 5%" auto-calculation on contributions.
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

## Issues and support

Please report any issues at https://github.com/twomice/com.joineryhq.metrotweaks.git/issues
