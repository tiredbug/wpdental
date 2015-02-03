<?php
/* 
 * Plugin Name: WP Dental
 * Plugin URI: http://drg.id/
 * Description: A WordPres Dental Records Plugin!
 * Version: 0.1
 * Author: drg. F. Basoro
 * Author URI: http://drg.id/
 * License: MIT 
 */

// Flush rewrite rules on activation
function my_rewrite_flush() {

    register_wpdental();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'my_rewrite_flush' );

include_once('post-type.php');
include_once('functions.php');

?>
