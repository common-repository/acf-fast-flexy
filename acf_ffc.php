<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              mediavuk.com
 * @since             1.0.0
 * @package           Acf_ffc
 *
 * @wordpress-plugin
 * Plugin Name:       ACF fast Flexy
 * Plugin URI:        mediavuk.com
 * Description:       Customize ACF Flexible field with zero coding.
 * Version:           1.0.0
 * Author:            Mediavuk
 * Author URI:        mediavuk.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acf_ffc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined('WPINC')) {
    die;
}

/**
 * @param $links
 * @return array
 */
function acf_ffc_action_links($links) {
    $links = array_merge(array(
        '<a href="' . esc_url(admin_url('/options-general.php?page=acf_ffc')) . '">' . __('Settings', 'textdomain') . '</a>'
    ), $links);

    return $links;
}

add_action('plugin_action_links_' . plugin_basename(__FILE__), 'acf_ffc_action_links');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-acf_ffc-activator.php
 */
function activate_acf_ffc() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-acf_ffc-activator.php';
    Acf_ffc_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-acf_ffc-deactivator.php
 */
function deactivate_acf_ffc() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-acf_ffc-deactivator.php';
    Acf_ffc_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_acf_ffc');
register_deactivation_hook(__FILE__, 'deactivate_acf_ffc');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-acf_ffc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_acf_ffc() {

    $plugin = new Acf_ffc();
    $plugin->run();
}

run_acf_ffc();
