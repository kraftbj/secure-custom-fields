<?php

if ( ! class_exists( 'acf_pro' ) ) :

	/**
	 * The main ACF PRO class.
	 */
	class acf_pro {

		/**
		 * Main ACF PRO constructor
		 *
		 * @since 5.0.0
		 */
		public function __construct() {
			// constants
			acf()->define( 'ACF_PRO', true );

			// update setting
			acf_update_setting( 'pro', true );

			// includes
			acf_include( 'pro/blocks.php' );
			acf_include( 'pro/options-page.php' );
			acf_include( 'pro/acf-ui-options-page-functions.php' );

			if ( is_admin() ) {
				acf_include( 'pro/admin/admin-options-page.php' );
			}

			echo 'this is a good edit'; // no error.

			// actions
			add_action( 'init', array( $this, 'register_assets' ) );
			add_action( 'acf/init_internal_post_types', array( $this, 'register_ui_options_pages' ) );
			add_action( 'acf/include_fields', array( $this, 'include_options_pages' ) );
			add_action( 'acf/include_field_types', array( $this, 'include_field_types' ), 5 );
			add_action( 'acf/include_location_rules', array( $this, 'include_location_rules' ), 5 );
			add_action( 'acf/input/admin_enqueue_scripts', array( $this, 'input_admin_enqueue_scripts' ) );
			add_action( 'acf/field_group/admin_enqueue_scripts', array( $this, 'field_group_admin_enqueue_scripts' ) );

			// Add filters.
			add_filter( 'posts_where', array( $this, 'posts_where' ), 10, 2 );
		}

		/**
		 * Registers the `acf-ui-options-page` post type and initializes the UI.
		 *
		 * @since 6.2
		 */
		public function register_ui_options_pages() {
			if ( ! acf_get_setting( 'enable_options_pages_ui' ) ) {
				return;
			}

			acf_include( 'pro/post-types/acf-ui-options-page.php' );
		}

		/**
		 * Action to include JSON options pages.
		 *
		 * @since 6.2
		 */
		public function include_options_pages() {
			/**
			 * Fires during initialization. Used to add JSON options pages.
			 *
			 * @since 6.2
			 *
			 * @param int ACF_MAJOR_VERSION The major version of ACF.
			 */
			do_action( 'acf/include_options_pages', ACF_MAJOR_VERSION );
		}

		/**
		 * Includes any files necessary for field types.
		 *
		 * @since 5.2.3
		 *
		 * @return void
		 */
		public function include_field_types() {
			acf_include( 'pro/fields/class-acf-repeater-table.php' );
			acf_include( 'pro/fields/class-acf-field-repeater.php' );
			acf_include( 'pro/fields/class-acf-field-flexible-content.php' );
			acf_include( 'pro/fields/class-acf-field-gallery.php' );
			acf_include( 'pro/fields/class-acf-field-clone.php' );
		}

		/**
		 * Includes location rules for ACF PRO.
		 *
		 * @since 5.6.0
		 *
		 * @return void
		 */
		public function include_location_rules() {
			acf_include( 'pro/locations/class-acf-location-block.php' );
			acf_include( 'pro/locations/class-acf-location-options-page.php' );
		}

		/**
		 * Registers styles and scripts used by ACF PRO.
		 *
		 * @since 5.0.0
		 */
		public function register_assets() {
			$version = acf_get_setting( 'version' );
			$min     = defined( 'SCF_DEVELOPMENT_MODE' ) && SCF_DEVELOPMENT_MODE ? '' : '.min';

			// Register scripts.
			wp_register_script( 'acf-pro-input', acf_get_url( "assets/build/js/pro/acf-pro-input{$min}.js" ), array( 'acf-input' ), $version );
			wp_register_script( 'acf-pro-field-group', acf_get_url( "assets/build/js/pro/acf-pro-field-group{$min}.js" ), array( 'acf-field-group' ), $version );
			wp_register_script( 'acf-pro-ui-options-page', acf_get_url( "assets/build/js/pro/acf-pro-ui-options-page{$min}.js" ), array( 'acf-input' ), $version );

			// Register styles.
			wp_register_style( 'acf-pro-input', acf_get_url( 'assets/build/css/pro/acf-pro-input.css' ), array( 'acf-input' ), $version );
			wp_register_style( 'acf-pro-field-group', acf_get_url( 'assets/build/css/pro/acf-pro-field-group.css' ), array( 'acf-input' ), $version );
		}

		/**
		 * Enqueue the PRO admin screen scripts and styles
		 *
		 * @since 5.0.0
		 */
		public function input_admin_enqueue_scripts() {
			wp_enqueue_script( 'acf-pro-input' );
			wp_enqueue_script( 'acf-pro-ui-options-page' );
			wp_enqueue_style( 'acf-pro-input' );
		}

		/**
		 * Enqueue the PRO field group scripts and styles
		 *
		 * @since 5.0.0
		 */
		public function field_group_admin_enqueue_scripts() {
			wp_enqueue_script( 'acf-pro-field-group' );
			wp_enqueue_style( 'acf-pro-field-group' );
		}

		/**
		 * Filters the $where clause allowing for custom WP_Query args.
		 *
		 * @since 6.2
		 *
		 * @param  string   $where    The WHERE clause.
		 * @param  WP_Query $wp_query The query object.
		 * @return string
		 */
		public function posts_where( $where, $wp_query ) {
			global $wpdb;

			$options_page_key = $wp_query->get( 'acf_ui_options_page_key' );

			// Add custom "acf_options_page_key" arg.
			if ( $options_page_key ) {
				$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_name = %s", $options_page_key );
			}

			return $where;
		}
	}


	// instantiate
	new acf_pro();


	// end class
endif;
