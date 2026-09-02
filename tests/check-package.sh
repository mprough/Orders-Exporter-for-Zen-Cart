#!/usr/bin/env bash

set -euo pipefail

root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
plugin="$root/files/zc_plugins/OrdersExporter/v3.0.0"

required=(
  "$plugin/manifest.php"
  "$plugin/Installer/ScriptedInstaller.php"
  "$plugin/Installer/languages/english/main.php"
  "$plugin/admin/orders_exporter.php"
  "$plugin/admin/includes/extra_datafiles/orders_exporter_filenames.php"
  "$plugin/admin/includes/languages/english/lang.orders_exporter.php"
)

for file in "${required[@]}"; do
  test -f "$file" || { echo "Missing required file: $file" >&2; exit 1; }
done

grep -q "'pluginVersion' => 'v3.0.0'" "$plugin/manifest.php"
grep -q "const ORDERS_EXPORTER_VERSION = '3.0.0'" "$plugin/admin/orders_exporter.php"
grep -q "PLUGIN_ORDERS_EXPORTER_VERSION.*3.0.0" "$plugin/Installer/ScriptedInstaller.php"

echo 'Package checks passed.'
