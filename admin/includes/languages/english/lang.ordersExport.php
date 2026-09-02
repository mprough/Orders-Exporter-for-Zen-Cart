<?php
$define = [
    'ORDERSEXPORT_CONFIG_TEMP_DIR' => 'oexport/',
    'ORDERSEXPORT_MSGSTACK_FILE_EXPORT_SUCCESS' => 'File <b>%s.txt</b> successfully exported! The file is ready for FTP download in your /%s directory.',
    'ORDERSEXPORT_MSGSTACK_ERROR_EXISTS' => 'That\'s unlucky, see below... ',
    'ORDERSEXPORT_MSGSTACK_ERROR_SQL' => 'There is an error in one of the database queries - %s',
    'ORDERSEXPORT_MSGSTACK_ERROR_DLTYPE' => 'Download type was not correctly specified!',
    'ORDERSEXPORT_PAGE_HEADING' => 'Orders Exporter',
    'ORDERSEXPORT_PAGE_HEADING2' => 'Download Orders - tab-delimited .txt file',
    'ORDERSEXPORT_LINK_DOWNLOAD1' => '...all orders full export',
    'ORDERSEXPORT_LINK_DOWNLOAD1B' => '...all orders full export WITHOUT DELIVERED ORDERS',
    'ORDERSEXPORT_LINK_DOWNLOAD2' => '...all ordered products  WITHOUT DELIVERED ORDERS (attributes excluded)',
    'ORDERSEXPORT_LINK_DOWNLOAD3' => '...ordered products with attributes (only) WITHOUT DELIVERED ORDERS',
    'ORDERSEXPORT_PAGE_HEADING3' => 'Save Orders in /oexport/ directory on the server - tab-delimited .txt file',
    'ORDERSEXPORT_LINK_SAVE1' => '...all orders full export',
    'ORDERSEXPORT_LINK_SAVE1B' => '...all orders full export WITHOUT DELIVERED ORDERS',
    'ORDERSEXPORT_LINK_SAVE2' => '...all ordered products WITHOUT DELIVERED ORDERS (attributes excluded)',
    'ORDERSEXPORT_LINK_SAVE3' => '...ordered products with attributes (only) WITHOUT DELIVERED ORDERS',
    'ORDERSEXPORT_VERSION' => 'Orders Export Version:',
    'ORDERSEXPORT_MSGSTACK_FILE_OPEN_FAILURE' => 'Could not open %s - check permissions', 
];

return $define;
