# Installation and upgrade

## Before installing

1. Confirm that the store runs Zen Cart 2.0.x, 2.1.x, or 2.2.x.
2. Confirm that the PHP version is supported by that Zen Cart release.
3. Make a complete backup of the store files and database.
4. If upgrading from an older Orders Exporter release, note whether the old `oexport` directory contains saved exports that must be retained securely.

Orders Exporter does not edit Zen Cart core files.

## New installation

1. Download or clone this repository.
2. Upload the complete `files/zc_plugins/OrdersExporter` directory to the store's `zc_plugins` directory.
3. Sign in to Zen Cart Admin.
4. Open **Modules > Plugin Manager**.
5. Find **Orders Exporter v3.0.0** and select **Install**.
6. Open **Tools > Orders Exporter** and confirm that the four downloads are displayed.

The installed structure begins with:

```text
zc_plugins/
└── OrdersExporter/
    └── v3.0.0/
```

Do not rename `OrdersExporter` or `v3.0.0`.

## Upgrade from version 2.0 or an earlier loose-file installation

Version 3.0.0 is a replacement package, not an overwrite of the old loose admin files.

1. Back up the store files and database.
2. Install v3.0.0 through Plugin Manager using the new-installation steps above.
3. Confirm that **Tools > Orders Exporter** opens and test a small export.
4. Remove the following obsolete files from the store's renamed admin directory:

```text
ordersExport.php
includes/extra_datafiles/ordersExport_filenames.php
includes/functions/extra_functions/reg_orders_export.php
includes/languages/english/extra_definitions/ordersExport.php
includes/languages/english/lang.ordersExport.php
```

5. Remove the catalog-side `oexport` directory after securely retaining or deleting any old export files it contains.

The old plugin did not create configuration database rows. Version 3.0.0 creates its own configuration group and admin menu registrations during installation.

## Configuration

Open **Configuration > Orders Exporter**.

### Installed version

The version field is read-only and displays `3.0.0`.

### Export query batch size

This controls how many result rows each database query retrieves.

- Default: 500.
- Minimum: 100.
- Maximum: 2,000.
- Use a smaller value on a memory-constrained or heavily loaded server.
- Use a larger value only after testing and when fewer database round trips are preferable.

Values outside the supported range are automatically limited at runtime.

## Uninstall

1. Open **Modules > Plugin Manager**.
2. Select **Uninstall** for Orders Exporter.
3. Delete the `zc_plugins/OrdersExporter` directory if the package will not be reinstalled.

Uninstalling removes the Orders Exporter configuration values, configuration group, and registered admin pages. It does not delete orders or modify order records.
