# Orders Exporter for Zen Cart

Orders Exporter downloads order and product data from Zen Cart as a tab-delimited text file for bookkeeping, reporting, and spreadsheet work.

## Current release and compatibility

Version 3.0.0 supports:

- Zen Cart 2.0.x, 2.1.x, and 2.2.x.
- PHP 8.0 through 8.5, within the limits supported by the installed Zen Cart version.
- Installation through Zen Cart Plugin Manager.

## Highlights

- Encapsulated Zen Cart Plugin Manager package with no core-file edits.
- Streams downloads instead of building the complete export in PHP memory.
- Reads large exports in configurable, keyset-paginated database batches.
- Preserves all four original export choices.
- Protects spreadsheet users from formula prefixes in exported customer and product data.
- Direct authenticated downloads; order data is no longer written below the public catalog directory.

The former **Save Orders on server** feature was intentionally retired. It placed unencrypted customer and order data in a catalog-side directory. Version 3.0.0 only sends exports through an authenticated admin download.

## Export choices

- All orders, full export.
- All orders except Delivered, full export.
- Products except Delivered, without attributes.
- Products except Delivered, attributes only.

For compatibility with the original plugin, **Delivered** remains order status ID 3.

## Documentation

- [Installation and upgrade](docs/INSTALLATION.md)
- [User guide](docs/USER_GUIDE.md)
- [Technical notes](docs/TECHNICAL_NOTES.md)
- [Security](SECURITY.md)
- [Version history](CHANGELOG.md)

## Support and license

Publisher: [PRO-Webs.net](https://pro-webs.net/)

Report reproducible bugs through the [PRO-Webs support desk](https://prowebsinc.zohodesk.com/portal/en/newticket). Include the Zen Cart version, PHP version, export choice, and relevant Zen Cart debug log.

Installation, store-specific troubleshooting, data analysis, and customization are separate services.

Licensed under GNU GPL v2. See `LICENSE`.

This software is provided without warranty. Test it on a backup or development copy before using it on a live store.

## Credits

Orders Exporter is maintained by PRO-Webs.net. The original work was based on Easy Populate and was developed over time by Matej Pekarek, PRO-Webs, DrByte, and earlier contributors.

[Zen Cart Plugin Library listing](https://www.zen-cart.com/plugins/orders-exporter-vb560)
