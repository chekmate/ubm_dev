<?php
/**
 * Add the Pro Sub Sections for all Instances
 */
add_filter( 'ubermenu_settings_subsections' , 'ubermenu_pro_instance_subsections' , 20 , 2 );
function ubermenu_pro_instance_subsections( $subsections , $config_id ){
	return array(
		'integration'	=> array(
			'title'	=> __( 'Integration' , 'ubermenu' ),
		),
		'basic' => array(
			'title' => __( 'Basic Configuration' , 'ubermenu' ),
		),
		'position'	=> array(
			'title'	=> __( 'Position & Layout' , 'ubermenu' ),
		),
		'submenus'	=> array(
			'title'	=> __( 'Submenus' , 'ubermenu' ),
		),
		'descriptions'	=> array(
			'title'	=> __( 'Descriptions' , 'ubermenu' ),
		),
		// 'custom_content'	=> array(
		// 	'title'	=> __( 'Custom Content' , 'ubermenu' ),
		// ),
		'images'	=> array(
			'title'	=> __( 'Images' , 'ubermenu' ),
		),
		'responsive'	=> array(
			'title'	=> __( 'Responsive & Mobile' , 'ubermenu' ),
		),
		'style_customizations' => array(
			'title'	=> __( 'Style Customizations' , 'ubermenu' ),
		),
		'icons'	=> array(
			'title'	=> __( 'Icons' , 'ubermenu' ),
		),
		'badges' => array(
			'title' => __( 'Badges', 'ubermenu' ),
		),
		'fonts'	=> array(
			'title'	=> __( 'Fonts' ),
		),
		'misc'	=> array(
			'title'	=> __( 'Miscellaneous' , 'ubermenu' ),
		),
		'advanced'	=> array(
			'title'	=> __( 'Advanced' , 'ubermenu' ),
		),
		'export'	=> array(
			'title'	=> __( 'Import/Export' , 'ubermenu' ),
		),
	);
}

/**
 * Add the Pro settings for all Instances
 */
function ubermenu_menu_integration_code( $args , $config_id ){

	//$shortcode = '';
	//$api = '';

	// if( $menu_id == '_default' ){
	// 	$shortcode = '<code class="ubermenu-highlight-code">[ubermenu config="'.$config_id.'"]</code>'; //Toggle Content[/ubermenu_toggle]</code>'
	// 	$api = '<code class="ubermenu-highlight-code">&lt;?php ubermenu( \''.$config_id.'\' ); ?&gt;</code>';
	// }
	//else{
		// $shortcode = '<code class="ubermenu-highlight-code">[ubermenu config="'.$config_id.'" menu="'.$menu_id.'"]</code>';//Toggle Content[/ubermenu_toggle]</code>'
		// $api = '<code class="ubermenu-highlight-code">&lt;?php ubermenu( \''.$config_id.'\' , array( \'menu\' => '.$menu_id.' ) ); ?&gt;</code>';
	//}

	$shortcode = '<code class="ubermenu-highlight-code">[ubermenu config_id="'.$config_id.'"';
	$api = '<code class="ubermenu-highlight-code">&lt;?php ubermenu( \''.$config_id.'\' ';
	if( is_array( $args ) && !empty( $args ) ){
		$api.= ', array( ';
		$k = 0;
		foreach( $args as $key => $val ){
			$shortcode.= ' '.$key.'="'.$val.'"';

			if( $k>0 ) $api.= ",";

			if( !is_numeric( $val ) ) $val = "'$val'";
			$api.= "'$key' => $val ";

			$k++;
		}
		$api.= ') ';
	}
	$shortcode.= ']</code>';
	$api.= '); ?&gt;</code>';

	$code_id = '_default';
	if( isset( $args['theme_location'] ) ) $code_id = $args['theme_location'];
	else if( isset( $args['menu'] ) ) $code_id = $args['menu'];

	$code =
		'<div class="ubermenu-integration-code ubermenu-integration-code-'.$code_id.'">'.
			'<div class="ubermenu-desc-row">
				<span class="ubermenu-code-snippet-type">PHP</span> '.$api.'
			</div>
			<div class="ubermenu-desc-row">
				<span class="ubermenu-code-snippet-type">Shortcode</span> '.$shortcode.'
			</div>
			<p class="ubermenu-sub-desc ubermenu-desc-mini" >Click to select, then <strong><em>&#8984;+c</em></strong> or <strong><em>ctrl+c</em></strong> to copy to clipboard</p>
			<p class="ubermenu-sub-desc ubermenu-desc-understated">Pick the appropriate code and add to your theme template or content where you want the menu to appear.</p>
			<p class="ubermenu-sub-desc ubermenu-sub-desc-manualint"><i class="fas fa-arrow-down"></i> Select a <strong>Theme Location</strong> or <strong>Menu</strong> below to generate the proper code above <i class="fas fa-arrow-up"></i></p>'.
		'</div>';

	return $code;
}
function ubermenu_integration_code_ui( $config_id ){
	$integration_code = '<div class="ubermenu-integration-code-wrap">'.ubermenu_menu_integration_code( array() , $config_id );

	$menu_select = '<h4>Integrate Specific Menu</h4>';
	$loc_select = '<h4>Integrate Specific Theme Location</h4>';

	$menus = wp_get_nav_menus( array('orderby' => 'name') );

	if( is_array( $menus ) ){
		foreach( $menus as $menu ){
			$integration_code.= ubermenu_menu_integration_code( array( 'menu' => $menu->term_id ) , $config_id );
		}

		$menu_select.= '<select class="ubermenu-manual-code-menu-selection">';
		$menu_select.= '<option value="_default">Default</option>';
		foreach( $menus as $menu ){
			$menu_select.= '<option value="'.$menu->term_id.'">'.$menu->name.'</option>';
		}
		$menu_select.= '</select>';

		$menu_select.= '<p class="ubermenu-sub-desc ubermenu-desc-understated">To display a specific menu, select the menu above to generate that code</p>';
	}

	$locs = get_registered_nav_menus();

	if( is_array( $locs ) ){

		foreach( $locs as $loc_id => $loc_name ){
			$integration_code.= ubermenu_menu_integration_code( array( 'theme_location' => $loc_id ) , $config_id );
		}

		$loc_select.= '<select class="ubermenu-manual-code-menu-selection">';
		$loc_select.= '<option value="_default">None</option>';
		foreach( $locs as $loc_id => $loc_name ){
			$loc_select.= '<option value="'.$loc_id.'">'.$loc_name.'</option>';
		}
		$loc_select.= '</select>';

		$loc_select.= '<p class="ubermenu-sub-desc ubermenu-desc-understated">To display a specific theme locaton, select the theme location above to generate that code</p>';
	}

	$integration_code.= $menu_select . $loc_select;

	$integration_code.='</div>';

	return $integration_code;
}

add_filter( 'ubermenu_instance_settings' , 'ubermenu_pro_instance_settings' , 20 , 2 );
function ubermenu_pro_instance_settings( $settings , $config_id ){

	//$integration_code = ubermenu_integration_code_ui( $config_id );

	//Integration

	$settings[5] = array(
		'name'	=> 'sandbox_viewer',
		'label'	=> __( 'Sandbox Viewer (Alpha)', 'ubermenu'),
		'type'	=> 'html',
		'desc'	=> '<a target="_blank" class="button button-primary" href="'.ubermenu_sandbox_url().'">View Menu in Sandbox</a> <p><span class="description">' . __('View the menu in a sandbox so you can see how the menu should look with no CSS or JS interference.  Note that PHP interference via filters or actions may still apply.', 'ubermenu' ) .'  <a href="https://sevenspark.com/docs/ubermenu-3/sandbox">Learn more</a></span></p>',
		'group'	=> 'integration',

	);

	$settings[10] = array(
		'name'	=> 'header_integration',
		'label'	=> __( 'Automatic Integration' , 'ubermenu' ),
		'desc'	=> __( 'To integrate UberMenu into your site, either (1) activate the Theme Location(s) below to automatically replace an existing theme menu, or (2) use the provided manual integration code to insert the menu wherever you like in your theme templates or content.<br/> Some themes may require manual integration if their CSS or Javascript interferes with the menu.' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'integration',
	);

	$settings[20] = array(
		'name'	=> 'auto_theme_location',
		'label'	=> __( 'Automatic Integration Theme Location' , 'ubermenu' ),
		'type'	=> 'multicheck',
		'desc'	=> __( 'Select the theme locations to activate automatically.  Works with most modularly coded themes.  The above theme locations are specific to your site.' , 'ubermenu' ) .
					'<div class="ubermenu-alert">Please note that if your menu doesn\'t seem to be working properly after using Automatic Integration, the most common scenario is that you have <a href="https://sevenspark.com/docs/ubermenu-3/integration/residual-styling" target="_blank" title="What is residual styling and how can it be avoided?">Residual Styling <i class="fas fa-book"></i></a> from your theme and would need to use <a href="https://sevenspark.com/docs/ubermenu-3/integration/manual" target="_blank" title="How to add the menu code directly to the theme template to avoid interference from the theme">Manual Integration <i class="fas fa-book"></i></a> to prevent the theme from interfering.  For assistance, try the <a href="https://sevenspark.com/docs/ubermenu-3/diagnostics/residual-styling-detection-tool" title="This tool helps you detect, locate, and resolve residual styling" target="_blank" >Residual Styling Detection / Manual Integration Tool <i class="fas fa-book"></i></a>.  If the issues occur in the Direct Injection menu as well, please see <a href="https://sevenspark.com/docs/ubermenu-3/integration/theme-interference" title="Knowledgebase: Other ways in which themes can interfere with the menu" target="_blank">Theme Interference <i class="fas fa-book"></i></a></div>',
		'options' => 'ubermenu_get_theme_location_ops',
		'default' => '',
		'group'	=> 'integration',
	);

	$settings[22] = array(
		'name'	=> 'disable_mobile',
		'label'	=> __( 'Disable UberMenu on Mobile' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'desc'	=> __( '(Automatic Integration Only).  When a mobile device is detected via <a href="https://codex.wordpress.org/Function_Reference/wp_is_mobile" target="_blank">wp_is_mobile()</a>, UberMenu will not replace the menu via automatic integration.  By default this includes tablets.  <br/>- <strong>Keep in mind if you are using a cache, you must cache desktop and mobile separately in order for this to work properly</strong>.  <br/>- Remember that UberMenu Advanced Items can only be used within UberMenus, so you likely don\'t want to enable this setting if you are using them.  <br/>- To customize what is considered "mobile", you can use the <a href="https://sevenspark.com/docs/ubermenu-3/developers/php-api/filters/ubermenu_is_mobile" target="_blank"><code>ubermenu_is_mobile</code></a> filter', 'ubermenu' ),
		'default' => 'off',
		'group'	=> 'integration',
	);



	$settings[25] = array(
		'name'	=> 'direct_inject',
		'label'	=> __( 'Direct Injection Testing' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'desc'	=> __( 'This setting is intended for testing - enable and compare this menu to your main menu.  It will add UberMenu to your site via the wp_footer() hook and fix it to the top of your site.  This allows you to test the menu without interference from the theme\'s menu (in the majority of cases).  It is a good way to test if you need to use Manual Integration.  You will need to assign a menu to the UberMenu [Direct Injection] theme location' , 'ubermenu' ),
		'default' => 'off',
		'group'	=> 'integration',
	);



	$settings[30] = array(
		'name'	=> 'header_manual_integration',
		'label'	=> __( 'Manual Integration' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'integration',
		'desc'	=> __( 'If your theme styles interfere when using Automatic Integration, or if you need to add a new UberMenu, generate the necessary Integration code here.' , 'ubermenu' ),
	);

	$settings[40] = array(
		'name'	=> 'php',
		'label'	=> __( 'Manual Integration Code' , 'ubermenu' ),
		'desc'	=> array( 'func' => 'ubermenu_integration_code_ui' , 'args' => $config_id ), //$integration_code,
		'type'	=> 'func_html',
		'group'	=> 'integration'
	);

	$settings[50] = array(
		'name'	=> 'nav_menu_id',
		'label'	=> __( 'Default Manual Integration Menu', 'ubermenu' ),
		'desc'	=> __( 'This is the default menu that will appear when you use the manual integration code.  It can be overridden by the <code>menu</code> parameter within the nav menu args array.  Please note that this setting will override any theme_location parameter passed to the ubermenu() function.', 'ubermenu' ),
		'type'	=> 'select',
		'default'	=> '_none',
		'options' => 'ubermenu_get_nav_menu_ops',
		'group'	=> 'integration',
	);




	/* Position & Layout */

	$settings[150] = array(
		'name'	=> 'header_position_menu_bar',
		'label'	=> __( 'Menu Bar' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'position',
	);

	$settings[160] = array(
		'name'		=> 'bar_align',
		'label'		=> __( 'Menu Bar Alignment' , 'ubermenu' ),
		'desc'		=> __( 'Alignment relative to the theme container.  <br/>The theme\'s container limits the maximum width of the menu bar.  If the theme\'s container element is 500px, the Full Width setting will make the menu 500px wide.  <br/>If you choose "Center", you must set a Menu Bar Width below.' , 'ubermenu' ),
		'type'		=> 'radio',
		'options' 	=> array(
			'full'	=> __( 'Full Width (of theme container element)', 'ubermenu' ),
			'left' 	=> __( 'Left', 'ubermenu' ),
			'right'	=> __( 'Right', 'ubermenu' ),
			'center'=> __( 'Center (requires Menu Bar Width)', 'ubermenu' ),
		),
		'default' 	=> 'full',
		'group'	=> 'position',
		'customizer'	=> true,
		'customizer_section' => 'menu_bar',

	);


	$settings[170] = array(
		'name'		=> 'bar_width',
		'label'		=> __( 'Menu Bar Width' , 'ubermenu' ),
		'desc'		=> __( 'Set an explicit width for the menu bar.  Required for centering.  Generally not needed.', 'ubermenu' ),
		'type'		=> 'text',
		'default' 	=> '',
		'group'	=> 'position',
		'custom_style' => 'bar_width',

		'customizer'	=> true,
		'customizer_section' => 'menu_bar',
	);

	$settings[172] = array(
		'name'		=> 'bar_margin_top',
		'label'		=> __( 'Menu Bar Margin Top' , 'ubermenu' ),
		'desc'		=> __( 'Useful for tweaking position', 'ubermenu' ),
		'type'		=> 'text',
		'default' 	=> '',
		'group'	=> 'position',
		'custom_style' => 'bar_margin_top',
		'customizer'=> true,
		'customizer_section' => 'menu_bar',
	);
	$settings[173] = array(
		'name'		=> 'bar_margin_bottom',
		'label'		=> __( 'Menu Bar Margin Bottom' , 'ubermenu' ),
		'desc'		=> __( 'Useful for spacing out elements', 'ubermenu' ),
		'type'		=> 'text',
		'default' 	=> '',
		'group'	=> 'position',
		'custom_style' => 'bar_margin_bottom',
		'customizer'=> true,
		'customizer_section' => 'menu_bar',
	);



	/* Menu Items Alignment */
	$settings[180] = array(
		'name'	=> 'header_position_menu_items',
		'label'	=> __( 'Top Level Menu Items' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'position',
	);

	$settings[190] = array(
		'name'		=> 'items_align',
		'label'		=> __( 'Horizontal Item Alignment' , 'ubermenu' ),
		'type'		=> 'radio',
		'options' 	=> array(
			'auto'	=> __( 'Automatic' , 'ubermenu' ),
			'left' 	=> __( 'Left', 'ubermenu' ),
			'center'=> __( 'Center', 'ubermenu' ),
			'right'	=> __( 'Right', 'ubermenu' ),
			'flex'	=> __( 'Flex (Justify)', 'ubermenu' ),
		),
		'desc'	=> __( 'The alignment of the top level menu items within the menu bar.  Please note that the menu item alignment will only have an effect if the menu bar is wider than the menu items.  <br/><strong>Automatic</strong> will align left/right based on the current language direction LTR/RTL.  <br/><strong>Flex (Justify)</strong> will use Flexbox styling to evenly distribute any extra space in the menu bar evenly over the items.' , 'ubermenu' ),
		'default' 	=> 'auto',
		'group'	=> 'position',
		'customizer'	=> true,
		'customizer_section' => 'menu_bar',
	);


	$settings[195] = array(
		'name' 	=> 'items_vertical_stretch',
		'label' => __( 'Vertical Item Stretch' , 'ubermenu' ),
		'desc'	=> __( 'Stretch all top level items to equal height and center content vertically', 'ubermenu' ),
		'type' 	=> 'checkbox',
		'default' => 'off',
		'group' => 'position',
	);

	/* Won't do anything with floated items.
	$settings[195] = array(
		'name'		=> 'items_align_vertical',
		'label'		=> __( 'Vertical Item Alignment' , 'ubermenu' ),
		'desc'		=> __( 'Align the menu items to the top or bottom of the menu bar.  Makes no difference if all items are the same height.  Most useful for scenarios like top level stacks.', 'ubermenu' ),
		'type'		=> 'radio',
		'options' 	=> array(
			'bottom'=> __( 'Default (Natural Height)', 'ubermenu' ),
			'top' 	=> __( 'Top', 'ubermenu' ),
		),
		'default' 	=> 'bottom',
		'group'	=> 'position',
	);
	*/

	/* Inner Bar Position & Layout */

	$settings[210] = array(
		'name'	=> 'header_position_bar_inner',
		'label'	=> __( 'Inner Menu Bar' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'position',
	);

	$settings[220] = array(
		'name'		=> 'bar_inner_center',
		'label'		=> __( 'Center Inner Menu Bar' , 'ubermenu' ),
		'desc'		=> __( 'Requires an Inner Menu Bar Width below.' , 'ubermenu' ),
		'type'		=> 'checkbox',
		'default' 	=> 'off',
		'group'	=> 'position',
	);

	$settings[230] = array(
		'name'		=> 'bar_inner_width',
		'label'		=> __( 'Inner Menu Bar Width' , 'ubermenu' ),
		'desc'		=> __( 'Set an explicit width for the inner menu bar.  Generally not needed except for inner menu bar centering.  You may also wish to set the "Bound Submenu" option to Inner', 'ubermenu' ),
		'type'		=> 'text',
		'default' 	=> '',
		'group'	=> 'position',
		'custom_style' => 'bar_inner_width',
	);






	/* SUBMENUS  */

	$settings[290] = array(
		'name'	=> 'header_position_submenus',
		'label'	=> __( 'Submenus' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> array( 'position' , 'submenus' ),
	);


	$settings[300] = array(
		'name'		=> 'bound_submenus',
		'label'		=> __( 'Bound Submenu To' , 'ubermenu' ),
		'desc'		=> __( 'Set to "Unbounded" if you want a submenu wider than the menu bar.  The submenu will be bound by the next relatively positioned ancestor element in your theme.  Only relevant for horizontally oriented menus.', 'ubermenu' ),
		'type'		=> 'radio',
		'default' 	=> 'on',
		'options'	=> array(
			'on'	=> __( 'Menu Bar' , 'ubermenu' ),
			'inner'	=> __( 'Inner menu bar width' , 'ubermenu' ),
			'off'	=> __( 'Unbounded' , 'ubermenu' ),
		),
		'group'		=> array( 'position' , 'submenus' ),
		'customizer'=> true,
		'customizer_section' => 'submenu',
		//'custom_style' => 'bound_submenus',
	);

	// Decided against this for now, because it requires customers to write a valid CSS selector -
	// If they don't, it could foul up other styles.
	//
	// $settings[301] = array(
	// 	'name'		=> 'bound_submenus_custom',
	// 	'label'		=> __( 'Custom Submenu Bounds' , 'ubermenu' ),
	// 	'desc'		=> __( 'If you set your bounds to "Unbounded" above, you can set another container from your theme to bound the submenus instead.  This is normally usefull when you have a right-aligned menu bar, and want the submenu to expand the full width of your content, but not to the edges of the viewport.', 'ubermenu' ),
	// 	'type'		=> 'text',
	// 	'default' 	=> '',
	// 	'group'		=> array( 'position' , 'submenus' ),
	// 	'customizer'=> true,
	// 	'customizer_section' => 'submenu',
	// 	'custom_style' => 'bound_submenus_custom',
	// );

	$settings[310] = array(
		'name'		=> 'submenu_inner_width',
		'label'		=> __( 'Submenu Row Width' , 'ubermenu' ),
		'desc'		=> __( 'If you are using Rows within your submenu, you can center the contents at this width.' , 'ubermenu' ),
		'type'		=> 'text',
		'default' 	=> '',
		'group'		=> array( 'position' , 'submenus' ),
		'custom_style' => 'submenu_inner_width',
	);

	$settings[314] = array(
		'name'		=> 'submenu_scrolling',
		'label'		=> __( 'Allow Submenu Scrolling' , 'ubermenu' ),
		'desc'		=> __( 'Enable to allow scrolling in the submenus.  There is currently a Chrome browser bug that causes rendering issues in overflow scrolled elements, so it is recommended to leave this disabled.  Note disabling submenu scrolling will have no effect if you use the Slide transition, so it is recommended to use a different transition.' , 'ubermenu' ),
		'type'		=> 'checkbox',
		'default' 	=> 'off',
		'group'		=> array( 'submenus' ),
	);

	$settings[315] = array(
		'name'		=> 'submenu_max_height',
		'label'		=> __( 'Mega Submenu Max Height' , 'ubermenu' ),
		'desc'		=> __( 'The maximum height of the submenu.  Submenus taller than this will get a vertical scrollbar if you have not disabled Submenu scrolling above.  Defaults to 600px.' , 'ubermenu' ),
		'type'		=> 'text',
		'default' 	=> '',
		'group'		=> array( 'position' , 'submenus' ),
		'custom_style' => 'submenu_max_height',
	);

	$settings[317] = array(
		'name'		=> 'dropdown_within_mega',
		'label'		=> __( 'Allow Dropdown within Mega Submenu' , 'ubermenu' ),
		'desc'		=> __( '<strong>Experimental</strong>.  Will allow dropdown submenus to appear within mega submenus.  May have side effects.  Not compatible with Slide submenu transition.' , 'ubermenu' ),
		'type'		=> 'checkbox',
		'default' 	=> 'off',
		'group'		=> array( 'position' , 'submenus' ),
		'custom_style' => 'dropdown_within_mega',
	);

	$settings[318] = array(
		'name'		=> 'force_current_submenus',
		'label'		=> __( 'Force Current Submenus Open Full Time' , 'ubermenu' ),
		'desc'		=> __( '<strong>Experimental</strong>.  Horizontal menus only.  This will force the submenu of the current menu item open and will not close.', 'ubermenu' ),
		'type'		=> 'checkbox',
		'default'	=> 'off',
		'group'		=> array( 'submenus' ),
	);

	$settings[319] = array(
		'name'		=> 'invert_submenus',
		'label'		=> __( 'Invert Submenus' , 'ubermenu' ),
		'desc'		=> __( '<strong>Experimental</strong>.  Make the submenus of a vertical menu expand left, or submenus of a horizontal menu expand up.', 'ubermenu' ),
		'type'		=> 'checkbox',
		'default'	=> 'off',
		'group'		=> array( 'submenus' ),
	);








	/** IMAGES **/

	$settings[320] = array(
		'name'	=> 'header_images',
		'label'	=> __( 'Images' , 'ubermenu' ),
		'type'	=> 'header',
		'desc'	=> __( 'Default image settings' , 'ubermenu' ),
		'group'	=> 'images',
	);

	$settings[330] = array(
		'name'		=> 'image_size',
		'label'		=> __( 'Image Size' , 'ubermenu' ),
		'type'		=> 'radio_advanced',
		'options' 	=> 'ubermenu_get_image_size_ops_inherit',
		'default' 	=> 'full',
		'desc'		=> __( 'Image sizes can be overridden on individual menu items' , 'ubermenu' ),
		'group'		=> 'images',
	);

	$settings[340] = array(
		'name'		=> 'image_width',
		'type'		=> 'text',
		'label'		=> __( 'Image Width' , 'ubermenu' ),
		'desc'		=> __( 'The width attribute value for menu item images in pixels.  Do not include units.  Leave blank to use actual dimensions.' , 'ubermenu' ),
		'group'		=> 'images',
		'custom_style' => 'image_width',
	);

	$settings[345] = array(
		'name'		=> 'image_height',
		'type'		=> 'text',
		'label'		=> __( 'Image Height' , 'ubermenu' ),
		'desc'		=> __( 'The height attribute value for menu item images in pixels.  Do not include units.  Leave blank to use actual dimensions.  Note that this only sets the attribute; the aspect ratio of the image will be maintained based on the width of the image, which may scale depending on browser width and column width.  If you want images to appear at consistent dimensions, make sure you choose an appropriate Image Size' , 'ubermenu' ),
		'group'		=> 'images',
	);

	$settings[350] = array(
		'name' 		=> 'image_set_dimensions',
		'label' 	=> __( 'Set Image Dimensions', 'ubermenu' ),
		'desc' 		=> __( 'Set the actual width and height attributes on an image if none are set manually.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'on',
		'group'		=> 'images',
	);

	$settings[355] = array(
		'name' 		=> 'image_layout_default',
		'label' 	=> __( 'Image Layout Default', 'ubermenu' ),
		'desc' 		=> __( 'Select the default image position (can be overridden in Menu Item Settings for specific menu items).  Don\'t forget to set the Image Width appropriately if you use the Left or Right settings.  "Left" means the image will appear to the left of the text, "Above" means the image will appear above the text, etc.', 'ubermenu' ),
		'type'		=> 'radio',
		'default'	=> ubermenu_new_default( $config_id , 'image_above' , 'image_left' ),
		'options'	=> array(
						'image_left' => __( 'Left', 'ubermenu' ),
						'image_above' => __( 'Above' , 'ubermenu' ),
						'image_right' => __( 'Right' , 'ubermenu' ),
						'image_below' => __( 'Below' , 'ubermenu' ),
						'image_only' => __( 'Image Only' , 'ubermenu' ),
					),

		'group'		=> 'images',
	);

	$settings[360] = array(
		'name' 		=> 'image_title_attribute',
		'label' 	=> __( 'Use Image Title Attribute', 'ubermenu' ),
		'desc' 		=> __( 'Set the title attribute on images', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'off',
		'group'		=> 'images',
	);

	$settings[361] = array(
		'name' 		=> 'image_alt_autofill',
		'label' 	=> __( 'Autofill Alt Attribute with Image Title', 'ubermenu' ),
		'desc' 		=> __( 'If the alt tag field for an image is empty, set the value to the image title', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'on',
		'group'		=> 'images',
	);

	$settings[365] = array(
		'name' 		=> 'disable_images_mobile',
		'label' 	=> __( 'Disable Images on Mobile', 'ubermenu' ),
		'desc' 		=> __( 'Detected via wp_is_mobile() - be aware if you set up caching, it would need to handle mobile device detection for this to work.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'off',
		'group'		=> 'images',
	);

	$settings[370] = array(
		'name' 		=> 'lazy_load_images',
		'label' 	=> __( 'Lazy Load Images (Experimental)', 'ubermenu' ),
		'desc' 		=> __( 'Only load the images when the submenu is opened.  More efficient bandwidth usage, but a slight delay for users depending on your server speed.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'off',
		'group'		=> 'images',
	);

	$settings[375] = array(
		'name' 		=> 'image_text_top_padding',
		'label' 	=> __( 'Image Text Top Padding', 'ubermenu' ),
		'desc' 		=> __( 'The top padding for the accompanying text when Image Left or Image Right layouts are displayed.  This allows control over the vertical alignment of the text relative to the image.  Can be overriden on individual menu items.', 'ubermenu' ),
		'type' 		=> 'text',
		'default' 	=> '',
		'group'		=> 'images',
		'custom_style'	=> 'image_text_top_padding',
	);


	/* Background Images */
	$settings[380] = array(
		'name'	=> 'header_background_images',
		'label'	=> __( 'Submenu Background Images' , 'ubermenu' ),
		'type'	=> 'header',
		'desc'	=> __( '' , 'ubermenu' ),
		'group'	=> 'images',
	);


	$settings[390] = array(
		'name' 		=> 'submenu_background_image_reponsive_hide',
		'label' 	=> __( 'Hide Background Images on Mobile', 'ubermenu' ),
		'desc' 		=> __( 'Don\'t display background images when on mobile devices', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'off',
		'group'		=> 'images',
	);










	/** STYLE CUSTOMIZATIONS **/

	$settings[480] = array(
		'name'	=> 'header_style_customizations',
		'label'	=> __( 'Style Customizations' , 'ubermenu' ),
		'type'	=> 'header',
		'desc'	=> __( 'Visit the Theme Customizer to edit most of these settings with a Live Preview.' , 'ubermenu' ) . ' <a class="button button-tertiary" href="'.admin_url('customize.php').'"><i class="fas fa-pencil-alt"></i> Customizer</a>',
		'group'	=> 'style_customizations',
	);

	$settings[485] = array(
		'name'	=> 'force_styles',
		'label'	=> __( 'Force Styles' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'default' => 'on',
		'desc'	=> __( 'Forces override of Skin styles.  For styles like border colors, also adds a border width and style, which may override skin settings.' , 'ubermenu' ),
		'group'	=> 'style_customizations',
	);

	$settings[490] = array(
		'name'	=> 'style_menu_bar_background',
		'label'	=> __( 'Menu Bar Background' , 'ubermenu' ),
		'type'	=> 'color_gradient',
		'desc'	=> __( '' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'menu_bar_background',
		'customizer'	=> true,
		'customizer_section' => 'menu_bar',
	);

	$settings[495] = array(
		'name'	=> 'style_menu_bar_transparent',
		'label'	=> __( 'Transparent menu bar' , 'ubermenu' ),
		//'type'	=> 'checkbox',
		'type'		=> 'radio',
		'default'	=> 'off',
		'options'	=> array(
			'on'	=> 'On',
			'off'	=> 'Off',
		),
		'desc'	=> __( 'Will remove menu background color, dividers, and glow' , 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'menu_bar_transparent',
		'customizer'	=> true,
		'customizer_section' => 'menu_bar',
	);

	$settings[500] = array(
		'name'	=> 'style_menu_bar_border',
		'label'	=> __( 'Menu Bar Border' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( '' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'menu_bar_border',
		'customizer'	=> true,
		'customizer_section' => 'menu_bar',
	);

	$settings[510] = array(
		'name'	=> 'style_menu_bar_radius',
		'label'	=> __( 'Menu Bar Border Radius' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'Pixel value (do not include px)' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'menu_bar_radius',
		'customizer'	=> true,
		'customizer_section' => 'menu_bar',
	);

	$settings[520] = array(
		'name'	=> 'style_top_level_font_size',
		'label'	=> __( 'Top Level Font Size' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( '' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_font_size',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[522] = array(
		'name'	=> 'style_top_level_line_height',
		'label'	=> __( 'Top Level Line Height' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'A good way to increase or balance top level item heights, provided your text does not wrap.' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_line_height',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);


	$settings[530] = array(
		'name'	=> 'style_top_level_text_transform',
		'label'	=> __( 'Top Level Text Transform' , 'ubermenu' ),
		'type'	=> 'select',
		'desc'	=> __( '' ),
		'options'	=> array(
			''			=> '&mdash;',
			'none'		=> 'None',
			'uppercase'	=> 'Uppercase',
			'capitalize'=> 'Capitalize',
		),
		'default'	=> '',
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_text_transform',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[540] = array(
		'name'	=> 'style_top_level_font_weight',
		'label'	=> __( 'Top Level Font Weight' , 'ubermenu' ),
		'type'	=> 'select',
		'desc'	=> __( '' ),
		'options'	=> array(
			''			=> '&mdash;',
			'normal'	=> 'Normal',
			'bold'		=> 'Bold',
			'100'			=> '100',
			'200'			=> '200',
			'300'			=> '300',
			'400'			=> '400',
			'500'			=> '500',
			'600'			=> '600',
			'700'			=> '700',
			'800'			=> '800',
			'900'			=> '900',
		),
		'default'	=> '',
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_font_weight',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);



	$settings[550] = array(
		'name'	=> 'style_top_level_font_color',
		'label'	=> __( 'Top Level Font Color' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( '' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_font_color',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[560] = array(
		'name'	=> 'style_top_level_font_color_hover',
		'label'	=> __( 'Top Level Font Color [Activated]' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Color of items that are active (hover/click depending on trigger)' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_font_color_hover',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[570] = array(
		'name'	=> 'style_top_level_font_color_current',
		'label'	=> __( 'Top Level Font Color [Current]' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Color of items current to the viewed page', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_font_color_current',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[580] = array(
		'name'	=> 'style_top_level_font_color_highlight',
		'label'	=> __( 'Top Level Font Color [Highlight]' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Color of items with the "Highlight Link" setting checked', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_font_color_highlight',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[584] = array(
		'name'	=> 'style_top_level_margin',
		'label'	=> __( 'Top Level Item Margin' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'Add a margin around individual items to space them out.  e.g. 0px 5px', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_margin',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[585] = array(
		'name'	=> 'style_top_level_border_radius',
		'label'	=> __( 'Top Level Border Radius (rounded corners)' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'If you are using backgrounds on individual items (below), you can round the corners with this setting.  You can round individual corners with values such as 10px 10px 0 0 (top left, top right, bottom right, bottom left)', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_border_radius',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[586] = array(
		'name'	=> 'style_top_level_background',
		'label'	=> __( 'Top Level Background' , 'ubermenu' ),
		'type'	=> 'color_gradient',
		'desc'	=> __( 'Individual item background color.  Normally transparent (adjust the menu bar background instead).  Useful in conjunction with margin setting and transparent menu bar to create "button" style.', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_background',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);


	$settings[590] = array(
		'name'	=> 'style_top_level_background_hover',
		'label'	=> __( 'Top Level Background [Activated]' , 'ubermenu' ),
		'type'	=> 'color_gradient',
		'desc'	=> __( 'Item background when activated (hover/click, depending on trigger)' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_background_hover',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);


	$settings[600] = array(
		'name'	=> 'style_top_level_background_current',
		'label'	=> __( 'Top Level Background [Current]' , 'ubermenu' ),
		'type'	=> 'color_gradient',
		'desc'	=> __( 'Item background when current on the viewed page' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_background_current',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[610] = array(
		'name'	=> 'style_top_level_background_highlight',
		'label'	=> __( 'Top Level Background [Highlight]' , 'ubermenu' ),
		'type'	=> 'color_gradient',
		'desc'	=> __( 'Item background when the "Highlight Link" setting is checked on the item' , 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_background_highlight',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[620] = array(
		'name'	=> 'style_top_level_item_divider_color',
		'label'	=> __( 'Top Level Item Divider Color' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'The color of the lines in between the top level items (borders)' , 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_item_divider_color',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[621] = array(
		'name'	=> 'style_top_level_item_divider_disable',
		'label'	=> __( 'Disable Top Level Item Dividers' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'desc'	=> __( 'You will likely want to set the Glow Opacity to 0 below as well' , 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_item_divider_disable',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[630] = array(
		'name'	=> 'style_top_level_item_glow_opacity',
		'label'	=> __( 'Top Level Item Divider Glow Opacity' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'A number between 0 and 1 representing the opacity of the inner box shadow on the item\'s left edge.  Used to give the buttons a sense of depth.', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_item_glow_opacity',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[640] = array(
		'name'	=> 'style_top_level_item_glow_opacity_hover',
		'label'	=> __( 'Top Level Item Divider Glow Opacity [Active]' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'A number between 0 and 1 representing the opacity of the inner box shadow on the item\'s left edge.', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_item_glow_opacity_hover',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[650] = array(
		'name'	=> 'style_top_level_padding',
		'label'	=> __( 'Top Level Vertical Padding' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'Adjusting the vertical (top and bottom) padding is the best way to make a menu bar taller or shorter.' , 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_padding',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	// $settings[651] = array(
	// 	'name'	=> 'style_top_level_padding_responsive',
	// 	'label'	=> __( 'Top Level Vertical Padding [Mobile]' , 'ubermenu' ),
	// 	'type'	=> 'text',
	// 	'desc'	=> __( 'Adjusting the vertical (top and bottom) padding is the best way to make a menu bar taller or shorter.' , 'ubermenu' ),
	// 	'group'	=> 'style_customizations',
	// 	'custom_style'	=> 'top_level_padding_responsive',
	// 	'customizer'	=> true,
	// 	'customizer_section' => 'top_level_items',
	// );

	$settings[660] = array(
		'name'	=> 'style_top_level_horiz_padding',
		'label'	=> __( 'Top Level Horizontal Padding' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'Adjusting the horizontal padding (left and right) is useful to adjust the width of the top level items' , 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_horiz_padding',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[670] = array(
		'name'	=> 'style_extra_submenu_indicator_padding',
		'label'	=> __( 'Leave space for submenu indicator' , 'ubermenu' ),
		//'type'	=> 'checkbox',
		'type'		=> 'radio',
		'default'	=> 'on',
		'options'	=> array(
			'on'	=> 'On',
			'off'	=> 'Off',
		),
		//'desc'	=> __( '' ),
		'group'	=> 'style_customizations',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);

	$settings[680] = array(
		'name'	=> 'style_align_submenu_indicator',
		'label'	=> __( 'Align submenu indicator' , 'ubermenu' ),
		//'type'	=> 'checkbox',
		'type'		=> 'radio',
		'default'	=> 'edge',
		'options'	=> array( 'edge' => 'Edge' , 'text' => 'Text', ),
		//'desc'	=> __( 'Normally, the submenu indicator would be aligned to the edge of the menu item' ),
		'group'	=> 'style_customizations',
		'customizer'	=> true,
		'customizer_section' => 'top_level_items',
	);



	$settings[690] = array(
		'name'	=> 'style_top_level_item_height',
		'label'	=> __( 'Top Level Menu Item Height' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'Generally best to leave blank and use the Top Level Vertical Padding setting to adjust menu bar height', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_item_height',
	);




	//Submenu

	$settings[700] = array(
		'name'	=> 'style_submenu_background_color',
		'label'	=> __( 'Submenu Background Color' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'submenu_background_color',
		'customizer'	=> true,
		'customizer_section' => 'submenu',
	);

	$settings[710] = array(
		'name'	=> 'style_submenu_border_color',
		'label'	=> __( 'Submenu Border Color' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'submenu_border_color',
		'customizer'	=> true,
		'customizer_section' => 'submenu',
	);

	$settings[712] = array(
		'name'	=> 'style_submenu_dropshadow_opacity',
		'label'	=> __( 'Submenu Dropshadow Opacity' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'A number between 0 and 1 that determines the opacity of the submenu drop shadow.  Set to 0 to remove.', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'submenu_dropshadow_opacity',
		'customizer'	=> true,
		'customizer_section' => 'submenu',
	);

	$settings[720] = array(
		'name'	=> 'style_submenu_fallback_font_color',
		'label'	=> __( 'Submenu Fallback Font Color' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'submenu_fallback_font_color',
		'customizer'	=> true,
		'customizer_section' => 'submenu',
	);

	$settings[730] = array(
		'name'	=> 'style_submenu_minimum_column_width',
		'label'	=> __( 'Submenu Minimum Column Width' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'Use with caution, can break Column Width item settings', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'submenu_minimum_column_width',
	);

	$settings[740] = array(
		'name'	=> 'style_submenu_highlight_font_color',
		'label'	=> __( 'Submenu Highlight Font Color' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'submenu_highlight_font_color',
		'customizer'	=> true,
		'customizer_section' => 'submenu',
	);


	$settings[750] = array(
		'name'	=> 'style_submenu_item_padding',
		'label'	=> __( 'Submenu Item Padding' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'Use this to adjust the spacing of submenu items.  This controls both horizontal and vertical padding, so you can enter two values - for instance, 5px 20px would set the vertical padding to 5px and the horizontal padding to 20px.  20px is the default horizontal padding.', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'submenu_item_padding',
		'customizer'	=> true,
		'customizer_section' => 'submenu',
	);




	//Headers

	$settings[760] = array(
		'name'	=> 'style_header_font_size',
		'label'	=> __( 'Column Header Font Size' , 'ubermenu' ),
		'type'	=> 'text',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'header_font_size',
		'customizer'	=> true,
		'customizer_section' => 'headers',
	);

	$settings[765] = array(
		'name'	=> 'style_header_text_transform',
		'label'	=> __( 'Column Header Text Transform' , 'ubermenu' ),
		'type'	=> 'select',
		'options'	=> array(
			''		=> '&mdash;',
			'none'	=> 'None',
			'uppercase'	=> 'Uppercase',
			'lowercase'	=> 'Lowercase',
		),
		'custom_style'	=> 'header_text_transform',
		'customizer'	=> true,
		'customizer_section' => 'headers',
	);

	$settings[770] = array(
		'name'	=> 'style_header_font_color',
		'label'	=> __( 'Column Header Font Color' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'header_font_color',
		'customizer'	=> true,
		'customizer_section' => 'headers',
	);

	$settings[780] = array(
		'name'	=> 'style_header_font_color_hover',
		'label'	=> __( 'Column Header Font Color [Hover]' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'header_font_color_hover',
		'customizer'	=> true,
		'customizer_section' => 'headers',
	);

	$settings[790] = array(
		'name'	=> 'style_header_font_color_current',
		'label'	=> __( 'Column Header Font Color [Current]' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'header_font_color_current',
		'customizer'	=> true,
		'customizer_section' => 'headers',
	);

	$settings[800] = array(
		'name'	=> 'style_header_font_weight',
		'label'	=> __( 'Column Header Font Weight' , 'ubermenu' ),
		'type'	=> 'select',
		//'desc'	=> __( '' ),
		'options'	=> array(
			''			=> '&mdash;',
			'normal'	=> 'Normal',
			'bold'		=> 'Bold',
			'100'			=> '100',
			'200'			=> '200',
			'300'			=> '300',
			'400'			=> '400',
			'500'			=> '500',
			'600'			=> '600',
			'700'			=> '700',
			'800'			=> '800',
			'900'			=> '900',
		),
		'default'	=> '',
		'group'	=> 'style_customizations',
		'custom_style'	=> 'header_font_weight',
		'customizer'	=> true,
		'customizer_section' => 'headers',
	);


	$settings[805] = array(
		'name'	=> 'style_header_background_color',
		'label'	=> __( 'Column Header Background Color' , 'ubermenu' ),
		'type'	=> 'color',
		'group'	=> 'style_customizations',
		'custom_style'	=> 'header_background_color',
		'customizer'	=> true,
		'customizer_section' => 'headers',
	);
	$settings[806] = array(
		'name'	=> 'style_header_background_color_hover',
		'label'	=> __( 'Column Header Background Color [Hover]' , 'ubermenu' ),
		'type'	=> 'color',
		'group'	=> 'style_customizations',
		'custom_style'	=> 'header_background_color_hover',
		'customizer'	=> true,
		'customizer_section' => 'headers',
	);
	$settings[807] = array(
		'name'	=> 'style_header_background_color_current',
		'label'	=> __( 'Column Header Background Color [Current]' , 'ubermenu' ),
		'type'	=> 'color',
		'group'	=> 'style_customizations',
		'custom_style'	=> 'header_background_color_current',
		'customizer'	=> true,
		'customizer_section' => 'headers',
	);



	$settings[810] = array(
		'name'	=> 'style_header_border_color',
		'label'	=> __( 'Column Header Border Color' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Color of the border below the header above the submenu stack' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'header_border_color',
		'customizer'	=> true,
		'customizer_section' => 'headers',
	);

	$settings[820] = array(
		'name'	=> 'display_header_border_color',
		'label'	=> __( 'Display Header Border Color' , 'ubermenu' ),
		//'type'	=> 'checkbox',
		'type'	=> 'radio',
		'default'	=> 'on',
		'options'	=> array( 'on' => 'On' , 'off' => 'Off' ),
		'desc'	=> __( 'Display a border below Column Headers with child items' ),
		'group'	=> 'style_customizations',
		'customizer'	=> true,
		'customizer_section' => 'headers',
	);








	//Normal Items
	$settings[830] = array(
		'name'	=> 'style_normal_font_color',
		'label'	=> __( 'Normal Items Font Color' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'normal_font_color',
		'customizer'	=> true,
		'customizer_section' => 'normal',
	);

	$settings[831] = array(
		'name'	=> 'style_normal_font_color_hover',
		'label'	=> __( 'Normal Items Font Color [Hover]' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'normal_font_color_hover',
		'customizer'	=> true,
		'customizer_section' => 'normal',
	);

	$settings[832] = array(
		'name'	=> 'style_normal_font_color_current',
		'label'	=> __( 'Normal Items Font Color [Current]' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'normal_font_color_current',
		'customizer'	=> true,
		'customizer_section' => 'normal',
	);

	$settings[833] = array(
		'name'	=> 'style_normal_font_size',
		'label'	=> __( 'Normal Items Font Size' , 'ubermenu' ),
		'type'	=> 'text',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'normal_font_size',
		'customizer'	=> true,
		'customizer_section' => 'normal',
	);
	$settings[834] = array(
		'name'	=> 'style_normal_font_weight',
		'label'	=> __( 'Normal Items Font Weight' , 'ubermenu' ),
		'type'	=> 'select',
		'options'	=> array(
			''			=> '&mdash;',
			'normal'	=> 'Normal',
			'bold'		=> 'Bold',
			'100'			=> '100',
			'200'			=> '200',
			'300'			=> '300',
			'400'			=> '400',
			'500'			=> '500',
			'600'			=> '600',
			'700'			=> '700',
			'800'			=> '800',
			'900'			=> '900',
		),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'normal_font_weight',
		'customizer'	=> true,
		'customizer_section' => 'normal',
	);
	$settings[835] = array(
		'name'	=> 'style_normal_text_transform',
		'label'	=> __( 'Normal Items Text Transform' , 'ubermenu' ),
		'type'	=> 'select',
		'options'	=> array(
			''		=> '&mdash;',
			'none'	=> 'None',
			'uppercase'	=> 'Uppercase',
			'lowercase'	=> 'Lowercase',
		),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'normal_text_transform',
		'customizer'	=> true,
		'customizer_section' => 'normal',
	);

	$settings[836] = array(
		'name'	=> 'style_normal_underline_hover',
		'label'	=> __( 'Normal Items Underline on Hover' , 'ubermenu' ),
		'type'	=> 'radio',
		'options'	=> array(
			'off'	=> 'Off',
			'on'	=> 'On',
		),
		'default'	=> 'off',
		'desc'	=> __( 'Display an underline when hovering over normal items in the submenu', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'normal_underline_hover',
		'customizer'	=> true,
		'customizer_section' => 'normal',
	);

	$settings[840] = array(
		'name'	=> 'style_normal_background_hover',
		'label'	=> __( 'Normal Items Background Hover' , 'ubermenu' ),
		'type'	=> 'color',
		'group'	=> 'style_customizations',
		'custom_style'	=> 'normal_background_hover',
		'customizer'	=> true,
		'customizer_section' => 'normal',
	);


	//Flyout
	$settings[860] = array(
		'name'	=> 'style_flyout_vertical_padding',
		'label'	=> __( 'Flyout Items Vertical Padding' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'Spacing specifically for flyout items', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'flyout_vertical_padding',
		'customizer'	=> true,
		'customizer_section' => 'normal',
	);
	$settings[861] = array(
		'name'	=> 'style_flyout_divier',
		'label'	=> __( 'Flyout Items Divider' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Horizontal divider between flyout menu items', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'flyout_divider',
		'customizer'	=> true,
		'customizer_section' => 'normal',
	);






	//Tabs

	$settings[870]	= array(
		'name'	=> 'style_tabs_font_size',
		'label'	=> __( 'Tab Toggles Font Size' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'Font size for the tab toggles.  If left blank, will inherit Column Header Font Size', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tabs_font_size',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);
	$settings[871]	= array(
		'name'	=> 'style_tabs_background',
		'label'	=> __( 'Tab Toggles Background' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Background color for the panel that contains the tab toggles', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tabs_background',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);

	$settings[872]	= array(
		'name'	=> 'style_tabs_font_weight',
		'label'	=> __( 'Tab Toggles Font Weight' , 'ubermenu' ),
		'type'	=> 'select',
		'options'	=> array(
			''			=> '&mdash;',
			'normal'	=> 'Normal',
			'bold'		=> 'Bold',
			'100'			=> '100',
			'200'			=> '200',
			'300'			=> '300',
			'400'			=> '400',
			'500'			=> '500',
			'600'			=> '600',
			'700'			=> '700',
			'800'			=> '800',
			'900'			=> '900',
		),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tabs_font_weight',
		'customizer'	=> true,
		'customizer_section' => 'tabs',
	);

	$settings[873]	= array(
		'name'	=> 'style_tabs_color',
		'label'	=> __( 'Tab Toggles Font Color' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Font color for the tab toggles', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tabs_color',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);

	$settings[876]	= array(
		'name'	=> 'style_tabs_color_hover',
		'label'	=> __( 'Tab Toggles Font Color [Active]' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Font color for the tab toggles on hover', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tabs_color_hover',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);

	$settings[877]	= array(
		'name'	=> 'style_tabs_color_current',
		'label'	=> __( 'Tab Toggles Font Color [Current]' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Font color for the tab toggles when current', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tabs_color_current',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);

	$settings[880]	= array(
		'name'	=> 'style_tabs_background_hover',
		'label'	=> __( 'Tab Toggles Background Color [Active]' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Background color for the tab toggles on hover', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tabs_background_hover',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);

	$settings[881]	= array(
		'name'	=> 'style_tabs_background_current',
		'label'	=> __( 'Tab Toggles Background Color [Current]' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Background color for the tab toggles when current', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tabs_background_current',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);



	$settings[883]	= array(
		'name'	=> 'style_tab_content_background',
		'label'	=> __( 'Tab Content Panel Background Color' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Background color for the tab content panel', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tab_content_background',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);

	$settings[886]	= array(
		'name'	=> 'style_tab_content_force_header_color',
		'label'	=> __( 'Tab Content Panel Force Header Color' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Force the header text color for all text in the tab content panel, for all states.  Generally not recommended, but here as an option.', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tab_content_force_header_color',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);

	$settings[890]	= array(
		'name'	=> 'style_tab_content_force_normal_color',
		'label'	=> __( 'Tab Content Panel Force Normal Color' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Force the normal text color for all text in the tab content panel, for all states.  Generally not recommended, but here as an option.', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tab_content_force_normal_color',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);

	$settings[893]	= array(
		'name'	=> 'style_tab_content_force_description_color',
		'label'	=> __( 'Tab Content Panel Force Description Color' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Force the normal description color for all text in the tab content panel, for all states.  Generally not recommended, but here as an option.', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tab_content_force_description_color',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);

	$settings[896]	= array(
		'name'	=> 'style_tab_divider_color',
		'label'	=> __( 'Tab Content Panel Divider Color' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'The color of the divider line between toggle and content panel', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'tab_divider_color',
		'customizer'	=> true,
		'customizer_section' => 'tabs',

	);










	//Descriptions
	$settings[900] = array(
		'name'	=> 'style_description_font_size',
		'label'	=> __( 'Description Font Size' , 'ubermenu' ),
		'type'	=> 'text',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'description_font_size',
		'customizer'	=> true,
		'customizer_section' => 'descriptions',
	);

	$settings[902] = array(
		'name'	=> 'style_description_font_color',
		'label'	=> __( 'Description Font Color' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'description_font_color',
		'customizer'	=> true,
		'customizer_section' => 'descriptions',
	);
	$settings[903] = array(
		'name'	=> 'style_description_font_color_hover',
		'label'	=> __( 'Description Font Color [Active/Hover]' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'description_font_color_hover',
		'customizer'	=> true,
		'customizer_section' => 'descriptions',
	);

	$settings[904] = array(
		'name'	=> 'style_description_text_transform',
		'label'	=> __( 'Description Text Transform' , 'ubermenu' ),
		'type'	=> 'select',
		'options'	=> array(
			''		=> '&mdash;',
			'none'	=> 'None',
			'uppercase'	=> 'Uppercase',
			'lowercase'	=> 'Lowercase',
		),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'description_text_transform',
		'customizer'	=> true,
		'customizer_section' => 'descriptions',
	);



	//Arrows
	$settings[910] = array(
		'name'	=> 'style_top_level_arrow_color',
		'label'	=> __( 'Top Level Arrow Color' , 'ubermenu' ),
		'desc'	=> __( 'Color of the submenu indicator' , 'ubermenu' ),
		'type'	=> 'color',
		'group'	=> 'style_customizations',
		'custom_style'	=> 'top_level_arrow_color',
		'customizer'	=> true,
		'customizer_section' => 'arrows',
	);

	$settings[920] = array(
		'name'	=> 'style_submenu_arrow_color',
		'label'	=> __( 'Submenu Arrow Color' , 'ubermenu' ),
		'desc'	=> __( 'Color of the submenu indicator within submenus' , 'ubermenu' ),
		'type'	=> 'color',
		'group'	=> 'style_customizations',
		'custom_style'	=> 'submenu_arrow_color',
		'customizer'	=> true,
		'customizer_section' => 'arrows',
	);


	//HR
	$settings[930] = array(
		'name'	=> 'style_hr',
		'label'	=> __( 'Horizontal Rule Color' , 'ubermenu' ),
		'type'	=> 'color',
		'group'	=> 'style_customizations',
		'custom_style'	=> 'hr',
		'customizer'	=> true
	);



	//Toggle Bar

	$settings[940] = array(
		'name'	=> 'style_toggle_font_size',
		'label'	=> __( 'Responsive Toggle Font Size' , 'ubermenu' ),
		'type'	=> 'text',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'toggle_font_size',
		'customizer'	=> true,
		'customizer_section' => 'toggle',
	);
	$settings[941] = array(
		'name'	=> 'style_toggle_font_weight',
		'label'	=> __( 'Responsive Toggle Font Weight' , 'ubermenu' ),
		'type'	=> 'select',
		//'desc'	=> __( '' , 'ubermenu' ),
		'options'	=> array(
			''			=> '&mdash;',
			'normal'	=> 'Normal',
			'bold'		=> 'Bold',
			'100'			=> '100',
			'200'			=> '200',
			'300'			=> '300',
			'400'			=> '400',
			'500'			=> '500',
			'600'			=> '600',
			'700'			=> '700',
			'800'			=> '800',
			'900'			=> '900',
		),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'toggle_font_weight',
		'customizer'	=> true,
		'customizer_section' => 'toggle',
	);

	$settings[945] = array(
		'name'	=> 'style_toggle_padding',
		'label'	=> __( 'Responsive Toggle Padding' , 'ubermenu' ),
		'type'	=> 'text',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'toggle_padding',
		'customizer'	=> true,
		'customizer_section' => 'toggle',
	);


	$settings[950] = array(
		'name'	=> 'style_toggle_background',
		'label'	=> __( 'Responsive Toggle Background' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'toggle_background',
		'customizer'	=> true,
		'customizer_section' => 'toggle',
	);

	$settings[955] = array(
		'name'	=> 'style_toggle_color',
		'label'	=> __( 'Responsive Toggle Font Color' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'toggle_color',
		'customizer'	=> true,
		'customizer_section' => 'toggle',
	);

	$settings[960] = array(
		'name'	=> 'style_toggle_background_hover',
		'label'	=> __( 'Responsive Toggle Background [Hover]' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'toggle_background_hover',
		'customizer'	=> true,
		'customizer_section' => 'toggle',
	);

	$settings[970] = array(
		'name'	=> 'style_toggle_color_hover',
		'label'	=> __( 'Responsive Toggle Font Color [Hover]' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'toggle_color_hover',
		'customizer'	=> true,
		'customizer_section' => 'toggle',
	);




	//Search Bar
	$settings[990] = array(
		'name'	=> 'style_search_background',
		'label'	=> __( 'Search Bar Background' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'search_background',
		'customizer'	=> true,
		'customizer_section' => 'search',
	);

	$settings[995] = array(
		'name'	=> 'style_search_color',
		'label'	=> __( 'Search Bar Text Color' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'search_color',
		'customizer'	=> true,
		'customizer_section' => 'search',
	);

	$settings[997] = array(
		'name'	=> 'style_search_font_size',
		'label'	=> __( 'Search Bar Font Size' , 'ubermenu' ),
		'type'	=> 'text',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'search_font_size',
		'customizer'	=> true,
		'customizer_section' => 'search',
	);

	$settings[1000] = array(
		'name'	=> 'style_search_placeholder_color',
		'label'	=> __( 'Search Bar Placeholder Color' , 'ubermenu' ),
		'type'	=> 'color',
		'desc'	=> __( 'Color of the placeholder text', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'search_placeholder_color',
		'customizer'	=> true,
		'customizer_section' => 'search',
	);

	$settings[1010] = array(
		'name'	=> 'style_search_icon_color',
		'label'	=> __( 'Search Bar Icon Color' , 'ubermenu' ),
		'type'	=> 'color',
		//'desc'	=> __( '', 'ubermenu' ),
		'group'	=> 'style_customizations',
		'custom_style'	=> 'search_icon_color',
		'customizer'	=> true,
		'customizer_section' => 'search',
	);








	$settings[1020] = array(
		'name'	=> 'header_style_customizations_row',
		'label'	=> __( 'Rows' , 'ubermenu' ),
		'type'	=> 'header',
		'desc'	=> __( '' ),
		'group'	=> 'style_customizations',
	);

	$settings[1025] = array(
		'name'		=> 'row_spacing',
		'label'		=> __( 'Row Spacing' , 'ubermenu' ),
		'desc'		=> __( 'The bottom margin to apply to rows.' , 'ubermenu' ),
		'type'		=> 'text',
		'default' 	=> '',
		'group'	=> 'style_customizations',
		'custom_style' => 'row_spacing',
	);






	/* Icons */
	$settings[2027] = array(
		'name'		=> 'icons_header',
		'label'		=> __( 'Icons' , 'ubermenu' ),
		'type'		=> 'header',
		'group'		=> 'icons',
	);


	$settings[2028] = array(
		'name'		=> 'icon_width',
		'label'		=> __( 'Icon Width' , 'ubermenu' ),
		'desc'		=> __( 'The width to allot for the icon.  Icon will be centered within this width.  1.3em by default.' , 'ubermenu' ),
		'type'		=> 'text',
		'group'		=> 'icons',
		'custom_style' => 'icon_width',
	);

	$settings[2029] = array(
		'name'		=> 'icon_nudge',
		'label'		=> __( 'Nudge Icons' , 'ubermenu' ),
		'desc'		=> __( 'Depending on your font, you may wish to nudge the icons up or down a few pixels.  Negative values move the icon up, positive values move the icon down.' , 'ubermenu' ),
		'type'		=> 'text',
		'group'		=> 'icons',
		'custom_style' => 'icon_nudge',
	);

	$settings[2030] = array(
		'name'		=> 'icon_tag',
		'label'		=> __( 'Icon Tag' , 'ubermenu' ),
		'desc'		=> __( 'The HTML tag to use for the icons.  Please note that with Font Awesome 5 Javascript, both tags will be converted to SVG dynamically.' , 'ubermenu' ),
		'type'		=> 'radio',
		'default'	=> 'i',
		'options'	=> array(
						'i'		=> '&lt;i&gt;',
						'span'	=> '&lt;span&gt;',
		),
		'group'		=> 'icons',
	);

	$settings[2031] = array(
		'name'		=> 'icon_display',
		'label'		=> __( 'Display Icons' , 'ubermenu' ),
		'desc'		=> __( 'If your text is too long to fit on one line along with the icon, set the icon display to Inline so that the icon and text will appear on the same line.' , 'ubermenu' ),
		'type'		=> 'radio',
		'default'	=> 'block',
		'options'	=> array(
			'block'		=> __( 'Block' , 'ubermenu' ),
			'inline'	=> __( 'Inline' , 'ubermenu' ),
		),
		'group'		=> 'icons',
	);


	//1032 is in icons extension



	/* Fonts */
	$settings[1055] = array(
		'name'		=> 'font_header',
		'label'		=> __( 'Font' , 'ubermenu' ),
		'desc'		=> __( 'Set a font for the menu.  Note that this may be overridden by the CSS of some themes.' , 'ubermenu' ),
		'type'		=> 'header',
		'group'		=> 'fonts'
	);

	$settings[1056] = array(
		'name'		=> 'google_font',
		'label'		=> __( 'Google Font' , 'ubermenu' ),
		'desc'		=> __( 'Using this property will (1) load the Google Font asset and (2) set this font as the menu font.' , 'ubermenu' ),
		'type'		=> 'select',
		'options'	=> ubermenu_get_font_ops(),
		'group'		=> 'fonts',
		'custom_style'	=> 'google_font',
		'customizer'=> true,
		'customizer_section' => 'fonts',

	);

	$settings[1057] = array(
		'name'		=> 'google_font_style',
		'label'		=> __( 'Google Font Style' , 'ubermenu' ),
		'desc'		=> __( 'Select the style to use for the menu.  Note that not all Google Fonts support all styles.' , 'ubermenu' ),
		'type'		=> 'select',
		'default'	=> '',
		'options'	=> ubermenu_get_font_style_ops(),
		'group'		=> 'fonts',
		'customizer'=> true,
		'customizer_section' => 'fonts',
	);

	$settings[1058] = array(
		'name'		=> 'custom_font_family',
		'label'		=> __( 'Font Family' , 'ubermenu' ),
		'desc'		=> __( 'Set a custom <strong>font-family</strong> CSS property for the menu.  Note that this does not load the font asset, just set the CSS property' , 'ubermenu' ),
		'type'		=> 'text',
		'group'		=> 'fonts',
		'custom_style'	=> 'custom_font_family',
		'customizer'=> true,
		'customizer_section' => 'fonts',
	);

	$settings[1059] = array(
		'name'		=> 'custom_font_property',
		'label'		=> __( 'Custom Font Property' , 'ubermenu' ),
		'desc'		=> __( 'Set a custom <strong>font</strong> CSS property for the menu.  Example: <strong><code>bold 12px/24px Helvetica, Arial, sans-serif</code></strong>  Not necessary in conjuction with Google Font setting above.' , 'ubermenu' ),
		'type'		=> 'text',
		'group'		=> 'fonts',
		'custom_style'	=> 'custom_font',
		'customizer'=> true,
		'customizer_section' => 'fonts',
	);





	/* Misc */
	$settings[1065] = array(
		'name'		=> 'misc_header',
		'label'		=> __( 'Miscellaneous' , 'ubermenu' ),
		'type'		=> 'header',
		'group'		=> 'misc',
	);

	$settings[1070] = array(
		'name'		=> 'container_tag',
		'label'		=> __( 'Container Tag' , 'ubermenu' ),
		'desc'		=> __( 'The tag that wraps the entire menu.  Switch to div for non-HTML5 sites.', 'ubermenu' ),
		'type'		=> 'radio',
		'default'	=> 'nav',
		'options'	=> array(
			'nav'	=> '&lt;nav&gt;',
			'div'	=> '&lt;div&gt;',
		),
		'group'		=> 'misc',
	);

	$settings[1080] = array(
		'name'		=> 'allow_shortcodes_in_labels',
		'label'		=> __( 'Allow Shortcodes in Navigation Label & Description' , 'ubermenu' ),
		'desc'		=> __( 'Enable to process shortcodes in the menu item Navigation Label and Description settings.' , 'ubermenu' ),
		'type'		=> 'checkbox',
		'default'	=> 'off',
		'group'		=> 'misc',
	);

	$settings[1085] = array(
		'name'		=> 'content_before_nav',
		'label'		=> __( 'Content Before Menu' , 'ubermenu' ),
		'desc'		=> __( 'Add HTML or shortcodes here to insert content before the start of the menu.' , 'ubermenu' ),
		'type'		=> 'textarea',
		'default'	=> '',
		'group'		=> 'misc',
		'sanitize_callback' => 'ubermenu_allow_html',
	);

	$settings[1090] = array(
		'name'		=> 'submenu_settings_header',
		'label'		=> __( 'Submenu Settings' , 'ubermenu' ),
		'type'		=> 'header',
		'group'		=> array( 'submenus' , 'misc' ),
	);


	$settings[1100] = array(
		'name'		=> 'display_submenu_indicators',
		'label'		=> __( 'Display Submenu Indicators' , 'ubermenu' ),
		'desc'		=> __( 'Display an arrow indicator when a drop submenu exists.' , 'ubermenu' ),
		'type'		=> 'checkbox',
		'default'	=> 'on',
		'group'		=> array( 'submenus' , 'misc' ),
	);

	$settings[1110] = array(
		'name'		=> 'display_submenu_close_button',
		'label'		=> __( 'Display Submenu Close Button' , 'ubermenu' ),
		'desc'		=> __( 'Display an x to close the submenu (at all sizes).  Useful for click trigger.' , 'ubermenu' ),
		'type'		=> 'checkbox',
		'default'	=> 'off',
		'group'		=> array( 'submenus' , 'misc' ),
	);



	/** ADVANCED **/
	$settings[1120] = array(
		'name'		=> 'header_advanced',
		'label'		=> __( 'Advanced' , 'ubermenu' ),
		'desc'		=> '<i class="fas fa-exclamation-triangle"></i> '.__( 'You should only adjust settings in this section if you are certain of what you are doing.', 'ubermenu' ),
		'type'		=> 'header',
		'group'		=> 'advanced',
	);
	$settings[1130] = array(
		'name'	=> 'theme_location_instance',
		'label'	=> __( 'Theme Location Instance' , 'ubermenu' ),
		'type'	=> 'text',
		'default'	=> 0,
		'desc'	=> __( 'Determines which instance of the theme location UberMenu should apply to.  0 means apply to all; set to 1 to apply to only the first, 2 to the second, etc.  Useful if your theme is reusing theme locations for mobile menu, sticky menu, etc.', 'ubermenu' ),
		'group'	=> 'advanced',
	);



	$settings[1150] = array(
		'name'		=> 'header_export',
		'type'		=> 'header',
		'label'		=> __( 'Import/Export' , 'ubermenu' ),
		'group'		=> 'export',
	);

	$settings[1151] = array(
		'name'		=> 'export',
		'label' 	=> __( 'Export' , 'ubermenu' ),
		'group'		=> 'export',
		'type'		=> 'func_html',
		'desc'		=> array(
			'func'		=> 'ubermenu_export_data',
			'args'		=> array( 'config_id' => $config_id ),
		),

	);

	$settings[1152] = array(
		'name'		=> 'import',
		'label' 	=> __( 'Import' , 'ubermenu' ),
		'group'		=> 'export',
		'type'		=> 'func_html',
		'desc'		=> array(
			'func'		=> 'ubermenu_import_data',
			'args'		=> array( 'config_id' => $config_id ),
		),

	);


	$settings[1160] = array(
		'name'		=> 'badges',
		'label' 	=> __( 'Badges' , 'ubermenu' ),
		'group'		=> 'badges',
		'type'		=> 'header',
		// 'desc'		=>

	);
	$settings[1161] = array(
		'name'		=> 'badge_text_color',
		'label' 	=> __( 'Default Badge Text Color' , 'ubermenu' ),
		'group'		=> 'badges',
		'type'		=> 'color',
		'customizer' => true,
		'customizer_section' => 'badges',
		'custom_style' => 'badge_text_color'
	);
	$settings[1162] = array(
		'name'		=> 'badge_background',
		'label' 	=> __( 'Default Badge Background Color' , 'ubermenu' ),
		'group'		=> 'badges',
		'type'		=> 'color',
		'customizer' => true,
		'customizer_section' => 'badges',
		'custom_style' => 'badge_background'
	);
	$settings[1163] = array(
		'name'		=> 'badge_font_size',
		'label' 	=> __( 'Badge Font Size' , 'ubermenu' ),
		'group'		=> 'badges',
		'type'		=> 'text',
		'customizer' => true,
		'customizer_section' => 'badges',
		'custom_style' => 'badge_font_size'
	);
	$settings[1165] = array(
		'name'		=> 'badge_border_radius',
		'label' 	=> __( 'Badge Border Radius' , 'ubermenu' ),
		'group'		=> 'badges',
		'type'		=> 'text',
		'customizer' => true,
		'customizer_section' => 'badges',
		'custom_style' => 'badge_border_radius'
	);
	$settings[1167] = array(
		'name'		=> 'badge_padding',
		'label' 	=> __( 'Badge Padding' , 'ubermenu' ),
		'group'		=> 'badges',
		'type'		=> 'text',
		'customizer' => true,
		'customizer_section' => 'badges',
		'custom_style' => 'badge_padding'
	);
	$settings[1168] = array(
		'name'		=> 'badge_font_weight',
		'label' 	=> __( 'Badge Font Weight' , 'ubermenu' ),
		'group'		=> 'badges',
		'type'	=> 'select',
		'options'	=> array(
			''			=> '&mdash;',
			'normal'	=> 'Normal',
			'bold'		=> 'Bold',
			'100'			=> '100',
			'200'			=> '200',
			'300'			=> '300',
			'400'			=> '400',
			'500'			=> '500',
			'600'			=> '600',
			'700'			=> '700',
			'800'			=> '800',
			'900'			=> '900',
		),
		'default'	=> '',
		'customizer' => true,
		'customizer_section' => 'badges',
		'custom_style' => 'badge_font_weight'
	);

	$settings[1169] = array(
		'name'	=> 'badge_text_transform',
		'label'	=> __( 'Badge Text Transform' , 'ubermenu' ),
		'type'	=> 'select',
		'options'	=> array(
			''		=> '&mdash;',
			'none'	=> 'None',
			'uppercase'	=> 'Uppercase',
			'lowercase'	=> 'Lowercase',
		),
		'custom_style'	=> 'badge_text_transform',
		'customizer'	=> true,
		'customizer_section' => 'badges',
	);

	return $settings;
}


//Customizer Panels

add_action( 'ubermenu_customizer_register_subsections' , 'ubermenu_pro_customizer_sections' , 10 , 2 );
function ubermenu_pro_customizer_sections( $wp_customize , $panel_id ){
	$wp_customize->add_section( $panel_id.'_badges', array(
		'title'		=> __( 'Badges', 'ubermenu' ),
		'priority'	=> 95,
		'panel'		=> $panel_id,
	) );
	$wp_customize->add_section( $panel_id.'_fonts', array(
		'title'		=> __( 'Fonts', 'ubermenu' ),
		'priority'	=> 100,
		'panel'		=> $panel_id,
	) );
}



function ubermenu_export_data( $data ){
	$config_id = $data['config_id'];
	$settings = ubermenu_get_instance_options( $config_id );
	unset( $settings['auto_theme_location'] );
	$html = '<textarea style="height:150px;">'.json_encode( $settings ).'</textarea>';
	$html.= '<br/><span class="description">Copy and paste this text into an Import box to copy these settings to a different Configuration.</span>';

	return $html;
}

function ubermenu_import_data( $data ){
	$config_id = $data['config_id'];
	// $name = 'ubermenu_'.$config_id.'[import_settings]';
	// $html = '<textarea id="'.$name.'" name="'.$name.'"></textarea>';
	// $html.= '<br/><span class="description">Paste export data above to import settings to this configuration</span>';
	// $html.= '<br/><div class="ubermenu-alert">WARNING: This will override all settings for this configuration!</div>';
	$html= '<a class="button button-primary" href="'.admin_url( 'themes.php?page=ubermenu-settings&amp;do=settings-import&amp;config_id='.$config_id .'&amp;ubermenu_nonce='.wp_create_nonce( 'ubermenu-control-panel-do' ) ).'">Import Settings</a>';
	return $html;
}

/**
 * Add the Pro settings for General
 */
add_filter( 'ubermenu_settings_panel_fields' , 'ubermenu_settings_panel_fields_pro' , 20 );
function ubermenu_settings_panel_fields_pro( $all_fields = array() ){

	////////////////////////////////////
	///GENERAL
	////////////////////////////////////

	$fields = $all_fields[UBERMENU_PREFIX.'general'];

	/* ASSETS */

	$fields[50] = array(
		'name'	=> 'header_assets',
		'label'	=> __( 'Assets' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'assets',
	);

	$fields[60] = array(
		'name' 		=> 'load_custom_css',
		'label' 	=> __( 'Load Custom Stylesheet', 'ubermenu' ),
		'desc' 		=> __( 'Enable this setting and then create a custom.css in the <code>custom/</code> directory in order to add your own custom external CSS.  You may wish to disable the skin preset.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'off',
		'group'		=> 'assets',
	);

	$fields[70] = array(
		'name' 		=> 'load_custom_js',
		'label' 	=> __( 'Load Custom Javascript', 'ubermenu' ),
		'desc' 		=> __( 'Enable this setting and then create a custom.js in the <code>custom/</code> directory in order to add your own custom Javascript.  Do not enable if you do not create the file.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'off',
		'group'		=> 'assets',
	);

	$fields[80] = array(
		'name' 		=> 'load_ubermenu_css',
		'label' 	=> __( 'Load UberMenu Core Layout', 'ubermenu' ),
		'desc' 		=> __( 'Don\'t disable this unless you include it elsewhere', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'on',
		'group'		=> 'assets',
	);

	$fields[85] = array(
		'name' 		=> 'use_core_svgs',
		'label' 	=> __( 'Use Core SVGs for Essential Icons', 'ubermenu' ),
		'desc' 		=> __( 'By default, UberMenu will use Font Awesome icons for its essential icons (submenu indicator icons, responsive toggle icons).  Enable this setting to use built-in SVGs, or if you disable Font Awesome.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'off',
		'group'		=> 'assets',
	);


	$fields[90] = array(
		'name'	=> 'header_assets_font_awesome',
		'label'	=> __( 'Font Awesome 5 - Font Icons' , 'ubermenu' ),
		'desc'	=> __( 'If you enable all Font Icon assets, they will be loaded as a package.  Disabling one or more will load the selected assets individually.' , 'ubermenu' ).'<br/>'.
							 __( 'If you are already loading Font Awesome 5 elsewhere in your setup, you can disable these assets.' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> array( 'assets', 'font_awesome' )
	);
	$fields[91] = array(
		'name' 		=> 'load_fontawesome_fontcss_solid',
		'label' 	=> __( 'Load Solid Icons <br/>[Font Icons]', 'ubermenu' ),
		'desc' 		=> __( 'Load the Font Awesome 5 Solid icon set via CSS and font icons.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'on',
		'group'	=> array( 'assets', 'font_awesome' )
	);
	$fields[92] = array(
		'name' 		=> 'load_fontawesome_fontcss_brands',
		'label' 	=> __( 'Load Brand Icons <br/>[Font Icons]', 'ubermenu' ),
		'desc' 		=> __( 'Load the Font Awesome 5 Brand icon set via CSS and font icons.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'on',
		'group'	=> array( 'assets', 'font_awesome' )
	);
	$fields[93] = array(
		'name' 		=> 'load_fontawesome_fontcss_regular',
		'label' 	=> __( 'Load Regular Icons <br/>[Font Icons]', 'ubermenu' ),
		'desc' 		=> __( 'Load the Font Awesome 5 Regular icon set via CSS and font icons.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'on',
		'group'	=> array( 'assets', 'font_awesome' )
	);

	//FA5 SVG
	$fields[94] = array(
		'name'	=> 'header_assets_font_awesome_svg',
		'label'	=> __( 'Font Awesome 5 - SVG Icons' , 'ubermenu' ),
		'desc'	=> __( 'If you enable all Font Icon assets, they will be loaded as a package.  Disabling one or more will load the selected assets individually.' , 'ubermenu' ).'<br/>'.
							 __( 'If you are already loading Font Awesome 5 elsewhere in your setup, you can disable these assets.' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> array( 'assets', 'font_awesome' )
	);
	$fields[95] = array(
		'name' 		=> 'load_fontawesome_svg_solid',
		'label' 	=> __( 'Load Solid Icons <br/>[SVG via JS]', 'ubermenu' ),
		'desc' 		=> __( 'Load the Font Awesome 5 Solid icon set as SVG icons via javascript', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'off',
		'group'	=> array( 'assets', 'font_awesome' )
	);
	$fields[96] = array(
		'name' 		=> 'load_fontawesome_svg_brands',
		'label' 	=> __( 'Load Brand Icons <br/>[SVG via JS]', 'ubermenu' ),
		'desc' 		=> __( 'Load the Font Awesome 5 Brands icon set as SVG icons via javascript', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'off',
		'group'	=> array( 'assets', 'font_awesome' )
	);
	$fields[97] = array(
		'name' 		=> 'load_fontawesome_svg_regular',
		'label' 	=> __( 'Load Regular Icons <br/>[SVG via JS]', 'ubermenu' ),
		'desc' 		=> __( 'Load the Font Awesome 5 Regular icon set as SVG icons via javascript', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'off',
		'group'	=> array( 'assets', 'font_awesome' )
	);

	//FA4 => FA5 Conversion
	$fields[98] = array(
		'name'	=> 'header_assets_font_awesome_conversion',
		'label'	=> __( 'Font Awesome 4 to Font Awesome 5 Conversion' , 'ubermenu' ),
		'desc'	=> __( 'These settings dynamically update your Font Awesome 4 icons to Font Awesome 5 icons.  They can be disabled if all of your icons are Font Awesome 5 icons.' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> array( 'assets', 'font_awesome' )
	);
	$fields[99] = array(
		'name' 		=> 'convert_fa4_to_fa5',
		'label' 	=> __( 'Dynamically Convert Font Awesome 4 Icons to Font Awesome 5', 'ubermenu' ),
		'desc' 		=> __( 'Font Awesome 5 breaks backwards compatibility by changing the classes of certain Font Awesome 4 icons.  This will dynamically update these icon names to the new Font Awesome 5 names.  This can be disabled once all icons are using the Font Awesome 5 classes.', 'ubermenu' ) . ' <a class="" href="'.admin_url('themes.php?page=ubermenu-settings&do=fa4_to_fa5&ubermenu_nonce='.wp_create_nonce( 'ubermenu-control-panel-do' ) ).'">'.__( 'Migrate Font Awesome 4 to 5' , 'ubermenu' ).'</a>',
		'type' 		=> 'checkbox',
		'default' 	=> 'on',
		'group'	=> array( 'assets', 'font_awesome' )
	);
	$fields[100] = array(
		'name' 		=> 'load_fontawesome4_shim',
		'label' 	=> __( 'Load Font Awesome 4 Shim', 'ubermenu' ),
		'desc' 		=> __( 'For use with the Font Awesome 5 SVG icons.  If you are using old Font Awesome 4 Icons on your site, you may want to use the Shim to make them compatible with Font Awesome 5.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'off',
		'group'	=> array( 'assets', 'font_awesome' )
	);

	$fields[101] = array(
		'name' 		=> 'fa4_compatibility',
		'label' 	=> __( 'Enable Font Awesome 4 Compatibility', 'ubermenu' ),
		'desc' 		=> __( 'If you are using Font Awesome 4, and your Font Awesome 4 assets load before your Font Awesome 5 assets, your Font Awesome 4 icons may appear as squares (undefined glyphs).  This setting adds a line of CSS to display icons with Font Awesome 4 classes (.fa) as Font Awesome 4 icons.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'on',
		'group'	=> array( 'assets', 'font_awesome' )
	);




	$fields[102] = array(
		'name'	=> 'header_assets_google_maps',
		'label'	=> __( 'Google Maps' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'assets',
	);
	$fields[103] = array(
		'name' 		=> 'load_google_maps',
		'label' 	=> __( 'Load Google Maps API', 'ubermenu' ),
		'desc' 		=> __( 'If you are already loading the Google Maps API, or if you do not need Google Maps in your menu, you can disable this.', 'ubermenu' ),
		'type' 		=> 'checkbox',
		'default' 	=> 'on',
		'group'		=> 'assets',
	);

	$fields[104] = array(
		'name' 		=> 'google_maps_api_key',
		'label' 	=> __( 'Google Maps API Key', 'ubermenu' ),
		'desc' 		=> __( 'Enter your Google Maps API Key.  As of July 2018, this is required by Google.', 'ubermenu' ) . '  <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/get-api-key">Get a key</a>',
		'type' 		=> 'text',
		'default' 	=> '',
		'group'		=> 'assets',
	);

	$fields[105] = array(
		'name' 		=> 'google_maps_language',
		'label' 	=> __( 'Google Maps Language', 'ubermenu' ),
		'desc' 		=> __( 'It is generally recommended to let Google Maps determine this automatically for the user, but you can override the language here if you want to.  ', 'ubermenu'). ' <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/localization">Google Maps Localization',
		'type' 		=> 'text',
		'default' 	=> '',
		'group'		=> 'assets',
	);
	$fields[106] = array(
		'name' 		=> 'google_maps_region',
		'label' 	=> __( 'Google Maps Region', 'ubermenu' ),
		'desc' 		=> __( 'It is generally recommended to let Google Maps determine this automatically for the user, but you can override the language here if you want to.  ', 'ubermenu'). ' <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/localization">Google Maps Localization',
		'type' 		=> 'text',
		'default' 	=> '',
		'group'		=> 'assets',
	);





	/* Responsive & Mobile */
	$fields[110] = array(
		'name'	=> 'header_responsive',
		'label'	=> __( 'Responsive & Mobile' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'responsive',
	);
	$fields[120] = array(
		'name'	=> 'responsive_breakpoint',
		'label'	=> __( 'Responsive Breakpoint' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'The viewport width at which the menu will collapse to mobile menu.  959 by default', 'ubermenu' ),
		'group'	=> 'responsive',
	);

	$fields[125] = array(
		'name'		=> 'retractor_display_strategy',
		'label'		=> __( 'Determine Retractor Display By' , 'ubermenu' ),
		'desc'		=> __( 'Choose when the retractors should be shown.  Note that if you are using a caching plugin, you\'ll need to configure it properly to allow the Mobile option to work; Using Touch Detection may result in the Close button appearing on desktop browsers that support touch events.' , 'ubermenu' ),
		'type'		=> 'radio',
		'default'	=> 'responsive',
		'options'	=> array(
			'responsive'	=> __( 'Responsive - Display below responsive breakpoint' , 'ubermenu' ),
			'mobile'		=> __( 'Mobile - Use wp_is_mobile() mobile device detection' , 'ubermenu' ),
			'touch'			=> __( 'Touch Detection - Display when browser supports touch events' , 'ubermenu' ),
		),
		'group'		=> 'responsive',
	);

	/*
		array(
			'name'	=> 'responsive_breakpoint_secondary',
			'label'	=> __( 'Secondary Responsive Breakpoint' , 'ubermenu' ),
			'type'	=> 'text',
			'desc'	=> __( 'The point at which the menu will collapse to a single-column mobile menu. 480 by default', 'ubermenu' ),
			'group'	=> 'responsive',
		),
		*/


	/* Widgets */
	$fields[130] = array(
		'name'	=> 'header_widgets',
		'label'	=> __( 'Widgets' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'widgets',
	);


	$fields[140] = array(
		'name'	=> 'num_widget_areas',
		'label'	=> __( 'Number of Widget Areas' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'Enter the number of widget areas to auto-generate', 'ubermenu' ),
		'group'	=> 'widgets',
	);

	$fields[150] = array(
		'name'	=> 'widget_area_names',
		'label'	=> __( 'Widget Area Names' , 'ubermenu' ),
		'type'	=> 'textarea',
		'desc'	=> __( 'Comma delimited list of widget area names to assign' , 'ubermenu' ),
		'group'	=> 'widgets',
	);

	$fields[160] = array(
		'name'	=> 'allow_top_level_widgets',
		'label'	=> __( 'Allow Top Level Widgets' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'default'	=> 'off',
		'desc'	=> __( 'Normally, widgets are only placed in a submenu.  Enable this to allow widgets to be placed in the top level of the menu.', 'ubermenu' ),
		'group'	=> 'widgets',
	);






	/* Advanced Menu Items */
	$fields[240] = array(
		'name'		=> 'adv_menu_items_header',
		'label'		=> __( 'Advanced Menu Items' , 'ubermenu' ),
		'type'		=> 'header',
		'group'		=> 'advanced_menu_items',
	);
	$fields[250] = array(
		'name'		=> 'autocomplete_max_term_results',
		'label'		=> __( 'Maximum Autocomplete Term Results' , 'ubermenu' ),
		'desc'		=> __( 'The maximum number of results that can appear in a Dynamic Posts or Dynamic Terms term autocomplete setting.  Limited for performance reasons for sites with huge numbers of terms.' , 'ubermenu' ),
		'type'		=> 'text',
		'default'	=> 100,
		'group'		=> 'advanced_menu_items',
	);

	$fields[255] = array(
		'name'		=> 'autocomplete_max_post_results',
		'label'		=> __( 'Maximum Autocomplete Post Results' , 'ubermenu' ),
		'desc'		=> __( 'The maximum number of results that can appear in a Dynamic Posts post autocomplete setting.  Limited for performance reasons for sites with huge numbers of posts.' , 'ubermenu' ),
		'type'		=> 'text',
		'default'	=> 100,
		'group'		=> 'advanced_menu_items',
	);

	$fields[260] = array(
		'name'		=> 'autocomplete_disable',
		'label'		=> __( 'Disable Autocomplete' , 'ubermenu' ),
		'desc'		=> __( 'Disable the autocomplete query.  If you have a massive number of terms or posts, this can cause a memory error when querying in the Appearance > Menus screen.  Enable this setting to disable the query' , 'ubermenu' ),
		'type'		=> 'checkbox',
		'default'	=> 'off',
		'group'		=> 'advanced_menu_items',
	);
	$fields[261] = array(
		'name'		=> 'dynamic_authors_disable',
		'label'		=> __( 'Disable Dynamic Posts Author Selection' , 'ubermenu' ),
		'desc'		=> __( 'Disable the Dynamic Posts Author query.  If you have a massive number of users on your site, this can cause a memory error when querying in the Appearance > Menus screen.  Enable this setting to disable the query' , 'ubermenu' ),
		'type'		=> 'checkbox',
		'default'	=> 'off',
		'group'		=> 'advanced_menu_items',
	);




	/** Misc **/

	$fields[290] = array(
		'name'	=> 'ubermenu_toolbar',
		'label'	=> __( 'Display UberMenu Toolbar' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'default'	=> 'on',
		'desc'	=> __( 'Display the UberMenu menu in the WordPress Toolbar.  Will ony be displayed to admins.', 'ubermenu' ),
		'group'	=> 'misc',
	);

	/** Theme Integration **/

	$fields[294] = array(
		'name'	=> 'ubermenu_theme_integration',
		'label' => __( 'Theme Integration / Interference' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> array( 'misc', 'theme_integration' ),
		'desc'	=> __( 'Settings to help tame misbehaving or complex themes.  See also: ' , 'ubermenu' ) . '<a href="https://sevenspark.com/docs/ubermenu-3/integration/theme-interference" target="_blank">Theme Interference</a>',
	);

	$fields[295] = array(
		'name'	=> 'theme_template_backtrace',
		'label'	=> __( 'Locate Theme Template Backtrace' , 'ubermenu' ),
		'desc'	=> __('Display an alert to admins above the menu to indicate which theme template the menu call originated from.  Useful for tracking down where to perform the manual integration if not directly in the header.php.  Disable when finished.' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'default' => 'off',
		'group'	=> 'theme_integration'
	);

	$fields[296] = array(
		'name'	=> 'custom_prefix',
		'label'	=> __( 'Custom Style Prefix (Specificity Boost) [Experimental]' , 'ubermenu' ),
		'type'	=> 'text',
		'desc'	=> __( 'If your theme is overriding the menu styles, you can try boosting the UberMenu style specificity with this setting.', 'ubermenu' ),
		'default' => '',
		'group'	=> 'theme_integration',
	);

	$fields[297]	= array(
		'name'	=> 'force_filter',
		'label'	=> __( 'Force Filter UberMenu Settings' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'default'	=> 'on',
		'desc'	=> __( 'Sometimes theme filters will override UberMenu\'s filters, preventing UberMenu from properly integrating.  Enable this to try to force UberMenu\'s filters.  If there are no interfering filters, this can be disabled.', 'ubermenu' ),
		'group'	=> array( 'misc' , 'theme_integration' ),
	);

	$fields[298]	= array(
		'name'	=> 'disable_class_filtering',
		'label'	=> __( 'Disable Menu Item Class Filtering' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'default'	=> 'off',
		'desc'	=> __( 'Some themes or plugins filter the core menu item classes, which can break things.  Enable this setting to attempt to remove them.  Please note this is a core hook, and disabling all filters on this hook could also disable functionality you want.', 'ubermenu' ),
		'group'	=> array( 'misc' , 'theme_integration' ),
	);

	$fields[299]	= array(
		'name'	=> 'disable_custom_admin_walker',
		'label'	=> __( 'Disable Custom Menus Panel Walker' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'default'	=> 'off',
		'desc'	=> __( 'A custom walker allows the theme or another plugin to take over control of the output of the menu item markup in Appearance > Menus.  In most cases, this is not an issue.  But certain themes choose to alter the structures within the menu items and can cause interference, breaking the UberMenu Menu Item Settings.  Enable this setting to try to disable the walker (and revert to the standard WordPress walker).  Note that this would disable plugins that rely on a custom admin walker, such as Nav Menu Roles.', 'ubermenu' ),
		'group'	=> array( 'misc' , 'theme_integration' ),
	);

	$fields[300]	= array(
		'name'	=> 'exclude_advanced_ubermenu_items',
		'label'	=> __( 'Exclude UberMenu Advanced Items from other menus [Experimental]' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'default'	=> 'off',
		'desc'	=> __( 'We do not recommend using UberMenu Advanced Items (Custom Content, Widgets, Rows, Columns, etc) in non-UberMenu menus (from your theme or another plugin), as those menus will not process UberMenu Advanced Items properly.  However, if you need to reuse an UberMenu in another menu, you can enable this setting, which will attempt to strip out the Advanced UberMenu items when other menus are generated.', 'ubermenu' ),
		'group'	=> array( 'misc' , 'theme_integration' ),
	);






	/** ADVANCED **/
	$fields[305] = array(
		'name'	=> 'header_advanced',
		'label'	=> __( 'Advanced' , 'ubermenu' ),
		'desc'	=> '<i class="fas fa-exclamation-triangle"></i> '. __( 'You should only adjust settings in this section if you are certain of what you are doing.'  , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'advanced',
	);


	$fields[310] = array(
		'name'	=> 'strict_mode',
		'label'	=> __( 'Strict Mode' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'default'	=> 'on',
		'desc'	=> __( 'Only auto-apply UberMenu to activated theme locations.  You should not deactivate this unless your theme is improperly using theme locations, as this will apply UberMenu to ALL menus.', 'ubermenu' ),
		'group'	=> 'advanced',
	);

	$fields[320] = array(
		'name'	=> 'ubermenu_theme_location',
		'label'	=> __( 'Register Easy Integration UberMenu Theme Location' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'default'	=> 'off',
		'desc'	=> __( 'When enabled, creates a new theme location called "ubermenu" which you can use to insert into your theme.', 'ubermenu' ),
		'group'	=> 'advanced',
	);

	$fields[325] = array(
		'name'	=> 'allow_custom_defaults',
		'label'	=> __( 'Allow custom defaults' , 'ubermenu' ),
		'type'	=> 'checkbox',
		'default'	=> 'off',
		'desc'	=> __( 'Allow setting of custom defaults for menu items.  USE WITH CAUTION.', 'ubermenu' ),
		'group'	=> 'advanced',
	);




	/** MAINTENANCE **/

	$fields[360] = array(
		'name'	=> 'reset_styles',
		'label'	=> __( 'Reset Style Customization Settings' , 'ubermenu' ),
		'desc'	=> '<a class="button button-primary" href="'.admin_url('themes.php?page=ubermenu-settings&do=reset-styles-check&ubermenu_nonce='.wp_create_nonce( 'ubermenu-control-panel-do' )).'">'.__( 'Reset Style Customizations' , 'ubermenu' ).'</a><br/><p>'.__( 'Reset Style Customization Settings to the factory defaults.', 'ubermenu' ).'</p>',
		'type'	=> 'html',
		'group'	=> 'maintenance',
	);

	$fields[362] = array(
		'name'	=> 'manage_widget_areas',
		'label'	=> __( 'Widget Area Manager' , 'ubermenu' ),
		'desc'	=> '<a class="button button-primary" href="'.admin_url('themes.php?page=ubermenu-settings&do=widget-manager&ubermenu_nonce='.wp_create_nonce( 'ubermenu-control-panel-do' )).'">'.__( 'Manage Widget Areas' , 'ubermenu' ).'</a><br/><p>'.__( 'Choose which Custom Widget Areas to delete.  Useful for orphaned widget areas from deleted menu items.', 'ubermenu' ).'</p>',
		'type'	=> 'html',
		'group'	=> array( 'maintenance' , 'widgets' ),
	);


	/** DIAGNOSTICS **/

	$fields[370] = array(
		'name'	=> 'header_diagnostics',
		'label'	=> __( 'Diagnostics' , 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'diagnostics',
	);
	$fields[371] = array(
		'name'	=> 'diagnostics',
		'label' => __( 'Enable Diagnostics (Experimental, Alpha status)' , 'ubermenu' ),
		'desc'	=> __( 'Enable front end diagnostics system.', 'ubermenu' ),
		'type'	=> 'checkbox',
		'default' => 'on',
		'group'	=> 'diagnostics',
	);

// ksort( $fields );
// uberp( $fields );

// foreach( $fields as $i => $f ){
// 	echo '<pre>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$i .':'.$f['label'].'</pre><br/>';
// }

	$all_fields[UBERMENU_PREFIX.'general'] = $fields;

	return $all_fields;
}

/**
 * Add the Pro Sub Sections for Instances
 */
add_filter( 'ubermenu_settings_panel_sections' , 'ubermenu_settings_panel_sections_pro' );
function ubermenu_settings_panel_sections_pro( $sections = array() ){

	$menus = ubermenu_get_menu_instances();

	//Add a Tab for each additional Instance
	foreach( $menus as $menu ){

		$sections[] = array(
			'id'	=> UBERMENU_PREFIX.$menu,
			'title' => '+'.$menu,
			'sub_sections'	=> ubermenu_get_settings_subsections( $menu ),
		);
	}

	return $sections;
}

/**
 * Add pro Sub Sections to General Panel
 */
add_filter( 'ubermenu_general_settings_sections' , 'ubermenu_general_settings_sections_pro' );
function ubermenu_general_settings_sections_pro( $section ){

	$section['sub_sections'] = array(

			// 'basic' => array(
			// 	'title' => __( 'Basic' , 'ubermenu' ),
			// ),
			'custom_css'=> array(
				'title'	=> __( 'Custom CSS' , 'ubermenu' ),
			),
			'assets'	=> array(
				'title'	=> __( 'Assets' , 'ubermenu' ),
			),
			'font_awesome'	=> array(
				'title'	=> __( 'Font Awesome' , 'ubermenu' ),
			),
			'responsive'=> array(
				'title'	=> __( 'Responsive &amp; Mobile' , 'ubermenu' ),
			),
			'widgets'=> array(
				'title'	=> __( 'Widgets' , 'ubermenu' ),
			),
			'script_config'=> array(
				'title'	=> __( 'Script Configuration' , 'ubermenu' ),
			),
			'accessibility'=> array(
				'title'	=> __( 'Accessibility' , 'ubermenu' ),
			),
			'advanced_menu_items' => array(
				'title'	=> __( 'Advanced Menu Items' , 'ubermenu' ),
			),
			'theme_integration'=> array(
				'title'	=> __( 'Theme Integration' , 'ubermenu' ),
			),
			'misc'=> array(
				'title'	=> __( 'Miscellaneous' , 'ubermenu' ),
			),
			'advanced'	=> array(
				'title'	=> __( 'Advanced' , 'ubermenu' ),
			),
			'maintenance'=> array(
				'title'	=> __( 'Maintenance', 'ubermenu' ),
			),
			'diagnostics'=> array(
				'title'	=> __( 'Diagnostics', 'ubermenu' ),
			),

			//$prefix.'main-'
		);

	return $section;
}

/**
 * Add Pro Settings Fields for each Instance
 */
add_filter( 'ubermenu_settings_panel_fields' , 'ubermenu_settings_panel_fields_instances' , 50 );
function ubermenu_settings_panel_fields_instances( $fields = array() ){

	//Add options for each additional Instance
	$menus = ubermenu_get_menu_instances();
	foreach( $menus as $menu ){
		$fields[UBERMENU_PREFIX.$menu] = ubermenu_get_settings_fields_instance( $menu );
	}

	return $fields;
}



add_action( 'init' , 'ubermenu_register_skins_pro' , 20 );
function ubermenu_register_skins_pro(){

	$main = UBERMENU_URL . 'pro/assets/css/skins/';

	//Custom prefix booster
	if( ubermenu_op( 'custom_prefix' , 'general' ) ){
		$uploads_url = wp_upload_dir();
		$uploads_url = trailingslashit( $uploads_url['baseurl'] );
		$main = $uploads_url . 'ubermenu/';
	}

	ubermenu_register_skin( 'white' , 'White' , $main.'white.css' );
	ubermenu_register_skin( 'deepsky' , 'Deep Sky' , $main.'deepsky.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'berry' , 'Berry' , $main.'berry.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'aqua' , 'Sea Green' , $main.'aqua.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'fire' , 'Fire' , $main.'fire.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'eggplant' , 'Eggplant' , $main.'eggplant.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'robinsegg' , 'Robin\'s Egg' , $main.'robinsegg.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'tangerine' , 'Tangerine' , $main.'tangerine.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'nightsky' , 'Night Sky' , $main.'nightsky.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'charcoal' , 'Charcoal' , $main.'charcoal.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'shinyblack' , 'Shiny Black' , $main.'shinyblack.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'simple-green' , 'Simple Green' , $main.'simplegreen.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'earthy' , 'Earthy' , $main.'earthy.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'black-silver' , 'Black & Silver' , $main.'blacksilver.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'blue-silver' , 'Blue & Silver' , $main.'bluesilver.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'red-black' , 'Red & Black' , $main.'redblack.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'orange' , 'Burnt Orange' , $main.'orange.css' , 'ubermenu-has-border' );
	ubermenu_register_skin( 'clean-white' , 'Clean White' , $main.'cleanwhite.css' );
	ubermenu_register_skin( 'trans-black' , 'Transparent Black' , $main.'trans_black.css' );
	ubermenu_register_skin( 'trans-black-hov' , 'Transparent Black - Hover' , $main.'trans_black_hover.css' );
	ubermenu_register_skin( 'silver-tabs' , 'Silver Tabs' , $main.'silvertabs.css' );

	ubermenu_register_skin( 'tt-silver' , 'Two Tone Silver & Black (Deprecated)' , $main.'twotone_silver_black.css' );
	ubermenu_register_skin( 'tt-black' , 'Two Tone Black & Black (Deprecated)' , $main.'twotone_black_black.css' );
	ubermenu_register_skin( 'tt-red' , 'Two Tone Red & Black (Deprecated)' , $main.'twotone_red_black.css' );
	ubermenu_register_skin( 'tt-blue' , 'Two Tone Blue & Black (Deprecated)' , $main.'twotone_blue_black.css' );
	ubermenu_register_skin( 'tt-green' , 'Two Tone Green & Black (Deprecated)' , $main.'twotone_green_black.css' );
	ubermenu_register_skin( 'tt-purple' , 'Two Tone Purple & Black (Deprecated)' , $main.'twotone_purple_black.css' );
	ubermenu_register_skin( 'tt-orange' , 'Two Tone Orange & Black (Deprecated)' , $main.'twotone_orange_black.css' );
	ubermenu_register_skin( 'tt-silver-s' , 'Two Tone Silver & Silver (Deprecated)' , $main.'twotone_silver_silver.css' );

}





function ubermenu_kb_search(){

	ob_start();

	?>
	<div class="ubermenu-kb-search">
		<div class="search-topper"><a target="_blank" href="<?php echo UBERMENU_KB_URL; ?>"><i class="fas fa-search"></i> Search the Knowledgebase</a></div>
		<gcse:search></gcse:search>
	</div>
	<?php

	$html = ob_get_clean();

	return $html;

}

function ubermenu_support_forum_help(){

	$html = '';


	if( defined( 'UBERMENU_PACKAGED_THEME' ) ){
		$btn_class = '';

		//Highlight support button if entered purchase code
		if( ubermenu_op( 'purchase_code' , 'updates' , '' ) ) $btn_class = 'button-tertiary';

		$html.= '<div id="ubermenu-unlicensed-notice" class="ubermenu-settings-notice ubermenu-settings-notice-large">
			<i class="ubermenu-settings-notice-icon fas fa-exclamation-triangle"></i>
			<strong>UberMenu Plugin License Required</strong>
			<p>Support through SevenSpark requires an UberMenu plugin license - as opposed to a theme license - which can be <a target="blank" href="http://codecanyon.net/item/ubermenu-wordpress-mega-menu-plugin/154703?ref=sevenspark">purchased here</a> if you do not already have one.  An UberMenu license code entitles you to updates and support directly from the plugin author; otherwise, support and updates will be provided by '. ( defined( "UBERMENU_PACKAGED_THEME" ) ? UBERMENU_PACKAGED_THEME : "the author of your theme" ) .'</p><br/><hr/><br/>';

			if( defined( "UBERMENU_PACKAGED_THEME_SUPPORT_NOTICE" ) ){
				$html.= '<p><strong>Note from '. UBERMENU_PACKAGED_THEME .':</strong> '.UBERMENU_PACKAGED_THEME_SUPPORT_NOTICE.'</p><br/><hr/><br/>';
			}

			$html.= '<p>';
			if( defined( 'UBERMENU_PACKAGED_THEME_SUPPORT_LINK' ) ) $html.= UBERMENU_PACKAGED_THEME_SUPPORT_LINK;
			$html.= ' <a target="_blank" class="button '.$btn_class.'" href="'.ubermenu_get_support_url().'">Visit SevenSpark Support (UberMenu license)</a> <a class="button button-secondary" target="_blank" href="http://codecanyon.net/item/ubermenu-wordpress-mega-menu-plugin/154703?ref=sevenspark">Purchase UberMenu License</a>';

			$html.= '</p>';
		$html.= '</div>';
	}
	else{

		$html.= '<div class="ubermenu-help-wrap">';
		$html.= '<h3><i class="fas fa-life-ring"></i> '.__( 'Support Center' , 'ubermenu' ).'</h3>';
		$html.= '<p>'.__( 'Didn\'t find the answer you needed in the Knowledgebase or Video Tutorials?  Visit the ' , 'ubermenu' ).
					'<a target="_blank" class="button" href="'.ubermenu_get_support_url().'"><i class="fas fa-life-ring"></i> Support Center</a></p>';
		$html.= '</div>';
	}
	return $html;
}

function ubermenu_video_tutorials_help(){

	$html = '';

	$html.= '<div class="ubermenu-help-wrap">';
	$html.= '<h3><i class="fas fa-video"></i> '.__( 'Video Tutorials' , 'ubermenu' ).'</h3>';
	$html.= '<a target="_blank" href="'.UBERMENU_VIDEOS_URL.'" class="ubermenu-help-video-tuts-link"><img src="'.UBERMENU_URL . 'admin/assets/images/video_tutorials.jpg"/><i class="fas fa-play"></i></a>';
	$html.= '</div>';

	return $html;
}


/**
 * HELP
 */
add_filter( 'ubermenu_settings_panel_sections' , 'ubermenu_help_section' , 100 );
add_filter( 'ubermenu_settings_panel_fields' , 'ubermenu_help_fields' , 100 );
function ubermenu_help_section( $sections ){
	$prefix = UBERMENU_PREFIX;
	$sections[] = array(
		'id' => $prefix.'help',
		'title' => __( 'Help', 'ubermenu' ),
		'sub_sections'	=> array(
			'knowledgebase'	=> array(
				'title' 	=> __( 'Knowledgebase' , 'ubermenu' ),
			),
			'video_tutorials'	=> array(
				'title' 	=> __( 'Video Tutorials' , 'ubermenu' ),
			),
			'support'	=> array(
				'title' 	=> __( 'Support' , 'ubermenu' ),
			),
		),
	);

	return $sections;
}
function ubermenu_help_fields( $fields = array() ){
	$section = UBERMENU_PREFIX.'help';
	$f = array();



	$f[] = array(
			'name'	=> 'search_knowledgebase',
			'label'	=> __( 'Search the Knowledgebase' , 'ubermenu' ),
			'desc'	=> ubermenu_kb_search(),
			'type'	=> 'html',
			'group'	=> 'knowledgebase',
		);

	$f[] = array(
			'name'	=> 'video_tutorials',
			'label' => __( 'Video Tutorials' , 'ubermenu' ),
			'desc'	=> ubermenu_video_tutorials_help(),
			'type'	=> 'html',
			'group'	=> 'video_tutorials',
		);

	$f[] = array(
			'name'	=> 'support_forum',
			'label' => __( 'Support Center' , 'ubermenu' ),
			'desc'	=> ubermenu_support_forum_help(),
			'type'	=> 'html',
			'group'	=> 'support',
		);


	$fields[$section] = $f;
	return $fields;
}


/**
 * EXTENSIONS
 */
add_filter( 'ubermenu_settings_panel_sections' , 'ubermenu_extensions_section' , 120 );
add_filter( 'ubermenu_settings_panel_fields' , 'ubermenu_extensions_fields' , 120 );
function ubermenu_extensions_section( $sections ){

	if( ubermenu_op( 'show_extensions' , 'general' ) == 'off' ) return $sections;

	$prefix = UBERMENU_PREFIX;
	$sections[] = array(
		'id' => $prefix.'extensions',
		'title' => __( 'Extensions', 'ubermenu' ),
		'sub_sections'	=> array(
			'ubermenu_extensions'	=> array(
				'title' 	=> __( 'UberMenu Extensions' , 'ubermenu' ),
			),
			'related_plugins'	=> array(
				'title' 	=> __( 'Related Plugins' , 'ubermenu' ),
			),
		),
	);


	return $sections;
}
function ubermenu_extensions_fields( $fields = array() ){

	//if( ubermenu_op( 'show_extensions' , 'general' ) == 'off' ) return $fields;

	$section = UBERMENU_PREFIX.'extensions';
	$f = array();

	$f[] = array(
			'name' 	=> 'um_extensions',
			'type' 	=> 'header',
			'label'	=> __('UberMenu Extensions' , 'ubermenu' ),
			'group'	=> 'ubermenu_extensions',
	);

	$f[] = array(
			'name'	=> 'extension_icons',
			'label'	=> __( 'Icons Extension' , 'ubermenu' ),
			'desc'	=> ubermenu_show_extension_icons(),
			'type'	=> 'html',
			'group'	=> 'ubermenu_extensions',
		);

	$f[] = array(
			'name'	=> 'extension_sticky',
			'label' => __( 'Sticky Extension' , 'ubermenu' ),
			'desc'	=> ubermenu_show_extension_sticky(),
			'type'	=> 'html',
			'group'	=> 'ubermenu_extensions',
		);

	$f[] = array(
			'name'	=> 'extension_conditionals',
			'label' => __( 'Conditionals Extension' , 'ubermenu' ),
			'desc'	=> ubermenu_show_extension_conditionals(),
			'type'	=> 'html',
			'group'	=> 'ubermenu_extensions',
		);
	$f[] = array(
			'name'	=> 'extension_skins_flat',
			'label' => __( 'Flat Skins Pack' , 'ubermenu' ),
			'desc'	=> ubermenu_show_extension_flat_skins_pack(),
			'type'	=> 'html',
			'group'	=> 'ubermenu_extensions',
		);


	$f[] = array(
			'name' 	=> 'um_related_plugins',
			'type' 	=> 'header',
			'label'	=> __('Related Plugins' , 'ubermenu' ),
			'group'	=> 'related_plugins',
	);

	$f[] = array(
			'name'	=> 'plugin_shiftnav',
			'label' => __( 'ShiftNav Mobile Menu' , 'ubermenu' ),
			'desc'	=> ubermenu_show_plugin_shiftnav(),
			'type'	=> 'html',
			'group'	=> 'related_plugins',
		);

	$f[] = array(
			'name'	=> 'plugin_bellows',
			'label' => __( 'Bellows Accordion Menu' , 'ubermenu' ),
			'desc'	=> ubermenu_show_plugin_bellows(),
			'type'	=> 'html',
			'group'	=> 'related_plugins',
		);

	$f[] = array(
			'name'	=> 'plugin_duplicator',
			'label' => __( 'Carbon Copy Menu Duplicator' , 'ubermenu' ),
			'desc'	=> ubermenu_show_plugin_duplicator(),
			'type'	=> 'html',
			'group'	=> 'related_plugins',
		);



	$fields[$section] = $f;
	return $fields;
}

function ubermenu_show_plugin_shiftnav(){
	$html = '';

	$installed = false;
	if( defined( 'SHIFTNAV_VERSION' ) ){
		$installed = true;
	}

	$html.= ubermenu_show_extension_info( array(
		'demo_url'	=> 'https://shiftnav.io/',
		'installed'	=> $installed,
		//'action'		=> $action,
		//'action_link' => $action_link,
		'img_src'	=> 'https://i.imgur.com/UqUMIUw.jpg',
		'purchase_url' => 'https://sevenspark.com/goods/shiftnav-pro',
		'description'	=> __( 'Enhance your mobile site with off-canvas navigation.' , 'ubermenu' ),
		'doc_url'	=> 'https://sevenspark.com/docs/shiftnav',
		'action_text' => __( 'Get the Plugin', 'ubermenu' ),
	));

	return $html;
}

function ubermenu_show_plugin_bellows(){
	$html = '';

	$installed = false;
	if( defined( 'BELLOWS_VERSION' ) ){
		$installed = true;
	}

	$html.= ubermenu_show_extension_info( array(
		'demo_url'	=> 'https://wpaccordionmenu.com/',
		'installed'	=> $installed,
		//'action'		=> $action,
		//'action_link' => $action_link,
		'img_src'	=> 'https://i.imgur.com/T3OIGRM.jpg',
		'purchase_url' => 'https://sevenspark.com/goods/bellows-pro',
		'description'	=> __( 'Complement your main navigation with a secondary accordion menu nav.' , 'ubermenu' ),
		'doc_url'	=> 'https://sevenspark.com/docs/bellows',
		'action_text' => __( 'Get the Plugin', 'ubermenu' ),
	));

	return $html;
}

function ubermenu_show_plugin_duplicator(){
	$html = '';

	$installed = false;
	if( defined( 'CARBON_COPY_MENU_DUPLICATOR_VERSION' ) ){
		$installed = true;
	}

	$html.= ubermenu_show_extension_info( array(
		'demo_url'	=> 'https://www.youtube.com/watch?v=2QmYWtR2-AM',
		'installed'	=> $installed,
		//'action'		=> $action,
		//'action_link' => $action_link,
		'img_src'	=> 'https://i.imgur.com/IrGlB3u.jpg',
		'purchase_url' => 'https://sevenspark.com/goods/carbon-copy-menu-duplicator',
		'description'	=> __( 'Duplicate and backup your menus, including UberMenu, ShiftNav, &amp; Bellows.' , 'ubermenu' ),
		'doc_url'	=> 'https://sevenspark.com/docs/carbon-copy-menu-duplicator',
		'action_text' => __( 'Get the Plugin', 'ubermenu' ),
	));

	return $html;
}

function ubermenu_show_extension_icons(){
	$html = '';

	$installed = false;
	if( defined( 'UM_ICONS_VERSION' ) ){
		$installed = true;
	}

	$html.= ubermenu_show_extension_info( array(
		'demo_url'	=> 'https://wpmegamenu.com/icons/',
		'installed'	=> $installed,
		//'action'		=> $action,
		//'action_link' => $action_link,
		'img_src'	=> 'https://i.imgur.com/Zksumwp.png',
		'purchase_url' => 'https://sevenspark.com/goods/ubermenu-icons',
		'description'	=> __( 'Add over 1,500 Font Awesome Icons, customize color, size, layout, and more.' , 'ubermenu' ),
		'doc_url'	=> 'https://sevenspark.com/docs/ubermenu-icons',
	));

	return $html;
}
function ubermenu_show_extension_conditionals(){
	$html = '';

	$installed = false;
	if( defined( 'UBERMENU_CONDITIONALS_VERSION' ) ){
		$installed = true;
	}

	$html.= ubermenu_show_extension_info( array(
		'demo_url'	=> 'https://wpmegamenu.com/conditionals/',
		'installed'	=> $installed,
		//'action'		=> $action,
		//'action_link' => $action_link,
		'img_src'	=> 'https://i.imgur.com/paUltRW.png',
		'purchase_url' => 'https://sevenspark.com/goods/ubermenu-conditionals',
		'description'	=> __( 'Control the visibility of individual menu items based on conditions.' , 'ubermenu' ),
		'doc_url'	=> 'https://sevenspark.com/docs/ubermenu-conditionals',
	));

	return $html;
}

function ubermenu_show_extension_sticky(){
	$html = '';

	$installed = false;
	if( defined( 'UM_STICKY_VERSION' ) ){
		$installed = true;
	}

	$html.= ubermenu_show_extension_info( array(
		'demo_url'	=> 'https://wpmegamenu.com/sticky/',
		'installed'	=> $installed,
		//'action'		=> $action,
		//'action_link' => $action_link,
		'img_src'	=> 'https://i.imgur.com/fV6VvkV.jpg',
		'purchase_url' => 'https://sevenspark.com/goods/ubermenu-sticky',
		'description'	=> __( 'Stick UberMenu to the top of the viewport as you scroll.' , 'ubermenu' ),
		'doc_url'	=> 'https://sevenspark.com/docs/ubermenu-sticky',
	));

	return $html;
}

function ubermenu_show_extension_flat_skins_pack(){
	$html = '';

	$installed = false;
	if( function_exists( 'ubermenu_skins_flat_register_ubermenu_skins' ) ){
		$installed = true;
	}

	$html.= ubermenu_show_extension_info( array(
		'demo_url'	=> 'http://wpmegamenu.com/flat-skins',
		'installed'	=> $installed,
		//'action'		=> $action,
		//'action_link' => $action_link,
		'img_src'	=> 'https://i.imgur.com/fk1jOF7.jpg',
		'purchase_url' => 'https://sevenspark.com/goods/ubermenu-flat-skins',
		'description'	=> __( 'Get 30 new flat-style skins to control the look of your menu.' , 'ubermenu' ),
		'doc_url'	=> 'https://wpmegamenu.com/help/flat-skins/',
	));

	return $html;
}

function ubermenu_show_extension_info( $info ){

	$analytics = '?utm_source=ubermenu_control_panel&utm_campaign=ubermenu_extensions';
	$action_text = isset( $info['action_text'] ) ? $info['action_text'] : 'Get the Extension';

	$html = '';

	$html.= '<div class="ubermenu-extension-img"><img src="'.$info['img_src'].'"/></div>';

	$html.= '<div class="ubermenu-extension-desc">'.$info['description'].'</div>';


	$html.= '<div class="ubermenu-extension-actions">';
	$html.= 	'<a target="_blank" href="'.$info['demo_url'].'" class="button">'.__( 'Demo' , 'ubermenu' ).'</a>';

	//Check if installed or not - visit settings vs Get Extension
	if( $info['installed'] ){
		$html.= ' <a target="_blank" class="button" href="'.$info['doc_url'].'">Doc</a>';
		$html.= '<span class="ubermenu-extension-installed">Installed <i class="fas fa-check"></i></span>';
	}
	else{
		$html.= ' <a target="_blank" class="button button-primary" href="'.$info['purchase_url'].$analytics.'">'.$action_text.'</a>';
	}

	$html.= '</div>';
	return $html;
}







/**
 * DELETE SETTINGS
 */

add_filter( 'ubermenu_settings_subsections' , 'ubermenu_settings_subsection_delete' , 1000 , 2 );
add_filter( 'ubermenu_settings_panel_fields' , 'ubermenu_settings_panel_fields_delete' , 1000 );

function ubermenu_settings_subsection_delete( $subsections , $config_id ){
	if( $config_id != 'main' ){
		$subsections['delete'] = array(
			'title'	=> __( 'Delete' ),
		);
	}
	return $subsections;
}

function ubermenu_settings_panel_fields_delete( $fields = array() ){

	$delete_header = array(
		'name'	=> 'header_delete',
		'label'	=> __( 'Delete', 'ubermenu' ),
		'type'	=> 'header',
		'group'	=> 'delete',
	);

	$menus = ubermenu_get_menu_instances( false );

	foreach( $menus as $menu ){

		//Requres $menu var
		$delete_instance = array(
			'name'	=> 'delete',
			'label'	=> __( 'Delete Configuration' , 'shiftnav' ),
			'desc'	=> '<a class="ubermenu_instance_button ubermenu_instance_button_delete" href="#" data-ubermenu-instance-id="'.$menu.'" data-ubermenu-nonce="'.wp_create_nonce( 'ubermenu-delete-instance' ).'">'.__( 'Permanently Delete Configuration' , 'ubermenu' ).'</a>',
			'type'	=> 'html',
			'group'	=> 'delete',
		);

		$fields[UBERMENU_PREFIX.$menu][2000] = $delete_header;
		$fields[UBERMENU_PREFIX.$menu][2000] = $delete_instance;
	}

	return $fields;
}







/**
 * WELCOME
 */

add_action( 'ubermenu_settings_after' , 'ubermenu_settings_welcome' );
function ubermenu_settings_welcome(){
	$show_welcome = get_option( UBERMENU_WELCOME_MSG , 1 );
	//echo $show_welcome ? 'true' : 'false';
	//$show_welcome = false;
	?>
	<div class="ubermenu-welcome <?php if( !$show_welcome ) echo 'ubermenu-welcome-hide'; ?>">
		<div class="ubermenu-welcome-inner">
			<h2>Welcome to UberMenu <?php echo UBERMENU_VERSION; ?></h2>
			<a class="ubermenu-welcome-dismiss" href="#" data-ubermenu-nonce="<?php echo wp_create_nonce( 'ubermenu-dismiss-welcome' ); ?>">
				&times;
				<span class="ubermenu-welcome-dismiss-alert">If you are unable to close this window, please check for javascript errors from other plugins or your theme</span>
			</a>

			<div class="ubermenu-welcome-buttons">
				<a target="_blank" class="button button-primary" href="<?php echo UBERMENU_KB_URL; ?>"><i class="fas fa-book"></i> Knowledgebase</a>
				<a target="_blank" class="button button-tertiary" href="<?php echo UBERMENU_VIDEOS_URL; ?>"><i class="fas fa-video"></i> Video Tutorials</a>
				<a target="_blank" class="button button-secondary" href="<?php echo ubermenu_get_support_url(); ?>"><i class="fas fa-life-ring"></i> Support</a>

				<span class="ubermenu-welcome-licenses">Your purchase entitles you to use UberMenu on one (1) site. <a target="_blank" class="ubermenu-license-info-link" href="http://goo.gl/lTLDhv"><i class="fas fa-question-circle"></i></a></span>
				<a target="_blank" class="button button-secondary button-new-license" href="http://wpmegamenu.com"><i class="fas fa-shopping-cart"></i> Need another license?</a>
			</div>

			<p>Links to the Knowledgebase, Video Tutorials, and Support Center will appear in the upper right
				of your Control Panel for easy access.  The QuickStart video below will help you get up and running quickly.</p>

			<?php if( $show_welcome ): ?>
				<iframe class="ubermenu-welcome-video" width="1000" height="563" src="<?php echo UBERMENU_QUICKSTART_URL; ?>" data-src="<?php echo UBERMENU_QUICKSTART_URL; ?>" frameborder="0" allowfullscreen></iframe>
			<?php else: ?>
				<iframe class="ubermenu-welcome-video" width="1000" height="563" data-src="<?php echo UBERMENU_QUICKSTART_URL; ?>" frameborder="0" allowfullscreen></iframe>
			<?php endif; ?>
		</div>
	</div>
	<?php
}


function ubermenu_dismiss_welcome_callback(){

	check_ajax_referer( 'ubermenu-dismiss-welcome' , 'ubermenu_nonce' );

	$response = array();

	update_option( UBERMENU_WELCOME_MSG , 0 );

	$response['welcome_msg'] = 0;

	echo json_encode( $response );

	die();
}
add_action( 'wp_ajax_ubermenu_dismiss_welcome', 'ubermenu_dismiss_welcome_callback' );





add_filter( 'plugin_action_links_'.UBERMENU_BASENAME , 'ubermenu_action_links' );
function ubermenu_action_links( $links ) {
	if( !is_array( $links ) ) return $links;
	$links[] = '<a href="'. admin_url( 'themes.php?page=ubermenu-settings' ) .'">Control Panel</a>';
	$links[] = '<a href="'. admin_url( 'themes.php?page=ubermenu-settings#ubermenu_updates' ) .'">Updates</a>';
	$links[] = '<a target="_blank" href="'.UBERMENU_KB_URL.'">Knowledgebase</a>';
	return $links;
}
