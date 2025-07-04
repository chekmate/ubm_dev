<?php
function ubermenu_get_settings_fields_instance( $config_id ){

	$settings = array(

		//Integration


		//Basic

		60 => array(
			'name'	=> 'header_basic',
			'label'	=> __( 'Basic Configuration' , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'basic',
		),



		70 => /* SKIN */
		array(
			'name'	=> 'skin',
			'label'	=> __( 'Skin' , 'ubermenu' ),
			'type'	=> 'select',
			'desc'	=> __( 'If you disable the skin, you must provide your own custom skin.  Otherwise, use the Minimal Base or Vanilla skin as a Customizer base.' , 'ubermenu' ). ' <br/> <a target="_blank" href="https://sevenspark.com/goods/category/ubermenu-skin-packs?src=plugin">Get more skins</a>',
			//'options'	=> array(),
			'options' => 'ubermenu_get_skin_ops',
			'default' => 'black-white-2',
			'group'	=> 'basic',
			'customizer' => true,
		),

		80 => array(
			'name'	=> 'orientation',
			'label'	=> __( 'Orientation' , 'ubermenu' ),
			'type'	=> 'radio',
			'desc'	=> __( 'Orient the menu vertically or horizontally' , 'ubermenu' ) . '<br/><a target="_blank" href="http://goo.gl/UaAb2z">Vertical Menu Demo</a>',
			'options'=> array(
				//'auto'		=> __( 'Automatic' , 'ubermenu' ),

				'horizontal'	=> __( 'Horizontal', 'ubermenu' ),
				'vertical'	 	=> __( 'Vertical', 'ubermenu' ),
			),
			'default'=> 'horizontal',
			'group'	=> 'basic',

			'customizer'	=> true,
			'customizer_section' => 'menu_bar',

		),

		90 => array(
			'name'	=> 'vertical_submenu_width',
			'label'	=> __( 'Vertical Menu Mega Submenu Width' , 'ubermenu' ),
			'type'	=> 'text',
			'default'=> '',
			'desc'	=> __( '600px by default.  Can be overridden on a per submenu basis in the Menu Item Settings.' , 'ubermenu' ),
			'group'	=> 'basic',
			'custom_style' => 'vertical_submenu_width',
		),


		/* TRIGGER */
		100 => array(
			'name'	=> 'trigger_header',
			'label'	=> __( 'Trigger' , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'basic',
		),

		110 => array(
			'name'	=> 'trigger',
			'label'	=> __( 'Trigger' , 'ubermenu' ),
			'type'	=> 'radio',
			'desc'	=> __( 'Open the submenu via this trigger' , 'ubermenu' ),
			'options'=> array(
				//'auto'		=> __( 'Automatic' , 'ubermenu' ),

				'hover' 		=> __( 'Hover', 'ubermenu' ),
				'hover_intent' 	=> __( 'Hover Intent', 'ubermenu' ),
				'click'			=> __( 'Click', 'ubermenu' ),
			),
			'default'=> 'hover_intent',
			'group'	=> 'basic',

			'customizer' => true,
		),






		/* TRANSITION */
		120 => array(
			'name'	=> 'transition_header',
			'label'	=> __( 'Dropdown Transitions' , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> array( 'basic', 'submenus' ),
		),

		130 => array(
			'name'	=> 'transition',
			'label'	=> __( 'Transition' , 'ubermenu' ),
			'desc'	=> __( 'Transitions supported in Chrome, Safari, Firefox, IE10+', 'ubermenu' ),
			'type'	=> 'radio',
			'options'=> array(
				'none'		=> __( 'None' , 'ubermenu' ),
				'slide' 	=> __( 'Slide Reveal', 'ubermenu' ),
				'fade'		=> __( 'Fade', 'ubermenu' ),
				'shift' 	=> __( 'Shift Up', 'ubermenu' ),
			),
			'default'=> 'shift',
			'group'	=> array( 'basic', 'submenus' ),

			'customizer' => true,
			'customizer_section' => 'submenu',
		),


		140 => array(
			'name'	=> 'transition_duration',
			'label'	=> __( 'Transition Duration' , 'ubermenu' ),
			'type'	=> 'text',
			'default'=> '',
			'desc'	=> __( 'You can use <code>.5s</code> or <code>500ms</code>.  Defaults to .3s' , 'ubermenu' ),
			'group'	=> array( 'basic', 'submenus' ),
			'custom_style' => 'transition_duration',
		),



		//Position & Layout

		//Menu Items Alignment







		/* DESCRIPTIONS */
		240 => array(
			'name'	=> 'header_descriptions',
			'label'	=> __( 'Descriptions' , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'descriptions',
		),


		250 => array(
			'name'		=> 'descriptions_top_level',
			'label'		=> __( 'Top Level Descriptions' , 'ubermenu' ),
			'desc'		=> __( 'Allow descriptions on top level menu items.' , 'ubermenu' ),
			'type'		=> 'checkbox',
			'default' 	=> 'on',
			'group'	=> 'descriptions',
		),

		260 => array(
			'name'		=> 'descriptions_headers',
			'label'		=> __( 'Header Item Descriptions' , 'ubermenu' ),
			'desc'		=> __( 'Allow descriptions on header menu items.' , 'ubermenu' ),
			'type'		=> 'checkbox',
			'default' 	=> 'on',
			'group'	=> 'descriptions',
		),

		270 => array(
			'name'		=> 'descriptions_normal',
			'label'		=> __( 'Normal Item Descriptions' , 'ubermenu' ),
			'desc'		=> __( 'Allow descriptions on normal menu items.' , 'ubermenu' ),
			'type'		=> 'checkbox',
			'default' 	=> 'on',
			'group'	=> 'descriptions',
		),

		275 => array(
			'name'		=> 'descriptions_tab',
			'label'		=> __( 'Tab Item Descriptions' , 'ubermenu' ),
			'desc'		=> __( 'Allow descriptions on tab toggle menu items.' , 'ubermenu' ),
			'type'		=> 'checkbox',
			'default' 	=> 'on',
			'group'	=> 'descriptions',
		),

		280 => array(
			'name'		=> 'target_divider',
			'label'		=> __( 'Target Divider' , 'ubermenu' ),
			'desc'		=> __( 'The character(s) separating the title from the description.  This will not be visible, but is useful for screen readers.' , 'ubermenu' ),
			'type'		=> 'text',
			'default' 	=> ' &ndash; ',
			'group'	=> 'descriptions',
			'sanitize_callback' => 'ubermenu_allow_html'
		),






		//Submenus

		//Images

		//Background Images






		/** RESPONSIVE **/

		400 => array(
			'name'	=> 'header_responsive',
			'label'	=> __( 'Responsive &amp; Mobile' , 'ubermenu' ),
			'type'	=> 'header',
			'desc'	=> __( 'Settings for responsiveness &amp; mobile devices' ),
			'group'	=> 'responsive',
		),


		401 => array(
			'name' 		=> 'mobile_menu_display',
			'label' 	=> __( 'Mobile Menu Display', 'ubermenu' ),
			'desc' 		=> __( 'By default, the menu displays Inline - in the natural flow of the layout.  You can switch to "Modal" to display in a full-screen, fixed-position overlay instead.', 'ubermenu' ),
			'type' 		=> 'radio',
			'options'	=> array(
				'inline'		=> __( 'Inline' , 'ubermenu' ),
				'modal'		=> __( 'Modal' , 'ubermenu' ),
			),
			'default'	=> 'inline',
			'group'		=> 'responsive',
		),



		410 => array(
			'name'		=> 'responsive_columns',
			'label'		=> __( 'Top Level Responsive Columns (Tablet)' , 'ubermenu' ),
			'desc'		=> __( 'By default, the top level menu items will appear in two (2) columns from 480px to your breakpoint (960 by default) - approximately tablet-size.  If you\'d like them to appear in a single column instead, you can change that here.  Note that a 2-column layout cannot have accordion submenus.  This will only affect top level menu items.' , 'ubermenu' ),
			'type'		=> 'radio',
			'options'	=> array(
				2		=> __( 'Two (2) Columns' , 'ubermenu' ),
				1		=> __( 'One (1) Column' , 'ubermenu' ),
			),
			'default'	=> 2,
			'group'		=> 'responsive',
		),

		412 => array(
			'name'		=> 'responsive_submenu_columns',
			'label'		=> __( 'Submenu Responsive Columns (Tablet)' , 'ubermenu' ),
			'desc'		=> __( 'By default, the mega submenu items will appear in two (2) columns from 480px to your breakpoint (960 by default) - approximately tablet-size.  If you\'d like them to appear in a single column instead, you can change that here.  This will only affect standard submenu items in mega submenus.' , 'ubermenu' ),
			'type'		=> 'radio',
			'options'	=> array(
				2		=> __( 'Two (2) Columns' , 'ubermenu' ),
				1		=> __( 'One (1) Column' , 'ubermenu' ),
			),
			'default'	=> 2,
			'group'		=> 'responsive',
		),


		415 => array(
			'name' 		=> 'responsive_collapse',
			'label' 	=> __( 'Collapse by default', 'ubermenu' ),
			'desc' 		=> __( 'Initiate the menu in a collapsed position on mobile.  Uncheck this if you do not want a responsive toggle, but just want to see all top level menu items on mobile.', 'ubermenu' ),
			'type' 		=> 'checkbox',
			'default' 	=> 'on',
			'group'		=> 'responsive',
		),

		416 => array(
			'name'	=> 'responsive_max_height',
			'label'	=> __( 'Responsive Max Height (px)' , 'ubermenu' ),
			'type'	=> 'text',
			'desc'	=> __( 'Adjusting this to the height of your responsive menu can make the transition smoother.  500 by default', 'ubermenu' ),
			'group'	=> 'responsive',
			'custom_style'	=> 'responsive_max_height',
		),

		417 => array(
			'name' 		=> 'responsive',
			'label' 	=> __( 'Responsive Menu', 'ubermenu' ),
			'desc' 		=> __( 'Uncheck this if you do not want a responsive menu at all.  Almost never used unless your site is not responsive at all.', 'ubermenu' ),
			'type' 		=> 'checkbox',
			'default' 	=> 'on',
			'group'		=> 'responsive',
		),




		/* Responsive Toggle */

		419 => array(
			'name'	=> 'header_responsive_toggle',
			'label'	=> __( 'Responsive Toggle' , 'ubermenu' ),
			'type'	=> 'header',
			'desc'	=> __( 'The Responsive Toggle is the button that toggles the menu open and closed below the mobile breakpoint' ),
			'group'	=> 'responsive',
		),

		420 => array(
			'name' 		=> 'responsive_toggle',
			'label' 	=> __( 'Responsive Toggle', 'ubermenu' ),
			'desc' 		=> __( 'Automatically display a responsive toggle for this menu.  Uncheck this if you wish to provide your own toggle, if have your menu expanded by default, or if you wish to hide the menu below the breakpoint.', 'ubermenu' ),
			'type' 		=> 'checkbox',
			'default' 	=> 'on',
			'group'		=> 'responsive',
		),

		421 => array(
			'name' 		=> 'responsive_toggle_close_icon',
			'label' 	=> __( 'Responsive Toggle Close Icon', 'ubermenu' ),
			'desc' 		=> __( 'Choose if you want to show an X icon when the menu is open.  Not compatible with SVG icons, font icons only', 'ubermenu' ),
			'type' 		=> 'radio',
			'options' => array(
				'bars' => '<i class="fas fa-bars"></i>',
				'times' => '<i class="fas fa-times"></i>',
			),
			'default' 	=> 'bars',
			'group'		=> 'responsive',
		),

		422 => array(
			'name' 		=> 'responsive_toggle_tag',
			'label' 	=> __( 'Responsive Toggle Tag', 'ubermenu' ),
			'desc' 		=> __( 'Button by default.', 'ubermenu' ),
			'type' 		=> 'radio',
			'default' 	=> 'button',
			'options'		=> array(
				'button'=> '&lt;button&gt;',
				'a'		=> '&lt;a&gt;',
				'div'	=> '&lt;div&gt;',
				'span'	=> '&lt;span&gt;',
			),
			'group'		=> 'responsive',
		),

		430 => array(
			'name' 		=> 'responsive_toggle_content',
			'label' 	=> __( 'Responsive Toggle Content', 'ubermenu' ),
			'desc' 		=> __( 'The text to display on the responsive toggle.', 'ubermenu' ),
			'type' 		=> 'text',
			'default' 	=> __( 'Menu', 'ubermenu' ),
			'group'		=> 'responsive',
			'sanitize_callback' => 'ubermenu_allow_html',
			'customizer'	=> true,
			'customizer_section' => 'toggle',
		),

		435 => array(
			'name' 		=> 'responsive_toggle_content_alignment',
			'label' 	=> __( 'Responsive Toggle Content Alignment', 'ubermenu' ),
			'desc' 		=> __( 'Alignment of the content within the toggle', 'ubermenu' ),
			'type' 		=> 'radio',
			'options'	=> array(
								'left'		=> __( 'Left' , 'ubermenu' ),
								'center'	=> __( 'Center' , 'ubermenu' ),
								'right'		=> __( 'Right' , 'ubermenu' ),
							),
			'default' 	=> 'left',
			'group'		=> 'responsive',
			'customizer'	=> true,
			'customizer_section' => 'toggle',
		),

		437 => array(
			'name' 		=> 'responsive_toggle_alignment',
			'label' 	=> __( 'Responsive Toggle Alignment', 'ubermenu' ),
			'desc' 		=> __( 'Alignment of the toggle button', 'ubermenu' ),
			'type' 		=> 'radio',
			'options'	=> array(
								'full'	=> __( 'Full Width' , 'ubermenu' ),
								'left'		=> __( 'Left' , 'ubermenu' ),
								'right'		=> __( 'Right' , 'ubermenu' ),
							),
			'default' 	=> 'full',
			'group'		=> 'responsive',
			'customizer'	=> true,
			'customizer_section' => 'toggle',
		),




		/* Responsive Submenu */

		450 => array(
			'name'		=> 'responsive_submenu_settings',
			'label'		=> __( 'Responsive Submenus' , 'ubermenu' ),
			'type'		=> 'header',
			'group'		=> array( 'responsive' , 'submenus' ),
		),

		451 => array(
			'name' 		=> 'mobile_submenu_type',
			'label' 	=> __( 'Mobile Submenu Type', 'ubermenu' ),
			'desc' 		=> __( 'Dropdowns will overlay other content in the menu.  Accordions will push down content below them.  Accordion mode will only apply when interactions are via click or tap, not hover.', 'ubermenu' ),
			'type' 		=> 'radio',
			'options'	=> array(
				'dropdown'		=> __( 'Dropdown' , 'ubermenu' ),
				'accordion'		=> __( 'Accordion' , 'ubermenu' ),
			),
			'default'	=> 'dropdown',
			'group'		=> array( 'responsive' , 'submenus' ),
		),

		453 => array(
			'name' 		=> 'display_retractor_top',
			'label' 	=> __( 'Display Submenu Retractor [Top]', 'ubermenu' ),
			'desc' 		=> __( 'Display a "Close" button at the top of the submenu on mobile devices.', 'ubermenu' ),
			'type' 		=> 'checkbox',
			'default' 	=> 'off',
			'group'		=> array( 'responsive' , 'submenus' ),
		),

		455 => array(
			'name' 		=> 'display_retractor_bottom',
			'label' 	=> __( 'Display Submenu Retractor [Bottom]', 'ubermenu' ),
			'desc' 		=> __( 'Display a "Close" button at the bottom of the submenu on mobile devices.', 'ubermenu' ),
			'type' 		=> 'checkbox',
			'default' 	=> 'off',
			'group'		=> array( 'responsive' , 'submenus' ),
		),

		457 => array(
			'name' 		=> 'retractor_label',
			'label' 	=> __( 'Submenu Retractor Text', 'ubermenu' ),
			'desc' 		=> __( 'By default, the retractor will read "Close", and will be translatable.  You can override it here but it will no longer be translatable.', 'ubermenu' ),
			'type' 		=> 'text',
			'default' 	=> '',
			'group'		=> array( 'responsive' , 'submenus' ),
		),


		/* Modal settings */

		460 => array(
			'name'		=> 'mobile_modal_header',
			'label'		=> __( 'Mobile Modal Settings' , 'ubermenu' ),
			'desc'		=> __( 'These settings only apply if you have set the Mobile Menu Display to Modal' , 'ubermenu' ),
			'type'		=> 'header',
			'group'		=> array( 'responsive' ),
		),

		462 => array(
			'name'		=> 'mobile_modal_header_content',
			'label'		=> __( 'Header Content' , 'ubermenu' ),
			'desc'		=> __( 'Add HTML or shortcodes here to insert content above the menu items in the modal.' , 'ubermenu' ),
			'type'		=> 'textarea',
			'default'	=> '',
			'group'		=> array( 'responsive' ),
			'sanitize_callback' => 'ubermenu_allow_html',
		),

		463 => array(
			'name'		=> 'mobile_modal_footer_content',
			'label'		=> __( 'Footer Content' , 'ubermenu' ),
			'desc'		=> __( 'Add HTML or shortcodes here to insert content below the menu items in the modal.' , 'ubermenu' ),
			'type'		=> 'textarea',
			'default'	=> '',
			'group'		=> array( 'responsive' ),
			'sanitize_callback' => 'ubermenu_allow_html',
		),

		464 => array(
			'name'		=> 'mobile_modal_background',
			'label'		=> __( 'Mobile Modal Background Color' , 'ubermenu' ),
			'desc'		=> __( 'The background color for the modal when in Modal mobile mode' , 'ubermenu' ),
			'type'		=> 'color',
			'group'		=> array( 'responsive' ),
			'customizer'	=> true,
			'customizer_section' => 'general', //'menu_bar',
			'custom_style' => 'mobile_modal_background',
		),



		);

	return apply_filters( 'ubermenu_instance_settings' , $settings , $config_id );
}


function ubermenu_get_settings_fields(){

	$prefix = UBERMENU_PREFIX;

	$settings_fields = _UBERMENU()->get_settings_fields();
	if( $settings_fields ) return $settings_fields;



	$main_assigned = '';
	if(!has_nav_menu('ubermenu')){
		$main_assigned = 'No Menu Assigned';
	}
	else{
    	$menus = get_nav_menu_locations();
    	$menu_title = wp_get_nav_menu_object($menus['ubermenu'])->name;
    	$main_assigned = $menu_title;
    }

    $main_assigned = '<span class="ubermenu-main-assigned">'.$main_assigned.'</span>  <p class="ubermenu-desc-understated">The menu assigned to the <strong>UberMenu [Main]</strong> theme location will be displayed.  <a href="'.admin_url( 'nav-menus.php?action=locations' ).'">Assign a menu</a></p>';

	$config_id = 'main';

	$fields = array(
		$prefix.$config_id => ubermenu_get_settings_fields_instance( $config_id )
	);


	$fields = apply_filters( 'ubermenu_settings_panel_fields' , $fields );

	// uberp( $fields , 2 );
	// echo '<pre>';
	// foreach( $fields['ubermenu_general'] as $i => $f ){
	// 	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$i .':'.$f['label']."\n";
	// }
	// echo '</pre>';

	//Allow ordering
	foreach( $fields as $section_id => $section_fields ){
		ksort( $fields[$section_id] );
		$fields[$section_id] = array_values( $fields[$section_id] );
	}

	_UBERMENU()->set_settings_fields( $fields );



//up( $fields );
	return $fields;
}

function ubermenu_get_settings_defaults(){

	$pro_defaults = false;
	if( !ubermenu_is_pro() ){
		//Setup pro defaults
		$pro_defaults = array(
			'auto_theme_location'	=> '',
			'nav_menu_id'			=> '_none',
			'bar_align'				=> 'full',
			'bar_width'				=> '',
			'items_align'			=> 'left',
			'items_align_vertical'	=> 'bottom',
			'bar_inner_center'		=> 'off',
			'bar_inner_width'		=> '',
			'bound_submenus'		=> 'on',
			'submenu_inner_width'	=> '',
			'submenu_max_height'	=> '',
			'image_size'			=> 'full',
			'image_width'			=> '',
			'image_height'			=> '',
			'image_set_dimensions'	=> 'on',
			'image_title_attribute'	=> 'off',
			'submenu_background_image_reponsive_hide' => 'off',

			'google_font'			=> '',
			'google_font_style'		=> '',
			'custom_font_property'	=> '',

			'container_tag'			=> 'nav',
			'allow_shortcodes_in_labels' => 'off',
			'display_submenu_indicators' => 'on',
			'display_submenu_close_button' => 'off',
			'theme_location_instance'	=> 0,

			'responsive_columns'	=> 2,
		);
	}

	$fields = ubermenu_get_settings_fields();

	$settings_defaults = array();

	foreach( $fields as $section_id => $ops ){
		$section_defaults = array();

		foreach( $ops as $op ){
			if( $op['type'] == 'header' ) continue;
			$section_defaults[$op['name']] = isset( $op['default'] ) ? $op['default'] : '';
		}

		if( $pro_defaults !== false ){
			$section_defaults = array_merge( $section_defaults , $pro_defaults );
		}

		$settings_defaults[$section_id] = $section_defaults;
	}
	return apply_filters( 'ubermenu_settings_defaults' , $settings_defaults );
}

add_filter( 'ubermenu_settings_panel_fields' , 'ubermenu_settings_panel_fields_general' );
function ubermenu_settings_panel_fields_general( $fields ){


	$fields[UBERMENU_PREFIX.'general'] = array(

		/* Custom Styles */
		10 => array(
			'name'	=> 'header_custom_styles',
			'label'	=> __( 'Custom Styles' , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'custom_css',
		),

		20 => array(
			'name'	=> 'custom_tweaks',
			'label'	=> __( 'Custom CSS Tweaks' , 'ubermenu' ),
			'type'	=> 'textarea',
			'group'	=> 'custom_css',
			'sanitize_callback' => 'ubermenu_allow_html',
		),

		30 => array(
			'name'	=> 'custom_tweaks_mobile',
			'label'	=> __( 'Custom CSS Tweaks - Mobile' , 'ubermenu' ),
			'desc'	=> __( 'Styles to apply below the responsive breakpoint only.' , 'ubermenu' ),
			'type'	=> 'textarea',
			'group'	=> 'custom_css',
			'sanitize_callback' => 'ubermenu_allow_html',
		),

		40 => array(
			'name'	=> 'custom_tweaks_desktop',
			'label'	=> __( 'Custom CSS Tweaks - Desktop' , 'ubermenu' ),
			'desc'	=> __( 'Styles to apply above the responsive breakpoint only.' , 'ubermenu' ),
			'type'	=> 'textarea',
			'group'	=> 'custom_css',
			'sanitize_callback' => 'ubermenu_allow_html',
		),









		/** Script Configuration **/
		170 => array(
			'name'	=> 'header_script_config',
			'label'	=> __( 'Script Configuration' , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'script_config',
		),

		175	=> array(
			'name'	=> 'touch_off_close',
			'label'	=> __( 'Touch-off Close' , 'ubermenu' ),
			'desc'	=> __( 'Close all submenus when the user clicks or touches off of the menu.  If you disable this, make sure you leave your users with another way to close the submenu.' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'=> 'on',
			'group'	=> 'script_config',
		),

		177	=> array(
			'name'	=> 'submenu_indicator_close_mobile',
			'label'	=> __( 'Show Indicator Submenu Close Button on Mobile' , 'ubermenu' ),
			'desc'	=> __( 'When a submenu is toggled open on mobile, a close button will appear in place of the submenu indicator on the parent item.' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'=> 'on',
			'group'	=> 'script_config',
		),


		180 => array(
			'name'	=> 'reposition_on_load',
			'label' => __( 'Reposition Submenus on window.load' , 'ubermenu' ),
			'desc'	=> __( 'Reposition the Submenus after other assets have loaded.  Useful if using @font-face fonts in the menu.', 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'=> 'off',
			'group'	=> 'script_config',
		),

		185 => array(
			'name'	=> 'remove_conflicts',
			'label' => __( 'Remove JS Conflicts' , 'ubermenu' ),
			'desc'	=> __( 'This will disable any event bindings added with jQuery unbind() or off() before the UberMenu script runs.  If you wish to bind your own events, or have other scripts act on the menu, you may need to disable this.', 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'=> 'on',
			'group'	=> 'script_config',
		),


		190 => array(
			'name'	=> 'header_hoverintent',
			'label'	=> __( 'HoverIntent Settings' , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'script_config',
		),
		191 => array(
			'name'	=> 'intent_delay',
			'label'	=> __( 'Hover Intent Delay' , 'ubermenu' ),
			'desc'	=> __( 'Time to wait until closing the submenu after hover-out (ms)' , 'ubermenu' ),
			'type'	=> 'text',
			'default'	=> 300,
			'group'	=> 'script_config',
		),

		195 => array(
			'name'	=> 'intent_interval',
			'label'	=> __( 'Hover Intent Interval' , 'ubermenu' ),
			'desc'	=> __( 'Polling interval for mouse comparisons (ms)' , 'ubermenu' ),
			'type'	=> 'text',
			'default'	=> 100,
			'group'	=> 'script_config',
		),

		200 => array(
			'name'	=> 'intent_threshold',
			'label'	=> __( 'Hover Intent Threshold' , 'ubermenu' ),
			'desc'	=> __( 'Maximum number of pixels over the target that the mouse can move (since the last poll) to be considered an intentional hover' , 'ubermenu' ),
			'type'	=> 'text',
			'default'	=> 7,
			'group'	=> 'script_config',
		),

		210 => array(
			'name'	=> 'header_scrollto',
			'label'	=> __( 'ScrollTo Settings' , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'script_config',
		),
		211 => array(
			'name'	=> 'scrollto_offset',
			'label' => __( 'ScrollTo Offset' , 'ubermenu' ),
			'desc'	=> __( 'Pixel offset to leave when scrolling.', 'ubermenu' ),
			'type'	=> 'text',
			'default'=> 50,
			'group'	=> 'script_config',
		),

		215 => array(
			'name'	=> 'scrollto_duration',
			'label' => __( 'ScrollTo Duration' , 'ubermenu' ),
			'desc'	=> __( 'Duration of the scroll animation in milliseconds.  The actual speed will be determined by the distance that needs to be traveled.  <em>1000</em> is 1 second.', 'ubermenu' ),
			'type'	=> 'text',
			'default'=> 1000,
			'group'	=> 'script_config',
		),
		216 => array(
			'name'	=> 'scrollto_disable_current',
			'label' => __( 'Automatically Disable Current Item Classes on ScrollTo Items' , 'ubermenu' ),
			'desc'	=> __( 'If you have multiple ScrollTo Links on the same page, they will all be marked as current on that page, if this setting is disabled.', 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'=> 'on',
			'group'	=> 'script_config',
		),

		217 => array(
			'name'	=> 'collapse_after_scroll',
			'label'	=> __( 'Collapse Menu after Scroll To (Mobile)' , 'ubermenu' ),
			'desc'	=> __( 'When a ScrollTo-enabled item is clicked on mobile, collapse the menu after the scroll completes' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'=> 'on',
			'group'	=> 'script_config',
		),













		/** Admin Notices **/


		270 => array(
			'name'	=> 'header_misc',
			'label'	=> __( 'Miscellaneous' , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'misc',
		),


		280 => array(
			'name'	=> 'admin_notices',
			'label'	=> __( 'Show Admin Notices' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'	=> 'on',
			'desc'	=> __( 'Display helpful notices - only to admins', 'ubermenu' ),
			'group'	=> 'misc',
		),



		283 => array(
			'name'	=> 'load_google_cse',
			'label'	=> __( 'Load Knowledgebase Search' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'=> 'on',
			'desc'	=> __( 'Load the Google Custom Search Engine to search the UberMenu Knowledgebase from the Help tab in this Control Panel' , 'ubermenu' ),
			'group'	=> 'misc',
		),
		284 => array(
			'name'	=> 'show_extensions',
			'label'	=> __( 'Show Extensions Tab' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'=> 'on',
			'desc'	=> __( 'Enable or disable the display of the Extensions tab in the Control Panel' , 'ubermenu' ),
			'group'	=> 'misc',
		),


		285 => array(
			'name'	=> 'disable_custom_content',
			'label'	=> __( 'Disable Custom Content Areas' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'=> 'off',
			'desc'	=> __( 'Easy way to test if conflicts are coming from custom content added within your menu.' , 'ubermenu' ),
			'group'	=> 'misc',
		),
		286 => array(
			'name'	=> 'disable_widget_areas',
			'label'	=> __( 'Disable Widget Areas' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'=> 'off',
			'desc'	=> __( 'Easy way to test if conflicts are coming from widget content added within your menu.' , 'ubermenu' ),
			'group'	=> 'misc',
		),






		/** MAINTAINENCE **/
		330 => array(
			'name'	=> 'header_maintenance',
			'label'	=> __( 'Maintenance' , 'ubermenu' ),
			'desc'	=> '<i class="fas fa-exclamation-triangle"></i> '. __( 'You should only adjust settings in this section if you are certain of what you are doing.'  , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'maintenance',
		),

		335 => array(
			'name'	=> 'migrate_fa4_fa5',
			'label'	=> __( 'Migrate Font Awesome', 'ubermenu' ),
			'desc'	=> '<a class="button button-primary" href="'.admin_url('themes.php?page=ubermenu-settings&do=fa4_to_fa5&ubermenu_nonce='.wp_create_nonce( 'ubermenu-control-panel-do' ) ).'">'.__( 'Migrate Font Awesome 4 to 5' , 'ubermenu' ).'</a><br/><p>'.__( 'Migrate Font Awesome 4 Icons to Font Awesome 5', 'ubermenu' ).'</p>',
			'type'	=> 'html',
			'group'	=> 'maintenance',

		),

		340 => array(
			'name'	=> 'migration',
			'label'	=> __( 'Migrate Settings' , 'ubermenu' ),
			'desc'	=> '<a class="button button-primary" href="'.admin_url('themes.php?page=ubermenu-settings&do=migration-check&ubermenu_nonce='.wp_create_nonce( 'ubermenu-control-panel-do' ) ).'">'.__( 'Migrate Settings' , 'ubermenu' ).'</a><br/><p>'.__( 'Migrate UberMenu 2 Settings to UberMenu 3', 'ubermenu' ).'</p>',
			'type'	=> 'html',
			'group'	=> 'maintenance',
		),

		350 => array(
			'name'	=> 'reset_all',
			'label'	=> __( 'Reset ALL Settings' , 'ubermenu' ),
			'desc'	=> '<a class="button button-primary" href="'.admin_url('themes.php?page=ubermenu-settings&do=reset-all-check&ubermenu_nonce='.wp_create_nonce( 'ubermenu-control-panel-do' )).'">'.__( 'Reset Settings' , 'ubermenu' ).'</a><br/><p>'.__( 'Reset ALL Control Panel settings to the factory defaults.', 'ubermenu' ).'</p>',
			'type'	=> 'html',
			'group'	=> 'maintenance',
		),


		/** ACCESSIBILITY */
		500 => array(
			'name'	=> 'accessibility_header',
			'label' => __( 'Accessibility' , 'ubermenu' ),
			'desc'	=> __( '' , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'accessibility',
		),
		510 => array(
			'name'	=> 'accessible',
			'label'	=> __( 'Enable Accessibility Features' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'	=> 'on',
			'desc'	=> __( 'Allow users to tab through the menu and highlight focused elements', 'ubermenu' ),
			'group'	=> 'accessibility',
		),
		520	=> array(
			'name'	=> 'skip_navigation',
			'label'	=> __( 'Skip Navigation Link' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'	=> 'off',
			'desc'	=> __( 'Add a hidden Skip Navigation link to assist those using screen readers.', 'ubermenu' ),
			'group'	=> 'accessibility',
		),
		524	=> array(
			'name'	=> 'esc_close_mobile',
			'label'	=> __( 'Collapse mobile menu with Escape key' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'	=> 'on',
			'desc'	=> __( 'On mobile, close the menu when the escape key is pressed.', 'ubermenu' ),
			'group'	=> 'accessibility',
		),
		//PROBLEM: different for each instance potentially
		// 525	=> array(
		// 	'name'	=> 'skip_navigation_target',
		// 	'label'	=> __( 'Skip Navigation Target' , 'ubermenu' ),
		// 	'type'	=> 'text',
		// 	'default'	=> '',
		// 	'desc'	=> __( 'Where to skip to.', 'ubermenu' ),
		// 	'group'	=> 'accessibility',
		// ),
		540	=> array(
			'name'	=> 'aria_header',
			'label' => __( 'ARIA' , 'ubermenu' ),
			'desc'	=> __( 'ARIA settings' , 'ubermenu' ),
			'type'	=> 'header',
			'group'	=> 'accessibility',
		),
		550	=> array(
			'name'	=> 'aria_role_navigation',
			'label'	=> __( 'role="navigation"' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'	=> 'off',
			'desc'	=> __( 'Add role="navigation" to the navigation container.  Note that this is redundant when using the HTML5 nav tag.', 'ubermenu' ),
			'group'	=> 'accessibility',
		),
		551	=> array(
			'name'	=> 'aria_nav_label',
			'label'	=> __( 'aria-label for nav' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'	=> 'off',
			'desc'	=> __( 'Add aria-label="{menu title}" to the nav tag.  The title of your menu assigned in Appearance - Menus will be used for this value', 'ubermenu' ),
			'group'	=> 'accessibility',
		),
		// 560	=> array(
		// 	'name'	=> 'aria_role_button',
		// 	'label'	=> __( 'role="button"' , 'ubermenu' ),
		// 	'type'	=> 'checkbox',
		// 	'default'	=> 'off',
		// 	'desc'	=> __( 'Add role="button" to items that toggle hidden panels.', 'ubermenu' ),
		// 	'group'	=> 'accessibility',
		// ),
		570	=> array(
			'name'	=> 'aria_expanded',
			'label'	=> __( 'aria-expanded' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'	=> 'off',
			'desc'	=> __( 'Add the aria-expanded attribute to activated anchors and their submenus when open', 'ubermenu' ),
			'group'	=> 'accessibility',
		),
		580	=> array(
			'name'	=> 'aria_hidden',
			'label'	=> __( 'aria-hidden [submenus]' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'	=> 'off',
			'desc'	=> __( 'Add the aria-hidden attribute to hidden submenus', 'ubermenu' ),
			'group'	=> 'accessibility',
		),
		// 590	=> array(
		// 	'name'	=> 'aria_controls',
		// 	'label'	=> __( 'aria-controls' , 'ubermenu' ),
		// 	'type'	=> 'checkbox',
		// 	'default'	=> 'off',
		// 	'desc'	=> __( 'Add the aria-controls attribute to anchors with submenus.  This necessitates adding an ID to each submenu', 'ubermenu' ),
		// 	'group'	=> 'accessibility',
		// ),
		594 => array(
			'name'	=> 'aria_responsive_toggle',
			'label'	=> __( 'ARIA for responsive toggle' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'	=> 'off',
			'desc'	=> __( 'Add role="button", aria-controls, aria-hidden, and aria-expanded attributes to responsive toggle.  Recommended: change the Responsive Toggle Tag setting to BUTTON', 'ubermenu' ),
			'group'	=> 'accessibility',
		),
		595	=> array(
			'name'	=> 'aria_hidden_icons',
			'label'	=> __( 'aria-hidden [icons]' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default'	=> 'off',
			'desc'	=> __( 'Add the aria-hidden attribute to Font Awesome icons.  You should enable this if your icons are purely decorative.', 'ubermenu' ),
			'group'	=> 'accessibility',
		),



	);


	if( UBERMENU_PRO ){
		$fields[UBERMENU_PREFIX.'general'][400] = array(
			'name'	=> 'lite_mode',
			'label'	=> __( 'Lite Mode' , 'ubermenu' ),
			'desc'	=> __( 'Use only the basic UberMenu options.  Note that you will lose all non-lite settings if you switch this on.' , 'ubermenu' ),
			'type'	=> 'checkbox',
			'default' => 'off',
			'group'	=> 'misc'
		);
	}


	return $fields;
}

add_action( 'admin_init' , 'ubermenu_reset_settings' , 100 );
function ubermenu_reset_settings(){

	if( isset( $_GET['page'] ) && $_GET['page'] == 'ubermenu-settings' ){

		if( !current_user_can( 'manage_options' ) ){
			die( 'You need to be an admin to do that' );
		}

		if( isset( $_GET['do'] ) && $_GET['do'] == 'reset-all' ){

			$instances = ubermenu_get_menu_instances( true );
			foreach( $instances as $config_id ){
				delete_option( UBERMENU_PREFIX.$config_id );
			}
			delete_option( UBERMENU_PREFIX.'general' );
			ubermenu_save_all_menu_styles();

		}
		else if( isset( $_GET['do'] ) && $_GET['do'] == 'reset-styles' ){

			$instances = ubermenu_get_menu_instances( true );
			$all_fields = ubermenu_get_settings_fields();
			foreach( $instances as $config_id ){
				$ops = ubermenu_get_instance_options( $config_id );
				$fields = $all_fields[UBERMENU_PREFIX.$config_id];
				//up( $fields , 2 );
				foreach( $fields as $field ){
					if( $field['group'] == 'style_customizations' && $field['type'] != 'header' ){
						$ops[$field['name']] = isset( $field['default'] ) ? $field['default'] : '';
					}
				}
				//up( $ops );
				update_option( UBERMENU_PREFIX.$config_id , $ops );
				ubermenu_save_all_menu_styles();

			}

		}
	}
}

function ubermenu_get_settings_sections(){

	$prefix = UBERMENU_PREFIX;

	$sections = array(

		array(
			'id' => $prefix.'main',
			'title' => __( 'Main UberMenu Configuration', 'ubermenu' ),
			'sub_sections'	=> ubermenu_get_settings_subsections( 'main' ),
		),

	);

	$sections = apply_filters( 'ubermenu_settings_panel_sections' , $sections );

	return $sections;

}

add_filter( 'ubermenu_settings_panel_sections' , 'ubermenu_general_settings_tab' , 80 );
function ubermenu_general_settings_tab( $sections ){
	$prefix = UBERMENU_PREFIX;
	$section = array(
		'id'	=> $prefix.'general',
		'title'	=> __( 'General Settings' , 'ubermenu' ),
		'sub_sections'	=> array(

			'custom_css'=> array(
				'title'	=> __( 'Custom CSS' , 'ubermenu' ),
			),
			'script_config'=> array(
				'title'	=> __( 'Script Configuration' , 'ubermenu' ),
			),
			'misc'=> array(
				'title'	=> __( 'Miscellaneous' , 'ubermenu' ),
			),
			'maintenance'=> array(
				'title'	=> __( 'Maintenance', 'ubermenu' ),
			),
			'accessibility'=> array(
				'title'	=> __( 'Accessibility', 'ubermenu' ),
			),

		),
	);

	$section = apply_filters( 'ubermenu_general_settings_sections' , $section );

	$sections[] = $section;

	return $sections;
}

function ubermenu_get_settings_subsections( $config_id ){
	return apply_filters( 'ubermenu_settings_subsections' ,
		array(
			'basic' => array(
				'title' => __( 'Basic Configuration' , 'ubermenu' ),
			),
			'descriptions'	=> array(
				'title'	=> __( 'Descriptions' , 'ubermenu' ),
			),
			'responsive'	=> array(
				'title'	=> __( 'Responsive & Mobile' , 'ubermenu' ),
			),
		),
		$config_id
	);
}

/**
 * Registers settings section and fields
 */
function ubermenu_admin_init() {

	$prefix = UBERMENU_PREFIX;

 	$sections = ubermenu_get_settings_sections();
 	$fields = ubermenu_get_settings_fields();

 	//set up defaults so they are accessible
	_UBERMENU()->set_defaults();


	$settings_api = _UBERMENU()->settings_api();

	//set sections and fields
	$settings_api->set_sections( $sections );
	$settings_api->set_fields( $fields );

	//initialize them
	$settings_api->admin_init();

}
add_action( 'admin_init', 'ubermenu_admin_init' );

function ubermenu_init_frontend_defaults(){
	if( !is_admin() ){
		_UBERMENU()->set_defaults();
	}
}
add_action( 'init', 'ubermenu_init_frontend_defaults' );

/**
 * Register the plugin page
 */
function ubermenu_admin_menu() {
	add_submenu_page(
		'themes.php',
		'UberMenu Settings',
		'UberMenu',
		'manage_options',
		'ubermenu-settings',
		'ubermenu_control_panel' //'ubermenu_settings_panel'
	);
}

add_action( 'admin_menu', 'ubermenu_admin_menu' );





/**
 * Display the plugin settings options page
 */
function ubermenu_control_panel() {

	if( !isset( $_GET['do'] ) ){
		ubermenu_settings_panel();
	}
	else{

		check_admin_referer( 'ubermenu-control-panel-do' , 'ubermenu_nonce' );

		switch( $_GET['do'] ){
			case 'fa4_to_fa5':
				ubermenu_fa4_to_fa5_panel();
				break;
			case 'widget-manager':
				ubermenu_widget_manager_panel();
				break;
			case 'migration-check':
				ubermenu_migration_panel();
				break;
			case 'migrate':
				ubermenu_migration_complete_panel();
				break;
			case 'reset-all-check':
				ubermenu_reset_all_check_panel();
				break;
			case 'reset-all':
				ubermenu_reset_all_complete_panel();
				break;

			case 'reset-styles-check':
				ubermenu_reset_styles_check_panel();
				break;
			case 'reset-styles':
				ubermenu_reset_styles_complete_panel();
				break;

			case 'settings-import':
				ubermenu_settings_import_panel();
				break;

			case 'no-migrate':
				//

			case 'reset-migration-check':
				//

			default:
				ubermenu_settings_panel();
				break;
		}
	}
}

function ubermenu_settings_import_panel(){

	?>
	<div class="wrap ubermenu-wrap">

		<h2><strong>UberMenu</strong> Settings Importer <span class="ubermenu-version">(alpha)</span></h2>

		<?php settings_errors(); ?>

		<?php


		//ACTUAL IMPORT ACTION
		if( isset( $_POST['ubermenu-settings-import-json'] ) && check_admin_referer( 'ubermenu-control-panel-do' , 'ubermenu_nonce' ) ){ //check_admin_referer( 'ubermenu-settings-import' , 'ubermenu_nonce' )

			$config_id = sanitize_text_field( $_POST['config_id'] );
			$json = stripslashes( $_POST['ubermenu-settings-import-json'] );

			if( !$json ){
				echo '<div class="error"><p>'.__( 'Please paste the data to import.' , 'ubermenu' ).'</p></div>';
			}
			else{
				$settings = json_decode( $json , true );	//decode into array
				if( !$settings ){
					echo '<div class="error"><p>'.__( 'Could not interpret import data.  Must be a valid JSON string.' , 'ubermenu' ).'</p></div>';
					echo '<pre style="border:1px solid #ddd; background:#eee; padding:10px; ">'.$json.'</pre>';
				}
				else{
					//uberp( $settings );
					update_option( UBERMENU_PREFIX.$config_id , $settings );
					echo '<div class="updated"><p>'.__( 'Settings imported successfully.' , 'ubermenu' ).'</p></div>';
				}
			}

		}

		//IMPORT DATA PANEL (PASTE EXPORT DATA)
		else{
			$config_id = sanitize_text_field( $_GET['config_id'] );
			if( $config_id == 'main' ): ?>
				<h3><?php _e( 'Import Settings for Main UberMenu Configuration' , 'ubermenu' ); ?></h3>
			<?php else: ?>
				<h3><?php _e( 'Import Settings for Configuration:' , 'ubermenu' ); ?> +<?php echo esc_html( $config_id ); ?></h3>
			<?php endif; ?>

			<?php
				echo '<div class="error"><p>'.__( 'This operation will overwrite all settings for this Configuration.  This cannot be undone.  This is the only warning/confirmation screen' , 'ubermenu' ).'</p></div>';
			?>

				<form action="<?php echo admin_url( 'themes.php?page=ubermenu-settings&do=settings-import' ); ?>" method="POST">
					<input type="hidden" name="page" value="ubermenu-settings" />
					<input type="hidden" name="do" value="settings-import" />
					<input type="hidden" name="config_id" value="<?php echo esc_html( $config_id ); ?>" />
					<?php //wp_nonce_field( 'ubermenu-settings-import' , 'ubermenu_nonce' ); ?>
					<?php wp_nonce_field( 'ubermenu-control-panel-do' , 'ubermenu_nonce' ); ?>

					<p><?php _e( 'Paste your export data here to import the settings into this configuration' , 'ubermenu' ); ?></p>

					<textarea style="width:600px; height:200px" name="ubermenu-settings-import-json"></textarea>

					<br/>
					<input type="submit" class="button button-primary" value="<?php _e( 'Confirm and Import Settings Now' , 'ubermenu' ); ?>" />
				</form>

				<br/><br/>
			<?php
		}

		ubermenu_admin_back_to_settings_button();

		?>
	</div>
	<?php
}

function ubermenu_migration_complete_panel(){

	?>
	<div class="wrap ubermenu-wrap">



		<h2><strong>UberMenu</strong> Migration <span class="ubermenu-version">v<?php echo UBERMENU_VERSION; ?></span></h2>

		<?php settings_errors(); ?>

		<?php

			$migration_status = get_option( UBERMENU_PREFIX.'migration_status' , false );

			//Settings Don't Exist
			if( $migration_status == 'complete' ){
				echo '<div class="updated"><p>'.__( 'Migration completed successfully!' , 'ubermenu' ).'</p></div>';
			}
			else if( $migration_status == 'in_progress' ){
				echo '<div class="error"><p>'.__( 'Migration error.  You may need to disable safe_mode to complete the migration.' , 'ubermenu' ).'</p></div>';
			}
			else{
				echo 'Ruh-roh, Reorge. Something\'s not right.';
				echo '<br/>migration_status: '.$migration_status;
			}

		?>
		<br/><br/>
		<?php ubermenu_admin_back_to_settings_button(); ?>
	</div>
	<?php
}

function ubermenu_migration_panel(){

	//ubermenu_migrate_item_settings();

	?>
	<div class="wrap ubermenu-wrap">

		<h2><strong>UberMenu</strong> Migration <span class="ubermenu-version">v<?php echo UBERMENU_VERSION; ?></span></h2>

		<?php
			$old_ops = get_option( 'wp-mega-menu-settings' , false ); // 'sparkops_ubermenu' );

			//Settings Don't Exist
			if( !$old_ops ){
				echo '<div class="error"><p>'.__( 'Sorry, couldn\'t find any UberMenu 2 settings to migrate' , 'ubermenu' ).'</p></div>';
				return;
			}
			else{
				echo '<div class="updated"><p>'.__( 'UberMenu 2 Settings Found' , 'ubermenu' ).'</p></div>';
				//uberp( $old_ops );

				echo '<div class="error"><p>'.__( 'Migrating will merge your UberMenu 2 settings into UberMenu 3, giving precedence to UberMenu 2 settings. This may overwrite existing UberMenu 3 settings.  Please be sure you wish to proceed, as this process is cannot be undone.  The process can take some time to complete.' , 'ubermenu' ).'</p></div>';


				?>
				<form action="<?php echo admin_url( 'themes.php' ); ?>" method="GET">
					<input type="hidden" name="page" value="ubermenu-settings" />
					<input type="hidden" name="do" value="migrate" />
					<?php wp_nonce_field( 'ubermenu-control-panel-do' , 'ubermenu_nonce' ); ?>

					<!-- <h4>Style Generator</h4> -->

					<br/>
					<h3>Migrate Control Panel Settings</h3>

					<label><input type="checkbox" checked="checked" value="on" name="migrate_control_panel" /> Migrate Control Panel</label>

					<p>This will migrate the settings from the UberMenu Control Panel, including the Style Generator settings if you had your Style Application set to the Style Generator.</p>

					<br/>
					<h3>Migrate Menu Item Settings</h3>

					<h4>Which Menus do you wish to migrate?</h4>

					<?php
						$menus = wp_get_nav_menus( array('orderby' => 'name') );
						foreach( $menus as $menu ): ?>
							<label><input type="checkbox" checked="checked" name="migrate_menu_ids[]" value="<?php echo $menu->term_id; ?>" /> <?php echo $menu->name; ?></label><br/>
						<?php endforeach; ?>

					<br/>
					<input type="submit" class="button button-primary" value="<?php _e( 'Confirm &amp; Migrate Settings' , 'ubermenu' ); ?>" />
				</form>

				<br/><br/>
				<?php
			}

			ubermenu_admin_back_to_settings_button();

		?>



	</div>
	<?php
}



function ubermenu_admin_back_to_settings_button(){
	?>
	<a class="button" href="<?php echo admin_url('themes.php?page=ubermenu-settings'); ?>">&laquo; Back to Control Panel</a>
	<?php
}



function ubermenu_reset_styles_complete_panel(){

	//ubermenu_migrate_item_settings();

	?>
	<div class="wrap ubermenu-wrap">



		<h2><strong>UberMenu</strong> Style Customization Settings Reset <span class="ubermenu-version">v<?php echo UBERMENU_VERSION; ?></span></h2>

		<?php settings_errors(); ?>

		<?php

			echo '<div class="updated"><p>'.__( 'Style Customizations reset complete!' , 'ubermenu' ).'</p></div>';

		?>
		<br/><br/>
		<?php ubermenu_admin_back_to_settings_button(); ?>
	</div>
	<?php
}



function ubermenu_reset_styles_check_panel(){

	//ubermenu_migrate_item_settings();

	?>
	<div class="wrap ubermenu-wrap">

		<h2><strong>UberMenu</strong> Style Customization Settings Reset <span class="ubermenu-version">v<?php echo UBERMENU_VERSION; ?></span></h2>

		<?php
			echo '<div class="error"><p>'.__( 'Please be sure you wish to proceed.  This will delete all Style Customizations (though not your custom CSS).  This cannot be undone.' , 'ubermenu' ).'</p></div>';


			?>
			<form action="<?php echo admin_url( 'themes.php' ); ?>" method="GET">
				<input type="hidden" name="page" value="ubermenu-settings" />
				<input type="hidden" name="do" value="reset-styles" />
				<?php wp_nonce_field( 'ubermenu-control-panel-do' , 'ubermenu_nonce' ); ?>

				<br/>
				<input type="submit" class="button button-primary" value="<?php _e( 'Confirm &amp; Reset Style Customizations' , 'ubermenu' ); ?>" />
			</form>
			<?php

		?>
		<br/><br/>
		<?php ubermenu_admin_back_to_settings_button(); ?>

	</div>
	<?php
}

function ubermenu_reset_all_complete_panel(){

	//ubermenu_migrate_item_settings();

	?>
	<div class="wrap ubermenu-wrap">



		<h2><strong>UberMenu</strong> Settings Reset <span class="ubermenu-version">v<?php echo UBERMENU_VERSION; ?></span></h2>

		<?php settings_errors(); ?>

		<?php

			echo '<div class="updated"><p>'.__( 'Settings reset complete!' , 'ubermenu' ).'</p></div>';

		?>
		<br/><br/>
		<?php ubermenu_admin_back_to_settings_button(); ?>
	</div>
	<?php
}



function ubermenu_reset_all_check_panel(){

	//ubermenu_migrate_item_settings();

	?>
	<div class="wrap ubermenu-wrap">

		<h2><strong>UberMenu</strong> Settings Reset <span class="ubermenu-version">v<?php echo UBERMENU_VERSION; ?></span></h2>

		<?php
			echo '<div class="error"><p>'.__( 'Please be sure you wish to proceed.  This will delete all Menu Settings, including Custom Styles, Instance Settings and General Settings.  This cannot be undone.' , 'ubermenu' ).'</p></div>';


			?>
			<form action="<?php echo admin_url( 'themes.php' ); ?>" method="GET">
				<input type="hidden" name="page" value="ubermenu-settings" />
				<input type="hidden" name="do" value="reset-all" />
				<?php wp_nonce_field( 'ubermenu-control-panel-do' , 'ubermenu_nonce' ); ?>

				<br/>
				<input type="submit" class="button button-primary" value="<?php _e( 'Confirm &amp; Reset Settings' , 'ubermenu' ); ?>" />
			</form>
			<?php

		?>
		<br/><br/>
		<?php ubermenu_admin_back_to_settings_button(); ?>

	</div>
	<?php
}

function ubermenu_settings_panel(){
	if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ){
		do_action( 'ubermenu_settings_panel_updated' );
	}

	$settings_api = _UBERMENU()->settings_api();

	echo '<div class="wrap ubermenu-wrap">';
	settings_errors();

	?>
	<div class="ubermenu-settings-links">
		<?php do_action( 'ubermenu_settings_before_title' ); ?>
	</div>
	<?php

	echo '<h1><strong>UberMenu</strong> Control Panel <span class="ubermenu-version">v'.UBERMENU_VERSION.'</span></h1>';

	do_action( 'ubermenu_settings_before' );

	$settings_api->show_navigation();
	$settings_api->show_forms();

	do_action( 'ubermenu_settings_after' );

	echo '</div>';
}

function ubermenu_settings_links(){
	if( ubermenu_is_pro() ) echo '<a class="button button-quickstart" href="#"><i class="fas fa-bolt"></i> QuickStart</a> ';
	echo '<a target="_blank" class="button button-primary" href="'.UBERMENU_KB_URL.'"><i class="fas fa-book"></i> Knowledgebase</a> ';
	echo '<a target="_blank" class="button button-tertiary" href="'.UBERMENU_VIDEOS_URL.'"><i class="fas fa-video"></i> Video Tutorials</a> ';
	if( ubermenu_is_pro() ) echo '<a target="_blank" class="button button-secondary" href="'.UBERMENU_TROUBLESHOOTER_URL.'"><i class="fas fa-wrench"></i> Troubleshooter</a> ';
	if( ubermenu_is_pro() &&
		( !defined( 'UBERMENU_PACKAGED_THEME' ) || ubermenu_op( 'purchase_code' , 'updates' , '' ) )
		) echo '<a target="_blank" class="button button-secondary ubermenu-button-support" href="'.ubermenu_get_support_url().'"><i class="fas fa-life-ring"></i> Support</a> ';
}
add_action( 'ubermenu_settings_before_title' , 'ubermenu_settings_links' );




/**
 * Get the value of a settings field
 *
 * @param string $option settings field name
 * @param string $section the section name this field belongs to
 * @param string $default default text if it's not found
 * @return mixed
 */
function ubermenu_op( $option, $section, $default = null ) {

	$options = get_option( UBERMENU_PREFIX.$section , array() );		//cached by WP

	//Value from settings
	if ( isset( $options[$option] ) ) {
		$val = $options[$option];
		//return $val;
	}
	//Default Fallback
	else{
		//No default passed
		if( $default === null ){
			//$default = _UBERMENU()->settings_api()->get_default( $option, UBERMENU_PREFIX.$section );
			$val = _UBERMENU()->get_default( $option, UBERMENU_PREFIX.$section );
		}
		//Use passed default
		else{
			$val = $default;
		}
	}

	$val = apply_filters( 'ubermenu_op' , $val , $option , $section );

	return $val;
}
function ubermenu_get_instance_options( $instance ){
	//echo UBERMENU_PREFIX.$instance;
	$defaults = _UBERMENU()->get_defaults( UBERMENU_PREFIX.$instance );
	$options = get_option( UBERMENU_PREFIX.$instance , $defaults );
	if( !is_array( $options ) || count( $options ) == 0 ) return $defaults;
	return $options;
}

function ubermenu_admin_panel_assets( $hook ){

	if( $hook == 'appearance_page_ubermenu-settings' ){
		wp_enqueue_script( 'ubermenu-control-panel' , UBERMENU_URL . 'admin/assets/admin.settings.js' , array( 'jquery' ) , UBERMENU_VERSION , true );
		wp_enqueue_style( 'ubermenu-settings-styles' , UBERMENU_URL.'admin/assets/admin.settings.css' );

		//font awesome 4
		//wp_enqueue_style( 'ubermenu-font-awesome' , UBERMENU_URL.'assets/css/fontawesome/css/font-awesome.min.css' );

		//font awesome 5
		wp_enqueue_style( 'ubermenu-font-awesome-all' , 	UBERMENU_URL .'assets/fontawesome/css/all.min.css' , false , false );

		wp_localize_script( 'ubermenu-control-panel' , 'ubermenu_control_panel' , array(
			'load_google_cse'	=> ubermenu_op( 'load_google_cse' , 'general' ),
		) );
	}
}
add_action( 'admin_enqueue_scripts' , 'ubermenu_admin_panel_assets' );



function ubermenu_check_menu_assignment(){
	$display = ubermenu_op(  'display_main' , 'ubermenu-main' );

	if( $display == 'on' ){
		if( !has_nav_menu( 'ubermenu' ) ){
			?>
			<div class="update-nag"><strong>Important!</strong> There is no menu assigned to the <strong>UberMenu [Main]</strong> Menu Location.  <a href="<?php echo admin_url( 'nav-menus.php?action=locations' ); ?>">Assign a menu</a></div>
			<br/><br/>
			<?php
		}
	}
}
add_action( 'ubermenu_settings_before' , 'ubermenu_check_menu_assignment' );

function ubermenu_allow_html( $str ){
	return $str;
}






add_filter( 'ubermenu_settings_panel_sections' , 'ubermenu_settings_panel_go_pro' , 1000 );
function ubermenu_settings_panel_go_pro( $sections = array() ){

	if( ! defined( 'UBERMENU_UPGRADE' ) )
			define( 'UBERMENU_UPGRADE' , true );

	if( !UBERMENU_UPGRADE || UBERMENU_PRO ) return $sections;

	$sections[] = array(
		'id'	=> UBERMENU_PREFIX.'go_pro',
		'title' => __( 'Go Pro' , 'ubermenu' ) . ' <i class="fas fa-rocket"></i>',
		'sub_sections'	=> array(
			'compare'	=> array(
				'title'	=> __( 'Compare', 'ubermenu' ),
			),
			// 'pro_demo'	=> array(
			// 	'title'	=> __( 'Demo', 'ubermenu' ),
			// ),
		),
	);

	return $sections;
}

add_filter( 'ubermenu_settings_panel_fields' , 'ubermenu_settings_panel_fields_go_pro' );
function ubermenu_settings_panel_fields_go_pro( $fields ){

	if( ! defined( 'UBERMENU_UPGRADE' ) )
			define( 'UBERMENU_UPGRADE' , true );

	if( !UBERMENU_UPGRADE || UBERMENU_PRO ) return $fields;

	$compare = '

	<h2>Turn your menu up a notch with UberMenu Pro</h2>

	<p>
	Your theme includes the lite version of UberMenu, which allows you to create awesome basic mega menus. Upgrade to the full UberMenu MegaMenu Plugin to get even more advanced features, like images, widgets, shortcodes, and more!
	</p>

	<div class="spark-action-button">
		<a href="http://wpmegamenu.com" target="_blank" class="">Learn More <i class="fas fa-chevron-right"></i></a>
	</div>
	<table class="ss-table-compare">
			<tbody><tr>
				<th></th>
				<th>UberMenu Lite					<span class="desc">Included with theme</span>
				</th>
				<th>UberMenu					<span class="desc">Full plugin upgrade</span>
				</th>
			</tr>
			<tr>
				<td class="ss-feature">Click or Hover Trigger</td>
				<td><i class="fas fa-check"></i></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">Slide or Fade Effects</td>
				<td><i class="fas fa-check"></i></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">Responsive</td>
				<td><i class="fas fa-check"></i></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">Mega Menus</td>
				<td><i class="fas fa-check"></i></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">Descriptions</td>
				<td><i class="fas fa-check"></i></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">Images
					<span class="desc">Insert images for each menu item based on the post\'s featured image, or upload your own.</span>
				</td>
				<td></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">Dynamic Menu Items					<span class="desc">Automatically generate menu items from your site content</span></td>
				<td></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">Tabbed Submenus					<span class="desc">Organize your submenus into tabs to display even more content</span></td>
				<td></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">Custom Content &amp; Widgets					<span class="desc">Add any custom HTML or widget content to your menu</span></td>
				<td></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">Google Maps					<span class="desc">Easily add Google Maps to your menu with a shortcode</span></td>
				<td></td>
				<td><i class="fas fa-check"></i></td>
			</tr>

			<tr>
				<td class="ss-feature">Contact Forms &amp; Shortcodes					<span class="desc">Display a Contact Form 7 form or any shortcode in your menu</span></td>
				<td></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">20+ Skins					<span class="desc">Choose from over 20 preset styles</span></td>
				<td></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">Style Customizer					<span class="desc">Tweak over 50 settings in the Customizer</span></td>
				<td></td>
				<td><i class="fas fa-check"></i></td>
			</tr>

			<tr>
				<td class="ss-feature">Fonts
					<span class="desc">Choose from 30 of the most popular Google Fonts</span>
				</td>
				<td></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
			<tr>
				<td class="ss-feature">Compatible with UberMenu Extensions					<span class="desc">Extend the functionality of your menu with great extensions like <a href="http://wpmegamenu.com/icons">Icons</a>, <a href="http://goo.gl/0LrTj">Conditionals</a> and <a href="wpmegamenu.com/sticky">Sticky</a></span></td>
				<td></td>
				<td><i class="fas fa-check"></i></td>
			</tr>
		</tbody></table>

		<div class="spark-action-button">
			<a href="http://wpmegamenu.com" target="_blank" class="">Get UberMenu Pro <i class="fas fa-chevron-right"></i></a>
		</div>

		<div style="font-size:11px; color:#999; border-top:1px dotted #ccc; margin-top:80px;">
			<p>If you would like to hide this panel, you can do so by adding the following code to your functions.php file: </p>

			<pre>define( \'UBERMENU_UPGRADE\' , false );</pre>

		<div id="container-ubermenu-pro-upgrade" class="spark-admin-op container-type-custom sub-container sub-container-wpmega-desc-header "></div>

															</div>
';

	$fields[UBERMENU_PREFIX.'go_pro'] = array(

		10 => array(
			'name'	=> 'go_pro_compare',
			'label'	=> __( 'Compare', 'ubermenu' ),
			'type'	=> 'html',
			'desc'	=> $compare,
			'group'	=> 'compare',
		)

	);

	return $fields;
}



function ubermenu_new_default( $config_id , $new_default , $old_default ){
	//echo $config_id;
	$settings_exist = get_option( UBERMENU_PREFIX.$config_id , false );
	//uberp( $settings_exist );
	if( $settings_exist ){ //echo 'hi';
		return $old_default; }
	else return $new_default;

}
