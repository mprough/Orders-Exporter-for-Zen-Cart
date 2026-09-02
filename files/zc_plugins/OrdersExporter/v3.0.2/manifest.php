<?php

declare(strict_types=1);

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

return [
    'pluginVersion' => 'v3.0.2',
    'pluginName' => 'Orders Exporter',
    'pluginDescription' => 'Stream order exports safely on stores of any size.',
    'pluginAuthor' => 'PRO-Webs.net',
    'pluginId' => 560,
    'zcVersions' => ['v200', 'v210', 'v220'],
    'changelog' => 'https://github.com/mprough/Orders-Exporter-for-Zen-Cart',
    'github_repo' => 'https://github.com/mprough/Orders-Exporter-for-Zen-Cart',
    'pluginGroups' => [],
];
