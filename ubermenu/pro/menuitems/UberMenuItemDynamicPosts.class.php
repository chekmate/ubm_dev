<?php

/** POSTS **/

class UberMenuItemDynamicPosts extends UberMenuItemDynamic{
	protected $type = 'dynamic_posts';
	protected $alter_structure = true;
	protected $post_count;
	protected $notice;
	protected $post_args;

	function init(){
		$this->source_id = $this->item->db_id; //$this->item->object_id;
	}

	function alter( &$children ){

		//$profiler = new UMProfiler();

//echo '<h3>dynamic_posts ' . $this->ID. '</h3>';

		//Dynamic Items are only good on submenus
		if( UBERMENU_ALLOW_TOP_LEVEL_DYNAMIC_ITEMS || $this->depth > 0 ){

			//Find the children of this item and remove them, but keep a
			//reference.  They will later be appended to the generated terms instead

			//Set the reference index during the first pass
			$reference_index = $this->create_reference( $this->source_id , $children );

			//Pull from Cache if possible
			$transient_key = $this->get_transient_key( 'dynT_' );
			//echo $transient_key.'<br/>';

			$posts = false;
			//$posts = array();
			//$posts = get_transient( $transient_key );

			if( $posts === false ){

				//Get the Post Query Settings
				$post_args = array(
					'offset'	=> 0,
					'no_found_rows' => true,
				);

				//WPML
				if( defined('ICL_SITEPRESS_VERSION') ){
					$post_args['suppress_filters'] = false;
				}

				$settings_map = array(
					'post_type'			=> 'dp_post_type',
					'orderby'			=> 'dp_orderby',
					'order'				=> 'dp_order',
					'posts_per_page'	=> 'dp_posts_per_page',
					'exclude'			=> 'dp_exclude',
					//'author'			=> 'dp_author',
					//'category'			=> 'dp_category',
					//'tag'				=> 'dp_tag',

				);
				//Setup terms args based on settings
				foreach( $settings_map as $d_arg => $s_key ){
					$v = $this->getSetting( $s_key ); //isset( $settings[$s_key] ) ? $settings[$s_key] : $defaults[$s_key];

					if( $v === 'on' ) $v = true;
					else if( $v === 'off' ) $v = false;

					$post_args[$d_arg] = $v;
				}


				//////////////////
				//Category
				//////////////////

				$category = $this->getSetting( 'dp_category' );

				//Inherit parent Category
				//Needs to work with both dynamic terms as well as normal taxonomy items
				if( $category == -1 ){
					$post_args['cat'] = $this->walker->find_parent_term();
				}
				else if( $category ) $post_args['cat'] = $category;


				//////////////////
				//Tag
				//////////////////

				$tag = $this->getSetting( 'dp_tag' );

				//Inherit parent Tag
				//Needs to work with both dynamic terms as well as normal taxonomy items
				if( $tag == -1 ){

					//NOTE: In the alter() function, the current_item() is still our parent

					//$pitem = $this->walker->current_item()->item;
					$pitem = $this->walker->current_item();

					//Pass through Columns and Rows
					if( $pitem->getType() == 'column' || $pitem->getType() == 'row' ){
						$pitem = $this->walker->parent_item();
					}

					//Dynamic Term Parent
					if( $pitem->item->custom_type == 'dynamic_term_item' ){
						if( $pitem->item->taxonomy_slug == 'post_tag' ){
							$post_args['tag_id'] = $pitem->item->term_id;
						}
					}
					//Tag Item Parent
					else if( $pitem->item->type == 'taxonomy' ){
						if( $pitem->item->object == 'post_tag' ){
							$post_args['tag_id'] = $pitem->item->object_id;
						}
					}
				}
				else $post_args['tag_id'] = $tag;


				////////////////////
				//CUSTOM TAXONOMIES
				////////////////////

				$taxonomies = get_taxonomies( array(
					'public'	=> true,
					'_builtin'	=> false,
					) , 'objects' );

				$taxonomies = apply_filters( 'ubermenu_dp_custom_taxonomies' , $taxonomies );

				foreach( $taxonomies as $tax_id => $tax ){

					$term_id = $this->getSetting( 'dp_' . $tax_id );

					//Use Parent ID
					if( $term_id == -1 ){

						//$pitem = $this->walker->current_item()->item;
						$pitem = $this->walker->current_item();

						//Pass through Columns and Rows
						if( $pitem->getType() == 'column' || $pitem->getType() == 'row' ){
							$pitem = $this->walker->parent_item();
						}

						//Dynamic Term Parent
						if( $pitem->item->custom_type == 'dynamic_term_item' ){
							if( $pitem->item->taxonomy_slug == $tax_id ){
								$term_id = $pitem->item->term_id;
							}
						}
						//Custom Taxonomy Item Parent
						else if( $pitem->item->type == 'taxonomy' ){
							if( $pitem->item->object == $tax_id ){
								$term_id = $pitem->item->object_id;
							}
						}
					}

					//If we found a term, create a tax query
					if( $term_id > 0 ){
						if( !isset( $post_args['tax_query'] ) || !is_array( $post_args['tax_query'] ) ){
							$post_args['tax_query'] = array();
						}
						$post_args['tax_query'][] = array(
								'taxonomy'	=> $tax_id,
								'field'		=> 'term_id',
								'terms'		=> $term_id,
						);
					}


				}





				//////////////////
				//Post Parent
				//////////////////

				$post_parent = $this->getSetting( 'dp_post_parent' );

				if( $post_parent == -1 ){

					//find_parent_post

					//$pitem = $this->walker->current_item()->item;
					$pitem = $this->walker->current_item();
					if( $pitem->getType() == 'column' || $pitem->getType() == 'row' ){
						$pitem = $this->walker->parent_item();
					}

					//Dynamic Post Parent
					if( $pitem->item->custom_type == 'dynamic_post_item' ){
						$post_args['post_parent'] = $pitem->item->dynamic_post_id;
					}
					//Post Item Parent
					else if( $pitem->item->type == 'post_type' ){

						//if( $pitem->object ==  /*'page'*/ ){	//Allow to work with custom post types
							$post_args['post_parent'] = $pitem->item->object_id;
						//}
					}

					//up( $pitem );
				}
				else $post_args['post_parent'] = $post_parent;


				///////////////////////////////
				//Author
				///////////////////////////////
				$authors = $this->getSetting( 'dp_author' );
				if( is_array( $authors ) ){
					if( count( $authors ) == 1 ){
						$post_args['author'] = $authors[0];	//single author, use 'author' parameter
					}
					else{
						$post_args['author__in'] = $authors; //multiple authors, pass array to 'author__in' parameter
					}
				}



				//Allow filtering
				$post_args = apply_filters( 'ubermenu_dynamic_posts_args' , $post_args , $this->ID );
				$this->post_args = $post_args;


				///////////////////////////////
				//Retrieve the posts
				///////////////////////////////

	//$post_args = array( 'post_type' => 'post' );
				$posts = get_posts( $post_args );

				//Cache Results - Set Transient
				//set_transient( $transient_key , $posts , 100 );
			}

//$profiler->output( $this->get_item()->title , 500 );

			$this->post_count = count( $posts );

			if( empty( $posts ) && $this->getSetting('dp_ignore_empty_results') !== 'on' ){
				$this->notice = '<strong>'.$this->item->title.' ('.$this->ID.')</strong>: '.__( 'No results found' , 'ubermenu' );

				$this->notice.= '<br/><em>'.__( 'Query Arguments' , 'ubermenu' ).':</em>';
				$this->notice.= '<pre>';
				$this->notice.= print_r( $post_args , true );
				$this->notice.= '</pre>';
			}

			$post_children = array();


			//Autocolumns setup
			$autocolumns = $this->getSetting( 'dp_autocolumns' );
			$post_count = $this->post_count;
			$items_per_column;
			$column_id;
			$column_children = array();

			$column_map = array();

			if( $autocolumns && $autocolumns != 'disabled' ){

				$remainder = $post_count % $autocolumns;
				$items_per_column = ceil( $post_count / $autocolumns );

				if( $remainder ){
					// $items_per_column = floor( $post_count / $autocolumns );
					// $items_per_column++;
					for( $_k = 0; $_k < $autocolumns; $_k++ ){
						$column_map[$_k] = $items_per_column;
						if( $_k+1 == $remainder ){
							$items_per_column--;
						}
					}
				}
				else{
					for( $_k = 0; $_k < $autocolumns; $_k++ ){
						$column_map[$_k] = $items_per_column;
					}
				}
			}
//echo "$post_count / $autocolumns = $items_per_column";

			//Loop through each term, get its info and create a Dummy Item to
			//stash in the children array.  The $_i keeps track of the index as
			//this is how child Dynamic Terms can map back
			$_i = 0;
			$_col = 0;
			foreach( $posts as $p ){

				//Find the URL for this Post
				$url = get_permalink( $p->ID );

				$post_item_id = $this->ID . '-post-' . $p->ID;

				$post_item =
					new UberMenu_dynamic_post_item(
							$post_item_id,
							$this->item,
							array(
								'dynamic_post_id' => $p->ID,
								//'attr_title' => $term->name,
								'url'		=> $url,
							),
							array( 'dynamic-post', 'item-'.$this->ID ),	//Add DynamicPosts Item ID to classes
							$this->get_settings()
						);

				//If we're using auto columns, add a new column every X items
				if( $autocolumns > 0 ){

					//Start a new column if this is the first item, or if we've hit zero in the previous row
					if( $_i == 0 || ( isset( $column_map[$_col] ) && $column_map[$_col] == 0 ) ){

						//move on to the next column
						if( $column_map[$_col] == 0 ){
							$_col++;
							//Record items in previous column
							$children[$column_id] = $column_children;
						}

						$column_children = array();
						$column_id = $this->ID . '-col-' . $_col;
						$post_children[] = new UberMenu_dummy_item(
							$column_id ,
							'column' ,
							'Auto Column' ,
							$this->ID,
							array( 'columns' => '1-'.$autocolumns ),
							array( 'ubermenu-autocolumn' )
						);
					}

					//Add this post to the current column
					$column_children[] = $post_item;

					//Decrement the number remaining in this column
					$column_map[$_col]--;
				}
				else{
					$post_children[] =	$post_item;
				}



				//Find the children of this item and remove them, but keep a
				//reference.  They will later be appended to the generated terms instead

				$mykids = false;
				if( isset( $children[$reference_index] ) ){

					$mykids = $children[$reference_index];
					$children[$post_item_id] = $mykids;

				}

				// if( $autocolumns > 0 ){
				// 	if( ( $_i + 1 ) % $items_per_column == 0 ){
				// 		$children[$column_id] = $column_children;
				// 	}
				// }


				$_i++;

			}

			//If we had an incomplete row (uneven division), tack on the remainder
			if( is_array( $column_children ) && !empty( $column_children ) ){
				$children[$column_id] = $column_children;
			}

 			$children[$this->ID] = $post_children;

		}

	}


	function get_start_el(){
		//$this->setupAutoChild();
		//$this->settingsvar 'submenu_type_calc'] = 'toggles-group';

		//Setup the submenu type
		$submenu_type = 'mega';
		if( $this->depth > 0 ){
			//echo $this->walker->parent_item()->getType();
			$submenu_type = $this->walker->parent_item()->getSetting( 'submenu_type_calc' );
			//echo $submenu_type.'<br/>';
		}
		$this->settings['submenu_type_calc'] = $submenu_type; // 'dynamic-terms';

		$html = "<!-- begin Dynamic Posts: ".$this->item->title." ID[$this->ID] count[$this->post_count] ";
		if( $this->getSetting('dp_ignore_empty_results') === 'on' && $this->post_count === 0 ) $html.= "[ignore empty]";
		$html.= " -->";

		if( $this->post_count == 0 ){
			$empty_results_message = $this->getSetting( 'empty_results_message' );
			if( $empty_results_message ){
				$html.= '<li class="ubermenu-item ubermenu-item-normal"><span class="ubermenu-target ubermenu-target-empty-posts">'.
								$empty_results_message .
								'</span></li>';
			}
		}

		if( $this->notice ){
			$html.= '<li class="ubermenu-item">'.ubermenu_admin_notice( $this->notice , false ).'</li>';
		}

		return $html;
	}
	function get_end_el(){
		//$this->resetAutoChild();

		$html = '';

		//More Link
		$view_all_taxonomy = $this->getSetting( 'dp_view_all' );
		if( $view_all_taxonomy && $view_all_taxonomy != 'none' ){

			$term = $this->getSetting( 'dp_'.$view_all_taxonomy );

			if( is_numeric( $term ) ){
				if( $term == -1 ){
					$term_id = $this->walker->find_parent_term();
				}
				else if( $term ) $term_id = $term;

				if( $term_id ){
					//$term_url = get_term_link( $term_id , $view_all_taxonomy );
					$term_url = get_term_link( intval( $term_id ) , $view_all_taxonomy );	//Some servers misinterpret as non-int?

					$icon_tag = $this->get_menu_op( 'icon_tag' );
					if( !$icon_tag ) $icon_tag = 'i';
					$view_all_link_text = 'View all <'.$icon_tag.' class="fas fa-angle-double-right"></'.$icon_tag.'>';
					if( $this->getSetting( 'dp_view_all_text' ) != '' ){
						$view_all_link_text_setting = $this->getSetting( 'dp_view_all_text' );
						$view_all_link_text = $view_all_link_text_setting;
					}
					$html.= '<li class="ubermenu-item ubermenu-item-normal ubermenu-item-view-all"><a href="'.$term_url.'" class="ubermenu-target">'.$view_all_link_text.'</a></li>';
				}
			}
		}

		//Allow filtering
		$html = apply_filters( 'ubermenu_dp_after' , $html , $this->ID , $this->post_args );

		$html.= "<!-- end Dynamic Posts: ".$this->item->title." ID[$this->ID] -->";
		return $html;
	}

}

class UberMenuItemDynamicPost extends UberMenuItemDefault{

	protected $type = 'dynamic_post';
	//protected $alter_structure = true;
	protected $toggle_group;
	protected $toggle_content_panel;

	var $post;


	function init(){
		//Act like one level up, since we've been pushed down 1
		//by the Dynamic Terms Item
		$this->depth--;

		//Set the Post for reference
		$this->post = get_post( $this->item->dynamic_post_id );
//up( $this->post );
//echo $this->post->post_title . '<br/>';

		//Set the Image to the featured image.  If not set, this can be overridden by
		//image set on Dynamic Posts Menu Item
		if( $this->getSetting( 'inherit_featured_image' ) !== 'off' ){
			$image_id = get_post_thumbnail_id( $this->post->ID );
			if( $image_id ) $this->settings['item_image'] = $image_id;
		}



		//If this Dynamic Item is a child of a "Tabs" item, it becomes a toggle
		if( $this->walker->parent_item() ){

			if( $this->walker->parent_item()->getType() == 'tabs' ){

				$this->is_tab = true;

				//Ask the tab to set it up
				$this->walker->parent_item()->setup_tab( $this );


				$this->item_classes[] = 'ubermenu-tab';
				$this->item_classes[] = 'ubermenu-has-submenu-drop';
				//$this->item_atts['data-ubermenu-toggle-target'] = '#ubermenu-panel-'.$this->ID;

				$cols = $this->getSetting( 'columns' );
				// if( $this->depth > 0 && $cols == 'auto' ){
				// 	$cols = $this->walker->parent_item()->getSetting( 'submenu_column_default' );
				// }

				//Change specific for Left/Right Tab layouts, so that by default we're full width
				if( $cols == 'auto' ){
					$tab_layout = $this->walker->parent_item()->getSetting( 'tab_layout' );
					if( $tab_layout == 'right' || $tab_layout == 'left' ){
						//$cols = 'full';

						$this->settings['columns'] = 'full';
					}
				}
			}
		}

		//If item is current
		global $wp_query;
		$queried_object_id = (int) $wp_query->queried_object_id;
		if( $this->item->dynamic_post_id == $queried_object_id && $wp_query->is_singular ){
			$this->item_classes[] = 'ubermenu-current-menu-item';	//hasn't been prefixed yet
		}

	}


	/**
	 * Get the Anchor and its contents
	 * @param  array $atts An array of attributes to add to the anchor
	 * @return string       The HTML for the anchor
	 */
	function get_anchor( $atts ){

		$p = $this->post;

		$a = '';
		$tag = 'a';

		//The parent item is the Dynamic Post item, so the grandparent item is what we seek
		$parent_item = false;
		if( $this->depth >= 2 ) $parent_item = $this->walker->grandparent_item();

		//Image
		$image = $this->get_image();
		if( $image ) $atts['class'] .= ' ubermenu-target-with-image';


		//Icon
		$icon = $this->getSetting( 'icon' );
		if( $icon ){
			$atts['class'] .= ' ubermenu-target-with-icon';

			$icon_tag = $this->get_menu_op( 'icon_tag' );
			if( !$icon_tag ) $icon_tag = 'i';
			$icon = '<'.$icon_tag.' class="ubermenu-icon '.$icon.'"></'.$icon_tag.'>';
		}


		//Layout
		$layout = $this->getSetting( 'item_layout' );
		$atts['class'].= ' ubermenu-item-layout-'.$layout;



		//Content Align
		$content_align = $this->getSetting( 'content_alignment' );
		//Check inherit from parent
		if( $content_align == 'default' ){

			if( $this->depth >= 2 ){
				$submenu_item_content_alignment = 'default';

				//If this is within a menu segment and we need to inherit back further, pass true
				if( $parent_item->getType() == 'menu_segment' ){
					$submenu_item_content_alignment = $parent_item->getSetting( 'submenu_item_content_alignment' , true );
				}
				else{
					$submenu_item_content_alignment = $parent_item->getSetting( 'submenu_item_content_alignment' );
				}

				if( $submenu_item_content_alignment !== 'default' ){
					$content_align = $submenu_item_content_alignment;
				}
			}
		}
		//If a content alignment has been set on this item or the parent, set the class
		if( $content_align != 'default' ){
			$atts['class'].= ' ubermenu-content-align-'.$content_align;
		}


		//ITEM LAYOUT
		if( $layout == 'default' ){

			//If the layout for the individual item is set to default, and this is a child item, check the parent item's setting
			if( $this->depth >= 2 ){
				$submenu_item_layout = 'default';
				if( $parent_item->getType() == 'menu_segment' ){
					$submenu_item_layout = $parent_item->getSetting( 'submenu_item_layout' , true );
				}
				else{
					$submenu_item_layout = $parent_item->getSetting( 'submenu_item_layout' );
				}

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

		//Disable Submenu Indicator
		$disable_submenu_indicator = false;
		if( $this->getSetting( 'disable_submenu_indicator' ) == 'on' ){
			$atts['class'].= ' ubermenu-noindicator';
		}

		//Global Submenu Indicators
		$display_submenu_indicators = $this->get_menu_op('display_submenu_indicators') === 'on' ? true : false;


		//Disabled Link (change tag)
		$disable_link = false;
		if( $this->getSetting( 'disable_link' ) == 'on' ){
			$tag = 'span';
			$disable_link = true;
			unset( $atts['href'] );
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


		//Title
		$title = '';
		if( $this->getSetting( 'disable_text' ) == 'off' ){

			$title = apply_filters( 'the_title', $p->post_title, $p->ID );
			$title = apply_filters( 'ubermenu_dp_title' , $title , $this->source_id , $p->ID );

			$title = '<span class="ubermenu-target-title ubermenu-target-text">' . $title . '</span>';
		}


		//Description
		$description = '';
		//if( $this->getSetting( 'disable_text' ) == 'off' ){
		if( $this->item->description ){
			$description.= '<span class="ubermenu-target-description ubermenu-target-text">';
			$description.= $this->item->description;
			$description.= '</span>';
		}

		//ARIA controls
		if( ubermenu_op( 'aria_controls' , 'general' ) == 'on' ){
			$atts['aria-controls'] = $this->get_submenu_id();
		}

		//Filter attributes
		$atts = apply_filters( 'ubermenu_anchor_attributes' , $atts , 'ubermenu-dynamic-post' , $this->source_id /* Menu Item ID */ , 'post' /* object type */ , $p->ID /* Post ID */ );


		//Anchor Attributes
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}


		//Dynamic Subcontent
		//Treat as description
		//$subcontent = '';
		$subcontent_type = $this->getSetting( 'dp_subcontent' );
		if( $subcontent_type != 'none' ){

			switch( $subcontent_type ){

				case 'excerpt':
					$description.= '<span class="ubermenu-target-description ubermenu-target-text">';
					$description.= $p->post_excerpt;
					$description.= '</span>';
					break;

				case 'date' :
					$description.= '<span class="ubermenu-target-description ubermenu-target-text">';
					$description.= mysql2date( get_option( 'date_format' ), $p->post_date );
					$description.= '</span>';
					break;

				case 'author' :
					$description.= '<span class="ubermenu-target-description ubermenu-target-text">';
					$description.= get_the_author_meta( 'display_name' , $p->post_author );
					$description.= '</span>';
					break;

				case 'custom':
					$description = apply_filters( 'ubermenu_dp_subcontent' , $description , $p , $this->ID );
					break;

				default:
					break;

			}

		}
		//$description.= $subcontent;	//Treat as description



		//Check if we still have something to print
		if( !$title && !$description && !$image && !$icon ){
			return '';
		}


		//Build the Layout

		//Get custom pieces
		$custom_pieces = array();
		extract( apply_filters( 'ubermenu_custom_item_layout_data' , $custom_pieces , $layout , $this->ID , $p->ID ) );

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
		// $submenu_type = $this->get_submenu_type();
		// if( $display_submenu_indicators && !$disable_submenu_indicator && $submenu_type && in_array( $submenu_type , array( 'mega' , 'flyout' , 'tab-content-panel' ) ) ){
		// 	$indicator_tag = $this->get_menu_op( 'icon_tag' );
		// 	if( !$indicator_tag ) $indicator_tag = 'i';
		// 	$a.= "<$indicator_tag class='ubermenu-sub-indicator fas fa-angle-down'></$indicator_tag>";
		// }

		if( isset( $this->args->link_after ) ) $a .= $this->args->link_after;
		$a .= '</'.$tag.'>';
		if( isset( $this->args->after ) ) $a .= $this->args->after;

		return $a;
	}

	function setup_trigger(){

		$trigger = $this->getSetting( 'item_trigger' );

		if( $this->is_tab ){
			//If auto, get trigger from Tabs Group
			if( !$trigger || $trigger == 'auto' ){
				$trigger = $this->walker->grandparent_item()->getSetting( 'tabs_trigger' );
			}
		}

		if( $trigger && $trigger != 'auto' ){
			$this->item_atts['data-ubermenu-trigger'] = $trigger;
		}
	}


	function get_settings(){
		if( isset( $this->settings ) ) return $this->settings;

		$settings = $this->item->parent_settings;
		$settings = apply_filters( 'ubermenu_item_settings' , $settings , $this->ID );

		return $settings;
	}

}
