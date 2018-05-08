<?php
/**
 * The functionality and control to install and activate plugins
 *
 * @link       https://pablocianes.com/
 * @since      1.0.0
 *
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/includes
 */

/**
 * All about the control to install all in the same page
 *
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/includes
 * @author     Pablo Cianes <pablo@pablocianes.com>
 */
class Simple_Blueprint_Installer_Control {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * An Ajax method for installing plugin. Return $json to admin frontend.
	 *
	 * @since    1.0.0
	 */
	public function sbi_plugin_installer() {

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to install plugins on this site.', 'simple-blueprint-installer' ) );
		}

		if ( ! check_ajax_referer( 'sbi_installer_nonce', 'nonce' ) ) {
			wp_die( esc_html__( 'Error - unable to verify nonce, please try again.', 'simple-blueprint-installer' ) );
		}

		if ( ! empty( $_POST['plugin'] ) ) {
			$plugin_slug = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
		}

		wp_send_json( self::install_plugin( $plugin_slug ) );

	}

	/**
	 * Install a plugin by slug
	 *
	 * @since    1.0.0
	 * @param   string $plugin_slug Slug of the plugins to install
	 */
	public static function install_plugin( $plugin_slug ) {

		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $plugin_slug,
				'fields' => array(
					'short_description' => false,
					'sections'          => false,
					'requires'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'compatibility'     => false,
					'homepage'          => false,
					'donate_link'       => false,
				),
			)
		);

		$skin     = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );
		$upgrader->install( $api->download_link );

		if ( $api->name ) {
			$status = 'success';
			$msg    = $api->name . ' successfully installed.';
		} else {
			$status = 'failed';
			$msg    = 'There was an error installing ' . $api->name . '.';
		}

		return array(
			'status' => $status,
			'msg'    => $msg,
		);

	}

	/**
	 * Activate plugin via Ajax. Return $json to admin frontend.
	 *
	 * @since    1.0.0
	 */
	public function sbi_plugin_activation() {

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to activate plugins on this site.', 'simple-blueprint-installer' ) );
		}

		if ( ! check_ajax_referer( 'sbi_installer_nonce', 'nonce' ) ) {
			wp_die( esc_html__( 'Error - unable to verify nonce, please try again.', 'simple-blueprint-installer' ) );
		}

		if ( ! empty( $_POST['plugin'] ) ) {
			$plugin_slug = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
		}

		wp_send_json( self::activate_plugin( $plugin_slug ) );

	}

	/**
	 * Activate a plugin by slug
	 *
	 * @since    1.0.0
	 * @param   string $plugin_slug Slug of the plugins to activate
	 */
	public static function activate_plugin( $plugin_slug ) {

		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $plugin_slug,
				'fields' => array(
					'short_description' => false,
					'sections'          => false,
					'requires'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'compatibility'     => false,
					'homepage'          => false,
					'donate_link'       => false,
				),
			)
		);

		if ( $api->name ) {
			$main_plugin_file = self::get_plugin_file( $plugin_slug );
			$status           = 'success';
			if ( $main_plugin_file ) {
				activate_plugin( $main_plugin_file );
				$msg = $api->name . ' successfully activated.';
			}
		} else {
			$status = 'failed';
			$msg    = 'There was an error activating ' . $api->name . '.';
		}

		return array(
			'status' => $status,
			'msg'    => $msg,
		);

	}

	/**
	 * Init the display of the plugins.
	 *
	 * @since    1.0.0
	 * @param   array $plugins Slugs of the plugins to display.
	 */
	public static function display_plugins( $plugins ) {
		?>
		<div class="sbi-plugin-installer">
		<?php

		foreach ( $plugins as $plugin_slug ) :

			$button_classes = 'install button';
			$button_text    = __( 'Install Now', 'simple-blueprint-installer' );
			$api            = plugins_api(
				'plugin_information',
				array(
					'slug'   => sanitize_file_name( $plugin_slug ),
					'fields' => array(
						'short_description' => true,
						'sections'          => false,
						'requires'          => false,
						'downloaded'        => true,
						'last_updated'      => true,
						'added'             => false,
						'tags'              => false,
						'compatibility'     => false,
						'homepage'          => false,
						'donate_link'       => false,
						'icons'             => true,
						'banners'           => true,
					),
				)
			);

			if ( ! is_wp_error( $api ) ) {
				$main_plugin_file = self::get_plugin_file( $plugin_slug );

				if ( self::check_file_extension( $main_plugin_file ) ) {
					if ( is_plugin_active( $main_plugin_file ) ) {
						$button_classes = 'button disabled';
						$button_text    = __( 'Activated', 'simple-blueprint-installer' );
					} else {
						$button_classes = 'activate button button-primary';
						$button_text    = __( 'Activate', 'simple-blueprint-installer' );
					}
				}

				if ( isset( $api->icons['1x'] ) ) {
					$icon = $api->icons['1x'];
				} else {
					$icon = $api->icons['default'];
				}

				include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/simple-blueprint-installer-plugin-view.php';

			}

		endforeach;
		?>
		</div>
		<?php
	}

	/**
	 * A method to get the main plugin file.
	 *
	 * @since    1.0.0
	 * @param   string $plugin_slug The slug of the plugin.
	 * @return  $plugin_file
	 */
	public static function get_plugin_file( $plugin_slug ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
		$plugins = get_plugins();

		foreach ( $plugins as $plugin_file => $plugin_info ) {
			$slug = dirname( plugin_basename( $plugin_file ) );
			if ( $slug ) {
				if ( $slug == $plugin_slug ) {
					return $plugin_file;
				}
			}
		}
		return null;
	}

	/**
	 * Helper to check if file extension is php
	 *
	 * @since    1.0.0
	 * @param    string $filename  The filename of the plugin.
	 * @return   boolean
	 */
	public static function check_file_extension( $filename ) {
		if ( substr( strrchr( $filename, '.' ), 1 ) === 'php' ) {
			return true;
		} else {
			return false;
		}
	}

}
