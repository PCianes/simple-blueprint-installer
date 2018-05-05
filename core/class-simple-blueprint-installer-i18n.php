<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://pablocianes.com/
 * @since      1.0.0
 *
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/core
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/core
 * @author     Pablo Cianes <pablo@pablocianes.com>
 */
class Simple_Blueprint_Installer_I18n {

	/**
	 * Unique identifier for retrieving translated strings.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $domain    Unique identifier for retrieving translated strings.
	 */
	protected $domain;

	/**
	 * Initialize the text domain for i18n.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $domain ) {
		$this->domain = $domain;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
