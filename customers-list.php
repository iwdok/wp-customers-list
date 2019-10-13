<?php
/*
Plugin Name: Список покупателей
Description: Плагин позволяет вести список клиентов, начавших оформление заказа.
Version: 1.0
Author: Андрей Шаповалов
Author URI: https://github.com/iwdok
Plugin URI: https://github.com/iwdok/wp-customers-list
License: GNU General Public License v3.0
*/


define('CUSTOMERS_LIST_MASSR_DIR', plugin_dir_path(__FILE__)); //полный путь к корню папки плагина (от сервера)
define('CUSTOMERS_LIST_MASSR_URL', plugin_dir_url(__FILE__)); //путь к корню папки плагина (лучше его использовать

add_action('admin_menu', 'customers_list_menu'); 

function customers_list_menu() {
    add_menu_page('Список покупателей', 'Покупатели', 'manage_options', 'customers-list/customers-list-admin.php', '', 'dashicons-groups' );
}

?>