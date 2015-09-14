<?php

// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

// Check if class already exists

if (!class_exists("NNR_Display_Conditions_Settings_v1")):

/* ================================================================================
 *
 * Base is the base class for Display Conditions to help with managing repetitive
 * tasks.
 *
 ================================================================================ */

if ( !class_exists('NNR_Display_Conditions_Base_v1') ) {
	require_once( dirname(dirname(__FILE__)) . '/base.php');
}

class NNR_Display_Conditions_Settings_v1 extends NNR_Display_Conditions_Base_v1 {

	/**
	 * prefix
	 *
	 * (default value: '')
	 *
	 * @var string
	 * @access public
	 */
	public $prefix = '';

	/**
	 * text_domain
	 *
	 * (default value: '')
	 *
	 * @var string
	 * @access public
	 */
	public $text_domain = '';

	/**
	 * Called when the object is first created
	 *
	 * @access public
	 * @param mixed $prefix
	 * @return void
	 */
	function NNR_Display_Conditions_Settings_v1( $prefix = '', $text_domain = '' ) {

		$this->prefix = $prefix;
		$this->text_domain = $text_domain;

		$this->include_scripts();
	}

	/**
	 * Include all scripts needed for the settings
	 *
	 * @access public
	 * @return void
	 */
	function include_scripts() {

		wp_register_style( 'selectize-css', plugins_url( 'css/selectize.bootstrap3.css', dirname(__FILE__)) );
		wp_enqueue_style( 'selectize-css' );

		wp_register_script( 'selectize-js', plugins_url( 'js/selectize.min.js', dirname(__FILE__)), array('jquery') );
		wp_enqueue_script( 'selectize-js' );

		wp_register_script( 'display_conditions-js', plugins_url( 'js/settings.js', dirname(__FILE__)), array('jquery') );
		wp_enqueue_script( 'display_conditions-js' );
		wp_localize_script( 'display_conditions-js', 'nnr_display_conditions_data' , array(
			'prefix'		=> $this->prefix,
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'post_types'	=> get_post_types(array('public' => true)),
		));

	}

	/**
	 * Display the Display On field
	 *
	 * @access public
	 * @param mixed $display_on
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_display_on( $display_on, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Display On -->
		<div class="form-group">
			<label for="' . $this->prefix . 'trigger-display-on" class="col-sm-3 control-label">' . __('Display On', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'trigger-display-on" name="' . $this->prefix . 'trigger-display-on">
					<option value="site" ' . selected('site', $display_on, false) . '>' . __('Site Wide', $this->text_domain) . '</option>
					<option value="frontpage" ' . selected('frontpage', $display_on, false) . '>' . __('Front Page', $this->text_domain) . '</option>
					<option value="category" ' . selected('category', $display_on, false). '>' . __('Category', $this->text_domain) . '</option>
					<option value="tag" ' . selected('tag', $display_on, false) . '>' . __('Tag', $this->text_domain) . '</option>';

					foreach (get_post_types(array('public' => true)) as $post_type) {
						$code .= '<option value="' . $post_type . '" ' . selected($post_type, $display_on, false) . '>' . __(ucfirst($post_type), $this->text_domain) . '</option>';
					}

				$code .= '</select>' .
				$help_text .
			'</div>
		</div>';

		return $code;
	}

	/**
	 * Display the Categories field
	 *
	 * @access public
	 * @param mixed $categories
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_categories( $categories, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Category -->
		<div class="form-group ' . $this->prefix . 'trigger ' . $this->prefix . 'trigger-category">
			<label for="' . $this->prefix . 'trigger-category-id" class="col-sm-3 control-label">' . __('Categories', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input id="' . $this->prefix . 'trigger-category-id" name="' . $this->prefix . 'trigger-category-id" class="form-control" type="text" value="' . (isset($categories) ? $categories :'' ) . '"/>'
				. $help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display the Tags field
	 *
	 * @access public
	 * @param mixed $tags
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_tags( $tags, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Tag -->
		<div class="form-group has-feedback ' . $this->prefix . 'trigger ' . $this->prefix . 'trigger-tag">
			<label class="col-sm-3 control-label">' . __('Tags', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input id="' . $this->prefix . 'trigger-tag-id" name="' . $this->prefix . 'trigger-tag-id" class="form-control" type="text" value="' . (isset($tags) ? $tags :'') . '"/>'
				. $help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display the Post Type fields
	 *
	 * @access public
	 * @param mixed $display_on
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_post_types( $post_types, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Post Types -->';

		foreach (get_post_types(array('public' => true)) as $post_type) {

		$code .= '<div class="' . $this->prefix . 'trigger-post-type" id="' . $this->prefix . 'trigger-post-type-' . $post_type . '">

			<!-- All -->

			<div class="form-group ' . $this->prefix . 'trigger-post-type-all-' . $post_type . '">
				<label for="' . $this->prefix . 'trigger-post-type-all-' . $post_type . '" class="col-sm-3 control-label">' . __('All', $this->text_domain) . '</label>
				<div class="col-sm-9">
					<input class="form-control ' . $this->prefix . 'trigger-post-type-all" id="' . $this->prefix . 'trigger-post-type-all-' . $post_type . '" name="' . $this->prefix . 'trigger-post-type-all-' . $post_type . '" data-post="' . $post_type . '" type="checkbox" ' . (isset($post_types[$post_type]['all']) && $post_types[$post_type]['all'] ? 'checked="checked"' : '' ) . '/>
				</div>
			</div>

			<!-- ID -->

			<div class="form-group ' . $this->prefix . 'trigger-post-type-id ' . $this->prefix . 'trigger-post-type-id-' . $post_type . '">
				<label for="' . $this->prefix . 'trigger-post-type-id-' . $post_type . '" class="col-sm-3 control-label">' . __(ucfirst($post_type) . 's', $this->text_domain) . '</label>
				<div class="col-sm-9">
					<input class="form-control" id="' . $this->prefix . 'trigger-post-type-id-' . $post_type . '" name="' . $this->prefix . 'trigger-post-type-id-' . $post_type . '" type="text" value="' . (isset($post_types[$post_type]['id']) ? $post_types[$post_type]['id'] : '' ) . '"/>' .
					$help_text .
				'</div>
			</div>

		</div>';

		}

		return $code;

	}

	/**
	 * Display the Referrer Type field
	 *
	 * @access public
	 * @param mixed $display_on
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_referrer_type( $referrer_type, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Referrer Type -->
		<div class="form-group">
			<label for="' . $this->prefix . 'trigger-referrer-type" class="col-sm-3 control-label">' . __('Referrer Type', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'trigger-referrer-type" name="' . $this->prefix . 'trigger-referrer-type">
					<option value="any" ' .selected('any', $referrer_type, false) . '>' . __('Any Domain', $this->text_domain) . '</option>
					<option value="specific" ' . selected('specific', $referrer_type, false) . '>' . __('Specific Domains', $this->text_domain) . '</option>
				</select>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display the Referrer Domain Field
	 *
	 * @access public
	 * @param mixed $referrer_domain
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_referrer_domain( $referrer_domain, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Referrer -->
		<div class="form-group ' . $this->prefix . 'trigger-referrer-domain ' . $this->prefix . 'trigger-referrer-specific">
			<label class="col-sm-3 control-label">' . __('Referrer Domains', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'trigger-referrer-domain" name="' . $this->prefix . 'trigger-referrer-domain" placeholder="' . __('e.g t.co,www.facebook.com,plus.url.google.com,www.linkedin.com', $this->text_domain) . '" value="' . (isset($referrer_domain) ? $referrer_domain : $default) . '" data-urls="' . (isset($referrer_domain) ? $referrer_domain : $default) . '"/>' .
				$help_text .
			'</div>
		</div>';

		return $code;
	}

	/**
	 * Display the Users Field
	 *
	 * @access public
	 * @param mixed $referrer_domain
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_users( $users, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Users -->
		<div class="form-group has-feedback">
			<label for="' . $this->prefix . 'trigger-users" class="col-sm-3 control-label">' . __('Users', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'trigger-users" name="' . $this->prefix . 'trigger-users">
					<option value="everyone" ' . selected('everyone', $users, false) . ' >' . __('Everyone', $this->text_domain) . '</option>
					<option value="logged_in" ' . selected('logged_in', $users, false) . ' >' . __('Only Users', $this->text_domain) . '</option>
					<option value="logged_out" ' . selected('logged_out', $users, false) . ' >' . __('Only Non-Users', $this->text_domain) . '</option>
					<option value="specific" ' . selected('specific', $users, false) . ' >' . __('Specific User Roles', $this->text_domain) . '</option>
				</select>' .
				$help_text .
			'</div>
		</div>';

		return $code;
	}

	/**
	 * Display the User Roles Field
	 *
	 * @access public
	 * @param mixed $referrer_domain
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_user_roles( $user_roles, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		global $wp_roles;

		$code = '<!-- User Roles -->

		<div class="form-group has-feedback ' . $this->prefix . 'trigger-users ' . $this->prefix . 'trigger-users-specific">
			<label class="col-sm-3 control-label">' . __('User Roles', $this->text_domain) . '</label>
			<div class="col-sm-9">' . $help_text;

				foreach ($wp_roles->role_names as $role_name => $role) {

					$code .= '<input type="checkbox" id="' . $this->prefix . 'role-' . $role_name . '" class="' . $this->prefix . 'role" name="' . $this->prefix . 'role-' . $role_name . '" ' . (isset($user_roles[$role_name]) && $user_roles[$role_name] ? 'checked="checked"' : $default) . ' class="form-control"/>
					<span>' . $role . '</span><br/>';

				}

			$code .= '</div>
		</div>';

		return $code;
	}

	/**
	 * Display the name field
	 *
	 * @access public
	 * @return void
	 */
	function get_data() {

		global $wp_roles;

		$roles = array();

		foreach ($wp_roles->role_names as $role_name => $role) {
			$roles[$role_name] = isset($_POST[$this->prefix . 'role-' . $role_name]) && $_POST[$this->prefix . 'role-' . $role_name] ? true : false;
		}

		$post_types = array();

		foreach (get_post_types(array('public' => true)) as $post_type) {
			$post_types[$post_type] = array(
				'all'	=> isset($_POST[$this->prefix . 'trigger-post-type-all-' . $post_type]) && isset($_POST[$this->prefix . 'trigger-post-type-all-' . $post_type]) ? true : false,
				'id'	=> isset($_POST[$this->prefix . 'trigger-post-type-id-' . $post_type]) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-post-type-id-' . $post_type]) : '',
			);
		}

		return array(
			'display_on'		=> isset($_POST[$this->prefix . 'trigger-display-on']) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-display-on']) : '',
			'categories'		=> isset($_POST[$this->prefix . 'trigger-categories-id']) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-categories-id']) : '',
			'tags'				=> isset($_POST[$this->prefix . 'trigger-tags-id']) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-tags-id']) : '',
			'post_types'		=> $post_types,
			'referrer_type'		=> isset($_POST[$this->prefix . 'trigger-referrer-type']) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-referrer-type']) : '',
			'referrer_domain'	=> isset($_POST[$this->prefix . 'trigger-referrer-domain']) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-referrer-domain']) : '',
			'users'				=> isset($_POST[$this->prefix . 'trigger-users']) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-users']) : '',
			'roles'				=> $roles,
		);

	}
}

// Get all tags and custom posts

add_action( 'wp_ajax_nnr_dis_con_get_categories', 				'nnr_dis_con_get_categories_v1');
add_action( 'wp_ajax_nnr_dis_con_get_tags', 					'nnr_dis_con_get_tags_v1');
add_action( 'wp_ajax_nnr_dis_con_get_posts', 					'nnr_dis_con_get_posts_v1');

/**
 * Get all Posts in post type
 *
 * @access public
 * @static
 * @return void
 */
function nnr_dis_con_get_posts_v1() {

	echo json_encode(get_posts('posts_per_page=-1&post_type=' . $_POST['post_type']));

	die(); // this is required to terminate immediately and return a proper response
}

/**
 * Get all Categories
 *
 * @access public
 * @static
 * @return void
 */
function nnr_dis_con_get_categories_v1() {

	echo json_encode(get_categories());

	die(); // this is required to terminate immediately and return a proper response
}

/**
 * Get all Tags
 *
 * @access public
 * @static
 * @return void
 */
function nnr_dis_con_get_tags_v1() {

	echo json_encode(get_tags());

	die(); // this is required to terminate immediately and return a proper response
}

endif;