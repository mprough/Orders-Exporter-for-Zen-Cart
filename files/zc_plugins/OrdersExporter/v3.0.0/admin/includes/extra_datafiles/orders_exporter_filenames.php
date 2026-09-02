<?php

declare(strict_types=1);

if (!defined('IS_ADMIN_FLAG') || IS_ADMIN_FLAG !== true) {
    die('Illegal Access');
}

define('FILENAME_ORDERS_EXPORTER', 'orders_exporter');
