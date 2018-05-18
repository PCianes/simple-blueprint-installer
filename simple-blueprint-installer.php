<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also core all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://pablocianes.com/
 * @since             1.0.0
 * @package           Simple_Blueprint_Installer
 *
 * @wordpress-plugin
 * Plugin Name:       Simple blueprint installer
 * Description:       Install this as your first plugin and make easy and fast the first setup of your WordPress.
 * Version:           1.0.0
 * Author:            Pablo Cianes
 * Author URI:        https://pablocianes.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-blueprint-installer
 * Domain Path:       /languages
 */

/*
Simple blueprint installer by Cianes' WP Suite is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
Simple blueprint installer by Cianes' WP Suite is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with Simple blueprint installer by Cianes' WP Suite. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs only in dev mode
 * and we know that because of exists autolad.php into vendor folder
 * when it is set 'composer install' in console to dev this plugin
 */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
	require_once __DIR__ . '/tests/dev/simple-blueprint-installer-configuration.php';
}

/**
 * The code that runs during plugin activation.
 * This action is documented in core/class-simple-blueprint-installer-activator.php
 */
function activate_simple_blueprint_installer() {
	require_once plugin_dir_path( __FILE__ ) . 'core/class-simple-blueprint-installer-activator.php';
	Simple_Blueprint_Installer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in core/class-simple-blueprint-installer-deactivator.php
 */
function deactivate_simple_blueprint_installer() {
	require_once plugin_dir_path( __FILE__ ) . 'core/class-simple-blueprint-installer-deactivator.php';
	Simple_Blueprint_Installer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simple_blueprint_installer' );
register_deactivation_hook( __FILE__, 'deactivate_simple_blueprint_installer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'core/class-simple-blueprint-installer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_simple_blueprint_installer() {

	$plugin = new Simple_Blueprint_Installer();
	$plugin->run();

}
run_simple_blueprint_installer();
