<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://pablocianes.com/
 * @since      1.0.0
 *
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/admin
 * @author     Pablo Cianes <pablo@pablocianes.com>
 */
class Simple_Blueprint_Installer_Admin {

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

		$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for the Admin facing functionality.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for install and activate plugins in the same screen
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-simple-blueprint-installer-control.php';
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Blueprint_Installer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Blueprint_Installer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/simple-blueprint-installer-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Blueprint_Installer_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Blueprint_Installer_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-blueprint-installer-admin.js', array( 'jquery' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name,
			'sbi_installer_localize',
			array(
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'admin_nonce'   => wp_create_nonce( 'sbi_installer_nonce' ),
				'install_btn'   => __( 'Install Now', 'simple-blueprint-installer' ),
				'activate_btn'  => __( 'Activate', 'simple-blueprint-installer' ),
				'installed_btn' => __( 'Activated', 'simple-blueprint-installer' ),
			)
		);

	}

	/**
	 * Redirect to settings page the first time this plugin is activate
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function redirect_to_settings_page() {
		if ( 'redirect' === get_option( 'sbi_do_activation_redirect', false ) ) {
			update_option( 'sbi_do_activation_redirect', 'activate' );
			wp_safe_redirect( admin_url( 'plugin-install.php?tab=sbi_blueprint' ) );
			exit;
		}
	}

	/**
	 * Add new custom tabs into plugins-install.php
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param   array $tabs All tabs register in WordPress for plugin-install.php.
	 */
	public function add_custom_tabs( $tabs ) {
		$tabs['sbi_blueprint'] = __( 'Blueprint', 'simple-blueprint-installer' );
		$tabs['sbi_setup']     = '<span class="dashicons dashicons-admin-generic"></span>';
		return $tabs;
	}

	/**
	 * Display the blueprint page of the plugin
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function display_plugin_blueprint_tab() {
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		Simple_Blueprint_Installer_Control::display_plugins();
	}

	/**
	 * Display the settings page of the plugin
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function display_plugin_settings_tab() {
		echo '<h2>SBI options</h2>';
	}

}
