<?php

declare(strict_types=1);

require 'includes/application_top.php';

const ORDERS_EXPORTER_VERSION = '3.0.2';

function ordersExporterBatchSize(): int
{
    $size = defined('PLUGIN_ORDERS_EXPORTER_QUERY_BATCH') ? (int)PLUGIN_ORDERS_EXPORTER_QUERY_BATCH : 500;
    return max(100, min(2000, $size));
}

function ordersExporterSafeCell(mixed $value): string
{
    $value = str_replace(["\r", "\n", "\t"], ' ', (string)($value ?? ''));
    if ($value !== '' && in_array($value[0], ['=', '+', '-', '@'], true)) {
        $value = "'" . $value;
    }
    return $value;
}

function ordersExporterDefinition(string $type): ?array
{
    $common = [
        'v_date_purchased', 'v_orders_status_name', 'v_orders_id', 'v_customers_id',
        'v_customers_name', 'v_customers_company', 'v_customers_street_address',
        'v_customers_suburb', 'v_customers_city', 'v_customers_postcode',
        'v_customers_country', 'v_customers_telephone', 'v_customers_email_address',
        'v_products_model', 'v_products_name',
    ];

    return match ($type) {
        'full' => ['fields' => array_merge($common, ['v_products_options', 'v_products_options_values']), 'exclude_delivered' => false, 'attributes' => 'left'],
        'full_open' => ['fields' => array_merge($common, ['v_products_options', 'v_products_options_values']), 'exclude_delivered' => true, 'attributes' => 'left'],
        'no_attributes' => ['fields' => array_merge($common, ['v_total_cost']), 'exclude_delivered' => true, 'attributes' => 'none'],
        'attributes_only' => ['fields' => array_merge($common, ['v_products_options', 'v_products_options_values']), 'exclude_delivered' => true, 'attributes' => 'inner'],
        default => null,
    };
}

function ordersExporterSql(array $definition, int $afterProductId, int $afterAttributeId, int $limit): string
{
    $attributeMode = $definition['attributes'];
    $join = '';
    $attributeColumns = '';
    $cursorCondition = "op.orders_products_id > {$afterProductId}";
    $orderBy = 'op.orders_products_id';

    if ($attributeMode === 'left') {
        $join = ' LEFT JOIN ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' opa ON opa.orders_products_id = op.orders_products_id';
        $attributeColumns = ', opa.products_options AS v_products_options, opa.products_options_values AS v_products_options_values';
        $cursorCondition = "(op.orders_products_id > {$afterProductId} OR (op.orders_products_id = {$afterProductId} AND COALESCE(opa.orders_products_attributes_id, 0) > {$afterAttributeId}))";
        $orderBy = 'op.orders_products_id, COALESCE(opa.orders_products_attributes_id, 0)';
    } elseif ($attributeMode === 'inner') {
        $join = ' INNER JOIN ' . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . ' opa ON opa.orders_products_id = op.orders_products_id';
        $attributeColumns = ', opa.products_options AS v_products_options, opa.products_options_values AS v_products_options_values';
        $cursorCondition = "(op.orders_products_id > {$afterProductId} OR (op.orders_products_id = {$afterProductId} AND opa.orders_products_attributes_id > {$afterAttributeId}))";
        $orderBy = 'op.orders_products_id, opa.orders_products_attributes_id';
    }

    $totalColumn = $attributeMode === 'none' ? ', o.order_total AS v_total_cost' : '';
    $deliveredCondition = $definition['exclude_delivered'] ? ' AND o.orders_status <> 3' : '';
    $attributeIdColumn = $attributeMode === 'none' ? ', 0 AS exporter_attribute_id' : ', COALESCE(opa.orders_products_attributes_id, 0) AS exporter_attribute_id';
    $languageId = (int)($_SESSION['languages_id'] ?? 1);

    return "SELECT op.orders_products_id AS exporter_product_id{$attributeIdColumn},
                   o.date_purchased AS v_date_purchased, os.orders_status_name AS v_orders_status_name,
                   o.orders_id AS v_orders_id, o.customers_id AS v_customers_id,
                   o.customers_name AS v_customers_name, o.customers_company AS v_customers_company,
                   o.customers_street_address AS v_customers_street_address, o.customers_suburb AS v_customers_suburb,
                   o.customers_city AS v_customers_city, o.customers_postcode AS v_customers_postcode,
                   o.customers_country AS v_customers_country, o.customers_telephone AS v_customers_telephone,
                   o.customers_email_address AS v_customers_email_address, op.products_model AS v_products_model,
                   op.products_name AS v_products_name{$totalColumn}{$attributeColumns}
              FROM " . TABLE_ORDERS_PRODUCTS . " op
              JOIN " . TABLE_ORDERS . " o ON o.orders_id = op.orders_id
              JOIN " . TABLE_ORDERS_STATUS . " os ON os.orders_status_id = o.orders_status AND os.language_id = {$languageId}
              {$join}
             WHERE {$cursorCondition}{$deliveredCondition}
             ORDER BY {$orderBy}
             LIMIT {$limit}";
}

function ordersExporterStream(string $type): void
{
    global $db, $request_type;

    $definition = ordersExporterDefinition($type);
    if ($definition === null) {
        http_response_code(400);
        die(ERROR_INVALID_EXPORT);
    }

    @set_time_limit(0);
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    $filename = 'orders-' . str_replace('_', '-', $type) . '-' . date('Ymd-Hi') . '.txt';
    header('Content-Type: text/tab-separated-values; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    if (($request_type ?? '') === 'NONSSL') {
        header('Pragma: no-cache');
    }

    echo implode("\t", $definition['fields']) . "\tENDOFROW\n";
    $afterProductId = 0;
    $afterAttributeId = -1;
    $batchSize = ordersExporterBatchSize();

    do {
        $result = $db->Execute(ordersExporterSql($definition, $afterProductId, $afterAttributeId, $batchSize));
        $count = 0;
        while (!$result->EOF) {
            $afterProductId = (int)$result->fields['exporter_product_id'];
            $afterAttributeId = (int)$result->fields['exporter_attribute_id'];
            $row = [];
            foreach ($definition['fields'] as $field) {
                $row[] = ordersExporterSafeCell($result->fields[$field] ?? '');
            }
            echo implode("\t", $row) . "\tENDOFROW\n";
            ++$count;
            $result->MoveNext();
        }
        flush();
    } while ($count === $batchSize && !connection_aborted());
    exit;
}

$exportType = (string)($_GET['export'] ?? '');
if ($exportType !== '') {
    ordersExporterStream($exportType);
}

$exports = [
    'full' => TEXT_EXPORT_FULL,
    'full_open' => TEXT_EXPORT_FULL_OPEN,
    'no_attributes' => TEXT_EXPORT_NO_ATTRIBUTES,
    'attributes_only' => TEXT_EXPORT_ATTRIBUTES_ONLY,
];
?>
<!doctype html>
<html <?= HTML_PARAMS ?>>
<head>
    <?php require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
    <style>
        .orders-exporter { max-width: 900px; margin: 20px auto; }
        .orders-exporter .list-group-item { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .orders-exporter .panel-body { padding: 20px; }
    </style>
</head>
<body>
<?php require DIR_WS_INCLUDES . 'header.php'; ?>
<main class="orders-exporter">
    <h1><?= HEADING_TITLE ?></h1>
    <div class="panel panel-default">
        <div class="panel-body">
            <p><?= TEXT_ORDERS_EXPORTER_INTRO ?></p>
        </div>
        <div class="list-group">
<?php foreach ($exports as $type => $label) { ?>
            <div class="list-group-item">
                <span><?= zen_output_string_protected($label) ?></span>
                <a class="btn btn-primary" href="<?= zen_href_link(FILENAME_ORDERS_EXPORTER, 'export=' . rawurlencode($type)) ?>"><?= BUTTON_DOWNLOAD_EXPORT ?></a>
            </div>
<?php } ?>
        </div>
    </div>
    <p class="text-center text-muted"><?= TEXT_VERSION ?></p>
</main>
<?php require DIR_WS_INCLUDES . 'footer.php'; ?>
</body>
</html>
<?php require DIR_WS_INCLUDES . 'application_bottom.php'; ?>
