# User guide

## Opening Orders Exporter

Sign in to Zen Cart Admin and open **Tools > Orders Exporter**.

Select **Download export** beside the required export choice. The browser downloads a tab-delimited `.txt` file. Open it with Excel, LibreOffice Calc, OpenOffice Calc, or another application that supports tab-delimited data.

The file name includes the export type and creation time, for example:

```text
orders-full-20260902-1030.txt
```

## Export choices

### All orders, full export

Includes every order status. Each exported row contains order and customer fields, one ordered product, and its attribute information when present.

### All orders except Delivered, full export

Uses the same fields as the full export but omits orders whose status ID is 3.

### Products except Delivered, without attributes

Exports one row per ordered product, omits attribute columns, includes the order total, and omits orders whose status ID is 3.

### Products except Delivered, attributes only

Exports only ordered products that have attributes and omits orders whose status ID is 3. A product with several attributes can produce several rows.

## Exported columns

Depending on the selected export, the file can contain:

- Purchase date.
- Order status name.
- Order ID and customer ID.
- Customer name and company.
- Customer street address, suburb, city, postal code, and country.
- Customer telephone number and email address.
- Product model and product name.
- Product option and option value.
- Order total in the export without attributes.

Every row ends with the legacy `ENDOFROW` marker for compatibility with workflows built around earlier releases.

## Large exports

The download begins immediately and is generated in batches. Keep the browser window open until the download finishes.

The plugin:

- Retrieves a limited number of rows in each query.
- Streams completed rows to the browser instead of collecting the entire file in memory.
- Continues from the last ordered-product and attribute IDs instead of repeatedly scanning an increasing SQL offset.
- Stops work if the browser disconnects.

The batch size is configured under **Configuration > Orders Exporter**. The default of 500 is appropriate for most stores.

## Spreadsheet safety

Text beginning with `=`, `+`, `-`, or `@` is prefixed with an apostrophe. This prevents customer-entered or product-entered text from being treated as a spreadsheet formula when the file is opened.

Carriage returns, line breaks, and tabs inside values are replaced with spaces so they do not break the tab-delimited layout.

## Troubleshooting

### The menu item is missing

- Confirm that v3.0.0 shows as installed under **Modules > Plugin Manager**.
- Sign out of Admin and sign in again.
- Confirm that the admin profile has permission to use Orders Exporter.

### The export stops before finishing

- Reduce **Export query batch size** to 250 or 100.
- Check the store's `logs` directory for a new Zen Cart debug log.
- Check the web server and PHP error logs for execution-time, proxy-timeout, or connection errors.
- Very large downloads can still be limited by the hosting provider, reverse proxy, or browser connection even though PHP memory use is controlled.

### A status name is blank or missing

Confirm that the order status has a name in the language currently selected in Admin.

### “Except Delivered” includes or excludes the wrong orders

For compatibility with the original plugin, these choices treat order status ID 3 as Delivered. Confirm the store's status IDs under **Localization > Order Status**.
