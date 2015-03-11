<?php
// 
// dev.xiligroup.com - msc - 2014-11-16 - first test with 2015 0.1
// dev.xiligroup.com - msc - 2014-12-16 - test with 2015 1.0 - WP 4.1-RC1
// 1.0.1 - msc - 2015-03-11 - add new filter for all menu descriptions

define( 'TWENTYFIFTEEN_XILI_VER', '1.0.1'); // as parent style.css

// main initialisation functions and version testing and message

function twentyfifteen_xilidev_setup () {

	$theme_domain = 'twentyfifteen';

	$minimum_xl_version = '2.15.3'; // >

	$xl_required_version = false;

	load_theme_textdomain( $theme_domain, get_stylesheet_directory() . '/langs' ); // now use .mo of child

	if ( class_exists('xili_language') ) { // if temporary disabled

		$xl_required_version = version_compare ( XILILANGUAGE_VER, $minimum_xl_version, '>' );

		global $xili_language;

		$xili_language_includes_folder = $xili_language->plugin_path .'xili-includes';

		$xili_functionsfolder = get_stylesheet_directory() . '/functions-xili' ;

		if ( file_exists( $xili_functionsfolder . '/multilingual-classes.php') ) {
			require_once ( $xili_functionsfolder . '/multilingual-classes.php' ); // xili-options created by developers in child theme in priority

		} elseif ( file_exists( $xili_language_includes_folder . '/theme-multilingual-classes.php') ) {
			require_once ( $xili_language_includes_folder . '/theme-multilingual-classes.php' ); // ref xili-options based in plugin
		}

		if ( file_exists( $xili_functionsfolder . '/multilingual-functions.php') ) {
			require_once ( $xili_functionsfolder . '/multilingual-functions.php' );
		}

		global $xili_language_theme_options ; // used on both side
		// Args dedicated to this theme named Twenty Fifteen
		$xili_args = array (
			'customize_clone_widget_containers' => true, // comment or set to true to clone widget containers
			'settings_name' => 'xili_2015_theme_options', // name of array saved in options table
			'theme_name' => 'Twenty Fifteen',
			'theme_domain' => $theme_domain,
			'child_version' => TWENTYFIFTEEN_XILI_VER
		);

		if ( is_admin() ) {

		// Admin args dedicaced to this theme

			$xili_admin_args = array_merge ( $xili_args, array (
				'customize_adds' => true, // add settings in customize page
				'customize_addmenu' => false, // done by 2013
				'capability' => 'edit_theme_options',
				'authoring_options_admin' => false
			) );

			if ( class_exists ( 'xili_language_theme_options_admin' ) ) {
				$xili_language_theme_options = new xili_language_theme_options_admin ( $xili_admin_args );
				$class_ok = true ;
			} else {
				$class_ok = false ;
			}


		} else { // visitors side - frontend

			if ( class_exists ( 'xili_language_theme_options' ) ) {
				$xili_language_theme_options = new xili_language_theme_options ( $xili_args );
				$class_ok = true ;
			} else {
				$class_ok = false ;
			}
		}
		// new ways to add parameters in authoring propagation
		add_theme_support('xiliml-authoring-rules', array (
			'post_content' => array('default' => '1',
				'data' => 'post',
				'hidden' => '',
				'name' => 'Post Content',
				/* translators: added in child functions by xili */
				'description' => __('Will copy content in the future translated post', 'twentyfifteen')
		),
			'post_parent' => array('default' => '1',
				'data' => 'post',
				'name' => 'Post Parent',
				'hidden' => '1',
				/* translators: added in child functions by xili */
				'description' => __('Will copy translated parent id (if original has parent and translated parent)!', 'twentyfifteen')
		))
		); //

		if ( $class_ok ) {
			$xili_theme_options = get_theme_xili_options() ;
			// to collect checked value in xili-options of theme
			if ( file_exists( $xili_functionsfolder . '/multilingual-permalinks.php') && $xili_language->is_permalink && isset( $xili_theme_options['perma_ok'] ) && $xili_theme_options['perma_ok']) {
				require_once ( $xili_functionsfolder . '/multilingual-permalinks.php' ); // require subscribing premium services
			}
			if ( $xl_required_version ) { // msg choice is inside class
				$msg = $xili_language_theme_options->child_installation_msg( $xl_required_version, $minimum_xl_version, $class_ok );
			} else {
				$msg = '
				<div class="error">'.
					/* translators: added in child functions by xili */
					'<p>' . sprintf ( __('The %1$s child theme requires xili_language version more recent than %2$s installed', 'twentyfifteen' ), get_option( 'current_theme' ), $minimum_xl_version ).'</p>
				</div>';

			}
		} else {

			$msg = '
			<div class="error">'.
				/* translators: added in child functions by xili */
				'<p>' . sprintf ( __('The %s child theme requires xili_language_theme_options class installed and activated', 'twentyfifteen' ), get_option( 'current_theme' ) ).'</p>
			</div>';

		}

	} else {

		$msg = '
		<div class="error">'.
			/* translators: added in child functions by xili */
			'<p>' . sprintf ( __('The %s child theme requires xili-language plugin installed and activated', 'twentyfifteen' ), get_option( 'current_theme' ) ).'</p>
		</div>';

	}

	// errors and installation informations
	// after activation and in themes list
	if ( isset( $_GET['activated'] ) || ( ! isset( $_GET['activated'] ) && ( ! $xl_required_version || ! $class_ok ) ) )
		add_action( 'admin_notices', $c = create_function( '', 'echo "' . addcslashes( $msg, '"' ) . '";' ) );

	// end errors...
	add_filter( 'pre_option_link_manager_enabled', '__return_true' ); // comment this line if you don't want links/bookmarks features

	add_action( 'widgets_init', 'twentyfifteen_xili_add_widgets' );

	remove_filter( 'walker_nav_menu_start_el', 'twentyfifteen_nav_description');

}
add_action( 'after_setup_theme', 'twentyfifteen_xilidev_setup', 11 ); // called after parent

function twentyfifteen_xili_add_widgets () {
	register_widget( 'xili_Widget_Categories' ); // in xili-language-widgets.php since 2.16.3
}


/**
 * This function is an example to customize flags with flags incorporated inside another child theme folder.
 * by default, xili-language plugin uses those in flags sub-folder overridden by those in medial library
 *
 */
function twentyfifteen_xili_flags_customize () {
	remove_theme_support ( 'custom_xili_flag' );
	$args = array(
		'en_us'	=> array(
			'path' => '%2$s/images/flags_2/en_us.png',
			'height'				=> 24,
			'width'					=> 24
			),
		'es_es'	=> array(
			'path' => '%2$s/images/flags_2/es_es.png',
			'height'				=> 24,
			'width'					=> 24
			),
		'fr_fr'	=> array(
			'path' => '%2$s/images/flags_2/fr_fr.png',
			'height'				=> 24,
			'width'					=> 24
			),
		'de_de'	=> array(
			'path' => '%2$s/images/flags_2/de_de.png', //wp-content/themes/twentyfifteen-xili/images/flags_2/de_de.png
			'height'				=> 24,
			'width'					=> 24
			),
	);
	add_theme_support ( 'custom_xili_flag', $args );
}
add_action( 'after_setup_theme', 'twentyfifteen_xili_flags_customize', 13 );

function twentyfifteen_get_default_xili_flag_options ( $default_array, $parent_theme ) {
	// because here above sizes have changed, need overhide default values

	// to recover these values: don not forget to reset to default values in xili flag options dashboard screen

	// $default_array['css_ul_nav_menu'] = 'ul.nav-menu';
		$default_array['css_li_hover'] = 'background-color:#f5f5f5; background:rgba(255,255,255,0.3);' ;
		$default_array['css_li_a'] = 'text-indent:30px; width:100%; background:transparent no-repeat 0 9px; margin:0;' ;
		$default_array['css_li_a_hover'] = 'background: no-repeat 0 10px !important;' ;
	return $default_array;
}
add_filter( 'get_default_xili_flag_options', 'twentyfifteen_get_default_xili_flag_options', 10, 2);



function twentyfifteen_xili_setup_custom_header () {

	// %2$s = in child
	// http://michel-i5-imac.local:8888/wp_svn41/wp-content/themes/twentyfifteen-xili/images/headers/header-en-thumbnail.jpg

	register_default_headers( array(
		'xili2015' => array(

			'url'			=> '%2$s/images/headers/header-en.jpg',
			'thumbnail_url' => '%2$s/images/headers/header-en-thumbnail.jpg',
			/* translators: added in child functions by xili */
			'description'	=> _x( '2015 by xili', 'header image description', 'twentyfifteen' )
			),
		'xili2015-2' => array(

			'url'			=> '%2$s/images/headers/header-fr.jpg',
			'thumbnail_url' => '%2$s/images/headers/header-fr-thumbnail.jpg',
			/* translators: added in child functions by xili */
			'description'	=> _x( '2015.2 by xili', 'header image description', 'twentyfifteen' )
			)
		)
	);

	$args = array(
		// Text color and image (empty to use none).
		'default-text-color'	=> '000000', // diff of parent
		'default-image'			=> '%2$s/images/headers/header-en.jpg',

		// Set height and width, with a maximum value for the width.
		'height'				=> 1300,
		'width'					=> 954,

		// Callbacks for styling the header and the admin preview.
		'wp-head-callback'			=> 'twentyfifteen_header_style',
		'admin-head-callback'		=> 'twentyfifteen_admin_header_style',
		'admin-preview-callback'	=> 'twentyfifteen_admin_header_image',
	);

	add_theme_support( 'custom-header', $args ); // need 8 in add_action to overhide parent

}
add_action( 'after_setup_theme', 'twentyfifteen_xili_setup_custom_header', 12 ); // 12 - child translation is active


/**
 * Styles the header image and text displayed on the blog. OVERWRITE parent function
 *
 * @since Twenty Fifteen xili 1.0
 *
 * @see twentyfifteen_custom_header_setup()
 */
function twentyfifteen_header_style() {
	$header_image = get_header_image();

	// If no custom options for text are set, let's bail.
	if ( empty( $header_image ) && display_header_text() ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css" id="twentyfifteen-header-css">
	<?php
		// Short header for when there is no Custom Header and Header Text is hidden.
		if ( empty( $header_image ) && ! display_header_text() ) :
	?>
		.site-header {
			padding-top: 14px;
			padding-bottom: 14px;
		}

		.site-branding {
			min-height: 42px;
		}

		@media screen and (min-width: 46.25em) {
			.site-header {
				padding-top: 21px;
				padding-bottom: 21px;
			}
			.site-branding {
				min-height: 56px;
			}
		}
		@media screen and (min-width: 55em) {
			.site-header {
				padding-top: 25px;
				padding-bottom: 25px;
			}
			.site-branding {
				min-height: 62px;
			}
		}
		@media screen and (min-width: 59.6875em) {
			.site-header {
				padding-top: 0;
				padding-bottom: 0;
			}
			.site-branding {
				min-height: 0;
			}
		}
	<?php
		endif;

		// Has a Custom Header been added?
		if ( ! empty( $header_image ) ) :
			$header_image_url = get_header_image();
			$header_image_width = get_custom_header()->width; // default values
			$header_image_height = get_custom_header()->height;
			if ( class_exists ( 'xili_language' ) ) {
				$xili_theme_options = get_theme_xili_options() ;
				if ( isset ( $xili_theme_options['xl_header'] ) && $xili_theme_options['xl_header'] ) {
				global $xili_language, $xili_language_theme_options ;
				// check if image exists in current language
				// 2013-10-10 - Tiago suggestion
				$curlangslug = ( '' == the_curlang() ) ? strtolower( $xili_language->default_lang ) : the_curlang() ;


					$headers = get_uploaded_header_images(); // search in uploaded header list

					$this_default_headers = $xili_language_theme_options->get_processed_default_headers () ;
					if ( ! empty( $this_default_headers ) ) {
						$headers = array_merge( $this_default_headers, $headers );
					}
					foreach ( $headers as $header_key => $header ) {

						if ( isset ( $xili_theme_options['xl_header_list'][$curlangslug] ) && $header_key == $xili_theme_options['xl_header_list'][$curlangslug] ) {
							$header_image_url = $header['url'];

							$header_image_width = ( isset($header['width']) ) ? $header['width']: get_custom_header()->width;
							$header_image_height = ( isset($header['height']) ) ? $header['height']: get_custom_header()->height; // not in default (but in uploaded)

							break ;
						}
					}
				}
			}


	?>
		.site-header {
			background: url(<?php echo $header_image_url; ?>) no-repeat 50% 50%; /*changed for child*/
			-webkit-background-size: cover;
			-moz-background-size:    cover;
			-o-background-size:      cover;
			background-size:         cover;
		}

		@media screen and (min-width: 59.6875em) {
			body:before {
				background: url(<?php echo $header_image_url; ?>) no-repeat 100% 50%; /*changed for child*/
				-webkit-background-size: cover;
				-moz-background-size:    cover;
				-o-background-size:      cover;
				background-size:         cover;
				border-right: 0;
			}

			.site-header {
				background: transparent;
			}
		}
	<?php
		endif;

		// Has the text been hidden?
		if ( ! display_header_text() ) :
	?>
		.site-title,
		.site-description {
			clip: rect(1px, 1px, 1px, 1px);
			position: absolute;
		}
	<?php endif; ?>
	</style>
	<?php
}


/**
 * condition to filter adjacent links
 * @since 1.1.4
 *
 */

function is_xili_adjacent_filterable() {

	if ( is_search () ) { // for multilingual search
		return false;
	}
	return true;
}

/**
 * define when search form is completed by radio buttons to sub-select language when searching
 *
 */
function special_head() {
	if ( class_exists('xili_language') ) {	// if temporary disabled
		// to change search form of widget
		// if ( is_front_page() || is_category() || is_search() )
		if ( is_search() || is_404() ) {
			add_filter('get_search_form', 'my_langs_in_search_form_2015', 10, 1); // here below
		}
	}
}
add_action( 'wp_head', 'special_head', 11) ;

/**
 * add search other languages in form - see functions.php when fired
 *
 */
function my_langs_in_search_form_2015 ( $the_form ) {

	$form = str_replace ( '</form>', '', $the_form ) . '<span class="xili-s-radio">' . xiliml_langinsearchform ( $before='<span class="radio-lang">', $after='</span>', false) . '</span>';
	$form .= '</form>';
	return $form ;
}

/**
 *
 * filter to improve translation of Reply button in comments list
 * from comment-template.php line #1358
 */
add_filter ( 'comment_reply_link_args', 'twentyfifteen_xili_comment_reply_link_args', 10, 3 );
function twentyfifteen_xili_comment_reply_link_args ( $args, $comment, $post ) {
	$args['reply_text']    = __( 'Reply', 'twentyfifteen' );
	$args['reply_to_text']    = __( 'Reply to %s', 'twentyfifteen' );
	$args['login_text']    = __( 'Log in to Reply', 'twentyfifteen' );
	return $args;
}

/**
 * Display translated descriptions in main navigation.
 *
 * @since Twenty Fifteen-xili 1.0.1
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function xili_twentyfifteen_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . _x($item->description, 'menu_description', 'twentyfifteen') . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
// parent removed in after_setup
add_filter( 'walker_nav_menu_start_el', 'xili_twentyfifteen_nav_description', 10, 4 );

// new filter for vertical nav menu description
// xl_nav_menu_page_attr_title
// xl_nav_menu_page_description
// xl_nav_menu_lang_description
/**
 * comment this line to avoid sub-title in languages switcher in nav menu
 */
function twentyfifteen_xl_nav_menu_lang_description ( $description, $language_slug ) {
	$language = xiliml_get_language( $language_slug );
	// please note variable language in context - language of the line in languages switcher !
	$description = sprintf(_x("for %s speaking people", 'menu_description '.$language_slug, 'twentyfifteen') , $language->description );
	return $description;
}
add_filter ('xl_nav_menu_lang_description','twentyfifteen_xl_nav_menu_lang_description', 10, 2 );

function twentyfifteen_xili_credits () {
	/* translators: added in child functions by xili */
	printf( __("Multilingual child theme of Twenty Fifteen by %s", 'twentyfifteen' ),"<a href=\"http://dev.xiligroup.com\">dev.xiligroup</a> - " );
}
add_action ('twentyfifteen_credits', 'twentyfifteen_xili_credits');

?>
