<?php

/*
Plugin Name: Contact Form 7 Sequence Generator
Plugin URI:  
Description: Generate a sequence for use with a Contact Form 7 form.
Version:     1.0
Author:      Aztec Online Solutions
Author URI:  http://www.aztecweb.net
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}


/**
 * Replace the shortcode [sequence-generator] in the Mail e Mail (2) bodies. The
 * sequence only will be increased if the one of two bodies has the shortcode.
 * The sequnece is individual for each form.
 * 
 * @param WPCF7_ContactForm $wpcf7_data The Contact Form instance.
 */
function wpcf7sg_generate_number( $wpcf7_data ) {
	$properties = $wpcf7_data->get_properties();
	$shortcode = '[sequence-generator]';
	$mail = $properties['mail']['body'];
	$mail_2 = $properties['mail_2']['body'];
	if( preg_match( "/{$shortcode}/", $mail ) || preg_match( "/[{$shortcode}]/", $mail_2 ) ) {
		$option = 'wpcf7sg_' . $wpcf7_data->id();
		$sequence_number = (int)get_option( $option ) + 1;
		update_option( $option, $sequence_number );
		
		$properties['mail']['body'] = str_replace( $shortcode, $sequence_number, $mail );
		$properties['mail_2']['body'] = str_replace( $shortcode, $sequence_number, $mail_2 );
		
		$wpcf7_data->set_properties( $properties );
	}
}
add_action( 'wpcf7_before_send_mail', 'wpcf7sg_generate_number' );
