<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

class FrmProStylesController extends FrmStylesController {

	public static function load_pro_hooks() {
		if ( ! FrmAppHelper::is_admin_page( 'formidable-styles' ) && ! FrmAppHelper::is_admin_page( 'formidable-styles2' ) ) {
			return;
		}

		add_action( 'frm_sample_style_form', 'FrmProStylesController::append_style_form' );
		add_action( 'frm_style_switcher_heading', 'FrmProStylesController::style_dropdown' );
		add_filter( 'frm_style_head', 'FrmProStylesController::maybe_new_style' );
		add_filter( 'frm_style_action_route', 'FrmProStylesController::pro_route' );
		add_action( 'frm_style_settings_top', 'FrmProStylesController::add_new_button' );
		add_action( 'admin_enqueue_scripts', 'FrmProAppController::load_style_manager_js_assets' );
		add_action( 'frm_style_settings_input_atts', 'FrmProStylesController::echo_style_settings_input_atts' );
		add_action( 'frm_style_settings_general_section_after_background', 'FrmProStylesController::echo_bg_image_settings', 10 );
		add_action( 'frm_style_settings_general_section_after_background', 'FrmProStylesController::echo_additional_background_image_settings', 20 );

		self::admin_css();
	}

	private static function admin_css() {
		if ( FrmAppHelper::doing_ajax() ) {
			return;
		}

		$version = FrmAppHelper::plugin_version();
		wp_register_style( 'formidable-pro-style-settings', FrmProAppHelper::plugin_url() . '/css/settings/style-settings.css', array(), $version );
		wp_enqueue_style( 'formidable-pro-style-settings' );
	}

	public static function add_style_boxes( $boxes ) {
		$add_boxes = array(
			'section-fields'  => __( 'Section Fields', 'formidable-pro' ),
			'repeater-fields' => __( 'Repeater Fields', 'formidable-pro' ),
			'date-fields'     => __( 'Date Fields', 'formidable-pro' ),
			'toggle-fields'   => __( 'Toggle Fields', 'formidable-pro' ),
			'slider-fields'   => __( 'Slider Fields', 'formidable-pro' ),
			'progress-bars'   => __( 'Progress Bars &amp; Rootline', 'formidable-pro' ),
		);
		$boxes     = array_merge( $boxes, $add_boxes );

		foreach ( $add_boxes as $label => $name ) {
			add_filter( 'frm_style_settings_' . $label, 'FrmProStylesController::style_box_file' );
		}

		return $boxes;
	}

	/**
	 * @since 3.01.01
	 */
	public static function style_box_file( $f ) {
		$path = explode( '/views/styles/', $f );
		return self::view_folder() . '/' . $path[1];
	}

	/**
	 * @since 3.03
	 */
	public static function jquery_themes( $selected_style = 'none' ) {
		$themes = self::get_date_themes( $selected_style );
		return apply_filters( 'frm_jquery_themes', $themes );
	}

	/**
	 * @since 3.03
	 */
	private static function get_date_themes( $selected_style = 'none' ) {
		if ( self::use_default_style( $selected_style ) ) {
			return array(
				'ui-lightness' => 'Default',
			);
		}

		$themes = array(
			'ui-lightness'  => 'Default',
			'ui-darkness'   => 'UI Darkness',
			'smoothness'    => 'Smoothness',
			'start'         => 'Start',
			'redmond'       => 'Redmond',
			'sunny'         => 'Sunny',
			'overcast'      => 'Overcast',
			'le-frog'       => 'Le Frog',
			'flick'         => 'Flick',
			'pepper-grinder' => 'Pepper Grinder',
			'eggplant'      => 'Eggplant',
			'dark-hive'     => 'Dark Hive',
			'cupertino'     => 'Cupertino',
			'south-street'  => 'South Street',
			'blitzer'       => 'Blitzer',
			'humanity'      => 'Humanity',
			'hot-sneaks'    => 'Hot Sneaks',
			'excite-bike'   => 'Excite Bike',
			'vader'         => 'Vader',
			'dot-luv'       => 'Dot Luv',
			'mint-choc'     => 'Mint Choc',
			'black-tie'     => 'Black Tie',
			'trontastic'    => 'Trontastic',
			'swanky-purse'  => 'Swanky Purse',
			'-1'            => 'None',
		);

		return $themes;
	}

	/**
	 * @since 3.03
	 */
	public static function jquery_css_url( $theme_css ) {
		if ( $theme_css == -1 ) {
			return;
		}

		if ( self::use_default_style( $theme_css ) ) {
			$css_file = FrmProAppHelper::plugin_url() . '/css/ui-lightness/jquery-ui.css';
		} elseif ( preg_match( '/^http.?:\/\/.*\..*$/', $theme_css ) ) {
			$css_file = $theme_css;
		} else {
			$uploads = FrmStylesHelper::get_upload_base();
			$file_path = '/formidable/css/' . $theme_css . '/jquery-ui.css';
			if ( file_exists( $uploads['basedir'] . $file_path ) ) {
				$css_file = $uploads['baseurl'] . $file_path;
			} else {
				$css_file = FrmProAppHelper::jquery_ui_base_url() . '/themes/' . $theme_css . '/jquery-ui.min.css';
			}
		}

		return $css_file;
	}

	/**
	 * @since 3.03
	 */
	private static function use_default_style( $selected ) {
		return empty( $selected ) || 'ui-lightness' === $selected;
	}

	/**
	 * @since 3.03
	 */
	public static function enqueue_jquery_css() {
		$form = self::get_form_for_page();
		$theme_css = FrmStylesController::get_style_val( 'theme_css', $form );

		$action     = FrmAppHelper::get_param( 'frm_action', '', 'get', 'sanitize_text_field' );
		$is_builder = FrmAppHelper::is_admin_page( 'formidable' ) && $action !== 'settings';

		if ( $theme_css != -1 && ! $is_builder ) {
			// Without this line, datepickers load without proper styling in the Form Scheduling settings when you set the form status dropdown to "Schedule".
			wp_enqueue_style( 'jquery-theme', self::jquery_css_url( $theme_css ), array(), FrmAppHelper::plugin_version() );
		}
	}

	/**
	 * @since 3.03
	 */
	private static function get_form_for_page() {
		global $frm_vars;
		$form_id = 'default';
		if ( ! empty( $frm_vars['forms_loaded'] ) ) {
			foreach ( $frm_vars['forms_loaded'] as $form ) {
				if ( is_object( $form ) ) {
					$form_id = $form->id;
					break;
				}
			}
		}
		return $form_id;
	}

	public static function append_style_form( $atts ) {
		$style = $atts['style'];
		$pos_class = $atts['pos_class'];
		include( self::view_folder() . '/_sample_form.php' );
	}

	/**
	 * @since 4.0
	 */
	public static function add_new_button( $style ) {
		include( self::view_folder() . '/_style_switcher.php' );
	}

	/**
	 * @since 4.0
	 */
	public static function style_dropdown( $atts ) {
		$style    = $atts['style'];
		$styles   = $atts['styles'];
		$base_url = '?page=' . FrmAppHelper::simple_get( 'page', 'sanitize_title' ) . '&frm_action=edit';
		include( self::view_folder() . '/_style-dropdown.php' );
	}

	public static function maybe_new_style( $style ) {
		$action = FrmAppHelper::get_param( 'frm_action', '', 'get', 'sanitize_title' );
		if ( 'new_style' == $action ) {
			$style = self::new_style('style');
		} else if ( 'duplicate' == $action ) {
			$style = self::duplicate('style');
		}
		return $style;
	}

	public static function new_style( $return = '' ) {
		$frm_style = new FrmStyle();
		$style = $frm_style->get_new();

		if ( 'style' == $return ) {
			// return style object for header css link
			return $style;
		}

		self::load_styler($style);
	}

	public static function duplicate( $return = '' ) {
		$style_id = FrmAppHelper::get_param( 'style_id', 0, 'get', 'absint' );

		if ( ! $style_id ) {
			self::new_style( $return );
			return;
		}

		$frm_style = new FrmProStyle();
		$style = $frm_style->duplicate( $style_id );

		if ( 'style' == $return ) {
			// return style object for header css link
			return $style;
		}

		self::load_styler( $style );
	}

	public static function destroy() {
		$id = FrmAppHelper::simple_get( 'id', 'absint' );

		$frm_style = new FrmStyle();
		$frm_style->destroy($id);

		$message = __( 'Your styling settings have been deleted.', 'formidable-pro' );

		self::edit('default', $message);
	}

	public static function pro_route( $action ) {
		switch ( $action ) {
			case 'new_style':
			case 'duplicate':
			case 'destroy':
				add_filter('frm_style_stop_action_route', '__return_true');
				return self::$action();
		}
	}

	/**
	 * @param array $args {
	 *     @type array $defaults
	 * }
	 * @return void
	 */
	public static function include_front_css( $args ) {
		$defaults  = $args['defaults'];
		$important = self::is_important( $defaults );
		$vars      = self::css_vars();

		self::maybe_include_icon_font_css();
		include FrmProAppHelper::plugin_path() . '/css/pro_fields.css.php';
		include FrmProAppHelper::plugin_path() . '/css/chosen.css.php';
		include FrmProAppHelper::plugin_path() . '/css/dropzone.css';
		include FrmProAppHelper::plugin_path() . '/css/progress.css.php';
	}

	/**
	 * Maybe read the font icons CSS when including additional CSS for the front end.
	 * Various Pro fields require this including: Collapsible sections, strong password meters, star ratings, repeater buttons, datepicker arrows, and signature toggle buttons.
	 * This version check is to make sure that we do not include the font icon CSS twice as Lite version before 5.5.1 include font icons in front end CSS.
	 * We plan to migrate away from this in the near future in favour of using SVG instead of font icons.
	 *
	 * @return void
	 */
	private static function maybe_include_icon_font_css() {
		if ( version_compare( FrmAppHelper::plugin_version(), '5.5.1', '>=' ) ) {
			readfile( FrmAppHelper::plugin_path() . '/css/font_icons.css' );
		}
	}

	/**
	 * @since 3.01.01
	 */
	public static function add_defaults( $settings ) {
		self::set_toggle_slider_colors( $settings );
		self::set_toggle_date_colors( $settings );
		self::set_bg_image_settings( $settings );
		return $settings;
	}

	/**
	 * @since 3.01.01
	 */
	public static function override_defaults( $settings ) {
		if ( ! isset( $settings['toggle_on_color'] ) && isset( $settings['progress_active_bg_color'] ) ) {
			self::set_toggle_slider_colors( $settings );
		}

		if ( ! isset( $settings['date_head_bg_color'] ) && isset( $settings['progress_active_bg_color'] ) ) {
			self::set_toggle_date_colors( $settings );
		}

		return $settings;
	}

	/**
	 * @since 3.01.01
	 */
	private static function set_toggle_slider_colors( &$settings ) {
		$settings['toggle_font_size'] = $settings['font_size'];
		$settings['toggle_on_color']  = $settings['progress_active_bg_color'];
		$settings['toggle_off_color'] = $settings['progress_bg_color'];

		$settings['slider_font_size'] = '24px';
		$settings['slider_color']     = $settings['progress_active_bg_color'];
		$settings['slider_bar_color'] = $settings['progress_active_bg_color'];
	}

	/**
	 * @since 3.03
	 */
	private static function set_toggle_date_colors( &$settings ) {
		$settings['date_head_bg_color'] = $settings['progress_active_bg_color'];
		$settings['date_head_color']    = $settings['progress_active_color'];
		$settings['date_band_color']    = FrmStylesHelper::adjust_brightness( $settings['progress_active_bg_color'], -50 );
	}

	/**
	 * @since 5.0.08
	 */
	private static function set_bg_image_settings( &$settings ) {
		$settings['bg_image_id']      = '';
		$settings['bg_image_opacity'] = '100%';
	}

	/**
	 * This CSS is only loaded with the ajax call.
	 *
	 * @since 3.0
	 */
	public static function include_pro_fields_ajax_css() {
		header('Content-type: text/css');

		$defaults  = self::get_default_style();
		$important = self::is_important( $defaults );

		$vars = self::css_vars();
		if ( is_callable( 'FrmStylesHelper::get_css_vars' ) ) {
			$vars = FrmStylesHelper::get_css_vars( array_keys( $defaults ) );
		}

		include( FrmProAppHelper::plugin_path() . '/css/pro_fields.css.php' );
	}

	public static function output_single_style( $settings ) {
		$important = empty( $settings['important_style'] ) ? '' : ' !important';

		// calculate the top position based on field padding
		$top_pad    = explode( ' ', $settings['field_pad'] );
		$top_pad    = reset( $top_pad ); // the top padding is listed first
		$pad_unit   = preg_replace( '/[0-9]+/', '', $top_pad ); //px, em, rem...
		$top_margin = (int) str_replace( $pad_unit, '', $top_pad ) / 2;
		$defaults   = self::get_default_style();
		$vars       = self::css_vars();

		$bg_image_url     = false;
		$bg_image_opacity = false;
		if ( ! empty( $settings['bg_image_id'] ) ) {
			$bg_image_url = wp_get_attachment_url( $settings['bg_image_id'] );
			if ( false !== $bg_image_url && isset( $settings['bg_image_opacity'] ) ) {
				$bg_image_opacity = $settings['bg_image_opacity'];
				if ( '%' === $bg_image_opacity[ strlen( $bg_image_opacity ) - 1 ] ) {
					$bg_image_opacity = substr( $bg_image_opacity, 0, strlen( $bg_image_opacity ) - 1 );
				}
				if ( is_numeric( $bg_image_opacity ) ) {
					$bg_image_opacity = floatval( $bg_image_opacity ) / 100;
				} else {
					$bg_image_opacity = false;
				}
			}
		}

		include FrmProAppHelper::plugin_path() . '/css/single-style.css.php';
	}

	/**
	 * @since 4.05
	 */
	private static function get_default_style() {
		$frm_style = new FrmStyle();
		$default_style = $frm_style->get_default_style();
		if ( is_admin() && ! empty( $_POST ) && isset( $_POST['action'] ) && $_POST['action'] === 'frm_change_styling' ) {
			// Reset to prevent posted values from being used on styler page.
			$_POST['action'] = '';
		}
		return FrmStylesHelper::get_settings_for_output( $default_style );
	}

	/**
	 * This is here for version mismatch. It can be removed later.
	 *
	 * @since 4.05
	 */
	private static function css_vars() {
		if ( is_callable( 'FrmStylesHelper::get_css_vars' ) ) {
			return array();
		}

		$vars = array( 'progress_color', 'progress_bg_color', 'progress_active_bg_color', 'progress_border_size', 'border_color', 'border_radius', 'field_border_width', 'field_border_style', 'field_font_size', 'field_margin', 'text_color', 'field_pad', 'bg_color', 'submit_text_color', 'submit_font_size', 'border_color_error', 'text_color_error', 'bg_color_error', 'description_color', 'slider_font_size', 'slider_bar_color', 'section_border_width', 'section_border_style', 'section_border_color', 'section_font_size', 'section_bg_color', 'section_color', 'section_weight', 'toggle_off_color', 'toggle_on_color', 'toggle_font_size', 'check_weight', 'check_label_color' );
		return array_unique( $vars );
	}

	private static function view_folder() {
		return FrmProAppHelper::plugin_path() . '/classes/views/styles';
	}

	private static function is_important( $defaults ) {
		return ( isset( $defaults['important_style'] ) && ! empty( $defaults['important_style'] ) ) ? ' !important' : '';
	}

	/**
	 * Called when the frm_style_settings_general_section_after_background action is triggered the first time in the pro plugin.
	 *
	 * @since 5.0.08
	 *
	 * @param array $args with keys 'frm_style', 'style'.
	 */
	public static function echo_bg_image_settings( $args ) {
		$style = $args['style'];

		if ( ! empty( $style->post_content['bg_image_id'] ) ) {
			$bg_image_id       = absint( $style->post_content['bg_image_id'] );
			$bg_image          = wp_get_attachment_image( $bg_image_id );
			$bg_image_filepath = get_attached_file( $bg_image_id );
			$bg_image_filename = basename( $bg_image_filepath );
		} else {
			$bg_image_id       = 0;
			$bg_image          = '<img src="" class="frm_hidden" />';
			$bg_image_filepath = '';
			$bg_image_filename = '';
		}

		include self::view_folder() . '/_bg-image.php';
	}

	/**
	 * Called when the frm_style_settings_general_section_after_background action is triggered the second time in the pro plugin.
	 *
	 * @since 5.0.08
	 *
	 * @param array $args with keys 'frm_style', 'style'.
	 */
	public static function echo_additional_background_image_settings( $args ) {
		$style            = $args['style'];
		$hidden           = empty( $style->post_content['bg_image_id'] );
		$class            = $hidden ? 'frm_hidden ' : '';
		$class           .= 'frm_bg_image_additional_settings';
		$bg_image_opacity = isset( $style->post_content['bg_image_opacity'] ) ? $style->post_content['bg_image_opacity'] : '100%';
		include self::view_folder() . '/_bg-image-settings.php';
	}

	/**
	 * Called when the frm_style_settings_input_atts action is triggered.
	 *
	 * @since 5.0.08
	 */
	public static function echo_style_settings_input_atts( $key ) {
		if ( self::is_colorpicker( $key ) ) {
			// Support alpha in color pickers in pro style settings.
			self::echo_alpha_colorpicker_atts();
		}
	}

	/**
	 * Determine if input is a colorpicker type based on key name.
	 *
	 * @since 5.0.08
	 *
	 * @param string $key
	 * @return bool
	 */
	private static function is_colorpicker( $key ) {
		if ( in_array( $key, array( 'error_bg', 'error_border', 'error_text' ), true ) ) {
			return true;
		}
		return '_color' === substr( $key, -6 ) || '_color_error' === substr( $key, -12 ) || '_color_active' === substr( $key, -13 ) || '_color_disabled' === substr( $key, -15 );
	}

	/**
	 * @since 5.0.08
	 */
	private static function echo_alpha_colorpicker_atts() {
		echo 'data-alpha-color-type="rgba" data-alpha-enabled="true"';
	}

	/**
	 * @deprecated 4.0
	 */
	public static function style_switcher( $style, $styles ) {
		_deprecated_function( __METHOD__, '4.0', 'FrmProStylesController::add_new_button' );
		self::add_new_button( $style );
	}

	/**
	 * @codeCoverageIgnore
	 */
	public static function section_fields_file() {
		_deprecated_function( __METHOD__, '3.01.01', 'FrmProStylesController::style_box_file' );
		return self::view_folder() . '/_section-fields.php';
	}

	/**
	 * @codeCoverageIgnore
	 */
	public static function date_settings_file() {
		_deprecated_function( __METHOD__, '3.01.01', 'FrmProStylesController::style_box_file' );
		return self::view_folder() . '/_date-fields.php';
	}

	/**
	 * @codeCoverageIgnore
	 */
	public static function progress_settings_file() {
		_deprecated_function( __METHOD__, '3.01.01', 'FrmProStylesController::style_box_file' );
		return self::view_folder() . '/_progress-bars.php';
	}

	/**
	 * @codeCoverageIgnore
	 */
	public static function get_datepicker_names( $jquery_themes ) {
		_deprecated_function( __METHOD__, '3.03' );
		$alt_img_name = array();
		$theme_names  = array_keys( $jquery_themes );
		$theme_names  = array_combine( $theme_names, $theme_names );
		$alt_img_name = array_merge( $theme_names, $alt_img_name );
		$alt_img_name['-1'] = '';

		return $alt_img_name;
	}
}
