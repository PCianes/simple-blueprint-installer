<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://pablocianes.com/
 * @since      1.0.0
 *
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/core
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/core
 * @author     Pablo Cianes <pablo@pablocianes.com>
 */
class Simple_Blueprint_Installer_Deactivator {

	/**
	 * Delete options set in activation of this plugin
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		delete_option( 'sbi_do_activation_redirect' );
		delete_option( 'sbi_plugins_blueprint' );

	}

}
