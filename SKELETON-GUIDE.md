# WordPress Skeleton

```bash
|-- .github
|   |-- hosts.yml
|   `-- workflows
|       |-- deploy_on_push.yml
|       `-- phpcs_on_pull_request.yml
|
|-- .gitignore
|
|-- README.md
|-- SKELETON-GUIDE.md
|
|-- mu-plugins
|   |-- plugin-update-notification.php
|
|-- phpcs.xml
|
|-- plugins
|   `-- .gitkeep
|
|-- rt-config
|   `-- rt-config.php
|
`-- themes
|   `-- .gitkeep
|
|-- webroot-files
|   `-- .gitkeep
```

## Description of the skeleton structure

### .github

1. `hosts.yml` - Branch to server mapping file for [action-deploy-wordpress](https://github.com/rtCamp/action-deploy-wordpress/).

2. `workflows` - GitHub actions yml files.
    i. `deploy_on_push.yml` - Action to deploy site and send success slack notification. Based on [action-deploy-wordpress](https://github.com/rtCamp/action-deploy-wordpress/) and [action-slack-notify](https://github.com/rtCamp/action-slack-notify/)
    ii. `phpcs_on_pull_request.yml` - Action to run PHPCS checks on PRs. Based on [action-phpcs-code-review](https://github.com/rtCamp/action-phpcs-code-review/).
    iii. `plugin_update_every_week.yml` - Action to check for plugin updates every week and generate PR if update available. Based on [action-plugin-update](https://github.com/rtCamp/action-plugin-update/)
    iv. `repo_housekeeping.yml` - Future automation action to cleanup merged branches, report stale issues and stale PRs to PM, cleanup old repos of stale things.

### .gitignore

Specifies intentionally untracked files to ignore for WordPress development.

### README.md

Contains the standard readme with all the things from title, enviournment, setup, migration etc. all details covered that should ideally be present in a project readme.

### SKELETON-GUIDE.md

The guide describing the skeleton repo structure and files.

### phpcs.xml

PHPCS Default ruleset Configuration File.

### rt-config

Folder containing `rt-config.php`.

1. `rt-config.php` - This file can be used similarly we are using `wp-config.php`. This file is loaded very early (immediately after `wp-config.php`).

### mu-plugins

Folder to keep WordPress mu-plugins.

1. `plugin-update-notification.php` - Display plugin update notification when `DISALLOW_FILE_MODS` constat set to true. Which is the case in wordpress-skeleton.

### plugins

Folder to keep WordPress plugins.

### themes

Folder to keep WordPress themes.