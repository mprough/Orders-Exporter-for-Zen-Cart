# Orders Exporter for Zen Cart

Orders Exporter provides a simple way to download order, customer, product, and product-attribute information from Zen Cart as a tab-delimited text file. The exported file can be opened in Excel, LibreOffice Calc, OpenOffice Calc, accounting software, reporting tools, and other applications that accept tab-delimited data.

Version 3.0.2 is a complete modernization of the original Orders Exporter. It is encapsulated for Zen Cart Plugin Manager, makes no core-file edits, and processes large stores in controlled database batches instead of loading the complete export into PHP memory.

## Compatibility

- Zen Cart 2.0.x.
- Zen Cart 2.1.x.
- Zen Cart 2.2.x.
- PHP 8.0 through 8.5, within the limits supported by the installed Zen Cart version.

## Features

- Beginner-level installation through Zen Cart Plugin Manager.
- No Zen Cart core-file edits.
- Four export choices retained from the original plugin.
- Tab-delimited `.txt` output that works with common spreadsheet and reporting applications.
- Original `v_` column names retained for compatibility with established workflows.
- Original `ENDOFROW` marker retained at the end of every exported row.
- Streamed downloads that do not assemble the complete file in PHP memory.
- Configurable database query batches for large stores.
- Keyset pagination that continues from the last processed record instead of repeatedly scanning an increasing SQL offset.
- Automatic limits prevent an accidental configuration value from creating excessively large query batches.
- Spreadsheet formula-prefix protection for customer-entered and product-entered text.
- Embedded tabs and line endings are replaced so one value cannot corrupt the tab-delimited layout.
- Downloads remain behind the authenticated Zen Cart admin session.
- No writable export directory is required.
- Clean uninstall removes the plugin configuration and admin-page registrations without changing order data.

## Export choices

### All orders, full export

Exports orders in every order status. Each row contains order and customer information, one ordered product, and its product-option information when present.

### All orders except Delivered, full export

Provides the same fields as the full export but excludes orders whose order status ID is 3.

### Products except Delivered, without attributes

Exports one row for each ordered product, excludes product-option columns, includes the order total, and excludes orders whose order status ID is 3.

### Products except Delivered, attributes only

Exports only ordered products that have product attributes and excludes orders whose order status ID is 3. A product with several attributes can produce several rows.

For compatibility with the original plugin, the three **except Delivered** exports continue to treat order status ID 3 as Delivered. Stores that have reassigned status ID 3 should review **Localization > Order Status** before using those exports.

## Exported information

The exact columns depend on the selected export. Available information includes:

- Purchase date.
- Order status name.
- Order ID.
- Customer ID.
- Customer name.
- Customer company.
- Customer street address.
- Customer suburb.
- Customer city.
- Customer postal code.
- Customer country.
- Customer telephone number.
- Customer email address.
- Product model.
- Product name.
- Product option name.
- Product option value.
- Order total in the export without attributes.

## Installation

1. Make a complete backup of the store files and database.
2. Download or clone this repository.
3. Upload the complete `files/zc_plugins/OrdersExporter` directory into the store's `zc_plugins` directory.
4. Sign in to Zen Cart Admin.
5. Open **Modules > Plugin Manager**.
6. Find **Orders Exporter v3.0.2** and select **Install**.
7. Open **Tools > Orders Exporter** and confirm that the four export choices are displayed.

The installed directory structure begins with:

```text
zc_plugins/
└── OrdersExporter/
    ├── v3.0.0/
    ├── v3.0.1/
    └── v3.0.2/
```

Do not rename the `OrdersExporter` or version directories.

## Upgrading from version 2.0 or an earlier release

Version 3.0.2 replaces the old loose admin files. It is not installed by copying files directly into the renamed admin directory.

1. Make a complete backup of the store files and database.
2. Check the old catalog-side `oexport` directory for saved exports that must be retained securely.
3. Install v3.0.2 through Plugin Manager using the installation instructions above.
4. Open **Tools > Orders Exporter** and test a small export.
5. Remove these obsolete files from the store's renamed admin directory:

```text
ordersExport.php
includes/extra_datafiles/ordersExport_filenames.php
includes/functions/extra_functions/reg_orders_export.php
includes/languages/english/extra_definitions/ordersExport.php
includes/languages/english/lang.ordersExport.php
```

6. After securely retaining or deleting any old export files, remove the catalog-side `oexport` directory.

The previous loose-file release did not create configuration database rows. Version 3.0.2 creates its own configuration group and admin menu registrations during installation.

The original v2.0 installation document is preserved at [`docs/archive/readme-v2.0.txt`](docs/archive/readme-v2.0.txt) for historical reference. Do not use those archived instructions to install v3.0.2.

## Configuration

Open **Configuration > Orders Exporter** after installing the plugin.

### Installed version

Displays the installed version. The value is limited to the installed version and is not a free-form field.

### Export query batch size

Controls the maximum number of result rows retrieved by each database query.

- Default: 500 rows.
- Minimum: 100 rows.
- Maximum: 2,000 rows.

The default is suitable for most stores. Use 250 or 100 on a memory-constrained or heavily loaded server. A larger value reduces the number of database round trips but increases the work performed by each query. Values outside the supported range are automatically limited at runtime.

## Using Orders Exporter

1. Sign in to Zen Cart Admin.
2. Open **Tools > Orders Exporter**.
3. Find the required export choice.
4. Select **Download export**.
5. Keep the browser window open until the download finishes.

The downloaded filename identifies the export choice and creation time. For example:

```text
orders-full-20260902-1030.txt
```

Open the file as tab-delimited text in the application of your choice. Every row retains the legacy `ENDOFROW` marker for compatibility with processes built around earlier releases.

## How large exports are controlled

The original exporter executed one unbounded joined query and appended every result to one PHP string. On a large store, the complete result had to fit in database and PHP resources at the same time.

Version 3.0.2 changes both parts of that process:

1. The database returns only the configured number of rows in each query.
2. Each completed row is written directly to the authenticated browser download.
3. The next query continues after the last ordered-product and attribute IDs already processed.
4. Processing stops if the browser disconnects.

This greatly reduces peak PHP memory use and prevents one export query from attempting to retrieve the entire order history at once. Hosting-provider execution limits, reverse-proxy timeouts, and interrupted browser connections can still limit exceptionally large downloads.

## Security and privacy

Order exports can contain names, addresses, telephone numbers, email addresses, product details, and order totals. Store exported files only where authorized staff can access them and delete them when they are no longer required.

Version 3.0.2 improves export safety:

- The export requires an authenticated Zen Cart admin session and admin-page permission.
- The plugin no longer saves unencrypted order data beneath the public catalog directory.
- Values beginning with `=`, `+`, `-`, or `@` are prefixed with an apostrophe so spreadsheet applications do not treat them as formulas.
- Line endings and tabs inside values are replaced with spaces to preserve the file structure.
- Export types are restricted to the four supported choices.
- SQL continuation values and batch limits are generated as integers by the plugin.

The former **Save Orders on server** feature was intentionally retired because it placed order data in a catalog-side directory. Version 3.0.2 only provides direct admin downloads.

## Database changes

Installation adds:

- One configuration group named `Orders Exporter`.
- `PLUGIN_ORDERS_EXPORTER_VERSION`.
- `PLUGIN_ORDERS_EXPORTER_QUERY_BATCH`.
- One Tools page registration.
- One Configuration page registration.

The installer does not alter order, customer, product, order-product, product-attribute, or order-status tables.

## Uninstall

1. Open **Modules > Plugin Manager**.
2. Select **Uninstall** for Orders Exporter.
3. Delete the `zc_plugins/OrdersExporter` directory if the package will not be reinstalled.

Uninstall removes the Orders Exporter configuration values, configuration group, and admin-page registrations. It does not delete or modify orders, customers, products, or exported files already downloaded to a computer.

## Troubleshooting

### The Orders Exporter menu item is missing

- Confirm that v3.0.2 is installed under **Modules > Plugin Manager**.
- Sign out of Admin and sign in again.
- Confirm that the current admin profile has permission to use Orders Exporter.

### An export stops before finishing

- Reduce **Configuration > Orders Exporter > Export query batch size** to 250 or 100.
- Check the store's `logs` directory for a new Zen Cart debug log.
- Check the PHP and web-server error logs for execution-time, proxy-timeout, or connection errors.
- Confirm that the browser remained connected until the download completed.

### An order status name is blank or missing

Confirm that the order status has a name in the language currently selected in Admin.

### An “except Delivered” export includes or excludes the wrong orders

These choices treat order status ID 3 as Delivered for compatibility with the original plugin. Confirm the store's status assignments under **Localization > Order Status**.

### The spreadsheet displays an apostrophe before a value

An apostrophe is intentionally added when a value begins with a spreadsheet formula character. It prevents imported customer or product text from being executed as a formula.

## Testing

The repository includes:

- A package structure and version consistency check.
- GitHub Actions PHP syntax checks for PHP 8.0 through 8.5.

Automated checks cannot reproduce every database, admin-profile, hosting, or third-party plugin combination. Test the plugin on a backup or development copy before using it on a live store.

## Support

Publisher and maintainer: [PRO-Webs.net](https://pro-webs.net/)

Report reproducible bugs through the [PRO-Webs support desk](https://prowebsinc.zohodesk.com/portal/en/newticket). Include:

- Orders Exporter version.
- Zen Cart version.
- PHP version.
- Export choice used.
- Relevant sanitized Zen Cart debug log.
- Clear steps to reproduce the problem.

Installation, store-specific troubleshooting, data analysis, and customization are separate services.

## License and warranty

Orders Exporter is free software licensed under GNU GPL v2. See [`LICENSE`](LICENSE).

This software is provided without warranty. Back up the store and test the plugin before using it on a live website.

## Credits

Orders Exporter is maintained by PRO-Webs.net. The export design was based on Easy Populate and was developed over time by Matej Pekarek, PRO-Webs, DrByte, and earlier contributors.

## Version history

### 3.0.2 - September 2, 2026

- Added the runtime admin `extra_definitions` language file required to display the Tools and Configuration menu captions.
- Corrected the missing **Tools > Orders Exporter** entry after installation or upgrade.
- Expanded the package test to require both the root filename definition and runtime menu-language definition.

### 3.0.1 - September 2, 2026

- Added the required Plugin Manager root-level `filenames.php` file.
- Corrected the missing **Tools > Orders Exporter** menu entry.
- Made installation and upgrade refresh both admin-page registrations.
- Updated the installed-version configuration value to 3.0.1 during upgrade.

### 3.0.0 - September 2, 2026

- Converted the plugin to an encapsulated Zen Cart Plugin Manager package.
- Added compatibility declarations for Zen Cart 2.0.x, 2.1.x, and 2.2.x.
- Modernized the code for PHP 8.0 through 8.5.
- Replaced the unbounded export query with configurable keyset-paginated query batches.
- Replaced complete-file PHP string assembly with streamed output.
- Preserved all four original export choices and their output column names.
- Preserved the legacy `ENDOFROW` marker.
- Preserved order status ID 3 as Delivered for the three applicable exports.
- Added protection against spreadsheet formula-prefix injection.
- Normalized embedded line endings and tabs to protect the output structure.
- Removed the server-side saved-export feature and writable catalog export directory.
- Added a current Zen Cart admin interface.
- Added an installed-version setting and configurable export query batch size.
- Added clean uninstall handling for configuration values and admin-page registrations.
- Added package validation and PHP 8.0 through 8.5 syntax checks.
- Replaced and expanded the installation, usage, security, and technical documentation.
- Archived the original v2.0 `readme.txt` for historical reference.

### 2.0

- Updated the plugin for Zen Cart 1.5.8, 2.0.x, and 2.1.x.

### Earlier releases

- Updated for Zen Cart 1.5.2 through 1.5.5 by DrByte in June 2017.
- Corrected documentation and descriptions by PRO-Webs on May 12, 2012.
- Created the Zen Cart 1.5.0 installation by PRO-Webs on February 26, 2012.
- Updated for Zen Cart 1.3.8 and 1.3.9 by PRO-Webs on August 14, 2011.
- Original Zen Cart 1.3.7 source by Matej Pekarek on March 1, 2008.
- Export design based on Easy Populate 1.2.5.4 by Langer.

## Additional documentation

- [Installation and upgrade](docs/INSTALLATION.md)
- [User guide](docs/USER_GUIDE.md)
- [Technical notes](docs/TECHNICAL_NOTES.md)
- [Security policy](SECURITY.md)
- [Dedicated changelog](CHANGELOG.md)
- [Archived v2.0 readme](docs/archive/readme-v2.0.txt)

[Zen Cart Plugin Library listing](https://www.zen-cart.com/plugins/orders-exporter-vb560)
