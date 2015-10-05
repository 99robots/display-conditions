<?php

// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

// Check if class already exists

if (!class_exists("NNR_Display_Conditions_Display_v1")):

/* ================================================================================
 *
 * Base is the base class for Display Conditions to help with managing repetitive
 * tasks.
 *
 ================================================================================ */

if ( !class_exists('NNR_Display_Conditions_Base_v1') ) {
	require_once( dirname(dirname(__FILE__)) . '/base.php');
}

/**
 * NNR_Display_Conditions_Display_v1 class.
 *
 * @extends NNR_Display_Conditions_Base_v1
 */
class NNR_Display_Conditions_Display_v1 extends NNR_Display_Conditions_Base_v1 {

	/**
	 * Check all the display conditions and return false if they are not meet.
	 *
	 * @access public
	 * @static
	 * @param mixed $display_condidtions
	 * @return void
	 */
	function check_conditions( $display_condidtions ) {

		do_action('nnr_dis_con_before_check_conditions_v1');

		global $post;

		// Check if mobile

    	if ( isset($display_condidtions['display_screen'] ) &&
    		($display_condidtions['display_screen'] == 'device' && !wp_is_mobile()) || ($display_condidtions['display_screen'] == 'computer' && wp_is_mobile())) {
    		return apply_filters('nnr_dis_con_check_conditions_screen_v1', false);
		}

		// Check if post is eligible

		if ( !$this->check_post( $display_condidtions ) ) {
			return apply_filters('nnr_dis_con_check_conditions_post_v1', false);
		}

		// Check user role

		if ( !$this->check_user_role( $display_condidtions ) ) {
    		return apply_filters('nnr_dis_con_check_conditions_user_v1', false);
		}

		// Check referrer

		if ( !$this->check_referrer( $display_condidtions ) ) {
			return apply_filters('nnr_dis_con_check_conditions_referrer_v1', false);
		}

		do_action('nnr_dis_con_after_check_conditions_v1');

		return apply_filters('nnr_dis_con_check_conditions_default_v1', true);
	}

	/**
	 * Check if we can display on this post
	 *
	 * @access public
	 * @param mixed $display_condidtions
	 * @return void
	 */
	function check_post( $display_condidtions ) {

		do_action('nnr_dis_con_before_check_post_v1');

		global $post;

		// Single Pages or Posts

		if ( is_singular() ) {

			// Do not show

			if ( isset($display_condidtions['taxonomies'][$post->post_type]['type']) && $display_condidtions['taxonomies'][$post->post_type]['type'] == 'none' ) {
				return apply_filters('nnr_dis_con_check_post_' . $post->post_type . '_exclude_all_v1', false);
			}

			// Check for excluded posts

			if ( isset($display_condidtions['post_types'][$post->post_type]['exclude']) &&
				 $display_condidtions['post_types'][$post->post_type]['exclude'] != '' &&
				 in_array($post->ID, explode(',', $display_condidtions['post_types'][$post->post_type]['exclude'])) ) {

	    		return apply_filters('nnr_dis_con_check_post_' . $post->post_type . '_exclude_id_v1', false, $display_condidtions['post_types'][$post->post_type]['exclude']);
			}

			$taxonomies = get_object_taxonomies( $post->post_type, 'objects' );

			// Check for excluded terms

			foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {

				if ( isset($display_condidtions['post_types'][$post->post_type]['type']) && $display_condidtions['post_types'][$post->post_type]['type'] == 'all' &&
					 isset($display_condidtions['taxonomies'][$taxonomy_slug]['exclude']) &&
					 $display_condidtions['taxonomies'][$taxonomy_slug]['exclude'] != '' &&
					 has_term(explode(',', $display_condidtions['taxonomies'][$taxonomy_slug]['exclude']), $taxonomy_slug, $post->ID) ) {

		    		return apply_filters('nnr_dis_con_check_post_' . $post->post_type . '_exclude_term_v1', true, $display_condidtions['taxonomies'][$taxonomy_slug]['exclude']);
				}

			}

			// Show Post - ID

			if ( isset($display_condidtions['post_types'][$post->post_type]['type']) && $display_condidtions['post_types'][$post->post_type]['type'] == 'specific' && in_array($post->ID, explode(',', $display_condidtions['post_types'][$post->post_type]['id'])) ) {
				return apply_filters('nnr_dis_con_check_post_' . $post->post_type . '_show_id_v1', true, $display_condidtions['post_types'][$post->post_type]['id']);
			}

			// Show Post from Term

			foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {

				if ( isset($display_condidtions['post_types'][$post->post_type]['type']) && $display_condidtions['post_types'][$post->post_type]['type'] == 'specific_' . $taxonomy_slug && has_term(explode(',', $display_condidtions['taxonomies'][$taxonomy_slug]['id']), $taxonomy_slug, $post->ID) ) {
					return apply_filters('nnr_dis_con_check_post_' . $post->post_type . '_show_term_v1', true, $display_condidtions['taxonomies'][$taxonomy_slug]['id']);
				}

			}

			// Show Post - All

			if ( isset($display_condidtions['post_types'][$post->post_type]['type']) && $display_condidtions['post_types'][$post->post_type]['type'] == 'all' ) {
				return apply_filters('nnr_dis_con_check_post_' . $post->post_type . '_show_all_v1', true);
			}
		}

		// Front Page

		if ( isset($display_condidtions['frontpage']) && $display_condidtions['frontpage'] && ( is_front_page() || is_home() ) ) {
			return apply_filters('nnr_dis_con_check_post_front_page_v1', true, $display_condidtions['frontpage']);
		}

		// Site

		if ( isset($display_condidtions['sitewide']) && $display_condidtions['sitewide'] ) {
			return apply_filters('nnr_dis_con_check_post_sitewide_v1', true, $display_condidtions['sitewide']);
		}

		do_action('nnr_dis_con_before_check_post_v1');

		// safeguard return false

		return apply_filters('nnr_dis_con_check_post_default_v1', false);
	}

	/**
	 * Determines if the referrer URL matches any given by the user
	 *
	 * @access public
	 * @static
	 * @param mixed $display_condidtions
	 * @return void
	 */
	function check_referrer( $display_condidtions ) {

		do_action('nnr_dis_con_before_check_referrer_v1');

		// Return true any domain is checked

		if ( $display_condidtions['referrer_type'] == 'any' ) {
			return apply_filters('nnr_dis_con_check_referrer_match_v1', true);
		}

		// Check is referrer domain matches ones given by user

		else if ( isset($_SERVER["HTTP_REFERER"]) ) {

			$urls = explode(',', $display_condidtions['referrer']);
			$domain = parse_url($_SERVER["HTTP_REFERER"]);

			if ( in_array($domain['host'], $urls) ) {
				return apply_filters('nnr_dis_con_check_referrer_match_v1', true, $display_condidtions['referrer']);
			}
		}

		do_action('nnr_dis_con_after_check_referrer_v1');

		// safeguard return false

		return apply_filters('nnr_dis_con_check_referrer_default_v1', false);
	}

    /**
     * Check if the current user has access to backend / frontend based on his role compared with role from settings
     *
     * @access public
     * @static
     * @param mixed $display_condidtions
     * @return void
     */
    function check_user_role( $display_condidtions ) {

	    do_action('nnr_dis_con_before_check_user_role_v1');

		// Check if Everyone users is true

		if ( isset($display_condidtions['users']) && $display_condidtions['users'] == 'everyone') {
			return apply_filters('nnr_dis_con_check_user_role_everyone_v1', true);
		}

		// Check if All users is true

		else if ( isset($display_condidtions['users']) && $display_condidtions['users'] == 'logged_in' && is_user_logged_in() ) {
			return apply_filters('nnr_dis_con_check_user_role_users_v1', true);
		}

		// Check if Logged Out Users is true

		else if ( isset($display_condidtions['users']) && $display_condidtions['users'] == 'logged_out' && !is_user_logged_in() ) {
			return apply_filters('nnr_dis_con_check_user_role_viewers_v1', true);
		}

		// Check if Specific User is true

		else if ( isset($display_condidtions['users']) && $display_condidtions['users'] == 'specific' && is_user_logged_in() ) {

			$is_allowed = false;
			global $wp_roles;
			$current_user = wp_get_current_user();

			foreach ($wp_roles->role_names as $role_name => $role) {

				if ( isset($display_condidtions['roles'][$role_name] ) && $display_condidtions['roles'][$role_name] && in_array($role_name, $current_user->roles)) {
					$is_allowed = apply_filters('nnr_dis_con_check_user_role_specifc_user_' . $role_name . '_v1', true, $display_condidtions['roles']);
				}
			}

			return apply_filters('nnr_dis_con_check_user_role_specifc_user_v1', $is_allowed);
		}

		do_action('nnr_dis_con_before_check_user_role_v1');

		// safeguard return false

        return apply_filters('nnr_dis_con_check_user_role_default_v1', false);
    }

}

endif;