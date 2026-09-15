<?php

declare(strict_types=1);

if (!defined('IS_ADMIN_FLAG') || IS_ADMIN_FLAG !== true) {
    die('Illegal Access');
}

// These definitions must exist before Zen Cart constructs the admin menus.
// Keep the fallbacks here as well as in extra_datafiles for compatibility with
// supported Zen Cart releases and different bootstrap sequences.
if (!defined('FILENAME_ORDERS_EXPORTER')) {
    define('FILENAME_ORDERS_EXPORTER', 'orders_exporter');
}
if (!defined('BOX_TOOLS_ORDERS_EXPORTER')) {
    define('BOX_TOOLS_ORDERS_EXPORTER', 'Orders Exporter');
}
if (!defined('BOX_CONFIGURATION_ORDERS_EXPORTER')) {
    define('BOX_CONFIGURATION_ORDERS_EXPORTER', 'Orders Exporter');
}

$ordersExporterInstalled = defined('PLUGIN_ORDERS_EXPORTER_VERSION');
if (!$ordersExporterInstalled && isset($db)) {
    $ordersExporterVersion = $db->Execute(
        "SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'PLUGIN_ORDERS_EXPORTER_VERSION' LIMIT 1"
    );
    $ordersExporterInstalled = !$ordersExporterVersion->EOF;
}

// Repair a missing Tools registration without touching an existing registration
// or the permissions Zen Cart has assigned to it.
if (function_exists('zen_register_admin_page')
    && $ordersExporterInstalled
    && !zen_page_key_exists('ordersExporter')
) {
    zen_register_admin_page(
        'ordersExporter',
        'BOX_TOOLS_ORDERS_EXPORTER',
        'FILENAME_ORDERS_EXPORTER',
        '',
        'tools',
        'Y',
        17
    );
}
unset($ordersExporterInstalled, $ordersExporterVersion);
