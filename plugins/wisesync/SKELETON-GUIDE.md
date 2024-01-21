# WordPress Skeleton

```bash
|-- .github
|   `-- workflows
|       `-- phpcs.yml
|
|-- plugins
|   `-- index.php
|
|-- themes
|   `-- index.php
|
|-- .eslintrc.js
|-- .gitignore
|-- phpcs.xml
|-- .stylelintrc.json
|-- index.php
|-- package.json
|-- README.md
|-- SECURITY.md
|-- SKELETON-GUIDE.md
```

## Description of the skeleton structure

### .github

1. `workflows` - GitHub actions yml files.
    i. `phpcs.yml` - Action to run PHPCS checks on PRs.

### .gitignore

Specifies intentionally untracked files to ignore for WordPress development.

### README.md

Contains the standard readme with all the things from title, enviournment, setup, migration etc. all details covered that should ideally be present in a project readme.

### SKELETON-GUIDE.md

The guide describing the skeleton repo structure and files.

### phpcs.xml

PHPCS Default ruleset Configuration File.

### plugins

Folder to keep WordPress plugins.

### themes

Folder to keep WordPress themes.