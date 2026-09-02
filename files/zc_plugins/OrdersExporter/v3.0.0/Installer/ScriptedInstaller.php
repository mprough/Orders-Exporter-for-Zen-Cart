<?php

declare(strict_types=1);

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

use Zencart\PluginSupport\ScriptedInstaller as ScriptedInstallBase;

class ScriptedInstaller extends ScriptedInstallBase
{
    protected string $configPageKey = 'configOrdersExporter';
    protected string $configGroupTitle = 'Orders Exporter';
    protected int $cgi;
    public string $pluginKey = 'OrdersExporter';
    public string $version = '3.0.0';

    protected function getOrCreateGroupId(): int
    {
        $title = zen_db_input($this->configGroupTitle);
        $result = $this->dbConn->Execute(
            "SELECT configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = '$title' LIMIT 1"
        );
        if (!$result->EOF) {
            return (int)$result->fields['configuration_group_id'];
        }

        $this->executeInstallerSql(
            "INSERT INTO " . TABLE_CONFIGURATION_GROUP . " (configuration_group_title, configuration_group_description, sort_order, visible) VALUES ('$title', 'Orders Exporter performance controls', 0, 1)"
        );
        $result = $this->dbConn->Execute(
            "SELECT configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = '$title' LIMIT 1"
        );
        $cgi = (int)($result->fields['configuration_group_id'] ?? 0);
        if ($cgi > 0) {
            $this->executeInstallerSql("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET sort_order = $cgi WHERE configuration_group_id = $cgi");
        }
        return $cgi;
    }

    protected function executeInstall(): bool
    {
        $this->cgi = $this->getOrCreateGroupId();
        $this->executeInstallerSql(
            "INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
                (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function)
             VALUES
                ('Installed version', 'PLUGIN_ORDERS_EXPORTER_VERSION', '3.0.0', 'Installed Orders Exporter version.', {$this->cgi}, 0, 'zen_cfg_select_option(array(\'3.0.0\'),'),
                ('Export query batch size', 'PLUGIN_ORDERS_EXPORTER_QUERY_BATCH', '500', 'Rows read by each database query. Accepted range: 100 to 2000. Smaller batches reduce peak resource use on constrained servers.', {$this->cgi}, 10, NULL)"
        );

        if (!zen_page_key_exists('ordersExporter')) {
            zen_register_admin_page('ordersExporter', 'BOX_TOOLS_ORDERS_EXPORTER', 'FILENAME_ORDERS_EXPORTER', '', 'tools', 'Y', 17);
        }
        if (!zen_page_key_exists($this->configPageKey)) {
            zen_register_admin_page($this->configPageKey, 'BOX_CONFIGURATION_ORDERS_EXPORTER', 'FILENAME_CONFIGURATION', "gID={$this->cgi}", 'configuration', 'Y');
        }
        return true;
    }

    protected function executeUpgrade(...$args): bool
    {
        return $this->executeInstall();
    }

    protected function executeUninstall(): bool
    {
        $this->executeInstallerSql("DELETE FROM " . TABLE_ADMIN_PAGES . " WHERE page_key IN ('ordersExporter', 'configOrdersExporter')");
        $this->executeInstallerSql("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key LIKE 'PLUGIN_ORDERS_EXPORTER_%'");
        $title = zen_db_input($this->configGroupTitle);
        $this->executeInstallerSql("DELETE FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = '$title'");
        return true;
    }
}
