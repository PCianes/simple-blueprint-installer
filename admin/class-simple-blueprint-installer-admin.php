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
	 * The WordPress unnecessary files to delete
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $files_to_delete    The path of WP unnecessary files to delete
	 */
	private $files_to_delete;

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

		$this->files_to_delete = array(
			'wp-config-sample.php'	=> trailingslashit( ABSPATH ) . 'wp-config-sample.php',
			'readme.html'			=> trailingslashit( ABSPATH ) . 'readme.html',
		);

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
				'install_btn'   => esc_html__( 'Install Now', 'simple-blueprint-installer' ),
				'activate_btn'  => esc_html__( 'Activate', 'simple-blueprint-installer' ),
				'installed_btn' => esc_html__( 'Activated', 'simple-blueprint-installer' ),
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
		$tabs['sbi_blueprint'] = esc_html__( 'Blueprint', 'simple-blueprint-installer' );
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
		Simple_Blueprint_Installer_Control::setup_blueprint();
	}

	/**
	 * Display the settings page of the plugin
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function display_plugin_settings_tab() {
		$default_post = get_the_title( 1 );
		$default_page = get_the_title( 2 );
		$category_name = get_cat_name( 1 );
		$permalink = get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : '/%postname%/';
		$allow_html = array( 'code' => array() );

		$themes = wp_get_themes();
		$current_theme = get_template();
		if ( 1 != count( $themes ) ) {
			$themes_names = '';
			foreach ( $themes as $theme_name => $theme_object ) {
				if ( $theme_name == $current_theme ) { continue; }
				$themes_names .= '<code>' . $theme_name . '</code> ';
			}
		} else {
			$themes = false;
		}

		$files_to_delete = '';
		$files_already_delete = '';
		foreach ( $this->files_to_delete as $file_name => $file_url ) {
			if ( file_exists( $file_url ) ) {
				$files_to_delete .= '<code>' . $file_name . '</code> ';
			} else {
				$files_already_delete .= '<code>' . $file_name . '</code> ';
			}
		}

		if ( 'open' === get_option( 'default_comment_status' ) || 'open' === get_option( 'default_ping_status' ) || '1' === get_option( 'default_pingback_flag' ) ) {
			$pings = true;
		} else {
			$pings = false;
		}

		$available_tags = array('year','monthnum','day','hour','minute','second','post_id','postname','category','author');
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/simple-blueprint-installer-plugin-settings-view.php';
	}

	/**
	 * Controller of settings tab to execute marked actions into the form
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function control_form_settings_tab() {

		check_admin_referer( 'sbi_setup_form', 'sbi_setup_nonce' );

		if( 'on' == $_POST['hello'] ){
			$this->delete_post_by_id( 1 );
		}
		if( 'on' == $_POST['sample'] ){
			$this->delete_post_by_id( 2 );
		}
		if( 'on' == $_POST['themes'] ){
			$this->delete_themes_except_given( get_template() );
		}
		if( 'on' == $_POST['files'] ){
			$this->delete_wp_core_unnecessary_files( $this->files_to_delete );
		}
		if( 'on' == $_POST['pings'] ){
			$this->disable_pings_trackbacks_comments();
		}
		if( isset( $_POST['category'] ) && '' != $_POST['category'] ){
		}
		if( isset( $_POST['permalink'] ) && '' != $_POST['permalink'] ){
		}
		if( 'on' == $_POST['deactivate'] ){
			$this->deactivate_this_plugin();
		}

		wp_redirect( esc_url( $_POST['_wp_http_referer'] ) );
		exit;
	}

	/**
	 * Remove a post/page by id and everything that is tied to it is deleted also.
	 * This includes comments, post meta fields, and terms associated with the post.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   integer $post_id The id of the post to delete.
	 */
	private function delete_post_by_id( $post_id ) {

		wp_delete_post( (int) $post_id , true );

	}

	/**
	 * Delete all themes except for current theme
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   string $current_theme The name of the current theme to avoid delete.
	 */
	private function delete_themes_except_given( $current_theme ) {

		foreach( wp_get_themes() as $theme_name => $theme_object ) {
			if ( $theme_name == $current_theme ) { continue; }
			delete_theme( $theme_name );
		}

	}

	/**
	 * Delete WordPress core unnecessary files
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   array $files_to_delete Array with the path of files to delete
	 */
	private function delete_wp_core_unnecessary_files( $files_to_delete ) {

		foreach ( $files_to_delete as $file_url ) {
			if ( file_exists( $file_url ) ) {
				unlink( $file_url );
			}
		}

	}

	/**
	 * Disable pings, trackbacks and comments on new articles
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function disable_pings_trackbacks_comments() {

		if( 'open' === get_option( 'default_comment_status' ) ) {
			update_option( 'default_comment_status', 'closed' );
		}

		if( 'open' === get_option( 'default_ping_status' ) ) {
			update_option( 'default_ping_status', 'closed' );
		}

		if( '1' === get_option( 'default_pingback_flag' ) ) {
			update_option( 'default_pingback_flag', '' );
		}

	}

	/**
	 * Deactivate this plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function deactivate_this_plugin() {

		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		deactivate_plugins( 'simple-blueprint-installer/simple-blueprint-installer.php' );

	}

}
