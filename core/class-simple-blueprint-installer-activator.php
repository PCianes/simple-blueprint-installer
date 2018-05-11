<?php
/**
 * Fired during plugin activation
 *
 * @link       https://pablocianes.com/
 * @since      1.0.0
 *
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/core
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/core
 * @author     Pablo Cianes <pablo@pablocianes.com>
 */
class Simple_Blueprint_Installer_Activator {

	/**
	 * Set option to redirect to new settings page of this plugin after activate
	 *
	 * When the plugin is activate this option control to redirect only the first time
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$all_plugins_installed_by_slug = Simple_Blueprint_Installer_Control::get_all_plugins_installed_by_slug();

		add_option( 'sbi_do_activation_redirect', 'redirect' );
		add_option( 'sbi_plugins_string', $all_plugins_installed_by_slug );

	}

}
