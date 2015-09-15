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

		global $post;

		// Check for excluded posts

		if ( is_singular() &&
			 isset($display_condidtions[$post->post_type]['exclude']) &&
			 in_array($post->ID, explode(',', $display_condidtions[$post->post_type]['exclude'])) ) {

    		return false;
		}

		// Check if mobile

    	if ( isset($display_condidtions['display_screen'] ) &&
    		($display_condidtions['display_screen'] == 'device' && !wp_is_mobile()) || ($display_condidtions['display_screen'] == 'computer' && wp_is_mobile())) {
    		return false;
		}

		// Check if post is eligible

		if ( !$this->check_post( $display_condidtions ) ) {
			return false;
		}

		// Check user role

		if ( !$this->check_user_role( $display_condidtions ) ) {
    		return false;
		}

		// Check referrer

		if ( !$this->check_referrer( $display_condidtions ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if we can display on this post
	 *
	 * @access public
	 * @param mixed $display_condidtions
	 * @return void
	 */
	function check_post( $display_condidtions ) {

		global $post;

		// Site

		if ( isset($display_condidtions['display_on']) && $display_condidtions['display_on'] == 'site' ) {
			return true;
		}

		// Front Page

		if ( isset($display_condidtions['display_on']) && $display_condidtions['display_on'] == 'frontpage' && ( is_front_page() || is_home() ) ) {
			return true;
		}

		// Single Pages or Posts

		if ( is_singular() ) {

			// Post Category

			if ( isset($display_condidtions['display_on']) && $display_condidtions['display_on'] == 'category' && isset($display_condidtions['category']) && in_category(explode(',', $display_condidtions['category']['id']), $post->ID) ) {
				return true;
			}

			// Post Tag

			if ( isset($display_condidtions['display_on']) && $display_condidtions['display_on'] == 'tag' && isset($display_condidtions['tag']) && has_tag(explode(',', $display_condidtions['tag']['id']), $post->ID) ) {
				return true;
			}

			// Post - All

			if ( isset($display_condidtions['display_on']) && $display_condidtions['display_on'] == $post->post_type && isset($display_condidtions['post_types'][$post->post_type]) && isset($display_condidtions['post_types'][$post->post_type]['all']) && $display_condidtions['post_types'][$post->post_type]['all'] ) {
				return true;
			}

			// Post - ID

			if ( isset($display_condidtions['display_on']) && $display_condidtions['display_on'] == $post->post_type && isset($display_condidtions['post_types'][$post->post_type]) && isset($display_condidtions['post_types'][$post->post_type]['all']) && !$display_condidtions['post_types'][$post->post_type]['all'] &&  in_array($post->ID, explode(',', $display_condidtions['post_types'][$post->post_type]['id'])) ) {
				return true;
			}

		}

		// safeguard return false

		return false;
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

		// Return true any domain is checked

		if ( $display_condidtions['referrer_type'] == 'any' ) {
			return true;
		}

		// Check is referrer domain matches ones given by user

		else if ( isset($_SERVER["HTTP_REFERER"]) ) {

			$urls = explode(',', $display_condidtions['referrer']);
			$domain = parse_url($_SERVER["HTTP_REFERER"]);

			if ( in_array($domain['host'], $urls) ) {
				return true;
			}
		}

		// safeguard return false

		return false;
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

		// Check if Everyone users is true

		if ( isset($display_condidtions['users']) && $display_condidtions['users'] == 'everyone') {
			return true;
		}

		// Check if All users is true

		else if ( isset($display_condidtions['users']) && $display_condidtions['users'] == 'logged_in' && is_user_logged_in() ) {
			return true;
		}

		// Check if Logged Out Users is true

		else if ( isset($display_condidtions['users']) && $display_condidtions['users'] == 'logged_out' && !is_user_logged_in() ) {
			return true;
		}

		// Check if Specific User is true

		else if ( isset($display_condidtions['users']) && $display_condidtions['users'] == 'specific' && is_user_logged_in() ) {

			$is_allowed = false;
			global $wp_roles;
			$current_user = wp_get_current_user();

			foreach ($wp_roles->role_names as $role_name => $role) {

				if ( isset($display_condidtions['roles'][$role_name] ) && $display_condidtions['roles'][$role_name] && in_array($role_name, $current_user->roles)) {
					$is_allowed = true;
				}
			}

			return $is_allowed;
		}

		// safeguard return false

        return false;
    }

}

endif;