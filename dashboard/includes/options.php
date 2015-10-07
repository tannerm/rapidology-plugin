<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Array of all sections. All sections will be added into sidebar navigation except for the 'header' section.
$rad_all_sections = array(
	'optin'  => array(
		'title'    => __( 'Optin Configuration', 'flm' ),
		'contents' => array(
			'setup'   => __( 'Setup', 'flm' ),
			'premade' => __( 'Premade Layouts', 'flm' ),
			'design'  => __( 'Design', 'flm' ),
			'display' => __( 'Display Settings', 'flm' ),
		),
	),
	'header' => array(
		'contents' => array(
			'stats'        => __( 'Optin Stats', 'flm' ),
			'accounts'     => __( 'Accounts settings', 'flm' ),
			'importexport' => __( 'Import & Export', 'flm' ),
			'home'         => __( 'Home', 'flm' ),
			'edit_account' => __( 'Edit Account', 'flm' ),
		),
	),
);

/**
 * Array of all options
 * General format for options:
 * '<option_name>' => array(
 *							'type' => ...,
 *							'name' => ...,
 *							'default' => ...,
 *							'validation_type' => ...,
 *							etc
 *						)
 * <option_name> - just an identifier to add the option into $rad_assigned_options array
 * Array of parameters may contain diffrent attributes depending on option type.
 * 'type' is the required attribute for all options. All other attributes depends on the option type.
 * 'validation_type' and 'name' are required attribute for the option which should be saved into DataBase.
 *
 */

require_once('options_config.php');

$more_info_hint_text = sprintf(
	'<a href="%2$s" target="_blank">%1$s</a>',
	__( 'Click here for more information', 'flm' ),
	esc_url( 'http://www.contestdomination.com' )
);

$flm_dashboard_options_all = array(
	'optin_name' => array(
		'section_start' => array(
			'type'  => 'section_start',
			'title' => __( 'Optin name', 'flm' ),
		),

		'option' => array(
			'type'            => 'text',
			'rows'            => '1',
			'name'            => 'optin_name',
			'placeholder'     => __( 'MyNewOptin', 'flm' ),
			'default'         => __( 'MyNewOptin', 'flm' ),
			'validation_type' => 'simple_text',
		),
	),

	'form_integration' => array(
		'section_start' => array(
			'type'  => 'section_start',
			'title' => __( 'Form Integration', 'flm' ),
			'class' => 'flm_dashboard_child_hidden',
		),
		'email_provider' => array(
			'type'            => 'select',
			'title'           => __( 'Select Email Provider', 'flm' ),
			'name'            => 'email_provider',
			'value'           => $email_providers_new_optin,
			'default'         => 'empty',
			'conditional'     => 'mailchimp_account#aweber_account#constant_contact_account#custom_html#activecampaign#display_name#name_fields#disable_dbl_optin',
			'validation_type' => 'simple_text',
			'class'           => 'flm_dashboard_select_provider',
		),
		'select_account' => array(
			'type'            => 'select',
			'title'           => __( 'Select Account', 'flm' ),
			'name'            => 'account_name',
			'value'           => array(
				'empty'       => __( 'Select One...', 'flm' ),
				'add_account' => __( 'Add Account', 'flm' ) ),
			'default'         => 'empty',
			'validation_type' => 'simple_text',
			'class'           => 'flm_dashboard_select_account',
		),
		'email_list' => array(
			'type'            => 'select',
			'title'           => __( 'Select Email List', 'flm' ),
			'name'            => 'email_list',
			'value'           => array(
				'empty' => __( 'Select One...', 'flm' )
			),
			'default'         => 'empty',
			'validation_type' => 'simple_text',
			'class'           => 'flm_dashboard_select_list',
		),
		'custom_html' => array(
			'type'            => 'text',
			'rows'            => '4',
			'name'            => 'custom_html',
			'placeholder'     => __( 'Insert HTML', 'flm' ),
			'default'         => '',
			'display_if'      => 'custom_html',
			'validation_type' => 'html',
		),
		'disable_dbl_optin' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Disable Double Optin', 'flm' ),
			'name'            => 'disable_dbl_optin',
			'default'         => false,
			'display_if'      => 'mailchimp',
			'validation_type' => 'boolean',
			'hint_text'       => __( 'Abusing this feature may cause your Mailchimp account to be suspended.', 'flm' ),
		),
	),

	'optin_title' => array(
		'section_start' => array(
			'type'     => 'section_start',
			'title'    => __( 'Optin title', 'flm' ),
			'subtitle' => __( 'No title will appear if left blank', 'flm' ),
		),

		'option' => array(
			'type'            => 'text',
			'rows'            => '1',
			'name'            => 'optin_title',
			'class'           => 'flm_dashboard_optin_title flm_dashboard_mce',
			'placeholder'     => __( 'Insert Text', 'flm' ),
			'default'         => __( 'Subscribe To Our Newsletter', 'flm' ),
			'validation_type' => 'html',
			'is_wpml_string'  => true,
		),
	),

	'optin_message' => array(
		'section_start' => array(
			'type'     => 'section_start',
			'title'    => __( 'Optin message', 'flm' ),
			'subtitle' => __( 'No message will appear if left blank', 'flm' ),
		),

		'option' => array(
			'type'            => 'text',
			'rows'            => '3',
			'name'            => 'optin_message',
			'class'           => 'flm_dashboard_optin_message flm_dashboard_mce',
			'placeholder'     => __( 'Insert Text', 'flm' ),
			'default'         => __( 'Join our mailing list to receive the latest news and updates from our team.', 'flm' ),
			'validation_type' => 'html',
			'is_wpml_string'  => true,
		),
	),

	'image_settings' => array(
		'section_start' => array(
			'type'  => 'section_start',
			'title' => __( 'Image Settings', 'flm' ),
			'class' => 'flm_dashboard_10_bottom',
		),
		'image_orientation' => array(
			'type'            => 'select',
			'title'           => __( 'Image Orientation', 'flm' ),
			'name'            => 'image_orientation',
			'value'           => array(
				'no_image' => __( 'No Image', 'flm' ),
				'above'    => __( 'Image Above Text', 'flm' ),
				'below'    => __( 'Image Below Text', 'flm' ),
				'right'    => __( 'Image Right of Text', 'flm' ),
				'left'     => __( 'Image Left of Text', 'flm' ),
			),
			'default'         => 'no_image',
			'conditional'     => 'image_upload',
			'validation_type' => 'simple_text',
			'class'           => 'flm_hide_for_widget flm_dashboard_image_orientation',
		),
		'image_orientation_widget' => array(
			'type'            => 'select',
			'title'           => __( 'Image Orientation', 'flm' ),
			'name'            => 'image_orientation_widget',
			'value'           => array(
				'no_image' => __( 'No Image', 'flm' ),
				'above'    => __( 'Image Above Text', 'flm' ),
				'below'    => __( 'Image Below Text', 'flm' ),
			),
			'default'         => 'no_image',
			'conditional'     => 'image_upload',
			'validation_type' => 'simple_text',
			'class'           => 'flm_widget_only_option flm_dashboard_image_orientation_widget',
		),
	),

	'image_upload' => array(
		'section_start' => array(
			'type'       => 'section_start',
			'name'       => 'image_upload',
			'class'      => 'e_no_top_space',
			'display_if' => 'above#below#right#left',
		),
		'image_url' => array(
			'type'            => 'image_upload',
			'title'           => __( 'Image URL', 'flm' ),
			'name'            => 'image_url',
			'class'           => 'flm_dashboard_upload_image',
			'button_text'     => __( 'Upload an Image', 'flm' ),
			'wp_media_title'  => __( 'Choose an Optin Image', 'flm' ),
			'wp_media_button' => __( 'Set as Optin Image', 'flm' ),
			'validation_type' => 'simple_array',
		),
		'image_animation' => array(
			'type'            => 'select',
			'title'           => __( 'Image Load-In Animation', 'flm' ),
			'name'            => 'image_animation',
			'value'           => array(
				'no_animation' => __( 'No Animation', 'flm' ),
				'fadein'       => __( 'Fade In', 'flm' ),
				'slideright'   => __( 'Slide Right', 'flm' ),
				'slidedown'    => __( 'Slide Down', 'flm' ),
				'slideup'      => __( 'Slide Up', 'flm' ),
				'lightspeedin' => __( 'Light Speed', 'flm' ),
				'zoomin'       => __( 'Zoom In', 'flm' ),
				'flipinx'      => __( 'Flip', 'flm' ),
				'bounce'       => __( 'Bounce', 'flm' ),
				'swing'        => __( 'Swing', 'flm' ),
				'tada'         => __( 'Tada!', 'flm' ),
			),
			'hint_text'       => __( 'Define the animation that is used to load the image', 'flm' ),
			'default'         => 'slideup',
			'validation_type' => 'simple_text',
		),
		'hide_mobile' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Hide image on mobile', 'flm' ),
			'name'            => 'hide_mobile',
			'default'         => false,
			'validation_type' => 'boolean',
		),
	),

	'form_setup' => array(
		'section_start' => array(
			'type'  => 'section_start',
			'title' => __( 'Form setup', 'flm' ),
		),
		'form_orientation' => array(
			'type'            => 'select',
			'title'           => __( 'Form Orientation', 'flm' ),
			'name'            => 'form_orientation',
			'value'           => array(
				'bottom' => __( 'Form On Bottom', 'flm' ),
				'right'  => __( 'Form On Right', 'flm' ),
				'left'   => __( 'Form On Left', 'flm' ),
			),
			'default'         => 'bottom',
			'validation_type' => 'simple_text',
			'class'           => 'flm_hide_for_widget flm_dashboard_form_orientation',
		),
		'display_name' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Display Name Field', 'flm' ),
			'name'            => 'display_name',
			'class'           => 'flm_dashboard_name_checkbox',
			'default'         => false,
			'conditional'     => 'single_name_text',
			'validation_type' => 'boolean',
			'display_if'      => 'getresponse#aweber',
		),
		'name_fields' => array(
			'type'            => 'select',
			'title'           => __( 'Name Field(s)', 'flm' ),
			'name'            => 'name_fields',
			'class'           => 'flm_dashboard_name_fields',
			'value'           => array(
				'no_name'         => __( 'No Name Field', 'flm' ),
				'single_name'     => __( 'Single Name Field', 'flm' ),
				'first_last_name' => __( 'First + Last Name Fields', 'flm' ),
			),
			'default'         => 'no_name',
			'conditional'     => 'name_text#last_name#single_name_text',
			'validation_type' => 'simple_text',
			'display_if'      => implode( '#', $show_name_fields ),
		),
		'name_text' => array(
			'type'            => 'input_field',
			'subtype'         => 'text',
			'name'            => 'name_text',
			'class'           => 'flm_dashboard_name_text',
			'title'           => __( 'Name Text', 'flm' ),
			'placeholder'     => __( 'First Name', 'flm' ),
			'default'         => '',
			'display_if'      => 'first_last_name',
			'validation_type' => 'simple_text',
			'is_wpml_string'  => true,
		),
		'single_name_text' => array(
			'type'            => 'input_field',
			'subtype'         => 'text',
			'name'            => 'single_name_text',
			'class'           => 'flm_dashboard_name_text_single',
			'title'           => __( 'Name Text', 'flm' ),
			'placeholder'     => __( 'Name', 'flm' ),
			'default'         => '',
			'display_if'      => 'single_name#true',
			'validation_type' => 'simple_text',
			'is_wpml_string'  => true,
		),
		'last_name' => array(
			'type'            => 'input_field',
			'subtype'         => 'text',
			'name'            => 'last_name',
			'class'           => 'flm_dashboard_last_name_text',
			'title'           => __( 'Last Name Text', 'flm' ),
			'placeholder'     => __( 'Last Name', 'flm' ),
			'default'         => '',
			'display_if'      => 'first_last_name',
			'validation_type' => 'simple_text',
			'is_wpml_string'  => true,
		),
		'email_text' => array(
			'type'            => 'input_field',
			'subtype'         => 'text',
			'name'            => 'email_text',
			'class'           => 'flm_dashboard_email_text',
			'title'           => __( 'Email Text', 'flm' ),
			'placeholder'     => __( 'Email', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
			'is_wpml_string'  => true,
		),
		'button_text' => array(
			'type'            => 'input_field',
			'subtype'         => 'text',
			'name'            => 'button_text',
			'class'           => 'flm_dashboard_button_text',
			'title'           => __( 'Button Text', 'flm' ),
			'placeholder'     => __( 'SUBSCRIBE!', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
			'is_wpml_string'  => true,
		),
		'button_text_color' => array(
			'type'            => 'select',
			'title'           => __( 'Button Text Color', 'flm' ),
			'name'            => 'button_text_color',
			'class'           => 'flm_dashboard_field_button_text_color',
			'value'           => array(
				'light' => __( 'Light', 'flm' ),
				'dark'  => __( 'Dark', 'flm' ),
			),
			'default'         => 'light',
			'validation_type' => 'simple_text',
		),
	),

	'optin_styling' => array(
		'section_start' => array(
			'type'  => 'section_start',
			'title' => __( 'Optin Styling', 'flm' ),
		),
		'header_bg_color' => array(
			'type'            => 'color_picker',
			'title'           =>  __( 'Background Color', 'flm' ),
			'name'            => 'header_bg_color',
			'class'           => 'flm_dashboard_optin_bg',
			'placeholder'     => __( 'Hex Value', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
		),
		'header_font' => array(
			'type'            => 'font_select',
			'title'           => __( 'Header Font', 'flm' ),
			'name'            => 'header_font',
			'class'           => 'flm_dashboard_header_font',
			'validation_type' => 'simple_text',
		),
		'body_font' => array(
			'type'            => 'font_select',
			'title'           => __( 'Body Font', 'flm' ),
			'name'            => 'body_font',
			'class'           => 'flm_dashboard_body_font',
			'validation_type' => 'simple_text',
		),
		'header_text_color' => array(
			'type'            => 'select',
			'title'           => __( 'Text Color', 'flm' ),
			'name'            => 'header_text_color',
			'class'           => 'flm_dashboard_text_color',
			'value'           => array(
				'light' => __( 'Light Text', 'flm' ),
				'dark'  => __( 'Dark Text', 'flm' ),
			),
			'default'         => 'dark',
			'validation_type' => 'simple_text',
		),
		'corner_style' => array(
			'type'            => 'select',
			'title'           => __( 'Corner Style', 'flm' ),
			'name'            => 'corner_style',
			'class'           => 'flm_dashboard_corner_style',
			'value'           => array(
				'squared' => __( 'Squared Corners', 'flm' ),
				'rounded' => __( 'Rounded Corners', 'flm' ),
			),
			'default'         => 'squared',
			'validation_type' => 'simple_text',
		),
		'border_orientation' => array(
			'type'            => 'select',
			'title'           => __( 'Border Orientation', 'flm' ),
			'name'            => 'border_orientation',
			'class'           => 'flm_dashboard_border_orientation',
			'value'           => array(
				'no_border'  => __( 'No Border', 'flm' ),
				'full'       => __( 'Full Border', 'flm' ),
				'top'        => __( 'Top Border', 'flm' ),
				'right'      => __( 'Right Border', 'flm' ),
				'bottom'     => __( 'Bottom Border', 'flm' ),
				'left'       => __( 'Left Border', 'flm' ),
				'top_bottom' => __( 'Top + Bottom Border', 'flm' ),
				'left_right' => __( 'Left + Right Border', 'flm' ),
			),
			'default'         => 'no_border',
			'conditional'     => 'border_color#border_style',
			'validation_type' => 'simple_text',
		),
		'border_color' => array(
			'type'            => 'color_picker',
			'title'           =>  __( 'Border Color', 'flm' ),
			'name'            => 'border_color',
			'class'           => 'flm_dashboard_border_color',
			'placeholder'     => __( 'Hex Value', 'flm' ),
			'default'         => '',
			'display_if'      => 'full#top#left#right#bottom#top_bottom#left_right',
			'validation_type' => 'simple_text',
		),
	),

	'form_styling' => array(
		'section_start' => array(
			'type'  => 'section_start',
			'title' => __( 'Form Styling', 'flm' ),
		),
		'field_orientation' => array(
			'type'            => 'select',
			'title'           => __( 'Form Field Orientation', 'flm' ),
			'name'            => 'field_orientation',
			'value'           => array(
				'stacked' => __( 'Stacked Form Fields', 'flm' ),
				'inline'  => __( 'Inline Form Fields', 'flm' ),
			),
			'default'         => 'inline',
			'validation_type' => 'simple_text',
			'class'           => 'flm_hide_for_widget flm_dashboard_field_orientation',
		),
		'field_corner' => array(
			'type'            => 'select',
			'title'           => __( 'Form Field Corner Style', 'flm' ),
			'name'            => 'field_corner',
			'class'           => 'flm_dashboard_field_corners',
			'value'           => array(
				'squared' => __( 'Squared Corners', 'flm' ),
				'rounded' => __( 'Rounded Corners', 'flm' ),
			),
			'default'         => 'rounded',
			'validation_type' => 'simple_text',
		),
		'text_color' => array(
			'type'            => 'select',
			'title'           => __( 'Form Text Color', 'flm' ),
			'name'            => 'text_color',
			'class'           => 'flm_dashboard_form_text_color',
			'value'           => array(
				'light' => __( 'Light Text', 'flm' ),
				'dark'  => __( 'Dark Text', 'flm' ),
			),
			'default'         => 'dark',
			'validation_type' => 'simple_text',
		),
		'form_bg_color' => array(
			'type'            => 'color_picker',
			'title'           =>  __( 'Form Background Color', 'flm' ),
			'name'            => 'form_bg_color',
			'class'           => 'flm_dashboard_form_bg_color',
			'placeholder'     => __( 'Hex Value', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
		),
		'form_button_color' => array(
			'type'            => 'color_picker',
			'title'           =>  __( 'Button Color', 'flm' ),
			'name'            => 'form_button_color',
			'class'           => 'flm_dashboard_form_button_color',
			'placeholder'     => __( 'Hex Value', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
		),
	),

	'edge_style' => array(
		'type'            => 'select_shape',
		'title'           => __( 'Choose form edge style', 'flm' ),
		'name'            => 'edge_style',
		'value'           => array(
			'basic_edge',
			'carrot_edge',
			'wedge_edge',
			'curve_edge',
			'zigzag_edge',
			'breakout_edge',
		),
		'default'         => 'basic_edge',
		'class'           => 'flm_dashboard_optin_edge',
		'validation_type' => 'simple_text',
	),

	'border_style' => array(
		'type'            => 'select_shape',
		'title'           => __( 'Choose border style', 'flm' ),
		'name'            => 'border_style',
		'class'           => 'flm_dashboard_border_style',
		'value'           => array(
			'solid',
			'dashed',
			'double',
			'inset',
			'letter',
		),
		'default'         => 'solid',
		'display_if'      => 'full#top#left#right#bottom#top_bottom#left_right',
		'validation_type' => 'simple_text',
	),

	'footer_text' => array(
		'section_start' => array(
			'type'  => 'section_start',
			'title' =>__( 'Form Footer Text', 'flm' ),
		),
		'option' => array(
			'type'            => 'text',
			'rows'            => '3',
			'name'            => 'footer_text',
			'class'           => 'flm_dashboard_footer_text',
			'placeholder'     => __( 'insert your footer text', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
			'is_wpml_string'  => true,
		),
	),

	'success_message' => array(
		'section_start' => array(
			'type'  => 'section_start',
			'title' =>__( 'Success Message Text', 'flm' ),
		),
		'option' => array(
			'type'            => 'text',
			'rows'            => '1',
			'name'            => 'success_message',
			'class'           => 'flm_dashboard_success_text',
			'placeholder'     => __( 'You have Successfully Subscribed!', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
			'is_wpml_string'  => true,
		),
	),

	'custom_css' => array(
		'section_start' => array(
			'type'  => 'section_start',
			'title' =>__( 'Custom CSS', 'flm' ),
		),
		'option' => array(
			'type'            => 'text',
			'rows'            => '7',
			'name'            => 'custom_css',
			'placeholder'     => __( 'insert your custom CSS code', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
		),
	),

	'load_in' => array(
		'section_start' => array(
			'type'  => 'section_start',
			'title' => __( 'Load-in settings', 'flm' ),
			'class' => 'flm_dashboard_for_popup',
		),
		'load_animation' => array(
			'type'            => 'select',
			'title'           => __( 'Intro Animation', 'flm' ),
			'name'            => 'load_animation',
			'value'           => array(
				'no_animation' => __( 'No Animation', 'flm' ),
				'fadein'       => __( 'Fade In', 'flm' ),
				'slideright'   => __( 'Slide Right', 'flm' ),
				'slideup'      => __( 'Slide Up', 'flm' ),
				'slidedown'    => __( 'Slide Down', 'flm' ),
				'lightspeedin' => __( 'Light Speed', 'flm' ),
				'zoomin'       => __( 'Zoom In', 'flm' ),
				'flipinx'      => __( 'Flip', 'flm' ),
				'bounce'       => __( 'Bounce', 'flm' ),
				'swing'        => __( 'Swing', 'flm' ),
				'tada'         => __( 'Tada!', 'flm' ),
			),
			'hint_text'       => __( 'Define the animation that is used, when you load the page.', 'flm' ),
			'class'           => 'flm_load_in_animation',
			'default'         => 'fadein',
			'validation_type' => 'simple_text',
		),
		'trigger_auto' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Trigger After Time Delay', 'flm' ),
			'name'            => 'trigger_auto',
			'default'         => '1',
			'conditional'     => 'load_delay',
			'validation_type' => 'boolean',
		),
		'load_delay' => array(
			'type'            => 'input_field',
			'subtype'         => 'number',
			'title'           => __( 'Delay (in seconds)', 'flm' ),
			'name'            => 'load_delay',
			'hint_text'       => __( 'Define how many seconds you want to wait before the pop up appears on the screen.', 'flm' ),
			'default'         => '20',
			'display_if'      => 'true',
			'validation_type' => 'number',
		),
		'trigger_idle' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Trigger After Inactivity', 'flm' ),
			'name'            => 'trigger_idle',
			'default'         => false,
			'conditional'     => 'idle_timeout',
			'validation_type' => 'boolean',
		),
		'idle_timeout' => array(
			'type'            => 'input_field',
			'subtype'         => 'number',
			'title'           => __( 'Idle Timeout ( in seconds )', 'flm' ),
			'name'            => 'idle_timeout',
			'hint_text'       => __( 'Define how many seconds user should be inactive before the pop up appears on screen.', 'flm' ),
			'default'         => '15',
			'display_if'      => 'true',
			'validation_type' => 'number',
		),
		'post_bottom' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Trigger At The Bottom of Post', 'flm' ),
			'name'            => 'post_bottom',
			'default'         => '1',
			'validation_type' => 'boolean',
		),
		'comment_trigger' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Trigger After Commenting', 'flm' ),
			'name'            => 'comment_trigger',
			'default'         => false,
			'validation_type' => 'boolean',
		),
        'exit_trigger' => array(
            'type'            => 'checkbox',
            'title'           => __( 'Trigger Before Leaving Page', 'flm' ),
            'name'            => 'exit_trigger',
            'default'         => false,
            'validation_type' => 'boolean',
        ),
		'trigger_scroll' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Trigger After Scrolling', 'flm' ),
			'name'            => 'trigger_scroll',
			'default'         => false,
			'conditional'     => 'scroll_pos',
			'validation_type' => 'boolean',
		),
		'scroll_pos' => array(
			'type'            => 'input_field',
			'subtype'         => 'number',
			'title'           => __( 'Percentage Down The Page', 'flm' ),
			'name'            => 'scroll_pos',
			'hint_text'       => __( 'Define the % of the page to be scrolled before the pop up appears on the screen.', 'flm' ),
			'default'         => '50',
			'display_if'      => 'true',
			'validation_type' => 'number',
		),
		'purchase_trigger' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Trigger After Purchasing', 'flm' ),
			'name'            => 'purchase_trigger',
			'default'         => false,
			'hint_text'       => __( 'Display on "Thank you" page of WooCommerce after purchase', 'flm' ),
			'validation_type' => 'boolean',
		),
		'session' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Display once per session', 'flm' ),
			'name'            => 'session',
			'default'         => false,
			'validation_type' => 'boolean',
			'conditional'     => 'session_duration',
		),
		'session_duration' => array(
			'type'            => 'input_field',
			'subtype'         => 'number',
			'title'           => __( 'Session Duration (in days)', 'flm' ),
			'name'            => 'session_duration',
			'hint_text'       => __( 'Define the length of time (in days) that a session lasts for. For example, if you input 2 a user will only see a popup on your site every two days.', 'flm' ),
			'default'         => '1',
			'validation_type' => 'number',
			'display_if'      => 'true',
		),
		'hide_mobile' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Hide on Mobile', 'flm' ),
			'name'            => 'hide_mobile_optin',
			'default'         => false,
			'validation_type' => 'boolean',
		),
		'click_trigger' => array(
			'type'            => 'checkbox',
			'title'           => __( 'Trigger When Element is Clicked', 'flm' ),
			'name'            => 'click_trigger',
			'hint_text'       => __( 'Adds new onclick shortcode option to Free List Machine editor when editing a page / post', 'flm' ),
			'default'         => false,
			'validation_type' => 'boolean',
		),
	),

	'flyin_orientation' => array(
		'section_start' => array(
			'type'  => 'section_start',
			'title' => __( 'Fly-In Orientation', 'flm' ),
			'class' => 'flm_dashboard_for_flyin',
		),
		'flyin_orientation' => array(
			'type'            => 'select',
			'title'           => __( 'Choose Orientation', 'flm' ),
			'name'            => 'flyin_orientation',
			'value'           => array(
				'right'  => __( 'Right', 'flm' ),
				'left'   => __( 'Left', 'flm' ),
				'center' => __( 'Center', 'flm' ),
			),
			'default'         => 'right',
			'validation_type' => 'simple_text',
		),
	),

	'post_types' => array(
		array(
			'type'  => 'section_start',
			'title' => __( 'Display on', 'flm' ),
			'class' => 'flm_dashboard_child_hidden display_on_section',
		),
		array(
			'type'            => 'checkbox_set',
			'name'            => 'display_on',
			'value'           => array(
				'everything' => __( 'Everything', 'flm' ),
				'home'       => __( 'Homepage', 'flm' ),
				'archive'    => __( 'Archives', 'flm' ),
				'category'   => __( 'Categories', 'flm' ),
				'tags'       => __( 'Tags', 'flm' ),
			),
			'default'         => array( '' ),
			'validation_type' => 'simple_array',
			'conditional'     => array(
				'everything' => 'pages_exclude_section#posts_exclude_section#pages_include_section#posts_include_section',
				'category'   => 'categories_include_section',
			),
			'class'           => 'display_on_checkboxes',
		),
		array(
			'type'            => 'checkbox_posts',
			'subtype'         => 'post_types',
			'name'            => 'post_types',
			'default'         => array( 'post' ),
			'validation_type' => 'simple_array',
			'conditional'     => array(
				'page'     => 'pages_exclude_section',
				'post'     => 'categories_include_section#posts_exclude_section',
				'any_post' => 'posts_exclude_section#categories_include_section',
			),
		),
	),

	'post_categories' => array(
		array(
			'type'       => 'section_start',
			'title'      => __( 'Display on these categories', 'flm' ),
			'class'      => 'flm_dashboard_child_hidden categories_include_section',
			'name'       => 'categories_include_section',
			'display_if' => 'true',
		),
		array(
			'type'            => 'checkbox_posts',
			'subtype'         => 'post_cats',
			'name'            => 'post_categories',
			'include_custom'  => true,
			'default'         => array(),
			'validation_type' => 'simple_array',
		),
	),

	'pages_exclude' => array(
		array(
			'type'       => 'section_start',
			'title'      => __( 'Do not display on these pages', 'flm' ),
			'class'      => 'flm_dashboard_child_hidden',
			'name'       => 'pages_exclude_section',
			'display_if' => 'true',
		),
		array(
			'type'            => 'live_search',
			'name'            => 'pages_exclude',
			'post_type'       => 'only_pages',
			'placeholder'     => __( 'start typing page name...', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
		),
	),

	'pages_include' => array(
		array(
			'type'       => 'section_start',
			'title'      => __( 'Display on these pages', 'flm' ),
			'subtitle'   => __( 'Pages defined below will override all settings above', 'flm' ),
			'class'      => 'flm_dashboard_child_hidden',
			'name'       => 'pages_include_section',
			'display_if' => 'false',
		),
		array(
			'type'            => 'live_search',
			'name'            => 'pages_include',
			'post_type'       => 'only_pages',
			'placeholder'     => __( 'start typing page name...', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
		),
	),

	'posts_exclude' => array(
		array(
			'type'       => 'section_start',
			'title'      => __( 'Do not display on these posts', 'flm' ),
			'class'      => 'flm_dashboard_child_hidden',
			'name'       => 'posts_exclude_section',
			'display_if' => 'true',
		),
		array(
			'type'            => 'live_search',
			'name'            => 'posts_exclude',
			'post_type'       => 'only_posts',
			'placeholder'     => __( 'start typing post name...', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
		),
	),

	'posts_include' => array(
		array(
			'type'       => 'section_start',
			'title'      => __( 'Display on these posts', 'flm' ),
			'subtitle'   => __( 'Posts defined below will override all settings above', 'flm' ),
			'class'      => 'flm_dashboard_child_hidden',
			'name'       => 'posts_include_section',
			'display_if' => 'false',
		),
		array(
			'type'            => 'live_search',
			'name'            => 'posts_include',
			'post_type'       => 'only_posts',
			'placeholder'     => __( 'start typing post name...', 'flm' ),
			'default'         => '',
			'validation_type' => 'simple_text',
		),
	),

	'authorization' => array(
		'authorization_title' => array(
			'type'  => 'main_title',
			'title' => __( 'Setup your accounts', 'flm' ),
		),

		'sub_section_mailchimp' => array(
			'type'        => 'section_start',
			'sub_section' => true,
			'title'       => __( 'MailChimp', 'flm' ),
		),

		'mailchimp_key' => array(
			'type'                 => 'input_field',
			'subtype'              => 'text',
			'name'                 => 'mailchimp_key',
			'title'                => __( 'MailChimp API Key', 'flm' ),
			'default'              => '',
			'class'                => 'api_option api_option_key',
			'hide_contents'        => true,
			'hint_text'            => $more_info_hint_text,
			'hint_text_with_links' => 'on',
			'validation_type'      => 'simple_text',
		),
		'mailchimp_button' => array(
			'type'      => 'button',
			'title'     => __( 'Authorize', 'Monarch' ),
			'link'      => '#',
			'class'     => 'flm_dashboard_authorize',
			'action'    => 'mailchimp',
			'authorize' => true,
		),

		'sub_section_aweber' => array(
			'type'        => 'section_start',
			'sub_section' => true,
			'title'       => __( 'AWeber', 'flm' ),
		),

		'aweber_key' => array(
			'type'                 => 'input_field',
			'subtype'              => 'text',
			'name'                 => 'aweber_key',
			'title'                => __( 'AWeber authorization code', 'flm' ),
			'default'              => '',
			'class'                => 'api_option api_option_key',
			'hide_contents'        => true,
			'hint_text'            => $more_info_hint_text,
			'hint_text_with_links' => 'on',
			'validation_type'      => 'simple_text',
		),
		'aweber_button' => array(
			'type'      => 'button',
			'title'     => __( 'Authorize', 'Monarch' ),
			'link'      => '#',
			'class'     => 'flm_dashboard_authorize',
			'action'    => 'aweber',
			'authorize' => true,
		),
	),

	'optin_type' => array(
		'type'            => 'hidden_option',
		'subtype'         => 'string',
		'name'            => 'optin_type',
		'validation_type' => 'simple_text',
	),

	'optin_status' => array(
		'type'            => 'hidden_option',
		'subtype'         => 'string',
		'name'            => 'optin_status',
		'validation_type' => 'simple_text',
	),

	'test_status' => array(
		'type'            => 'hidden_option',
		'subtype'         => 'string',
		'name'            => 'test_status',
		'validation_type' => 'simple_text',
	),

	'next_optin' => array(
		'type'            => 'hidden_option',
		'subtype'         => 'string',
		'name'            => 'next_optin',
		'default'         => '-1',
		'validation_type' => 'simple_text',
	),

	'child_of' => array(
		'type'            => 'hidden_option',
		'subtype'         => 'string',
		'name'            => 'child_of',
		'validation_type' => 'simple_text',
	),

	'child_optins' => array(
		'type'            => 'hidden_option',
		'subtype'         => 'array',
		'name'            => 'child_optins',
		'validation_type' => 'simple_array',
	),

	'setup_title' => array(
		'type'     => 'main_title',
		'title'    => __( 'Setup your optin form', 'flm' ),
		'subtitle' => __( 'Name your optin and configure your form integration.', 'flm' ),
	),

	'design_title' => array(
		'type'     => 'main_title',
		'title'    => __( 'Design your optin form', 'flm' ),
		'subtitle' => __( 'Configure your content, layout, and optin styling below.', 'flm' ),
		'class'    => 'flm_dashboard_design_title',
	),

	'display_title' => array(
		'type'     => 'main_title',
		'title'    => __( 'Display Settings', 'flm' ),
		'subtitle' => __( 'Define when and where to display this optin on your website.', 'flm' ),
	),

	'import_export' => array(
		'type'  => 'import_export',
		'title' => __( 'Import/Export', 'flm' ),
	),

	'home' => array(
		'type'  => 'home',
		'title' => __( 'Home', 'flm' ),
	),

	'stats' => array(
		'type'  => 'stats',
		'title' => __( 'Optin Stats', 'flm' ),
	),

	'accounts' => array(
		'type'  => 'account',
		'title' => __( 'Accounts', 'flm' ),
	),

	'edit_account' => array(
		'type'  => 'edit_account',
		'title' => __( 'Edit Account', 'flm' ),
	),

	'preview_optin' => array(
		'type'  => 'preview_optin',
		'title' => __( 'Preview', 'flm' ),
	),

	'premade_templates_start' => array(
		'type'     => 'main_title',
		'title'    => __( 'Choose a template', 'flm' ),
		'subtitle' => __( 'These are just starting points that you can full customize on the next step.', 'flm' ),
	),

	'premade_templates_main' => array(
		'type'  => 'premade_templates',
		'title' => __( 'Choose a template', 'flm' ),
	),

	'end_of_section' => array(
		'type' => 'section_end',
	),

	'end_of_sub_section' => array(
		'type'        => 'section_end',
		'sub_section' => 'true',
	),
);

/**
 * Array of options assigned to sections. Format of option key is following:
 * 	<section>_<sub_section>_options
 * where:
 *	<section> = $rad_ -> $key
 *	<sub_section> = $rad_ -> $value['contents'] -> $key
 *
 * Note: name of this array shouldn't be changed. $rad_assigned_options variable is being used in FLM_Dashboard class as options container.
 */
$rad_assigned_options = array(
	'optin_setup_options' => array(
		$flm_dashboard_options_all[ 'setup_title' ],
		$flm_dashboard_options_all[ 'optin_type' ],
		$flm_dashboard_options_all[ 'optin_status' ],
		$flm_dashboard_options_all[ 'test_status' ],
		$flm_dashboard_options_all[ 'child_of' ],
		$flm_dashboard_options_all[ 'child_optins' ],
		$flm_dashboard_options_all[ 'next_optin' ],
		$flm_dashboard_options_all[ 'optin_name' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'optin_name' ][ 'option' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'form_integration' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'form_integration' ][ 'email_provider' ],
			$flm_dashboard_options_all[ 'form_integration' ][ 'select_account' ],
			$flm_dashboard_options_all[ 'form_integration' ][ 'email_list' ],
			$flm_dashboard_options_all[ 'form_integration' ][ 'custom_html' ],
			$flm_dashboard_options_all[ 'form_integration' ][ 'disable_dbl_optin' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
	),
	'optin_premade_options' => array(
		$flm_dashboard_options_all[ 'premade_templates_start' ],
		$flm_dashboard_options_all[ 'premade_templates_main' ],
	),
	'optin_design_options' => array(
		$flm_dashboard_options_all[ 'preview_optin' ],
		$flm_dashboard_options_all[ 'design_title' ],
		$flm_dashboard_options_all[ 'optin_title' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'optin_title' ][ 'option' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'optin_message' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'optin_message' ][ 'option' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'image_settings' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'image_settings' ][ 'image_orientation' ],
			$flm_dashboard_options_all[ 'image_settings' ][ 'image_orientation_widget' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'image_upload' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'image_upload' ][ 'image_url' ],
			$flm_dashboard_options_all[ 'image_upload' ][ 'image_animation' ],
			$flm_dashboard_options_all[ 'image_upload' ][ 'hide_mobile' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'optin_styling' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'optin_styling' ][ 'header_bg_color' ],
			$flm_dashboard_options_all[ 'optin_styling' ][ 'header_font' ],
			$flm_dashboard_options_all[ 'optin_styling' ][ 'body_font' ],
			$flm_dashboard_options_all[ 'optin_styling' ][ 'header_text_color' ],
			$flm_dashboard_options_all[ 'optin_styling' ][ 'corner_style' ],
			$flm_dashboard_options_all[ 'optin_styling' ][ 'border_orientation' ],
			$flm_dashboard_options_all[ 'optin_styling' ][ 'border_color' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'border_style' ],
		$flm_dashboard_options_all[ 'form_setup' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'form_setup' ][ 'form_orientation' ],
			$flm_dashboard_options_all[ 'form_setup' ][ 'display_name' ],
			$flm_dashboard_options_all[ 'form_setup' ][ 'name_fields' ],
			$flm_dashboard_options_all[ 'form_setup' ][ 'name_text' ],
			$flm_dashboard_options_all[ 'form_setup' ][ 'single_name_text' ],
			$flm_dashboard_options_all[ 'form_setup' ][ 'last_name' ],
			$flm_dashboard_options_all[ 'form_setup' ][ 'email_text' ],
			$flm_dashboard_options_all[ 'form_setup' ][ 'button_text' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'form_styling' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'form_styling' ][ 'field_orientation' ],
			$flm_dashboard_options_all[ 'form_styling' ][ 'field_corner' ],
			$flm_dashboard_options_all[ 'form_styling' ][ 'text_color' ],
			$flm_dashboard_options_all[ 'form_styling' ][ 'form_bg_color' ],
			$flm_dashboard_options_all[ 'form_styling' ][ 'form_button_color' ],
			$flm_dashboard_options_all[ 'form_setup' ][ 'button_text_color' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'edge_style' ],
		$flm_dashboard_options_all[ 'footer_text' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'footer_text' ][ 'option' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'success_message' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'success_message' ][ 'option' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'custom_css' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'custom_css' ][ 'option' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
	),
	'optin_display_options' => array(
		$flm_dashboard_options_all[ 'display_title' ],
		$flm_dashboard_options_all[ 'flyin_orientation' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'flyin_orientation' ][ 'flyin_orientation' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'load_in' ][ 'section_start' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'load_animation' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'trigger_auto' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'load_delay' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'trigger_idle' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'idle_timeout' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'post_bottom' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'comment_trigger' ],
            $flm_dashboard_options_all[ 'load_in' ][ 'exit_trigger' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'click_trigger' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'trigger_scroll' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'scroll_pos' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'purchase_trigger' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'session' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'session_duration' ],
			$flm_dashboard_options_all[ 'load_in' ][ 'hide_mobile' ],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'post_types' ][0],
			$flm_dashboard_options_all[ 'post_types' ][1],
			$flm_dashboard_options_all[ 'post_types' ][2],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'post_categories' ][0],
			$flm_dashboard_options_all[ 'post_categories' ][1],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'pages_include' ][0],
			$flm_dashboard_options_all[ 'pages_include' ][1],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'pages_exclude' ][0],
			$flm_dashboard_options_all[ 'pages_exclude' ][1],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'posts_exclude' ][0],
			$flm_dashboard_options_all[ 'posts_exclude' ][1],
		$flm_dashboard_options_all[ 'end_of_section' ],
		$flm_dashboard_options_all[ 'posts_include' ][0],
			$flm_dashboard_options_all[ 'posts_include' ][1],
		$flm_dashboard_options_all[ 'end_of_section' ],
	),
	'header_importexport_options' => array(
		$flm_dashboard_options_all[ 'import_export' ],
	),
	'header_home_options' => array(
		$flm_dashboard_options_all[ 'home' ],
	),
	'header_accounts_options' => array(
		$flm_dashboard_options_all[ 'accounts' ],
	),
	'header_edit_account_options' => array(
		$flm_dashboard_options_all[ 'edit_account' ],
	),
	'header_stats_options' => array(
		$flm_dashboard_options_all[ 'stats' ],
	),
);