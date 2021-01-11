<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Release_instructions
 *
 * @wordpress-plugin
 * Plugin Name:       Release Instructions
 * Plugin URI:        http://example.com/release-instructions-uri/
 * Description:       Run custom code per deployment/release.
 * Version:           1.0.0
 * Author:            Alexander Kucherov (avdkucherov@gmail.com)
 * Author URI:        https://github.com/Zinkutal
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       release-instructions
 * Domain Path:       /languages
 * Network:           true
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RELEASE_INSTRUCTIONS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-release-instructions-activator.php
 */
function activate_release_instructions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-release-instructions-activator.php';
	Release_instructions_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-release-instructions-deactivator.php
 */
function deactivate_release_instructions() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-release-instructions-deactivator.php';
	Release_instructions_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_release_instructions' );
register_deactivation_hook( __FILE__, 'deactivate_release_instructions' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-release-instructions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_release_instructions() {

	$plugin = new Release_instructions();
	$plugin->run();

}
run_release_instructions();
