<?php
/*
Plugin Name: CRUD
Plugin URI: http://www.web-pioneer.com
Description: CRUD
Author: RAMESH
Version: 1.0
Author URI: http://www.web-pioneer.com
*/

function install() {
    global $wpdb; 
    $table = $wpdb->prefix."crud";
    $sql = "CREATE TABLE $table (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, Title VARCHAR(25) NOT NULL, Description text NOT NULL,Status VARCHAR(10) NOT NULL) ENGINE=MyISAM;";
    $wpdb->query($sql);}
    register_activation_hook( __FILE__, 'install' );

function uninstall() {
    global $wpdb;    //required global declaration of WP variable
    $table = $wpdb->prefix."crud";
    $sql = "DROP TABLE $table";
    $wpdb->query($sql);     }
register_deactivation_hook( __FILE__, 'uninstall' );

add_action('admin_menu', 'render');

function render(){
    add_menu_page('CRUD', 'CRUD', 'manage_options', 'crud/crud-audq.php',null,plugins_url( 'crud/images/icon-bw-20x20.png' ), 6 );
    add_submenu_page( 'crud/crud-audq.php', 'Level 1', 'Level 1', 'manage_options', 'crud/crud-audq.php');
    add_submenu_page( 'crud/crud-audq.php', 'Level 2', 'Level 2', 'manage_options', 'crud/myplugin-admin.php');
}

?>