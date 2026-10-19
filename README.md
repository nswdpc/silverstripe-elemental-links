# Links content block for Silverstripe CMS

Add a list of links via a content block to a page.

## Features

+ Adds display choices about how links are presented, for use in your templates

## Link migration

This version includes [staged migration of links](https://github.com/nswdpc/silverstripe-linkfield/blob/feat-link-migrator-staged/docs/en/migration.md) to the core Silverstripe Link model.

1. *Important*: Update all calls to `$ElementLinks`/`getElementLinks()`/`ElementLinks()` in your project to `$DerivedElementLinksList`/`getDerivedElementLinksList()`/`DerivedElementLinksList()`
2. Once the above change is available on your site, in the "Link migration" admin section any existing links will show with a  "Migrate" button
3. Hit the "Migrate" button
4. Records will migrate to the core Silverstripe Link model and the links element interface will display the core Silverstripe link field.
5. Template usage on site frontend will pick up the new, core, link.

The next major release of this module will remove dependency of `nswdpc/silverstripe-linkfield` and legacy linkfield implementation.

## Requirements

See composer.json

## Installation

```sh
composer require nswdpc/silverstripe-elemental-links
```

## License

[BSD-3-Clause](./LICENSE.md)

## Maintainers

+ [dpcdigital@NSWDPC:~$](https://dpc.nsw.gov.au)

## Bugtracker

We welcome bug reports, pull requests and feature requests on the Github Issue tracker for this project.

Please review the [code of conduct](./code-of-conduct.md) prior to opening a new issue.

## Security

If you have found a security issue with this module, please email digital[@]dpc.nsw.gov.au in the first instance, detailing your findings.

## Development and contribution

If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.

Please review the [code of conduct](./code-of-conduct.md) prior to completing a pull request.
