<?php

// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

// Check if class already exists

if (!class_exists("NNR_Display_Conditions_Base_v2")):

/* ================================================================================
 *
 * Base is the base class for Display Conditions to help with managing repetitive
 * tasks.
 *
 ================================================================================ */

class NNR_Display_Conditions_Base_v2 {

	/**
	 * Get post types
	 *
	 * @access public
	 * @return void
	 */
	function get_post_types( $attachment = true ) {

		do_action('nnr_dis_con_before_get_post_types_v2');

		$post_types = get_post_types(array('public' => true));

		foreach( $post_types as $key => $post_type ) {

			// Unset attachment

			if ( !$attachment && $post_type == 'attachment' ) {
				unset($post_types[$key]);
			}
		}

		do_action('nnr_dis_con_after_get_post_types_v2');

		return apply_filters('nnr_dis_con_get_post_types_v2', $post_types);
	}

	/**
	 * Get the taxonomies
	 *
	 * @access public
	 * @return void
	 */
	function get_taxonomies($post_types = array()) {

		do_action('nnr_dis_con_before_get_taxonomies_v2');

		if ( count($post_types) < 1 ) {
			$post_types = $this->get_post_types();
		}

		$taxonomies = get_object_taxonomies($post_types, 'objects');

		foreach ( $taxonomies as $key => $taxonomy ) {

			// Unset Post Format

			if ( $taxonomy->name == 'post_format' ) {
				unset($taxonomies[$key]);
			}

		}

		do_action('nnr_dis_con_after_get_taxonomies_v2');

		return apply_filters('nnr_dis_con_get_taxonomies_v2', $taxonomies);

	}

	/**
	 * Sanitize the input value
	 *
	 * @access public
	 * @param mixed $value
	 * @param mixed $html
	 * @return void
	 */
	function sanitize_value( $value, $html = false ) {
		return apply_filters('nnr_dis_con_base_sanitize_value_v2', stripcslashes( sanitize_text_field( $value ) ) );
	}

}

endif;