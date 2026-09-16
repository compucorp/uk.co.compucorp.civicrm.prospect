[![Build Status](https://github.com/compucorp/uk.co.compucorp.civicrm.prospect/workflows/Tests/badge.svg)](https://github.com/compucorp/uk.co.compucorp.civicrm.prospect/workflows/Tests/badge.svg)

# CiviProspect
## Is CiviProspect for me?
CiviProspect provides the core structure for creating/ managing/ converting the fundraising opportunities of an organisation. It can also be used to manage any monetary opportunities such as sales opportunities and event sponsorships.

#### Create prospects
CiviProspect inherits all the features from CiviCase. You are able to create prospects in the system with your designed prospect stages and relationships/ roles. Additionally, you can record prospect-specific information such as budgeted amount, prospect amount, probability, expected date and restriction code (if applicable).

#### Manage prospects
You are able to keep all of your prospect expectation up to date by updating the probability, amounts and dates. You also have a chance to update these information when you move a prospect from one stage to another.

When a prospect becomes successful, you can easily convert the prospect into a contribution/ pledge. The payment progress will be shown on the relevant prospect. You are able to view the linked contribution/ pledge from  each prospect.

#### Prospect reporting
Since CiviProspect is an extended part of CiviCase, all CiviCase reports in CiviCRM can be used to report on CiviProspect. Apart from these default reports in CiviCRM, we also integrated CiviProspect with the powerful [ Pivot Reports extension](https://civicrm.org/extensions/civicrm-pivot-reports). Install the extension and you can start create your own flexible prospect reports!

## Requirements

CiviProspect is an extension of [CiviCase](https://github.com/compucorp/uk.co.compucorp.civicase), our redesigned case-management interface. It is not a standalone extension, and it brings a short chain of dependencies with it:

```
uk.co.compucorp.civicrm.prospect   (CiviProspect)
└── uk.co.compucorp.civicase       (Compucorp CiviCase UI)    ← download
    ├── org.civicrm.shoreditch     (CiviCRM Bootstrap theme)  ← download
    ├── uk.co.compucorp.usermenu   (User Menu)                ← download
    └── org.civicrm.afform         (Form Core / Afform)       ← ships with CiviCRM
        └── authx                                             ← ships with CiviCRM
```

### Which CiviCase?

`uk.co.compucorp.civicase` is **not** the CiviCase component that ships with CiviCRM core, and it is not a newer version of it. It is a separate extension maintained by Compucorp that replaces the core CiviCase *user interface* with a redesigned one. It works alongside the core component, which stays enabled.

CiviProspect is built against our interface, which is why it requires this extension specifically.

### You need to download

* [CiviCase](https://github.com/compucorp/uk.co.compucorp.civicase/releases): version >= v7.0.0, preferably the latest version.
* [Shoreditch](https://github.com/civicrm/org.civicrm.shoreditch/releases): version >= v0.1-alpha32, preferably the latest version.
* [User Menu](https://github.com/compucorp/uk.co.compucorp.usermenu/releases): latest version.

### Already included in CiviCRM

* `org.civicrm.afform` (Form Core) and its own dependency `authx` ship with CiviCRM and are enabled by default on recent versions. Check **Administer → System Settings → Extensions** — you will usually find them installed already, and you only need to enable them if they are not.

### CiviCRM version

CiviProspect requires CiviCRM 5.51 or later. Note that CiviCase declares its tested baseline in the `comments` field of its `info.xml`; running these extensions on a much later CiviCRM than that baseline is common, but is not something we test exhaustively.

### Choosing a release

CiviProspect and CiviCase are published in **two parallel release lines**, which GitHub interleaves in the releases list:

* **7.x** — CiviProspect `7.x`, CiviCase `7.x`, User Menu `7.x` (current)
* **4.x** — CiviProspect `3.x`, CiviCase `4.x` (legacy, still maintained)

Do not mix the two lines. Take the 7.x releases throughout unless you have a specific reason to stay on the legacy line.

## Installation

Install the dependencies first and CiviProspect last. If this is your first time installing a CiviCRM extension, see the [CiviCRM System Administrator Guide](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/) for background.

### Installation (Web UI)

Download the zip for each extension from its releases page, then install them via **Administer → System Settings → Extensions** in the order listed above.

### Installation (CLI, Zip)

Using the command-line tool [cv](https://github.com/civicrm/cv), substituting the latest tag for each extension:

```bash
cv dl org.civicrm.shoreditch@https://github.com/civicrm/org.civicrm.shoreditch/archive/refs/tags/1.0.0-beta.16.zip
cv dl uk.co.compucorp.usermenu@https://github.com/compucorp/uk.co.compucorp.usermenu/archive/refs/tags/7.1.0.zip
cv dl uk.co.compucorp.civicase@https://github.com/compucorp/uk.co.compucorp.civicase/archive/refs/tags/7.1.3.zip
cv dl uk.co.compucorp.civicrm.prospect@https://github.com/compucorp/uk.co.compucorp.civicrm.prospect/archive/refs/tags/7.1.0.zip

cv en shoreditch usermenu civicase prospect
```

### Installation (CLI, Git)

```bash
cd <extension-dir>
git clone https://github.com/civicrm/org.civicrm.shoreditch.git shoreditch
git clone https://github.com/compucorp/uk.co.compucorp.usermenu.git usermenu
git clone https://github.com/compucorp/uk.co.compucorp.civicase.git civicase
git clone https://github.com/compucorp/uk.co.compucorp.civicrm.prospect.git prospect

cv en shoreditch usermenu civicase prospect
```

You can also get the latest release of CiviProspect from the [CiviCRM extension directory page](https://civicrm.org/extensions/civiprospect) or our [Github repository release page](https://github.com/compucorp/uk.co.compucorp.civicrm.prospect/releases).

## How to configure CiviProspect?
CiviProspect does not require any additional configurations. For basic CiviCase configurations, please visit  [here](https://docs.civicrm.org/user/en/latest/case-management/set-up/) .

## Support
CiviCRM extension directory page: [https://civicrm.org/extensions/civiprospect](https://civicrm.org/extensions/civiprospect)

Please contact the follow email if you have any question: <hello@compucorp.co.uk>

Paid support for this extension is available, please contact us either via Github or at <hello@compucorp.co.uk>
