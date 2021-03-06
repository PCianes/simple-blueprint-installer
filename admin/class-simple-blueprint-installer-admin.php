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
			'wp-config-sample.php' => trailingslashit( ABSPATH ) . 'wp-config-sample.php',
			'readme.html'          => trailingslashit( ABSPATH ) . 'readme.html',
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

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/simple-blueprint-installer-admin.min.css', array(), $this->version, 'all' );

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

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-blueprint-installer-admin.min.js', array( 'jquery' ), $this->version, false );

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
	 * Add into this plugin new link to settings & blueprint tabs
	 * in the page of all plugins 'plugins.php'
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param array $links Array with all the links of the plugin.
	 */
	public function add_settings_link_on_plugins_table( $links ) {

		$blueprint_link = '<a href="plugin-install.php?tab=sbi_blueprint">' . esc_html__( 'Blueprint', 'simple-blueprint-installer' ) . '</a>';
		array_unshift( $links, $blueprint_link );

		$settings_link = '<a href="plugin-install.php?tab=sbi_setup">' . esc_html__( 'Settings', 'simple-blueprint-installer' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
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

		/**
		 * Enqueue scripts only into this plugin tab
		 */
		wp_enqueue_script( $this->plugin_name );
		wp_enqueue_style( $this->plugin_name );

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

		/**
		 * Enqueue scripts only into this plugin tab.
		 */
		wp_enqueue_script( $this->plugin_name );
		wp_enqueue_style( $this->plugin_name );

		/**
		 * Cleaning Tasks.
		 */
		$default_post = get_the_title( 1 );
		$default_page = get_the_title( 2 );

		$themes        = wp_get_themes();
		$current_theme = get_template();
		if ( 1 !== count( $themes ) ) {
			$themes_names = '';
			foreach ( $themes as $theme_name => $theme_object ) {
				if ( $theme_name === $current_theme ) {
					continue; }
				$themes_names .= '<code>' . $theme_name . '</code> ';
			}
		} else {
			$themes = false;
		}

		$files_to_delete      = '';
		$files_already_delete = '';
		foreach ( $this->files_to_delete as $file_name => $file_url ) {
			if ( file_exists( $file_url ) ) {
				$files_to_delete .= '<code>' . $file_name . '</code> ';
			} else {
				$files_already_delete .= '<code>' . $file_name . '</code> ';
			}
		}
		$allowed_html = array(
			'p'        => array(),
			'span'     => array(
				'class' => array(),
			),
			'strong'   => array(),
			'br'       => array(),
			'code'     => array(),
			'select'   => array(),
			'optgroup' => array(
				'label'    => array(),
				'value'    => array(),
				'selected' => array(),
			),
			'option'   => array(
				'label'    => array(),
				'value'    => array(),
				'selected' => array(),
			),
		);

		/**
		 * Other Tasks.
		 */
		require_once ABSPATH . '/wp-admin/includes/translation-install.php';
		$languages    = get_available_languages();
		$translations = wp_get_available_translations();
		$locale       = get_locale();
		if ( ! in_array( $locale, $languages, true ) ) {
			$locale = '';
		}

		$current_offset = get_option( 'gmt_offset' );
		$tzstring       = get_option( 'timezone_string' );
		if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
			$tzstring = '';
		}
		if ( empty( $tzstring ) ) {
			if ( 0 === $current_offset ) {
				$tzstring = 'UTC+0';
			} elseif ( $current_offset < 0 ) {
				$tzstring = 'UTC' . $current_offset;
			} else {
				$tzstring = 'UTC+' . $current_offset;
			}
		}

		$date_formats = array_unique( apply_filters( 'date_formats', array( __( 'F j, Y' ), 'Y-m-d', 'm/d/Y', 'd/m/Y' ) ) );
		$time_formats = array_unique( apply_filters( 'time_formats', array( __( 'g:i a' ), 'g:i A', 'H:i' ) ) );

		$category_name  = get_cat_name( 1 );
		$category_base  = get_option( 'category_base' );
		$tag_base       = get_option( 'tag_base' );
		$permalink      = get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : '/%postname%/';
		$available_tags = array( 'year', 'monthnum', 'day', 'hour', 'minute', 'second', 'post_id', 'postname', 'category', 'author' );

		if ( 'open' === get_option( 'default_comment_status' ) || 'open' === get_option( 'default_ping_status' ) || '1' === get_option( 'default_pingback_flag' ) ) {
			$pings = true;
		} else {
			$pings = false;
		}

		/**
		 * Include here all html with a view file.
		 */
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

		/**
		 * Handle translation installation.
		 */
		if ( ! empty( $_POST['WPLANG'] ) && current_user_can( 'install_languages' ) ) {
			require_once ABSPATH . 'wp-admin/includes/translation-install.php';

			if ( wp_can_install_language_pack() ) {
				$language = wp_download_language_pack( sanitize_text_field( wp_unslash( $_POST['WPLANG'] ) ) );
				if ( $language ) {
					$_POST['WPLANG'] = $language;
					$this->set_wplang( sanitize_text_field( wp_unslash( $_POST['WPLANG'] ) ) );
				}
			}
		}

		/**
		 * Map UTC+- timezones to gmt_offsets and set timezone_string to empty.
		 */
		if ( ! empty( $_POST['timezone_string'] ) ) {
			$timezone_string = sanitize_text_field( wp_unslash( $_POST['timezone_string'] ) );
		}
		if ( ! empty( $timezone_string ) && preg_match( '/^UTC[+-]/', $timezone_string ) ) {
			$_POST['gmt_offset']      = $timezone_string;
			$_POST['gmt_offset']      = preg_replace( '/UTC\+?/', '', $timezone_string );
			$_POST['timezone_string'] = '';
			$timezone_string          = '';
		}

		/**
		 * Cleaning Tasks.
		 */
		if ( ! empty( $_POST['hello'] ) && 'on' === $_POST['hello'] ) {
			$this->delete_post_by_id( 1 );
		}
		if ( ! empty( $_POST['sample'] ) && 'on' === $_POST['sample'] ) {
			$this->delete_post_by_id( 2 );
		}
		if ( ! empty( $_POST['themes'] ) && 'on' === $_POST['themes'] ) {
			$this->delete_themes_except_given( get_template() );
		}
		if ( ! empty( $_POST['files'] ) && 'on' === $_POST['files'] ) {
			$this->delete_wp_core_unnecessary_files( $this->files_to_delete );
		}

		/**
		 * Other Tasks.
		 */
		if ( '' === $_POST['WPLANG'] ) {
			$this->set_wplang( '' );
		}
		$gmt_offset ='';
		if ( ! empty( $_POST['gmt_offset'] ) ) {
			$gmt_offset = sanitize_text_field( wp_unslash( $_POST['gmt_offset'] ) );
		}
		$timezone_array = array(
			'timezone_string' => $timezone_string,
			'gmt_offset'      => $gmt_offset,
		);
		$this->set_timezone( $timezone_array );

		if ( isset( $_POST['date_format'] ) && '' !== $_POST['date_format'] ) {
			$this->set_date_format( sanitize_text_field( wp_unslash( $_POST['date_format'] ) ) );
		}
		if ( isset( $_POST['time_format'] ) && '' !== $_POST['time_format'] ) {
			$this->set_time_format( sanitize_text_field( wp_unslash( $_POST['time_format'] ) ) );
		}

		if ( isset( $_POST['category'] ) && '' !== $_POST['category'] ) {
			$this->set_default_category_name( sanitize_text_field( wp_unslash( $_POST['category'] ) ) );
		}
		if ( isset( $_POST['category_base'] ) || '' === $_POST['category_base'] ) {
			$this->set_category_base( sanitize_text_field( wp_unslash( $_POST['category_base'] ) ) );
		}
		if ( isset( $_POST['tag_base'] ) || '' === $_POST['tag_base'] ) {
			$this->set_tag_base( sanitize_text_field( wp_unslash( $_POST['tag_base'] ) ) );
		}
		if ( isset( $_POST['permalink'] ) && '' !== $_POST['permalink'] ) {
			$this->set_custom_permalink( sanitize_option( 'permalink_structure', wp_unslash( $_POST['permalink'] ) ) );
		}
		if ( isset( $_POST['pings'] ) && 'on' === $_POST['pings'] ) {
			$this->disable_pings_trackbacks_comments();
		}

		if ( isset( $_POST['media'] ) ) {
			$this->update_media_options( sanitize_text_field( wp_unslash( $_POST['media'] ) ) );
		} else {
			$this->update_media_options( false );
		}
		if ( isset( $_POST['indexing'] ) && '0' === $_POST['indexing'] ) {
			$this->update_indexing_options( '0' );
		} else {
			$this->update_indexing_options( '1' );
		}

		/**
		 * The last thing to do
		 */
		flush_rewrite_rules();

		/**
		 * Go back to settings tab
		 */
		if ( ! empty( $_POST['_wp_http_referer'] ) ) {
			wp_safe_redirect( esc_url( $_POST['_wp_http_referer'] ) );
			exit;
		}

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

		wp_delete_post( (int) $post_id, true );

	}

	/**
	 * Delete all themes except for current theme
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   string $current_theme The name of the current theme to avoid delete.
	 */
	private function delete_themes_except_given( $current_theme ) {

		foreach ( wp_get_themes() as $theme_name => $theme_object ) {
			if ( $theme_name === $current_theme ) {
				continue; }
			delete_theme( $theme_name );
		}

	}

	/**
	 * Delete WordPress core unnecessary files
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   array $files_to_delete Array with the path of files to delete.
	 */
	private function delete_wp_core_unnecessary_files( $files_to_delete ) {

		foreach ( $files_to_delete as $file_url ) {
			if ( file_exists( $file_url ) ) {
				unlink( $file_url );
			}
		}

	}

	/**
	 * Set new WPLANG
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   string $value String with the option to change.
	 */
	private function set_wplang( $value ) {

		$user_language_old = get_user_locale();

		if ( ! is_array( $value ) ) {
			$value = trim( $value );
		}
		$value = wp_unslash( $value );
		update_option( 'WPLANG', $value );

		/*
		 * Switch translation in case WPLANG was changed.
		 * The global $locale is used in get_locale() which is
		 * used as a fallback in get_user_locale().
		 */
		unset( $GLOBALS['locale'] );
		$user_language_new = get_user_locale();
		if ( $user_language_old !== $user_language_new ) {
			load_default_textdomain( $user_language_new );
		}
	}

	/**
	 * Set new timezone
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   array $timezone Array with the options to change.
	 */
	private function set_timezone( $timezone ) {

		foreach ( $timezone as $key => $value ) {
			if ( isset( $value ) ) {
				if ( ! is_array( $value ) ) {
					$value = trim( $value );
				}
				$value = wp_unslash( $value );
			}
			update_option( $key, $value );
		}
	}

	/**
	 * Set new date format
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   string $value String with the option to change.
	 */
	private function set_date_format( $value ) {

		if ( ! is_array( $value ) ) {
			$value = trim( $value );
		}
		$value = wp_unslash( $value );
		update_option( 'date_format', $value );

	}

	/**
	 * Set new time format
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   string $value String with the option to change.
	 */
	private function set_time_format( $value ) {

		if ( ! is_array( $value ) ) {
			$value = trim( $value );
		}
		$value = wp_unslash( $value );
		update_option( 'time_format', $value );

	}

	/**
	 * Disable pings, trackbacks and comments on new articles
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function disable_pings_trackbacks_comments() {

		if ( 'open' === get_option( 'default_comment_status' ) ) {
			update_option( 'default_comment_status', 'closed' );
		}

		if ( 'open' === get_option( 'default_ping_status' ) ) {
			update_option( 'default_ping_status', 'closed' );
		}

		if ( '1' === get_option( 'default_pingback_flag' ) ) {
			update_option( 'default_pingback_flag', '' );
		}

	}

	/**
	 * Update media options
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    string $option The selection to update media options.
	 */
	private function update_media_options( $option ) {

		if ( $option ) {
			update_option( 'uploads_use_yearmonth_folders', '1' );
		} else {
			update_option( 'uploads_use_yearmonth_folders', '' );
		}

	}

	/**
	 * Update indexing options
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    string $option The selection to update indexing options.
	 */
	private function update_indexing_options( $option ) {
		update_option( 'blog_public', $option );
	}

	/**
	 * Set default category name
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   string $new_category_name String with the new name of the general category of posts.
	 */
	private function set_default_category_name( $new_category_name ) {

		$new_category_name = sanitize_text_field( $new_category_name );

		wp_update_term(
			1,
			'category',
			array(
				'name' => $new_category_name,
				'slug' => str_replace( ' ', '-', strtolower( $new_category_name ) ),
			)
		);
	}

	/**
	 * Set default base category of urls
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   string $new_category_base String with the new name of the base category.
	 */
	private function set_category_base( $new_category_base ) {
		global $wp_rewrite;
		$wp_rewrite->set_category_base( '/' . sanitize_text_field( $new_category_base ) );
	}

	/**
	 * Set default base tag of urls
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   string $new_tag_base String with the new name of the base tag.
	 */
	private function set_tag_base( $new_tag_base ) {
		global $wp_rewrite;
		$wp_rewrite->set_tag_base( '/' . sanitize_text_field( $new_tag_base ) );
	}

	/**
	 * Set new custom permalink
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param   string $new_custom_permalink String with the new custom permalink to set.
	 */
	private function set_custom_permalink( $new_custom_permalink ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( sanitize_option( 'permalink_structure', $new_custom_permalink ) );
	}

}
