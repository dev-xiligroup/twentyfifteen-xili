<?php
/**
 * Twenty Fifteen xili Customizer functionality
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since
 */

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Twenty Fifteen 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function twentyfifteen_xili_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'texts_section' , array(
					'title'       => __( 'Other texts section', 'twentyfifteen' ),
					'priority'    => 421
	));

	$wp_customize->add_setting( 'copyright', array(
		'sanitize_callback' => 'twentyfifteen_sanitize_text',
		'default' => __('My company','twentyfifteen'),
		'transport' => 'postMessage',
		) );

	$wp_customize->add_control( 'copyright', array(
				'label'    => __( 'Your copyright (footer)', 'twentyfifteen' ),
				'section'  => 'texts_section',
				'settings' => 'copyright',
				'priority'    => 1,
		) );

}
add_action( 'customize_register', 'twentyfifteen_xili_customize_register', 12 ); // after parent


function twentyfifteen_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since 1.2.0
 */
function twentyfifteen_xili_customize_preview_js() {
	wp_enqueue_script( 'twentyfifteen-xili-customize-preview', get_stylesheet_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '20150705', true );
}
add_action( 'customize_preview_init', 'twentyfifteen_xili_customize_preview_js' );
?>