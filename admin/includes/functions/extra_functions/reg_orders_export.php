<?php
/*
  Released under the GNU General Public License       
  available at www.zen-cart.com/license/2_0.txt       
  or see "license.txt" in the downloaded zip          

  DESCRIPTION: Add Export Orders to Tools Menu
*/

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

if (function_exists('zen_register_admin_page')) {
    if (!zen_page_key_exists('orders_export')) {
        zen_register_admin_page('orders_export', 'BOX_TOOLS_ORDERSEXPORT','FILENAME_ORDERSEXPORT', '', 'tools', 'Y', 17);
    }
}
