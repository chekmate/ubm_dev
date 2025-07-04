<?php


/**
 * Parent Class
 */
abstract class UberMenuItem{

	protected $type = 'unknown';	//Item Type 		getType()			default, dynamic_term, dynamic_post,
	protected $ID = 0;				//Menu Item ID 		getID()
	protected $source_id = 0;

	protected $output;
	protected $item;
	protected $depth;
	protected $args;
	protected $id;
	protected $walker;

	protected $settings;
	protected $submenu_advanced;
	protected $submenu_tag = 'ul';
	protected $submenu_classes = array();

	protected $item_classes = array( 0 => '' );	//Put in an empty entry to mimic the custom class used by real items, for when we have a dummy item
	protected $item_atts = array();

	protected $auto_child = '';
	protected $prev_auto_child = '';

	protected $alter_structure = false;


	protected $has_children = false;
	protected $submenu_type = false;
	protected $drop_sub = false;
	protected $closable_sub = false;

	protected $is_dummy = false;
	protected $is_tab = false;

	protected $predetermined_submenu_type = false;

	//protected $branch_prefix = '';

	/*
	protected $detached = false;
	protected $detached_submenu = false;
	protected $parent_detached_context = 0;
	protected $passed_content = '';
	*/

	function __construct( &$output , &$item , $depth = 0, &$args = array() , $id = 0 , &$walker , $has_children = false ){
		$this->output 	= &$output;
		$this->item 	= &$item;
		$this->depth 	= $depth;
		$this->args 	= &$args;
		$this->id 		= $id;
		$this->walker 	= &$walker;
		$this->has_children = $has_children;

		$this->ID = $this->item->ID;
		//up( $this->ID );

		//Setup dummy
		if( isset( $this->item->is_dummy ) && $this->item->is_dummy ){
			$this->is_dummy = true;
		}

		//Setup settings
		$this->settings = $this->get_settings();

		$this->source_id = $this->item->db_id;

		if( isset( $this->item->object ) && ( $this->item->object == 'ubermenu-custom' ) ){
			//echo 'set source_id ' . $this->item->object_id;
			$this->source_id = $this->item->object_id;
		}


		//New filter in 4.4 - 'nav_menu_item_args'
		//Since this filter is most likely going to just break the menu, it is disabled by default, but can be enabled by adding
		//define( 'UBERMENU_ALLOW_NAV_MENU_ITEM_ARGS_FILTER' , true );
		//in the wp-config.php
		if( UBERMENU_ALLOW_NAV_MENU_ITEM_ARGS_FILTER ) $this->args = apply_filters( 'nav_menu_item_args' , $this->args , $this->item );

		$this->init();
		//$this->initialize_submenu();


		//Only check if necessary (Advanced submenu set to auto)
		if( $this->getSetting( 'submenu_advanced' ) == 'auto' ){
			if( $this->item->classes == null ){
				//echo '<h3>[['.$this->item->title.']]</h3>';
				//up( $this->item );
			}
			if( is_array( $this->item->classes ) ){
				if( in_array( 'advanced-sub' , $this->item->classes ) ){
					$this->submenu_advanced = true;
					$this->submenu_tag = 'div';
				}
			}
		}
		else if( $this->getSetting( 'submenu_advanced' ) == 'enabled' ){
			$this->submenu_advanced = true;
			$this->submenu_tag = 'div';
		}

	}

	/* Allows subclasses to hook in */
	function init(){}

	function get_item(){
		return $this->item;
	}

	function get_branch_prefix(){
		return $this->branch_prefix;
	}

	function get_id(){
		return $this->ID;
	}
	function getID(){
		return $this->ID;
	}

	function get_transient_key( $prefix ){
		return 'ubertk_'.$prefix.$this->walker->unique_path_key( $this->get_id() );
	}

	function get_depth(){
		return $this->depth;
	}

	function is_tab(){
		return $this->is_tab;
	}

	function display_on(){

		if( $this->getSetting( 'disable_on_mobile' ) == 'on' ){
			if( ubermenu_is_mobile( 'disable_on_mobile' ) ){
				return false;
			}
		}

		if( $this->getSetting( 'disable_on_desktop' ) == 'on' ){
			if( !ubermenu_is_mobile( 'disable_on_desktop' ) ){
				return false;
			}
		}

		return true;
	}

	function disable_children(){
		$this->has_children = false;

		$_item = $this->item;
		if( ( $key = array_search( 'menu-item-has-children', $_item->classes ) ) !== false) {
			unset( $_item->classes[$key] );
		}
		//Possibly remove menu item parent/menu item ancestor classes as well
	}


	function create_reference( $source_id , &$children , $reference_index = '' ){

		if( $reference_index == '' ){
			$reference_index = '_ref_'.$source_id;
		}

		if( !$this->walker->feed_trash_collector( $reference_index ) ){

			if( isset( $children[$source_id] ) && !empty( $children[$source_id] ) ){
				$children[$reference_index] = $children[$source_id];

			}
		}

		return $reference_index;

	}


	/* If this item models a term, return its ID */
	function get_term_id(){
		return false;
	}
	/* If this item models a post, return its ID */
	function get_post_id(){
		return false;
	}

	function dynamic_alter( $tab_id , $source_id , $umitem , &$children ){
		return false;
	}

	function get_settings(){
		if( isset( $this->settings ) ) return $this->settings;

		$settings = get_post_meta( $this->item->ID, UBERMENU_MENU_ITEM_META_KEY , true );
		if( !$settings ) $settings = array();

		//Allow dummy settings to override source item settings
		if( $this->is_dummy && isset( $this->item->settings ) ){
			if( !is_array( $settings ) ) $settings = array();
			foreach( $this->item->settings as $key => $val ){
				$settings[$key] = $val;
			}
		}

		$settings = apply_filters( 'ubermenu_item_settings' , $settings , $this->ID );

		return $settings;
	}

	function get_menu_op( $op ){
		//Determine menu instance
		//$instance = 'main';		//TODO
		//$instance = $this->args[0]->uber_instance;

		$instance = $this->get_config_id();
		return ubermenu_op( $op , $instance );
	}

	function get_config_id(){
		return $this->args->uber_instance;
	}

	function alter_structure(){
		return $this->alter_structure;
	}
	function alter( &$children ){}

	function get_submenu_tag(){
		return $this->submenu_tag;
	}

	function pass_content( $content ){
		$this->passed_content.= $content;
	}

	function getAutoChild(){
		return $this->auto_child;
	}

	function getType(){
		return $this->type;
	}

	function getSetting( $key ){
		if( isset( $this->settings[$key] ) ){
			$val = $this->settings[$key];
		}
		else{ //} if( isset( $this->walker->setting_defaults[$key] ) ){
			$val = $this->walker->setting_defaults[$key];
		}
		return $val;
	}

	//1
	function start_el(){
		$this->output.= apply_filters( 'walker_nav_menu_start_el' , $this->get_start_el() , $this->item , $this->depth , $this->args );
	}

	//4
	function end_el(){
		$this->output.= $this->get_end_el();
	}

	//2
	function start_lvl(){
		$this->output.= $this->get_submenu_wrap_start();
	}

	//3
	function end_lvl(){
		$this->output.= $this->get_submenu_wrap_end();
	}

	//Detached content

	function detach(){
		$this->detached_submenu = true;
		$this->walker->detach( $this->ID );
		//echo 'detached ' . $this->ID . '<br/>';
	}
	function undetach(){
		$this->detached_submenu = false;
		$this->walker->undetach();
	}
	function complete_detachment(){}	//do nothing by default



	abstract function get_start_el();

	function get_end_el(){
		$item_output = "</li>"; //<!-- end ".$this->item->ID."-->\n";
		return $item_output;
	}

	function get_submenu_type( $submenu_type = false ){

		if( !$this->has_children ) return false;

		//If already cached, don't reprocess
		if( $this->submenu_type ) return $this->submenu_type;

		//If not passed, grab setting
		if( !$submenu_type ) $submenu_type = $this->getSetting( 'submenu_type' );

		//echo $this->item->title . ' : '. $submenu_type ." : $this->depth <br/>";
		if( $submenu_type == 'auto' ){

			//$classes[] = 'ubermenu-submenu-type-auto';

			//figure it out
			if( $this->depth == 0 ){
				$submenu_type = 'mega';
			}
			else if( $this->depth >= 1 ){
				$parent = $this->walker->parent_item();
				if( $parent && $parent->type == 'row' ){
					$parent = $this->walker->grandparent_item();
				}
				//up( $parent );
				//echo 'Parent of '.$this->item->title . ' is ' . $parent->getSetting( 'submenu_type_calc' ) .'<br/>';
				$parent_submenu = $parent->getSetting( 'submenu_type_calc' );
//echo ' -- ' .$this->item->title . ' : ' . $parent_submenu . '<br/>';
				switch( $parent_submenu ){
					case 'mega':
					case 'block':
					case 'tab-content-panel':
					case 'toggles-content-panel':
						$submenu_type = 'stack';
						break;
					case 'flyout':
						$submenu_type = 'flyout';
						break;
					default:
						//inherit parent
						$submenu_type = $parent_submenu;
						break;
				}

			}
		}

		$this->submenu_type = $submenu_type;

		return $submenu_type;
	}

	function get_submenu_id(){
		return 'ubermenu-submenu-'.$this->getID();
	}

	function initialize_submenu(){

		//Standard WordPress Classes
		$this->submenu_classes[] = 'ubermenu-submenu';
		$this->submenu_classes[] = 'ubermenu-submenu-id-' . $this->item->ID;


		//Submenu Type
		$submenu_type = $this->getSetting( 'submenu_type' );
		if( $submenu_type == 'auto' ){
			$this->submenu_classes[] = 'ubermenu-submenu-type-auto';
			$submenu_type = $this->get_submenu_type( $submenu_type );
		}

		$this->settings['submenu_type_calc'] = $submenu_type;

		$this->submenu_classes[] = 'ubermenu-submenu-type-'.$submenu_type;

		if( in_array( $submenu_type , array( 'mega' , 'flyout' ) ) ){
			$this->drop_sub = true;
			$this->submenu_classes[] = 'ubermenu-submenu-drop';
		}
		if( $this->drop_sub || $submenu_type == 'tab-content-panel' ){
			$this->closable_sub = true;
		}

		$this->submenu_type = $submenu_type;
	}

	function predetermine_submenu_type(){
		if( $this->predetermined_submenu_type ){
			return $this->predetermined_submenu_type;
		}

		$submenu_type = $this->getSetting( 'submenu_type' );
		if( $submenu_type == 'auto' ){
			$submenu_type = $this->get_submenu_type( $submenu_type );
		}
		if( in_array( $submenu_type , array( 'mega' , 'flyout' ) ) ){
			$this->drop_sub = true;
		}
		if( $this->drop_sub || $submenu_type == 'tab-content-panel' ){
			$this->closable_sub = true;
		}

		$this->predetermined_submenu_type = $submenu_type;
	}


	function get_submenu_wrap_start(){

		$this->initialize_submenu();

		$classes = $this->submenu_classes;
		$submenu_type = $this->submenu_type;

		//Mega menu submenu alignment
		if( $submenu_type == 'mega' ){
			$classes[] = 'ubermenu-submenu-align-' . $this->getSetting( 'submenu_position' );
		}
		else if( $submenu_type == 'flyout' ){
			$classes[] = 'ubermenu-submenu-align-' . $this->getSetting( 'flyout_submenu_position' );
		}

		//Menu menu submenu content alignment
		$submenu_content_align = $this->getSetting( 'submenu_content_align' );
		if( $submenu_content_align && $submenu_content_align != 'default' ){
			$classes[] = 'ubermenu-submenu-content-align-' . $submenu_content_align;
		}

		//Autoclear
		$submenu_col_default = $this->getSetting( 'submenu_column_default' );
		if( $this->getSetting( 'submenu_column_autoclear' ) == 'on' && $submenu_col_default != 'auto' && $submenu_col_default != 'natural' ){
			$classes[] = 'ubermenu-autoclear';
		}

		//Padding
		if( $this->getSetting( 'submenu_padded' ) == 'on' ){
			$classes[] = 'ubermenu-submenu-padded';
		}

		//Background Image
		if( $this->getSetting( 'submenu_background_image' ) ){	//Not 'on', image URL
			$classes[] = 'ubermenu-submenu-bkg-img';
		}

		//Submenu Grid
		if( $this->getSetting( 'submenu_grid' ) == 'on' ){
			$classes[] = 'ubermenu-submenu-grid';
		}


		//Indent
		if( $this->getSetting( 'submenu_indent' ) == 'on' ){
			$classes[] = 'ubermenu-submenu-indent';
		}

		//Retractors
		$retractor_top = $this->closable_sub && ubermenu_display_retractors() && ( ubermenu_op( 'display_retractor_top' , $this->args->uber_instance ) == 'on' );
		if( $retractor_top ) $classes[] = 'ubermenu-submenu-retractor-top';

		//Close Button
		$close_button = $this->closable_sub && ( ubermenu_op( 'display_submenu_close_button' , $this->args->uber_instance ) == 'on' );
		if( $close_button ){
			if( $retractor_top ) $classes[] = 'ubermenu-submenu-retractor-top-2';
			else 				 $classes[] = 'ubermenu-submenu-retractor-top';
		}


		$class = 'class="'.implode( ' ' , $classes ).'"';


		//Inline styles
		$_styles = array();

		//Explicit width (should be moved)

		/*$submenu_width = $this->getSetting( 'submenu_width' );
		if( $submenu_width != '' ){
			if( is_numeric( $submenu_width ) ) $submenu_width.= 'px';
			$_styles['width'] = $submenu_width;
		}
		*/

		//Create inline styles string if necessary
		$styles = '';
		if( count( $_styles ) > 0 ){
			$styles.= 'style="';
			foreach( $_styles as $property => $val ){
				$styles.= "$property:$val;";
			}
			$styles.='"';
		}


		$aria = '';
		if( ubermenu_op( 'aria_hidden' , 'general' ) == 'on' ){
			$this->predetermine_submenu_type();
			if( $this->drop_sub ){
				$aria = 'aria-hidden="true"';
			}
		}

		//Add an ID we want to use aria-controls
		$id = '';
		if( ubermenu_op( 'aria_controls' , 'general' ) == 'on' ){
			$id = 'id="'.$this->get_submenu_id().'"';
		}


		$item_output = "<$this->submenu_tag $id $class $styles $aria>";


		// $retractor_icon_tag = $this->get_menu_op( 'icon_tag' );
		// if( !$retractor_icon_tag ) $retractor_icon_tag = 'i';

		//Retractor Top
		if( $retractor_top ){
			$retractor_tag = $this->submenu_tag == 'ul' ? 'li' : 'div';


			$retractor_label = ubermenu_op( 'retractor_label' , $this->args->uber_instance );
			if( !$retractor_label )	$retractor_label = __( 'Close' , 'ubermenu' );

			$item_output.= '<'.$retractor_tag.' class="ubermenu-retractor ubermenu-retractor-mobile">'.$this->get_close_icon_tag().' '.$retractor_label.'</'.$retractor_tag.'>';
		}

		//Close button
		if( $close_button ){
			$retractor_tag = $this->submenu_tag == 'ul' ? 'li' : 'div';
			$item_output.= '<'.$retractor_tag.' class="ubermenu-retractor ubermenu-retractor-desktop">'.$this->get_close_icon_tag().'</'.$retractor_tag.'>';
		}


		return $item_output;

	}
	function get_submenu_wrap_end(){

		$html = '';

		//Footer Content
		$footer_content = $this->getSetting( 'submenu_footer_content' );
		if( $footer_content ){
			$fc_tag = 'li';
			if( $this->submenu_tag != 'ul' ) $fc_tag = 'div';
			$html.= '<'.$fc_tag.' class="ubermenu-submenu-footer ubermenu-submenu-footer-id-'.$this->ID.'">'.$footer_content.'</'.$fc_tag.'>';
		}

//'tab-content-panel'

		//Retractor Bottom
		if( $this->closable_sub && ubermenu_display_retractors() && ( ubermenu_op( 'display_retractor_bottom' , $this->args->uber_instance ) == 'on' ) ){
			$retractor_tag = $this->submenu_tag == 'ul' ? 'li' : 'div';

			$retractor_label = ubermenu_op( 'retractor_label' , $this->args->uber_instance );
			if( !$retractor_label )	$retractor_label = __( 'Close' , 'ubermenu' );

			$html.= '<'.$retractor_tag.' class="ubermenu-retractor ubermenu-retractor-mobile">'. $this->get_close_icon_tag().' '.$retractor_label.'</'.$retractor_tag.'>';
		}

		$html.= "</$this->submenu_tag>";

		return $html;
	}

	function get_close_icon_tag(){
		if( ubermenu_op( 'use_core_svgs', 'general' ) === 'on' ){
			return ubermenu_get_essential_icon( 'times' );
		}
		else{
			$retractor_icon_tag = $this->get_menu_op( 'icon_tag' );
			if( !$retractor_icon_tag ) $retractor_icon_tag = 'i';

			return '<'.$retractor_icon_tag.' class="fas fa-times"></'.$retractor_icon_tag.'>';
		}
	}


	function getVirtualDepth(){
		return $this->depth;
	}




	function add_class_item_defaults(){
		//$this->item_classes = empty( $this->item->classes ) ? array() : (array) $this->item->classes;
		if( is_array( $this->item->classes ) ){
			$this->item_classes = array_merge( $this->item_classes , $this->item->classes );

			//Disable Current Menu Item Classes (do this first for efficiency)
			if(
				( $this->getSetting( 'disable_current' ) == 'on' ) ||
				( ubermenu_op( 'scrollto_disable_current', 'general' ) !== 'off' && $this->getSetting( 'scrollto' ) )
				){
				$remove_current = array( 'current-menu-item' , 'current-menu-parent' , 'current-menu-ancestor' );
				foreach( $this->item_classes as $k => $c ){
					if( in_array( $c ,  $remove_current ) ){
						unset( $this->item_classes[$k] );
					}
				}
				$this->item_classes[] = 'nocurrent';
			}
		}
	}
	function add_class_id(){
		$this->item_classes[] = 'menu-item-' . $this->item->ID;
	}
	function prefix_classes(){
		//uberp( $this->item_classes );
		//if( $this->type == 'tabs' ) uberp( $this->item_classes );
		$k = 0;
		$found = false;
		foreach( $this->item_classes as $i => $class ){
			//if( $class == 'menu-item' ) $classes[$i] = 'ubermenu-item';

			//The first class is custom, so ignore it
			//if( $k == 0 ){ $k++; continue; }

			//menu-item marks the first class we want to preix, so ignore everything before that
			if( !$found && $class == 'menu-item' ) $found = true;
			if( !$found ) continue;

			if( $class ){
				if( substr( $class , 0 , 4 ) == 'menu' ){
					$this->item_classes[$i] = 'uber'.$class;
				}
				else $this->item_classes[$i] = 'ubermenu-'.$class;
				//add to end if using both
			}
		}
	}
	function add_class_item_display(){

		$this->settings['item_display_calc'] = '';

		//Item Display
		if( $this->depth > 0 ){
			$item_display = $this->getSetting( 'item_display' );
			$this->item_classes[] = 'ubermenu-item-'.$item_display;

			//Determine auto
			if( $item_display == 'auto' ){

				$parent_type = $this->walker->parent_item()->getType();

				switch( $parent_type ){

					//For items inside a content panel, act like a mega sub
					case 'toggle_content_panel':
						$item_display = 'header';
						break;

					//For terms inside a content panel, look to the grandparent
					case 'dynamic_posts':
					case 'dynamic_terms':
						if( $this->walker->grandparent_item() ){
							if( $this->walker->grandparent_item()->getType() == 'toggle_content_panel' ){
								$item_display = 'header';
							}
							//echo '//'.$this->walker->grandparent_item()->getType().'//<br/>';
						}
						break;

					//case 'column':
					//	$item_display = 'normal';
					//	break;

				}

				//Still auto?
				if( $item_display == 'auto' ){

					$in_sub = $this->walker->parent_item()->getSetting('submenu_type_calc');

					switch( $in_sub ){

						case 'mega' :

							if( $this->depth == 1 ){
								$item_display = 'header';
							}
							else if( $this->depth > 1 && $this->walker->parent_item()->getVirtualDepth() == 1 ){
								$item_display = 'header';
							}
							else if( $this->walker->parent_item()->getType() == 'row' ){
								$item_display = 'header';
							}
							else if( $this->walker->parent_item()->getType() == 'menu_segment' &&
										$this->walker->grandparent_item()->getType() == 'row'){
								$item_display = 'header';
							}
							else if( $this->depth > 1 && $this->walker->grandparent_item()->getSetting('submenu_type_calc') == 'flyout' ){
								$item_display = 'header';
							}
							else{
								//For items that are in the submenu but yet undetermined
								if( $this->depth > 1 ){
									//If it's parent wasn't a header, but the sub of the parent was a mega, this should probably be a header
									if( $this->walker->parent_item()->getSetting( 'item_display_calc' ) != 'header' ){
										$item_display = 'header';
									}
								}
								else $item_display = 'normal';
							}
							break;

						case 'flyout':
							$item_display = 'normal';
							break;

						case 'stack':
							$item_display = 'normal';
							break;
						case 'block':
							$item_display = 'header';
							break;
						case 'tabs-group':
						case 'toggles-group':
							//Ignore, use 'ubermenu-toggle' instead
							$item_display = '';
							break;
						case 'tab-content-panel':
						case 'toggles-content-panel':
							$item_display = 'header';
							break;
						/*
						case 'dynamic-terms':
							$item_display = 'header';
							break;*/

						default:
							$item_display = 'unknown-['.$in_sub.']';
							break;

					}
				}

				if( $item_display ){
					$this->item_classes[] = 'ubermenu-item-'.$item_display;
					// $this->settings['item_display_calc'] = $item_display;
				}
			}

			$this->settings['item_display_calc'] = $item_display;

		}
	}

	function add_class_level(){
		$this->item_classes[] = 'ubermenu-item-level-'.$this->getVirtualDepth(); //$this->depth;
	}

	function add_class_layout_columns(){

		if( $this->depth > 0 ){
			$parent_submenu_type = $this->walker->parent_item()->getSetting('submenu_type_calc');
			if( $parent_submenu_type == 'flyout' ) return;	//no columns in flyouts
		}

		//if( $this->depth > 1 ){

		$cols = $this->getSetting( 'columns' );
		if( $this->depth > 0 ){
			//Widgets are full width if Columns set to Auto
			if( ( $this->getSetting( 'widget_area' ) || $this->getSetting( 'auto_widget_area' ) )
				&& $cols == 'auto' ){
				$cols = 'full';
			}
			//If set to auto, apply submenu column default from parent item
			else if( $cols == 'auto' ){
				$cols = $this->walker->parent_item()->getSetting( 'submenu_column_default' );
			}
		}
		$this->item_classes[] = 'ubermenu-column ubermenu-column-' . $cols;


		//New Row
		if( $this->getSetting( 'clear_row' ) == 'on' ){
			$this->item_classes[] = 'ubermenu-clear-row';
		}

		//if( $this->walker->parent_item()->getSetting)
	}

	function add_class_alignment(){
		$align = $this->getSetting( 'item_align' );
		if( $align && $align != 'auto' ){
			$this->item_classes[] = 'ubermenu-align-'.$align;
		}
	}

	function add_class_mini_item(){
		if( $this->getSetting( 'mini_item' ) == 'on' ){
			$this->item_classes[] = 'ubermenu-item-mini';
		}
	}

	function add_class_rtl_sub(){
		if( ( $this->get_submenu_type() == 'mega' ) && ( $this->getSetting( 'submenu_position' ) == 'right_edge_item' ) ) {
			$this->item_classes[] = 'ubermenu-submenu-rtl';
		}
		else if( $this->depth >= 1 && ( $this->get_submenu_type() == 'mega' ) && ( $this->getSetting( 'submenu_position' ) == 'left_edge_item' ) ) {
			$this->item_classes[] = 'ubermenu-submenu-flyout-mega-left'; //special flag in case flyout > mega goes left
		}
		else if( ( $this->get_submenu_type() == 'flyout' ) && ( $this->getSetting( 'flyout_submenu_position' ) == 'right_edge_item' ) ){
			$this->item_classes[] = 'ubermenu-submenu-rtl';
			$this->item_classes[] = 'ubermenu-submenu-reverse';
		}
	}
	function add_class_responsive(){
		if( $this->getSetting( 'hide_on_mobile' ) == 'on' ){
			$this->item_classes[] = 'ubermenu-hide-mobile';
		}
		if( $this->getSetting( 'hide_on_desktop' ) == 'on' ){
			$this->item_classes[] = 'ubermenu-hide-desktop';
		}
	}
	function add_class_submenu(){
		$submenu_type = $this->get_submenu_type();
		if( $submenu_type ){
			if( in_array( $submenu_type , array( 'mega' , 'flyout' ) ) ){
				$this->item_classes[] = 'ubermenu-has-submenu-drop';

				//Show current
				if( ( $this->getSetting( 'show_current' ) == 'on' ) &&
					( in_array( 'ubermenu-current-menu-ancestor' , $this->item_classes ) ||
					  in_array( 'ubermenu-current-menu-item' , $this->item_classes )
					) ){
					$this->item_classes[] = 'ubermenu-active';
				}

				//Show Default
				if( $this->getSetting( 'show_default' ) == 'on' ){
					$this->item_classes[] = 'ubermenu-active';
				}
			}
			$this->item_classes[] = 'ubermenu-has-submenu-'.$submenu_type;
		}

		if( ( $this->getSetting( 'submenu_position' ) == 'vertical_parent_item' ) ||
			( $this->getSetting( 'flyout_submenu_position' ) == 'vertical_parent_item' ) ){
			$this->item_classes[] = 'ubermenu-relative';
		}

		if( $submenu_type == 'flyout' && $this->getSetting( 'flyout_submenu_position' ) == 'vertical_full_height' ){
			$this->item_classes[] = 'ubermenu-flyout-full-height';
		}
	}

	function add_class_disable_padding(){
		$disable_padding = $this->getSetting( 'disable_padding' );
		if( $disable_padding == 'on' ){
			$this->item_classes[] = 'ubermenu-disable-padding';
		}
	}

	function filter_item_classes(){
		/**
		 * Filter the CSS class(es) applied to a menu item's <li>.
		 *
		 * @since 3.0.0
		 *
		 * @param array  $classes The CSS classes that are applied to the menu item's <li>.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of arguments. @see wp_nav_menu()
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $this->item_classes ), $this->item, $this->args , $this->depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		return $class_names;
	}

	function filter_item_id(){
		/**
		 * Filter the ID applied to a menu item's <li>.
		 *
		 * @since 3.0.1
		 *
		 * @param string The ID that is applied to the menu item's <li>.
		 * @param object $item The current menu item.
		 * @param array $args An array of arguments. @see wp_nav_menu()
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $this->item->ID, $this->item, $this->args , $this->depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		return $id;
	}


	function setup_trigger(){
		$trigger = $this->getSetting( 'item_trigger' );
		if( $trigger && $trigger != 'auto' ){
			$this->item_atts['data-ubermenu-trigger'] = $trigger;
		}
	}

	function get_url(){
		return $this->item->url;
	}
	function set_url( $url ){
		$this->item->url = $url;
	}

	/**
	 * Get the attributes for the anchor, including class, title, target, rel, href
	 * Filterable with 'nav_menu_link_attributes'
	 * @return array 	An array of attributes with attribute names as keys and attribute values as values i.e. $key="$val"
	 */
	function anchor_atts(){
		$atts = array();
		$atts['class']	= 'ubermenu-target';	//add UberMenu specific meta
		$atts['title']  = ! empty( $this->item->attr_title ) ? $this->item->attr_title : '';
		$atts['target'] = ! empty( $this->item->target )     ? $this->item->target     : '';
		$atts['rel']    = ! empty( $this->item->xfn )        ? $this->item->xfn        : '';
		$atts['href']   = ! empty( $this->item->url )        ? $this->item->url        : '';

		if( $this->depth == 0 ){
			$atts['tabindex'] = 0;
		}

		/**
		 * Filter the HTML attributes applied to a menu item's <a>.
		 *
		 * @since 3.6.0
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's <a>, empty strings are ignored.
		 *
		 *     @type string $title  The title attribute.
		 *     @type string $target The target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item The current menu item.
		 * @param array  $args An array of arguments. @see wp_nav_menu()
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $this->item, $this->args , $this->depth );

		return $atts;
	}


	/**
	 * Get the Anchor and its contents
	 * @param  array $atts An array of attributes to add to the anchor
	 * @return string       The HTML for the anchor
	 */
	function get_anchor( $atts ){

		if( $this->item->title == '--divide--' ){
			return '<div class="ubermenu-divider"><hr/></div>';
		}


		$a = '';
		$tag = 'a';

		$parent_item = false;
		if( $this->depth >= 1 ) $parent_item = $this->walker->parent_item();

		//Highlight
		if( $this->getSetting( 'highlight' ) == 'on' ){
			$atts['class'].= ' ubermenu-highlight';
		}


		//Image
		$image = $this->get_image();
		if( $image ) $atts['class'] .= ' ubermenu-target-with-image';


		//Icon
		$icon = $this->getSetting( 'icon' );
		if( $icon ) $icon = ubermenu_fa5_convert( $icon , true );
		$icon_classes = apply_filters( 'ubermenu_icon_custom_class' , $icon , $this->ID , isset( $this->settings['icon_custom_class'] ) ? $this->settings['icon_custom_class'] : '' );
		if( $icon_classes ){
			$atts['class'] .= ' ubermenu-target-with-icon';
			$icon_tag = $this->get_menu_op( 'icon_tag' );
			if( !$icon_tag ) $icon_tag = 'i';

			//Accessibility
			$aria = '';
			$icon_title = $this->getSetting( 'icon_title' );

			if( $icon_title ){
				$icon_title = ' title="'.$icon_title.'"';
				//Font Awesome JS takes care of aria hidden if there's a title
			}
			else if( ubermenu_op( 'aria_hidden_icons' , 'general' ) == 'on' ){	//TODO deprecate
				$aria = 'aria-hidden="true"';
			}

			$icon = '<'.$icon_tag.' class="ubermenu-icon '.$icon_classes.'" '.$aria.$icon_title.'></'.$icon_tag.'>';
		}

		//Layout
		$layout = $this->getSetting( 'item_layout' );
		//If there is no image, don't allow image layout
		if( !$image && ( 0 === strpos( $layout, 'image' ) ) ) $layout = 'default';
		$atts['class'].= ' ubermenu-item-layout-'.$layout;



		//Content Align
		$content_align = $this->getSetting( 'content_alignment' );
		//Check inherit from parent
		if( $content_align == 'default' ){
//if( $this->id == 604 ) echo $this->item->title;
			//if( $parent_item && $parent_item->getType() == 'menu_segment' ) echo '<br/>['.$parent_item->getType();
			if( $this->depth >= 1 && $submenu_item_content_alignment = $parent_item->getSetting( 'submenu_item_content_alignment' ) ){
				if( $submenu_item_content_alignment !== 'default' ){
					$content_align = $submenu_item_content_alignment;
				}
			}
			//if( $parent_item && $parent_item->getType() == 'menu_segment' ) echo ']';
		}
		//If a content alignment has been set on this item or the parent, set the class
		if( $content_align != 'default' ){
			$atts['class'].= ' ubermenu-content-align-'.$content_align;
		}

		if( $layout == 'default' ){

			//If the layout for the individual item is set to default, and this is a child item, check the parent item's setting
			if( $this->depth >= 1 && $submenu_item_layout = $parent_item->getSetting( 'submenu_item_layout' ) ){
				if( $submenu_item_layout !== 'default' ){
					$layout = $submenu_item_layout;
				}
			}

			//If the layout hasn't been determined yet, and we're using an image, check to see if there's a default image layout
			if( $layout == 'default' && $image ){
				$layout = $this->get_menu_op( 'image_layout_default' );
				if( !$layout ) $layout = 'image_left';
			}
			//If there's an icon, use the default icon layout
			else if( $icon ){
				if( function_exists( 'ubermenu_icon_layout_default' ) ){
					$layout = ubermenu_icon_layout_default( $this );
				}
				else $layout = 'icon_left';
			}
			//If nothing else has claimed it, we default to text_only
			else if( $layout == 'default' ){
				$layout = 'text_only';
			}

			$atts['class'].= ' ubermenu-item-layout-'.$layout;
		}

		$layout_order = ubermenu_get_item_layouts( $layout );
		if( !$layout_order ){
			ubermenu_admin_notice( __( 'Unknown layout order:', 'ubermenu' ).' '.$layout.' ['.$this->item->title.'] ('.$this->ID.')' );
		}


		//No wrap
		if( $this->getSetting( 'no_wrap' ) == 'on' ){
			$atts['class'].= ' ubermenu-target-nowrap';
		}



		//Disabled Link (change tag)
		$disable_link = false;
		if( $this->getSetting( 'disable_link' ) == 'on' ){
			$tag = 'span';
			$disable_link = true;
			unset( $atts['href'] );
		}


		//Disable Submenu Indicator
		$disable_submenu_indicator = false;
		if( $this->getSetting( 'disable_submenu_indicator' ) == 'on' ){
			$disable_submenu_indicator = true;
			$atts['class'].= ' ubermenu-noindicator';
		}

		//Global Submenu Indicators
		// $display_submenu_indicators = $this->get_menu_op('display_submenu_indicators') === 'on' ? true : false; /// moved to own function


		//ScrollTo
		$scrollTo = $this->getSetting( 'scrollto' );
		if( $scrollTo ){
			$atts['data-ubermenu-scrolltarget'] = $scrollTo;
		}

		//Target ID
		$target_id = $this->getSetting( 'target_id' );
		if( $target_id ){
			$atts['id'] = $target_id;
		}

		//Target Class
		$target_class = $this->getSetting( 'target_class' );
		if( $target_class ){
			$atts['class'].= ' '.$target_class;
		}

		//Note: anchor atts used to be here

		//Title
		$title = '';
		if( $this->getSetting( 'disable_text' ) == 'off' ){

			$_title = $this->item->title;
			if( $this->get_menu_op( 'allow_shortcodes_in_labels' ) == 'on' ){
				$_title = do_shortcode( $_title );
			}

			$title .= '<span class="ubermenu-target-title ubermenu-target-text">';
			$title .= apply_filters( 'the_title', $_title, $this->item->ID );
			//$title .= $_title;
			$title .= '</span>';

		}
		else{
			//Flag items with disabled text
			$atts['class'].= ' ubermenu-item-notext';
		}


		//Description
		$description = '';
		//if( $this->getSetting( 'disable_text' ) == 'off' ){
		if( $this->item->description ){

			if( ( ( $this->depth == 0 ) && ( $this->get_menu_op( 'descriptions_top_level' ) == 'on' ) ) ||
				( ( $this->depth > 0  ) && ( $this->getSetting('item_display_calc') == 'header' ) && ( $this->get_menu_op( 'descriptions_headers' ) == 'on' ) ) ||
				( ( $this->depth > 0  ) && ( $this->getSetting('item_display_calc') == 'normal' ) && ( $this->get_menu_op( 'descriptions_normal'  ) == 'on' ) ) ||
				( ( $this->depth > 0  ) && ( $this->type == 'tab' ) && $this->get_menu_op( 'descriptions_tab' ) == 'on' ) ){

				$_desc = $this->item->description;
				if( $this->get_menu_op( 'allow_shortcodes_in_labels' ) == 'on' ){
					$_desc = do_shortcode( $_desc );
				}

				//Divider
				$divider = $this->get_menu_op( 'target_divider' );
				if( $title && $divider ) $description.= '<span class="ubermenu-target-divider">'.$divider.'</span>';

				$description.= '<span class="ubermenu-target-description ubermenu-target-text">';
				$description.= $_desc;
				$description.= '</span>';
			}
		}


		//ARIA controls
		//$title = $title . ( $this->drop_sub ? 'drop' : 'nodrop' );
		if( $this->has_children && ubermenu_op( 'aria_controls' , 'general' ) == 'on' ){
			$atts['aria-controls'] = $this->get_submenu_id();
		}


		//ShiftNav Toggle
		if( $this->getSetting( 'shiftnav_target' ) ){
			$atts['data-shiftnav-target'] = $this->getSetting( 'shiftnav_target' );
			$atts['class'].= ' shiftnav-toggle';
		}


		//Filter attributes
		$atts = apply_filters( 'ubermenu_anchor_attributes' , $atts , $this->item->type , $this->ID /* Menu Item ID */ , $this->item->object /* object type */ , $this->item->object_id /* Post ID */ );


		//Anchor Attributes
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) || $value === 0 ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );

				if( $attr === 'href' && ( $custom_url = $this->getSetting( 'custom_url' ) ) ){
					$value = do_shortcode( $custom_url );
				}

				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		//Check if we still have something to print
		if( !$title && !$description && !$image && !$icon ){
			return '';
		}


		//Build the Layout

		//Get custom pieces
		$custom_pieces = array();
		extract( apply_filters( 'ubermenu_custom_item_layout_data' , $custom_pieces , $layout , $this->ID , $this->item->object_id ) );

		//Gather all the pieces in the layout order into an array
		$layout_pieces = compact( $layout_order );

		//Output the anchor
		if( isset( $this->args->before ) ) $a .= $this->args->before;
		$a .= '<'.$tag. $attributes .'>';
		if( isset( $this->args->link_before ) ) $a .= $this->args->link_before;

		//Add pieces based on layout order
		foreach( $layout_pieces as $piece ){
			$a.= $piece;
		}


		// Badge
		$badge = $this->get_badge();
		if( $badge ){
			$a.= $badge;
		}

		//Submenu indicator
		$a.= $this->get_submenu_indicator( $disable_submenu_indicator );

		//Display mobile close toggle - do in JS?
		// if( in_array( $submenu_type , array( 'mega' , 'flyout' , 'tab-content-panel' ) ) ){
		// 	$a.= '<span class="ubermenu-sub-indicator-close"><i class="fas fa-times"></i></span>';
		// }

		if( isset( $this->args->link_after ) ) $a .= $this->args->link_after;
		$a .= '</'.$tag.'>';
		if( isset( $this->args->after ) ) $a .= $this->args->after;

		return $a;
	}

	function get_submenu_indicator( $disable_submenu_indicator ){
		$html = '';

		$submenu_type = $this->get_submenu_type();
		$display_submenu_indicators = $this->get_menu_op('display_submenu_indicators') === 'on' ? true : false;

		if( $display_submenu_indicators && !$disable_submenu_indicator && $submenu_type && in_array( $submenu_type , array( 'mega' , 'flyout' , 'tab-content-panel' ) ) ){

			if( ubermenu_op( 'use_core_svgs', 'general' ) === 'on' ){
				$html.= '<span class="ubermenu-sub-indicator">'.ubermenu_get_essential_icon( 'angle' ).'</span>';
			}
			else{
				$indicator_tag = $this->get_menu_op( 'icon_tag' );
				if( !$indicator_tag ) $indicator_tag = 'i';
				$html.= "<$indicator_tag class='ubermenu-sub-indicator fas fa-angle-down'></$indicator_tag>";
			}

		}
		return $html;
	}

	/**
	 * Get the HTML for the image attached to this menu item
	 *
	 * Any set img ID will override image src filtering
	 *
	 * @return string img HTML
	 */
	function get_image(){

		//Ignore mobile?
		if( ( $this->get_menu_op( 'disable_images_mobile' ) == 'on' ) && ubermenu_is_mobile( 'disable_images_mobile' ) ){
			return '';
		}

		//Image
		$img = apply_filters( 'ubermenu_item_image' , '' , $this );
		if( $img ) return $img;

		//Allow ID filtering
		$img_id = apply_filters( 'ubermenu_item_image_id' , $this->getSetting( 'item_image' ) , $this );

		//Allow src filtering
		$img_src = apply_filters( 'ubermenu_item_image_src' , '' , $this );
		$thumb_id = false;

		//Inherit featured image dynamically
		if( $this->getSetting( 'inherit_featured_image' ) == 'on' ){
			// Posts
			if( $this->item->type == 'post_type' ){
				$thumb_id = get_post_thumbnail_id( $this->item->object_id );
			}
			// WooCommerce Product Category Images
			else if( $this->item->type === 'taxonomy' && $this->item->object === 'product_cat' ){ //'$this->ID == 854 ){
				$thumb_id = ubermenu_get_woocommerce_product_category_image_id( $this->item->object_id );
			}
			// Dynamic Terms - WooCommerce Product Category Images
			else if( $this->item->type === 'custom' && $this->item->custom_type === 'dynamic_term_item' ){
				if( $this->term ) $thumb_id = ubermenu_get_woocommerce_product_category_image_id( $this->term->term_id );
			}
			if( $thumb_id ) $img_id = $thumb_id;
		}

		if( $img_id || $img_src ){
			$atts = array();
			$img_srcset = $img_sizes = '';

			$atts['class'] = 'ubermenu-image';

			//Determine size of image to get
			$img_size = $this->getSetting( 'image_size' );
			if( $img_size == 'inherit' ){
				$img_size = $this->get_menu_op( 'image_size' );
			}
			//echo '['.$img_size.']';
			$atts['class'].= ' ubermenu-image-size-'.$img_size;

			//If the img_id is set, get the right image src file
			if( $img_id ){
				$img_src = wp_get_attachment_image_src( $img_id , $img_size );
				if( function_exists( 'wp_get_attachment_image_srcset' ) ){
					$img_srcset = wp_get_attachment_image_srcset( $img_id , $img_size );
					$img_sizes = wp_get_attachment_image_sizes( $img_id , $img_size );
				}
			}

			//Lazy Load
			if( $this->depth > 0 && $this->get_menu_op( 'lazy_load_images' ) == 'on' ){
				$atts['class'].= ' ubermenu-image-lazyload';
				$atts['data-src'] = $img_src[0];
				if( $img_srcset ){
					$atts['data-srcset'] = $img_srcset;
					if( $img_sizes ) $atts['data-sizes'] = $img_sizes;
				}
			}
			//Normal Load
			else{
				$atts['src'] = $img_src[0];
				if( $img_srcset ){
					$atts['srcset'] = $img_srcset;
					if( $img_sizes ) $atts['sizes'] = $img_sizes;
				}
			}


			//Determine dimensions
			$img_w = '';
			$img_h = '';
			$dimensions = $this->getSetting( 'image_dimensions' );

			switch( $dimensions ){

				//Custom Dimensions use Menu Item Settings
				case 'custom':
					$img_w = $this->getSetting( 'image_width_custom' );
					$img_h = $this->getSetting( 'image_height_custom' );
					break;

				//Inherit settings from main Menu Settings
				case 'inherit':
					$img_w = $this->get_menu_op( 'image_width' );
					$img_h = $this->get_menu_op( 'image_height' );
					break;

				//Add width and height atts for natural width
				case 'natural':
					//Done below
					break;

				default:
					break;
			}

			//Apply natural dimensions if not already set
			if( $this->get_menu_op( 'image_set_dimensions' ) ){
				if( $img_w == '' && $img_h == '' ){
					$img_w = $img_src[1];
					$img_h = $img_src[2];
				}
			}

			//Add dimensions as attributes, with pixel units if missing
			if( $img_w ){
				//if( is_numeric( $img_w ) ) $img_w.='px';	//Should always be numeric only, no units
				$atts['width']	= $img_w;
			}
			if( $img_h ){
				//if( is_numeric( $img_h ) ) $img_h.='px';	//Should always be numeric only, no units
				$atts['height'] = $img_h;
			}

			//Add 'alt' & 'title'
			if( $img_id ){
				$meta = get_post_custom( $img_id );
				$alt = isset( $meta['_wp_attachment_image_alt'] ) ? $meta['_wp_attachment_image_alt'][0] : '';	//Alt field
				$title = '';

				if( $alt == '' && $this->get_menu_op( 'image_alt_autofill' ) == 'on' ){
					$title = get_the_title( $img_id );
					$alt = $title;
				}
				if( $alt ) $atts['alt'] = $alt;

				if( $this->get_menu_op( 'image_title_attribute' ) == 'on' ){
					if( $title == '' ) $title = get_the_title( $img_id );
					$atts['title'] = $title;
				}
			}



			//Build attributes string
			$atts = apply_filters( 'ubermenu_item_image_attributes' , $atts , $this );
			$attributes = '';
			foreach( $atts as $name => $val ){
				$attributes.= $name . '="'. esc_attr( $val ) .'" ';
			}

			$img = "<img $attributes />";
			//$img = "<span class='ubermenu-image'><img $attributes /></span>";
		}
		return $img;
	}

	function get_custom_content(){

		$html = '';

		$custom_content = $this->getSetting( 'custom_content' );
		if( $custom_content ){

			if( ubermenu_op( 'disable_custom_content' , 'general' ) === 'on' ){
				return "<!-- Custom Content Areas currently disabled $this->ID -->";
			}

			//Pad the custom content wrapper?
			$pad_custom_content = $this->getSetting( 'pad_custom_content' ) == 'on' ? ' ubermenu-custom-content-padded' : '' ;

			//Add a custom class to the custom content wrapper?
			$custom_class = $this->getSetting( 'custom_content_class' );
			if( $custom_class ) $custom_class = ' ' . sanitize_html_class( $custom_class );

			$html.= '<div class="ubermenu-content-block ubermenu-custom-content'.$pad_custom_content.$custom_class.'">';
			$html.= do_shortcode( $custom_content );
			$html.= '</div>';
		}

		return $html;

	}

	function get_badge(){
		$html = '';

		$badge_content = apply_filters( 'ubermenu_badge_content', $this->getSetting( 'badge_content' ), $this->ID, $this->item );

		if( $badge_content ){

			$atts = array(
			 'class' => ubermenu_classNames( array(
					'ubermenu-badge' => true,
					'ubermenu-badge-'.$this->getSetting( 'badge_position' ) => true,
				))
			);

			$html.= '<span '.ubermenu_html_atts( $atts ) .'>'.do_shortcode( $badge_content ).'</span>';
		}

		return $html;
	}

	function get_widget_area(){

		$html = '';

		$widget_area_id = $this->getSetting( 'widget_area' );

		if( $this->getSetting( 'auto_widget_area' ) ){

			if( ubermenu_op( 'disable_widget_areas' , 'general' ) === 'on' ){
				return "<!-- Widget Areas currently disabled $this->ID -->";
			}

			$custom_area_id = 'umitem_'.$this->ID;
			if( is_active_sidebar( $custom_area_id ) ){
				$widget_area_id = $custom_area_id;
			}
			else{
				$notice = __( 'The widget area is empty.' , 'ubermenu' );
				$notice.= ' <a target="_blank" href="'.admin_url( 'widgets.php' ).'">'.__( 'Assign a widget' , 'ubermenu' ).'</a>';
				global $wp_registered_sidebars;
				if( isset( $wp_registered_sidebars[$custom_area_id] ) ){
					$sidebar = $wp_registered_sidebars[$custom_area_id];
					$notice.= ' to <strong>'.$sidebar['name'].'</strong>';
				}

				$html.= ubermenu_admin_notice( $notice , false );
				return $html;
			}
		}

		//If this is a top level widget and that setting is not enabled, show an admin message
		if( $this->depth == 0 && $widget_area_id && ubermenu_op( 'allow_top_level_widgets' , 'general' ) != 'on' ){

			$msg = '<strong>[Menu Item: '. $this->item->title . ']</strong> '. __( 'You have assigned a widget area to a top level menu item.  If you want the widget to appear in a submenu, please attach it to a child menu item.  If you want the widget to appear in the menu bar (always visible), please enable the setting in the UberMenu Control Panel > General Settings > Widgets > Allow Top Level Widgets', 'ubermenu' );
			ubermenu_admin_notice( $msg , true );	//Deliberately printed BEFORE the menu rather than within it because the message is so long.
			//$html.= ubermenu_admin_notice( $msg , true );
			return $html;
		}

		if( $widget_area_id && is_active_sidebar( $widget_area_id ) ){

			global $wp_registered_sidebars;
			global $wp_registered_widgets;

			//global $_wp_sidebars_widgets;
			$sidebars_widgets = wp_get_sidebars_widgets();

			$num_widgets = count( $sidebars_widgets[$widget_area_id] );

			//Evenly divided
			$cols = 'ubermenu-column-1-'.$num_widgets;
			if( $num_widgets == 1 ){
				$cols = 'ubermenu-column-full';
			}


			//If col number is set
			$widget_area_columns = $this->getSetting( 'widget_area_columns' );
			if( is_numeric( $widget_area_columns ) ){
				if( $widget_area_columns == 1 ){
					$cols = 'ubermenu-column-full';
				}
				else $cols = 'ubermenu-column-1-'.$widget_area_columns;
			}

			foreach( $sidebars_widgets[$widget_area_id] as $widget_id ){
				if( isset( $wp_registered_widgets[$widget_id] ) ){
					$wp_registered_widgets[$widget_id]['classname'].=' '.$cols;
				}
			}


			//ob_flush();
			ob_start();
			dynamic_sidebar( $widget_area_id );
			$widget_area = ob_get_contents();
			//$widget_area = ob_get_clean(); //ob_get_contents();
			ob_end_clean();

			$html.= '<ul class="ubermenu-content-block ubermenu-widget-area ubermenu-autoclear">'; //ubermenu-row
			$html.= $widget_area;
			$html.= '</ul>';
		}
		//No widgets
		else if( $widget_area_id ){

			global $wp_registered_sidebars;
			$notice = __( 'The widget area is empty.  ' , 'ubermenu' );
			$notice.= '<a target="_blank" href="'.admin_url( 'widgets.php' ).'">'.__( 'Assign a widget' , 'ubermenu' ).'</a>';
			if( isset( $wp_registered_sidebars[$widget_area_id] ) ){
				$sidebar = $wp_registered_sidebars[$widget_area_id];
				$notice.= ' to <strong>'.$sidebar['name'].'</strong>';
			}
			$html.= ubermenu_admin_notice( $notice , false );

			return $html;
		}
		else{
			//Nothing assigned - fine if a normal menu item, but if this is a Widget Area menu item, stop the presses.
			if( $this->type == 'widget_area' ){
				$notice = __( 'Please enter a name for your Custom Widget Area, or assign a Reusable Widget Area to this menu item.' , 'ubermenu' );
				$notice.= ' <strong>Item ID: '.$this->ID.' '.$this->item->title.'</strong>';
				$html.= ubermenu_admin_notice( $notice , false );
			}
		}

		return $html;

	}



}



/* A Horizontal Rule */
class UberMenuItemDivider extends UberMenuItem{
	function get_start_el(){
		return '<li class="ubermenu-divider"><hr/>';
	}
}
