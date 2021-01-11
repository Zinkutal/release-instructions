<?php

use Release_Instructions\Release_Instructions;

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/Zinkutal/release-instructions
 * @since             1.0.0
 * @package           Release_Instructions
 *
 * @wordpress-plugin
 * Plugin Name:       Release Instructions
 * Plugin URI:        https://github.com/Zinkutal/release-instructions
 * Description:       Run custom code per deployment/release.
 * Version:           1.0.0
 * Author:            Alexander Kucherov (avdkucherov@gmail.com)
 * Author URI:        https://github.com/Zinkutal
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       release-instructions
 * Domain Path:       /languages
 * Network:           false
 * RI:                true
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('RELEASE_INSTRUCTIONS_VERSION', '1.0.0');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
if (function_exists('plugin_dir_path')) {
    require plugin_dir_path(__FILE__) . 'includes/class-release-instructions.php';
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_release_instructions()
{
    $plugin = new Release_Instructions();
    $plugin->run();
}

/**
 * Extends plugin headers to support Release Instructions plugin.
 */
add_filter(
    'extra_plugin_headers',
    function () {
        return array('RI' => 'RI');
    }
);

run_release_instructions();
