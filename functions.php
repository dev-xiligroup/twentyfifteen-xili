<?php
// 
// dev.xiligroup.com - msc - 2014-11-16 - first test with 2015 0.1


define( 'TWENTYFIFTEEN_XILI_VER', '0.1'); // as parent style.css

// main initialisation functions and version testing and message

function twentyfifteen_xilidev_setup () {

	$theme_domain = 'twentyfifteen';

	$minimum_xl_version = '2.15.3';

	load_theme_textdomain( $theme_domain, get_stylesheet_directory() . '/langs' ); // now use .mo of child

}
add_action( 'after_setup_theme', 'twentyfifteen_xilidev_setup', 11 );
?>