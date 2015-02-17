<?php

/*
Plugin Name: Contact Form 7 Sequence Generator
Plugin URI:  https://github.com/aztecweb/contact-form-7-sequence-generator
Description: Generate a sequence for use with a Contact Form 7 form.
Version:     1.0
Author:      Aztec Online Solutions
Author URI:  http://www.aztecweb.net
License:	 GPLv2
*/

/*  Copyright 2015 Aztec Online Solutions (email: aztec at aztecweb.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
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
