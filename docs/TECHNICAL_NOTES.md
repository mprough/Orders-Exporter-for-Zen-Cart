# Technical notes

## Package design

Orders Exporter v3.0.3 is an encapsulated Zen Cart Plugin Manager package. Its executable and language files remain inside:

```text
files/zc_plugins/OrdersExporter/v3.0.3/
```

The scripted installer registers the Tools menu page, creates the configuration group, and installs the batch-size setting. The admin `extra_datafiles` file defines the route during bootstrap, while the `extra_functions` bootstrap supplies defensive route/menu constants and repairs an absent registration only when the installed-version configuration exists. No Zen Cart core file is replaced.

## Export processing

Earlier releases executed one unbounded joined query and appended every row to one PHP string before sending or saving the file. On a large store, that design increased database load and PHP memory usage in proportion to the complete export.

Version 3.0.3 uses two controls:

1. Keyset pagination retrieves a bounded batch after the last processed ordered-product and attribute IDs.
2. Streaming writes each completed row directly to the HTTP response.

The configured batch size is normalized to 100 through 2,000 rows. The query uses the ordered-product primary key and, where attributes are joined, the ordered-product-attribute primary key as its continuation cursor. It does not use increasing `LIMIT offset, count` scans.

## Export compatibility

- Output remains tab-delimited text.
- Column names retain the legacy `v_` prefixes.
- Every row retains the `ENDOFROW` marker.
- The two full exports retain the original left join to product attributes.
- The attributes-only export retains an inner join to product attributes.
- The no-attributes export produces one row for each ordered product.
- The three “except Delivered” exports retain the original status ID 3 rule.

The standard full-export header order is:

```text
v_date_purchased	v_orders_status_name	v_orders_id	v_customers_id	v_customers_name	v_customers_company	v_customers_street_address	v_customers_suburb	v_customers_city	v_customers_postcode	v_customers_country	v_customers_telephone	v_customers_email_address	v_products_model	v_products_name	v_products_options	v_products_options_values	ENDOFROW
```

The no-attributes header replaces the two option columns with `v_total_cost`.

## Admin-menu release checklist

Every encapsulated plugin page must be checked as one complete integration:

1. The installer registers the `admin_pages` row with the intended page key, language key, filename constant, menu key, and visibility.
2. An admin `extra_datafiles` file defines the filename constant before menu construction.
3. An admin-language `extra_definitions` file provides every menu-caption key.
4. A defensive `extra_functions` bootstrap covers supported Zen Cart bootstrap differences and repairs a genuinely absent registration only when the plugin is installed.
5. The registered route resolves to an existing admin page file.
6. Upgrade and uninstall paths refresh or remove the same page keys.
7. Package tests verify all integration files, constants, keys, routes, and version strings before release.
8. Testing confirms the page appears for both a superuser and a permitted non-superuser profile.

## Data handling

The export is available only through the authenticated Zen Cart admin page. Version 3.0.3 does not create server-side export files and does not require a writable catalog directory.

Values are flattened to one line and one tab-delimited cell. Formula-like prefixes are neutralized before output.

## Database changes

Installation creates:

- One configuration group named `Orders Exporter`.
- `PLUGIN_ORDERS_EXPORTER_VERSION`.
- `PLUGIN_ORDERS_EXPORTER_QUERY_BATCH`.
- Admin-page registrations for the Tools page and Configuration page.

No order, customer, product, or order-status table is altered.

## Automated checks

The repository includes:

- A package-structure and version-consistency check in `tests/check-package.sh`.
- GitHub Actions PHP syntax checks for PHP 8.0 through 8.5.

These checks confirm package structure and PHP syntax. A live-store test remains necessary because Zen Cart, database contents, installed plugins, admin profiles, and server limits differ between stores.
