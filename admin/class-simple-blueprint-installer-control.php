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
			$plugin = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
		}

		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $plugin,
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

		$json = array(
			'status' => $status,
			'msg'    => $msg,
		);

		wp_send_json( $json );

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
			$plugin = sanitize_text_field( wp_unslash( $_POST['plugin'] ) );
		}

		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $plugin,
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
			$main_plugin_file = self::get_plugin_file( $plugin );
			$status           = 'success';
			if ( $main_plugin_file ) {
				activate_plugin( $main_plugin_file );
				$msg = $api->name . ' successfully activated.';
			}
		} else {
			$status = 'failed';
			$msg    = 'There was an error activating ' . $api->name . '.';
		}

		$json = array(
			'status' => $status,
			'msg'    => $msg,
		);

		wp_send_json( $json );

	}


	/**
	 * Init the display of the plugins.
	 *
	 * @since    1.0.0
	 * @param   array $plugins Slugs of the plugins to display.
	 */
	public static function init_display( $plugins ) {
	?>
	<div class="sbi-plugin-installer">
	<?php
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

	foreach ( $plugins as $plugin ) :

		$button_classes = 'install button';
		$button_text    = __( 'Install Now', 'simple-blueprint-installer' );
		$api            = plugins_api(
			'plugin_information',
			array(
				'slug'   => sanitize_file_name( $plugin['slug'] ),
				'fields' => array(
					'short_description' => true,
					'sections'          => false,
					'requires'          => false,
					'downloaded'        => true,
					'last_updated'      => false,
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
			$main_plugin_file = self::get_plugin_file( $plugin['slug'] );

			if ( self::check_file_extension( $main_plugin_file ) ) {
				if ( is_plugin_active( $main_plugin_file ) ) {
					$button_classes = 'button disabled';
					$button_text    = __( 'Activated', 'simple-blueprint-installer' );
				} else {
					$button_classes = 'activate button button-primary';
					$button_text    = __( 'Activate', 'simple-blueprint-installer' );
				}
			}

			// Send plugin data to template.
			self::render_template( $plugin, $api, $button_text, $button_classes );

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

	/**
	 * Render display template for each plugin.
	 *
	 * @since 1.0.0
	 * @param array  $plugin Original data passed to init().
	 * @param array  $api Results from plugins_api.
	 * @param string $button_text Text for the button.
	 * @param string $button_classes Classnames for the button.
	 */
	public static function render_template( $plugin, $api, $button_text, $button_classes ) {
		if ( isset( $api->icons['1x'] ) ) {
			  $icon = $api->icons['1x'];
		} else {
			$icon = $api->icons['default'];
		}
		?>
		<div class="plugin">
		  <div class="plugin-wrap">
			  <img src="<?php echo $icon; ?>" alt="">
		   <h2><?php echo $api->name; ?></h2>
		   <p><?php echo $api->short_description; ?></p>
		   <p class="plugin-author"><?php _e( 'By', 'simple-blueprint-installer' ); ?> <?php echo $api->author; ?></p>
		   </div>
		   <ul class="activation-row">
		   <li>
			  <a class="<?php echo $button_classes; ?>"
				  data-slug="<?php echo $api->slug; ?>"
							data-name="<?php echo $api->name; ?>"
								href="<?php echo get_admin_url(); ?>/update.php?action=install-plugin&amp;plugin=<?php echo $api->slug; ?>&amp;_wpnonce=<?php echo wp_create_nonce( 'install-plugin_' . $api->slug ); ?>">
						<?php echo $button_text; ?>
			  </a>
		   </li>
		   <li>
			  <a href="https://wordpress.org/plugins/<?php echo $api->slug; ?>/" target="_blank">
					<?php _e( 'More Details', 'simple-blueprint-installer' ); ?>
			  </a>
		   </li>
			</ul>
		 </div>
		<?php
	}

}
