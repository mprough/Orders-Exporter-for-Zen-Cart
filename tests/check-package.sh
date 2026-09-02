#!/usr/bin/env bash

set -euo pipefail

root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
plugin="$root/files/zc_plugins/OrdersExporter/v3.0.1"

required=(
  "$plugin/manifest.php"
  "$plugin/filenames.php"
  "$plugin/Installer/ScriptedInstaller.php"
  "$plugin/Installer/languages/english/main.php"
  "$plugin/admin/orders_exporter.php"
  "$plugin/admin/includes/languages/english/lang.orders_exporter.php"
)

for file in "${required[@]}"; do
  test -f "$file" || { echo "Missing required file: $file" >&2; exit 1; }
done

grep -q "'pluginVersion' => 'v3.0.1'" "$plugin/manifest.php"
grep -q "const ORDERS_EXPORTER_VERSION = '3.0.1'" "$plugin/admin/orders_exporter.php"
grep -q "PLUGIN_ORDERS_EXPORTER_VERSION.*3.0.1" "$plugin/Installer/ScriptedInstaller.php"
grep -q "define('FILENAME_ORDERS_EXPORTER', 'orders_exporter')" "$plugin/filenames.php"

echo 'Package checks passed.'
