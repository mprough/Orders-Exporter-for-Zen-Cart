# Version history

## 3.0.3 - 2026-09-02

- Added the admin `extra_datafiles` route definition required during admin bootstrap.
- Added a defensive `extra_functions` bootstrap for route and menu constants.
- Added safe self-repair for a missing Tools registration when the plugin is installed.
- Expanded package validation and missing-menu troubleshooting.
- Documented the exact export column names and order.
- Added a reusable admin-menu release checklist for future encapsulated plugins.

## 3.0.2 - 2026-09-02

- Added the runtime admin `extra_definitions` language file required for menu captions.
- Corrected the missing Tools and Configuration menu entries.
- Expanded package validation for both required menu integration files.

## 3.0.1 - 2026-09-02

- Added the required root-level `filenames.php` file for Plugin Manager.
- Corrected the missing Tools menu entry.
- Made installation and upgrade refresh the Tools and Configuration page registrations.
- Updated the installed-version configuration value during upgrade.

## 3.0.0 - 2026-09-02

- Converted the plugin to an encapsulated Zen Cart Plugin Manager package.
- Added compatibility declarations for Zen Cart 2.0.x, 2.1.x, and 2.2.x.
- Modernized the code for PHP 8.0 through 8.5.
- Replaced the unbounded export query with configurable keyset-paginated query batches.
- Replaced whole-file PHP string assembly with streamed output.
- Preserved all four original export choices and their output column names.
- Preserved the legacy `ENDOFROW` marker.
- Preserved order status ID 3 as Delivered for the three applicable exports.
- Added protection against spreadsheet formula-prefix injection.
- Normalized embedded line breaks and tabs to protect the output structure.
- Removed the server-side saved-export feature and writable catalog export directory.
- Added a current Zen Cart admin interface.
- Added a read-only installed-version setting and configurable export query batch size.
- Added clean uninstall handling for configuration values and admin-page registrations.
- Added package validation and PHP 8.0 through 8.5 syntax checks.
- Replaced the old installation notes and documentation.

## 2.0

- Updated the plugin for Zen Cart 1.5.8, 2.0.x, and 2.1.x.

## Earlier releases

- Updated for Zen Cart 1.5.2 through 1.5.5 by DrByte in June 2017.
- Corrected documentation and descriptions by PRO-Webs in May 2012.
- Created the Zen Cart 1.5.0 installation by PRO-Webs in February 2012.
- Updated for Zen Cart 1.3.8 and 1.3.9 by PRO-Webs in August 2011.
- Original Zen Cart 1.3.7 source by Matej Pekarek in March 2008.
- Export design based on Easy Populate 1.2.5.4 by Langer.
