# Orders Exporter for Zen Cart

Orders Exporter downloads order and product data from Zen Cart as a tab-delimited text file for bookkeeping, reporting, and spreadsheet work.

## Current release

Version 3.0.0 supports Zen Cart 2.0.x, 2.1.x, and 2.2.x with PHP 8.0 through 8.5.

## Highlights

- Encapsulated Zen Cart Plugin Manager package with no core-file edits.
- Streams downloads instead of building the complete export in PHP memory.
- Reads large exports in configurable, keyset-paginated database batches.
- Preserves all four original export choices.
- Protects spreadsheet users from formula prefixes in exported customer and product data.
- Direct authenticated downloads; order data is no longer written below the public catalog directory.

The former **Save Orders on server** links were intentionally retired. They placed unencrypted customer and order data in a catalog-side directory; version 3.0.0 only sends exports through the authenticated admin download.

## Installation

### Plugin Manager (recommended)

Copy `files/zc_plugins/OrdersExporter` into the store's `zc_plugins` directory. In Admin, open **Modules > Plugin Manager**, then install **Orders Exporter**.

## Large-store controls

The Plugin Manager installer creates **Configuration > Orders Exporter > Export query batch size**. The default is 500 database rows per query. The accepted range is 100 through 2,000; a smaller batch reduces peak database and PHP memory use.

## Support and license

Publisher: [PRO-Webs.net](https://pro-webs.net/)

Report bugs through the [PRO-Webs support desk](https://prowebsinc.zohodesk.com/portal/en/newticket). Customization and installation are available separately.

Licensed under GNU GPL v2. See `LICENSE`.

## History

- 3.0.0 (2026-09-02): Encapsulated package, PHP 8 modernization, streamed downloads, throttled keyset queries, output hardening, and refreshed interface and documentation.
- 2.0: Updated for Zen Cart 1.5.8, 2.0.x, and 2.1.x.
- Earlier work: Matej Pekarek, PRO-Webs, DrByte, and Easy Populate contributors.

Zen Cart Plugin Library: https://www.zen-cart.com/plugins/orders-exporter-vb560
