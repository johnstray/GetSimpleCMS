# Contributing to GetSimple CMS

This repository is John Stray’s GetSimple CMS fork, maintained at
[johnstray/GetSimpleCMS](https://github.com/johnstray/GetSimpleCMS).
Report issues and propose changes here for this fork. For significant changes,
open an issue first so we can discuss scope and compatibility.

Please follow our [Code of Conduct](CODE_OF_CONDUCT.md).

## Development workflow

We are establishing the initial baseline on `main`. GitHub Actions tests will be
added after that baseline. Once those checks are in place, development will use
feature branches and pull requests targeting `main`, with passing required checks
before merging. Required checks must be configured in the repository’s branch
protection or ruleset settings after the workflows exist; this document does not
enable them. Automated checks are not yet available in this baseline.

For contributions, branch from the latest `main`, keep changes focused, and explain
what changed, why, and how you tested it. Reference related issues and update
relevant documentation. Avoid unrelated formatting or refactoring.

## Local setup and testing

The documented application requirements are PHP 8.3 or later, the SimpleXML, xml,
json, dom, curl, and gd extensions, and Apache for the supplied `.htaccess` security
rules. Use a disposable local installation with test accounts and content.

Run `composer install` to install the dependencies recorded in `composer.lock`.
Development tools may require a newer PHP version than the application minimum;
check Composer’s platform requirements for your environment.

There is no Composer test script or automated test suite in this baseline.
For PHP changes, lint the changed files with `php -l path/to/file.php` and manually
test the affected behaviour. Where relevant, test installation, admin login,
page editing and saving, frontend rendering, and plugin or theme compatibility.
Describe the PHP version, checks performed, results, and any limitations in the PR.

PHPStan configuration is available in `phpstan.neon`; run
`vendor/bin/phpstan analyse` when relevant. Existing analysis findings do not
constitute a clean baseline or an established required check. Do not describe
checks as passing unless you ran them successfully.

## Code and distribution files

Follow the surrounding PHP, JavaScript, CSS, and template conventions. Preserve
compatibility with the documented application requirements, or discuss requirement
changes explicitly. Keep commits focused and use descriptive messages.

Keep bundled admin assets, plugins, themes, `temp.gsconfig.php`, `temp.htaccess`,
Composer manifests and lockfile, and shipped data/backup protection files,
placeholders, and sample images in version control. Do not commit installed
`vendor/` dependencies, generated site data, uploads, backups, caches, logs,
user accounts, authentication salts, or local credentials. `.gitignore` preserves
the distribution files while excluding runtime data at the default paths. Review
your diff carefully if you customise those paths. Ignore rules do not protect
secrets in already tracked files.

## Issues and security

Use the issue forms for reproducible bugs and feature requests. Include the fork
version or commit, PHP and web server versions, relevant plugins/themes, and
sanitised reproduction details. Never attach a live site backup, user XML,
authorization XML, credentials, or private configuration.

Do not disclose suspected vulnerabilities in public issues or pull requests.
Contact the maintainer privately at [github@johnstray.id.au](mailto:github@johnstray.id.au)
with affected versions, impact, and sanitised reproduction steps.

## AI-assisted contributions and licensing

You are responsible for all submitted work, including AI-assisted changes.
Review it for correctness, compatibility, security, licensing, and maintainability,
and test it before submission. Do not share confidential data with AI tools.

By contributing, you agree that your contributions may be distributed under the
project’s [GPL-3.0-or-later licence](../LICENSE.txt). Submit only material you have
the right to contribute, and preserve applicable attribution and licence notices.
