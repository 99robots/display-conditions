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

/**
 * NNR_Display_Conditions_Settings_v1 class.
 *
 * @extends NNR_Display_Conditions_Base_v1
 */
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
	function __construct( $prefix = '', $text_domain = '' ) {

		do_action('nnr_dis_con_before_new_settings_controller_v1');

		$this->prefix = $prefix;
		$this->text_domain = $text_domain;

		$this->include_scripts();

		do_action('nnr_dis_con_after_new_settings_controller_v1');
	}

	/**
	 * Include all scripts needed for the settings
	 *
	 * @access public
	 * @return void
	 */
	function include_scripts() {

		do_action('nnr_dis_con_before_settings_include_scripts_v1');

		wp_register_style( 'selectize-css', plugins_url( 'css/selectize.bootstrap3.css', dirname(__FILE__)) );
		wp_enqueue_style( 'selectize-css' );

		wp_register_script( 'selectize-js', plugins_url( 'js/selectize.min.js', dirname(__FILE__)), array('jquery') );
		wp_enqueue_script( 'selectize-js' );

		wp_register_script( 'display_conditions-js', plugins_url( 'js/settings.js', dirname(__FILE__)), array('jquery') );
		wp_enqueue_script( 'display_conditions-js' );
		wp_localize_script( 'display_conditions-js', 'nnr_display_conditions_data_v1', apply_filters('nnr_dis_con_settings_scripts_data', array(
			'prefix'		=> $this->prefix,
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'post_types'	=> $this->get_post_types(),
			'taxonomies'	=> $this->get_taxonomies(),
		) ) );

		do_action('nnr_dis_con_after_settings_include_scripts_v1');
	}

	/**
	 * Display all the settings
	 *
	 * @access public
	 * @param mixed $display_settings
	 * @param string $args (default: array('default' => array())
	 * @param array 'help-text' (default: > array()))
	 * @return void
	 */
	function display_all_settings( $display_settings, $args = array('default' => array(), 'help-text' => array()) ) {

		do_action('nnr_dis_con_before_settings_display_all_v1');

		// Display Headers

		echo '<div class="page-header">
		  <h1>' . __('Display On', $this->text_domain) . '</h1>
		</div>';

		echo $this->display_sitewide($display_settings['sitewide']);
		echo $this->display_frontpage($display_settings['frontpage']);

		// Post Type Headers

		echo '<div class="page-header">
		  <h1>' . __('Post Types', $this->text_domain) . '</h1>
		</div>';

		echo $this->display_post_types($display_settings['post_types'], $display_settings['taxonomies']);

		// Taxonomies Headers

		echo '<div class="page-header">
		  <h1>' . __('Excludes', $this->text_domain) . '</h1>
		</div>';

		echo $this->display_post_type_excludes($display_settings['post_types']);
		echo $this->display_taxonomy_excludes($display_settings['taxonomies']);

		// Referrer Domain Headers

		echo '<div class="page-header">
		  <h1>' . __('Referrer Domain', $this->text_domain) . '</h1>
		</div>';

		echo $this->display_referrer_type($display_settings['referrer_type']);
		echo $this->display_referrer_domain($display_settings['referrer_domain']);

		// User Role Headers

		echo '<div class="page-header">
		  <h1>' . __('Users', $this->text_domain) . '</h1>
		</div>';

		echo $this->display_users($display_settings['users']);
		echo $this->display_user_roles($display_settings['roles']);

		// Screen Header

		echo '<div class="page-header">
		  <h1>' . __('Screen Size', $this->text_domain) . '</h1>
		</div>';

		echo $this->display_display_screen($display_settings['display_screen']);

		do_action('nnr_dis_con_after_settings_display_all_v1');

	}

	/**
	 * Display the Display sitewide setting
	 *
	 * @access public
	 * @param mixed $sitewide
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_sitewide( $sitewide, $default = '', $help_text = null, $format = 'inline' ) {

		do_action('nnr_dis_con_after_settings_sitewide_v1');

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		if ( $format == 'inline' ) {
			$code = '<!-- Display Sitewide -->
			<div class="form-group">
				<label for="' . $this->prefix . 'trigger-sitewide" class="col-sm-3 control-label">' . __('Site Wide', $this->text_domain) . '</label>
				<div class="col-sm-9">
					<input type="checkbox" id="' . $this->prefix . 'trigger-sitewide" name="' . $this->prefix . 'trigger-sitewide" ' . (isset($sitewide) && $sitewide ? 'checked="checked"' : '' ) . '/>' .
					$help_text .
				'</div>
			</div>';
		} else {
			$code = '<!-- Display Sitewide -->
			<div class="nnr-block-group">
				<label>
					<input type="checkbox" id="' . $this->prefix . 'trigger-sitewide" name="' . $this->prefix . 'trigger-sitewide" ' . (isset($sitewide) && $sitewide ? 'checked="checked"' : '' ) . '/>' . __('Site Wide', $this->text_domain) .
					$help_text .
				'</label>
			</div>';
		}

		do_action('nnr_dis_con_after_settings_sitewide_v1');

		return apply_filters('nnr_dis_con_settings_sitewide_v1', $code);
	}

	/**
	 * Display the Display sitewide setting
	 *
	 * @access public
	 * @param mixed $sitewide
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_frontpage( $frontpage, $default = '', $help_text = null, $format = 'inline' ) {

		do_action('nnr_dis_con_before_settings_frontpage_v1');

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		if ( $format == 'inline' ) {
			$code = '<!-- Display Front Page -->
			<div class="form-group ' . $this->prefix . 'trigger-sitewide">
				<label for="' . $this->prefix . 'trigger-frontpage" class="col-sm-3 control-label">' . __('Front Page', $this->text_domain) . '</label>
				<div class="col-sm-9">
					<input type="checkbox" id="' . $this->prefix . 'trigger-frontpage" name="' . $this->prefix . 'trigger-frontpage" ' . (isset($frontpage) && $frontpage ? 'checked="checked"' : '' ) . '/>
					<div><a href="' . get_home_url() . '" target="_blank">' . get_home_url() . '</a></div>' .
					$help_text .
				'</div>
			</div>';
		} else {
			$code = '<!-- Display Front Page -->
			<div class="nnr-block-group ' . $this->prefix . 'trigger-sitewide">
				<label for="' . $this->prefix . 'trigger-frontpage">
					<input type="checkbox" id="' . $this->prefix . 'trigger-frontpage" name="' . $this->prefix . 'trigger-frontpage" ' . (isset($frontpage) && $frontpage ? 'checked="checked"' : '' ) . '/>' . $help_text . __('Front Page', $this->text_domain) .
				'</label>
			</div>';
		}

		do_action('nnr_dis_con_after_settings_frontpage_v1');

		return apply_filters('nnr_dis_con_settings_frontpage_v1', $code);
	}

	/**
	 * Display the Post Type fields
	 *
	 * @access public
	 * @param mixed $post_types
	 * @param mixed $taxonomies
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_post_types( $post_types, $taxonomies, $default = '', $help_text = null, $format = 'inline' ) {

		do_action('nnr_dis_con_before_settings_post_types_v1');

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$post_type_names = $this->get_post_types();

		$code = '<!-- Post Types -->';

		foreach ( $post_type_names as $post_type) {

			$post_type_tax = '';

			foreach ($this->get_taxonomies(array($post_type)) as $taxonomy) {

				$taxonomy_name = $taxonomy->name;

				if ( $taxonomy_name == 'post_tag' ) {
					$taxonomy_name = 'tag';
				}

				$post_type_tax .= '<option data-taxonomy="' . $taxonomy->name . '" value="specific_' . $taxonomy->name . '" ' . selected('specific_' . $taxonomy->name, $post_types[$post_type]['type'], false) . '>' . __('Show on Specific ' . ucfirst($taxonomy_name), $this->text_domain) . '</option>';
			}

			$code .= '<div class="nnr-block-group ' . $this->prefix . 'trigger-sitewide" id="' . $this->prefix . 'trigger-post-type-' . $post_type . '">';

				if ( $format == 'inline' ) {
					$code .= '<!-- Type -->

					<div class="form-group ' . $this->prefix . 'trigger-post-type-type-' . $post_type . '">
						<label for="' . $this->prefix . 'trigger-post-type-type-' . $post_type . '" class="col-sm-3 control-label">' . __(ucfirst($post_type) . 's', $this->text_domain) . '</label>
						<div class="col-sm-9">
							<select class="' . $this->prefix . 'trigger-post-type-type" id="' . $this->prefix . 'trigger-post-type-type-' . $post_type . '" name="' . $this->prefix . 'trigger-post-type-type-' . $post_type . '" data-post="' . $post_type . '">
								<option value="none" ' . selected('none', $post_types[$post_type]['type'], false) . '>' . __('Do Not Show', $this->text_domain) . '</option>
								<option value="all" ' . selected('all', $post_types[$post_type]['type'], false) . '>' . __('Show on All', $this->text_domain) . '</option>
								<option value="specific" ' . selected('specific', $post_types[$post_type]['type'], false) . '>' . __('Show on Specific ' . ucfirst($post_type) . 's', $this->text_domain) . '</option>' . $post_type_tax .
							'</select>
						</div>
					</div>

					<!-- ID -->

					<div class="form-group ' . $this->prefix . 'trigger-post-type-id ' . $this->prefix . 'trigger-post-type-id-' . $post_type . '">
						<label for="' . $this->prefix . 'trigger-post-type-id-' . $post_type . '" class="col-sm-3 control-label"></label>
						<div class="col-sm-9">
							<input class="form-control" id="' . $this->prefix . 'trigger-post-type-id-' . $post_type . '" name="' . $this->prefix . 'trigger-post-type-id-' . $post_type . '" type="text" value="' . (isset($post_types[$post_type]['id']) ? $post_types[$post_type]['id'] : '' ) . '" placeholder="' . __('Select ' . ucfirst($post_type) . '(s)', $this->text_domain) . '"/>' .
							$help_text .
						'</div>
					</div>';
				} else {
					$code .= '<!-- Type -->

					<div class="nnr-block-group ' . $this->prefix . 'trigger-post-type-type-' . $post_type . '">
						<label for="' . $this->prefix . 'trigger-post-type-type-' . $post_type . '">' . __(ucfirst($post_type) . 's', $this->text_domain) . '</label>
						<select class="' . $this->prefix . 'trigger-post-type-type" id="' . $this->prefix . 'trigger-post-type-type-' . $post_type . '" name="' . $this->prefix . 'trigger-post-type-type-' . $post_type . '" data-post="' . $post_type . '">
							<option value="none" ' . selected('none', $post_types[$post_type]['type'], false) . '>' . __('Do Not Show', $this->text_domain) . '</option>
							<option value="all" ' . selected('all', $post_types[$post_type]['type'], false) . '>' . __('Show on All', $this->text_domain) . '</option>
							<option value="specific" ' . selected('specific', $post_types[$post_type]['type'], false) . '>' . __('Show on Specific ' . ucfirst($post_type) . 's', $this->text_domain) . '</option>' . $post_type_tax .
						'</select>
					</div>

					<!-- ID -->

					<div class="nnr-block-group ' . $this->prefix . 'trigger-post-type-id ' . $this->prefix . 'trigger-post-type-id-' . $post_type . '">
						<input class="form-control" id="' . $this->prefix . 'trigger-post-type-id-' . $post_type . '" name="' . $this->prefix . 'trigger-post-type-id-' . $post_type . '" type="text" value="' . (isset($post_types[$post_type]['id']) ? $post_types[$post_type]['id'] : '' ) . '" placeholder="' . __('Select ' . ucfirst($post_type) . '(s)', $this->text_domain) . '"/>' .
							$help_text .
					'</div>';
				}

				foreach ($this->get_taxonomies(array($post_type)) as $taxonomy) {

					$taxonomy_name = $taxonomy->name;

					if ( $taxonomy_name == 'post_tag' ) {
						$taxonomy_name = 'tag';
					}

					if ( $format == 'inline' ) {

						$code .= '<!-- Taxonomy ID -->

						<div class="form-group ' . $this->prefix . 'trigger-taxonomy-id ' . $this->prefix . 'trigger-taxonomy-id-' . $taxonomy->name . '">
							<label for="' . $this->prefix . 'trigger-taxonomy-id-' . $taxonomy->name . '" class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<input class="form-control" id="' . $this->prefix . 'trigger-taxonomy-id-' . $taxonomy->name . '" name="' . $this->prefix . 'trigger-taxonomy-id-' . $taxonomy->name . '" type="text" value="' . (isset($taxonomies[$taxonomy->name]['id']) ? $taxonomies[$taxonomy->name]['id'] : '' ) . '" placeholder="' . __('Select ' . ucfirst($taxonomy_name) . '(s)', $this->text_domain) . '"/>' .
								$help_text .
							'</div>
						</div>';

					} else {
						$code .= '<!-- Taxonomy ID -->

						<div class="' . $this->prefix . 'trigger-taxonomy-id ' . $this->prefix . 'trigger-taxonomy-id-' . $taxonomy->name . '">
							<input class="form-control" id="' . $this->prefix . 'trigger-taxonomy-id-' . $taxonomy->name . '" name="' . $this->prefix . 'trigger-taxonomy-id-' . $taxonomy->name . '" type="text" value="' . (isset($taxonomies[$taxonomy->name]['id']) ? $taxonomies[$taxonomy->name]['id'] : '' ) . '" placeholder="' . __('Select ' . ucfirst($taxonomy_name) . '(s)', $this->text_domain) . '"/>' .
								$help_text .
						'</div>';
					}
				}

			$code .= '</div>';

		}

		do_action('nnr_dis_con_after_settings_post_types_v1');

		return apply_filters('nnr_dis_con_settings_post_types_v1', $code);

	}

	/**
	 * Display the Post Type Excludes fields
	 *
	 * @access public
	 * @param mixed $post_types
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_post_type_excludes( $post_types, $default = '', $help_text = null, $format = 'inline' ) {

		do_action('nnr_dis_con_before_settings_post_type_excludes_v1');

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$post_type_names = $this->get_post_types();

		$code = '<!-- Excludes -->';

		// Excludes

		foreach ( $post_type_names as $post_type) {

			if ( $format == 'inline' ) {
				$code .= '<div class="form-group ' . $this->prefix . 'trigger-post-type-exclude ' . $this->prefix . 'trigger-post-type-exclude-' . $post_type . '">
					<label for="' . $this->prefix . 'trigger-post-type-exclude-' . $post_type . '" class="col-sm-3 control-label">' . __('Exclude ' . ucfirst($post_type) . 's', $this->text_domain) . '</label>
					<div class="col-sm-9">
						<input class="form-control" id="' . $this->prefix . 'trigger-post-type-exclude-' . $post_type . '" name="' . $this->prefix . 'trigger-post-type-exclude-' . $post_type . '" type="text" value="' . (isset($post_types[$post_type]['exclude']) ? $post_types[$post_type]['exclude'] : '' ) . '" placeholder="' . __('Select ' . ucfirst($post_type) . '(s)', $this->text_domain) . '"/>' .
						$help_text .
					'</div>
				</div>';
			} else {
				$code .= '<div class="nnr-block-group ' . $this->prefix . 'trigger-post-type-exclude ' . $this->prefix . 'trigger-post-type-exclude-' . $post_type . '">
					<label for="' . $this->prefix . 'trigger-post-type-exclude-' . $post_type . '" class="control-label">' . __('Exclude ' . ucfirst($post_type) . 's', $this->text_domain) . '</label>
					<input class="form-control" id="' . $this->prefix . 'trigger-post-type-exclude-' . $post_type . '" name="' . $this->prefix . 'trigger-post-type-exclude-' . $post_type . '" type="text" value="' . (isset($post_types[$post_type]['exclude']) ? $post_types[$post_type]['exclude'] : '' ) . '" placeholder="' . __('Select ' . ucfirst($post_type) . '(s)', $this->text_domain) . '"/>' .
						$help_text .
				'</div>';
			}

		}

		do_action('nnr_dis_con_after_settings_post_type_excludes_v1');

		return apply_filters('nnr_dis_con_settings_post_type_excludes_v1', $code);

	}

	/**
	 * Display the Taxonomies Excludes fields
	 *
	 * @access public
	 * @param mixed $taxonomies
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_taxonomy_excludes( $taxonomies, $default = '', $help_text = null, $format = 'inline' ) {

		do_action('nnr_dis_con_before_settings_taxonomy_excludes_v1');

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$taxonomy_names = $this->get_taxonomies();

		$code = '<!-- Excludes -->';

		// Excludes

		foreach ( $taxonomy_names as $taxonomy ) {

			$taxonomy_name = $taxonomy->name;

			if ( $taxonomy_name == 'post_tag' ) {
				$taxonomy_name = 'tag';
			}

			if ( $format == 'inline' ) {

				$code .= '<div class="form-group ' . $this->prefix . 'trigger-taxonomy-exclude ' . $this->prefix . 'trigger-taxonomy-exclude-' . $taxonomy->name . '">
					<label for="' . $this->prefix . 'trigger-taxonomy-exclude-' . $taxonomy->name . '" class="col-sm-3 control-label">' . __('Exclude ' . ucfirst($taxonomy_name) . 's', $this->text_domain) . '</label>
					<div class="col-sm-9">
						<input class="form-control" id="' . $this->prefix . 'trigger-taxonomy-exclude-' . $taxonomy->name . '" name="' . $this->prefix . 'trigger-taxonomy-exclude-' . $taxonomy->name . '" type="text" value="' . (isset($taxonomies[$taxonomy->name]['exclude']) ? $taxonomies[$taxonomy->name]['exclude'] : '' ) . '" placeholder="' . __('Select ' . ucfirst($taxonomy_name) . '(s)', $this->text_domain) . '"/>' .
						$help_text .
					'</div>
				</div>';

			} else {

				$code .= '<div class="nnr-block-group ' . $this->prefix . 'trigger-taxonomy-exclude ' . $this->prefix . 'trigger-taxonomy-exclude-' . $taxonomy->name . '">
					<label for="' . $this->prefix . 'trigger-taxonomy-exclude-' . $taxonomy->name . '" class="control-label">' . __('Exclude ' . ucfirst($taxonomy_name) . 's', $this->text_domain) . '</label>
					<input class="form-control" id="' . $this->prefix . 'trigger-taxonomy-exclude-' . $taxonomy->name . '" name="' . $this->prefix . 'trigger-taxonomy-exclude-' . $taxonomy->name . '" type="text" value="' . (isset($taxonomies[$taxonomy->name]['exclude']) ? $taxonomies[$taxonomy->name]['exclude'] : '' ) . '" placeholder="' . __('Select ' . ucfirst($taxonomy_name) . '(s)', $this->text_domain) . '"/>' .
						$help_text .
				'</div>';

			}
		}

		do_action('nnr_dis_con_after_settings_taxonomy_excludes_v1');

		return apply_filters('nnr_dis_con_settings_taxonomy_excludes_v1', $code);

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
	function display_referrer_type( $referrer_type, $default = '', $help_text = null, $format = 'inline' ) {

		do_action('nnr_dis_con_before_settings_referrer_type_v1');

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		if ( $format == 'inline' ) {
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
		} else {
			$code = '<!-- Referrer Type -->
			<div class="nnr-block-group">
				<label for="' . $this->prefix . 'trigger-referrer-type">' . __('Referrer Type', $this->text_domain) . '</label>
				<select id="' . $this->prefix . 'trigger-referrer-type" name="' . $this->prefix . 'trigger-referrer-type">
					<option value="any" ' .selected('any', $referrer_type, false) . '>' . __('Any Domain', $this->text_domain) . '</option>
					<option value="specific" ' . selected('specific', $referrer_type, false) . '>' . __('Specific Domains', $this->text_domain) . '</option>
				</select>' .
				$help_text .
			'</div>';
		}

		do_action('nnr_dis_con_before_settings_referrer_type_v1');

		return apply_filters('nnr_dis_con_settings_referrer_type_v1', $code);

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
	function display_referrer_domain( $referrer_domain, $default = '', $help_text = null, $format = 'inline' ) {

		do_action('nnr_dis_con_before_settings_referrer_domain_v1');

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		if ( $format == 'inline' ) {
			$code = '<!-- Referrer -->
			<div class="form-group ' . $this->prefix . 'trigger-referrer-domain ' . $this->prefix . 'trigger-referrer-specific">
				<label class="col-sm-3 control-label">' . __('Referrer Domains', $this->text_domain) . '</label>
				<div class="col-sm-9">
					<input class="form-control" id="' . $this->prefix . 'trigger-referrer-domain" name="' . $this->prefix . 'trigger-referrer-domain" placeholder="' . __('e.g t.co,www.facebook.com,plus.url.google.com,www.linkedin.com', $this->text_domain) . '" value="' . (isset($referrer_domain) ? $referrer_domain : $default) . '" data-urls="' . (isset($referrer_domain) ? $referrer_domain : $default) . '"/>' .
					$help_text .
				'</div>
			</div>';
		} else {
			$code = '<!-- Referrer -->
			<div class="nnr-block-group ' . $this->prefix . 'trigger-referrer-domain ' . $this->prefix . 'trigger-referrer-specific">
				<label class="control-label">' . __('Referrer Domains', $this->text_domain) . '</label>
				<input class="form-control" id="' . $this->prefix . 'trigger-referrer-domain" name="' . $this->prefix . 'trigger-referrer-domain" placeholder="' . __('e.g t.co,www.facebook.com,plus.url.google.com,www.linkedin.com', $this->text_domain) . '" value="' . (isset($referrer_domain) ? $referrer_domain : $default) . '" data-urls="' . (isset($referrer_domain) ? $referrer_domain : $default) . '"/>' .
					$help_text .
			'</div>';
		}

		do_action('nnr_dis_con_after_settings_referrer_domain_v1');

		return apply_filters('nnr_dis_con_settings_referrer_domain_v1', $code);
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
	function display_users( $users, $default = '', $help_text = null, $format = 'inline' ) {

		do_action('nnr_dis_con_before_settings_users_v1');

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		if ( $format == 'inline' ) {
			$code = '<!-- Users -->
			<div class="form-group">
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
		} else {
			$code = '<!-- Users -->
			<div class="nnr-block-group">
				<label for="' . $this->prefix . 'trigger-users">' . __('Users', $this->text_domain) . '</label>
				<select id="' . $this->prefix . 'trigger-users" name="' . $this->prefix . 'trigger-users">
					<option value="everyone" ' . selected('everyone', $users, false) . ' >' . __('Everyone', $this->text_domain) . '</option>
					<option value="logged_in" ' . selected('logged_in', $users, false) . ' >' . __('Only Users', $this->text_domain) . '</option>
					<option value="logged_out" ' . selected('logged_out', $users, false) . ' >' . __('Only Non-Users', $this->text_domain) . '</option>
					<option value="specific" ' . selected('specific', $users, false) . ' >' . __('Specific User Roles', $this->text_domain) . '</option>
				</select>' .
				$help_text .
			'</div>';
		}

		do_action('nnr_dis_con_after_settings_users_v1');

		return apply_filters('nnr_dis_con_settings_users_v1', $code);
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
	function display_user_roles( $user_roles, $default = '', $help_text = null, $format = 'inline' ) {

		do_action('nnr_dis_con_before_settings_user_roles_v1');

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		global $wp_roles;

		if ( $format == 'inline' ) {
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
		} else {
			$code = '<!-- User Roles -->
			<div class="nnr-block-group ' . $this->prefix . 'trigger-users ' . $this->prefix . 'trigger-users-specific">
				<div><label>' . __('User Roles', $this->text_domain) . '</label></div>
				' . $help_text;

					foreach ($wp_roles->role_names as $role_name => $role) {

						$code .= '<input type="checkbox" id="' . $this->prefix . 'role-' . $role_name . '" class="' . $this->prefix . 'role" name="' . $this->prefix . 'role-' . $role_name . '" ' . (isset($user_roles[$role_name]) && $user_roles[$role_name] ? 'checked="checked"' : $default) . ' class="form-control"/>
						<span>' . $role . '</span><br/>';

					}

			$code .= '</div>';
		}

		do_action('nnr_dis_con_after_settings_user_roles_v1');

		return apply_filters('nnr_dis_con_settings_user_roles_v1', $code);
	}

	/**
	 * Display the Display Screen Field
	 *
	 * @access public
	 * @param mixed $display_screen
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_display_screen( $display_screen, $default = '', $help_text = null, $format = 'inline' ) {

		do_action('nnr_dis_con_before_settings_display_screen_v1');

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		if ( $format == 'inline' ) {
			$code = '<!-- Display Screen -->
			<div class="form-group">
				<label for="' . $this->prefix . 'trigger-display-screen" class="col-sm-3 control-label">' . __('Display on Screen', $this->text_domain) . '</label>
				<div class="col-sm-9">
					<select id="' . $this->prefix . 'trigger-display-screen" name="' . $this->prefix . 'trigger-display-screen">
						<option value="both" ' . selected('both', $display_screen, false) . '>' . __('Display on both Computers and Mobile Devices', $this->text_domain) . '</option>
						<option value="computer" ' . selected('computer', $display_screen, false) . '>' . __('Display only on Computers', $this->text_domain) . '</option>
						<option value="device" ' . selected('device', $display_screen, false) . '>' . __('Display only on Mobile Devices', $this->text_domain) . '</option>
					</select>' .
				$help_text .
				'</div>
			</div>';
		} else {
			$code = '<!-- Display Screen -->
			<div class="nnr-block-group">
				<label for="' . $this->prefix . 'trigger-display-screen">' . __('Display on Screen', $this->text_domain) . '</label>
				<select id="' . $this->prefix . 'trigger-display-screen" name="' . $this->prefix . 'trigger-display-screen">
						<option value="both" ' . selected('both', $display_screen, false) . '>' . __('Display on both Computers and Mobile Devices', $this->text_domain) . '</option>
						<option value="computer" ' . selected('computer', $display_screen, false) . '>' . __('Display only on Computers', $this->text_domain) . '</option>
						<option value="device" ' . selected('device', $display_screen, false) . '>' . __('Display only on Mobile Devices', $this->text_domain) . '</option>
					</select>' .
				$help_text .
			'</div>';
		}

		do_action('nnr_dis_con_after_settings_display_screen_v1');

		return apply_filters('nnr_dis_con_settings_display_screen_v1', $code);
	}

	/**
	 * Display the name field
	 *
	 * @access public
	 * @return void
	 */
	function get_data() {

		// Post Type Data

		$post_types = array();

		foreach ($this->get_post_types() as $post_type) {
			$post_types[$post_type] = array(
				'type'		=> isset($_POST[$this->prefix . 'trigger-post-type-type-' . $post_type]) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-post-type-type-' . $post_type]) : '',
				'id'		=> isset($_POST[$this->prefix . 'trigger-post-type-id-' . $post_type]) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-post-type-id-' . $post_type]) : '',
				'exclude'	=> isset($_POST[$this->prefix . 'trigger-post-type-exclude-' . $post_type]) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-post-type-exclude-' . $post_type]) : '',
			);
		}

		// Taxonomies

		$taxonomies = array();

		foreach ($this->get_taxonomies() as $taxonomy) {
			$taxonomies[$taxonomy->name] = array(
				'type'		=> isset($_POST[$this->prefix . 'trigger-taxonomy-type-' . $taxonomy->name]) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-taxonomy-type-' . $taxonomy->name]) : '',
				'id'		=> isset($_POST[$this->prefix . 'trigger-taxonomy-id-' . $taxonomy->name]) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-taxonomy-id-' . $taxonomy->name]) : '',
				'exclude'	=> isset($_POST[$this->prefix . 'trigger-taxonomy-exclude-' . $taxonomy->name]) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-taxonomy-exclude-' . $taxonomy->name]) : '',
			);
		}

		// User Roles

		global $wp_roles;

		$roles = array();

		foreach ($wp_roles->role_names as $role_name => $role) {
			$roles[$role_name] = isset($_POST[$this->prefix . 'role-' . $role_name]) && $_POST[$this->prefix . 'role-' . $role_name] ? true : false;
		}

		return apply_filters('nnr_dis_con_settings_get_data_v1', array(
			'sitewide'			=> isset($_POST[$this->prefix . 'trigger-sitewide']) && $_POST[$this->prefix . 'trigger-sitewide'] ? true : false,
			'frontpage'			=> isset($_POST[$this->prefix . 'trigger-frontpage']) && $_POST[$this->prefix . 'trigger-frontpage'] ? true : false,
			'post_types'		=> $post_types,
			'taxonomies'		=> $taxonomies,
			'referrer_type'		=> isset($_POST[$this->prefix . 'trigger-referrer-type']) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-referrer-type']) : '',
			'referrer_domain'	=> isset($_POST[$this->prefix . 'trigger-referrer-domain']) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-referrer-domain']) : '',
			'users'				=> isset($_POST[$this->prefix . 'trigger-users']) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-users']) : '',
			'roles'				=> $roles,
			'display_screen'	=> isset($_POST[$this->prefix . 'trigger-display-screen']) ? $this->sanitize_value($_POST[$this->prefix . 'trigger-display-screen']) : '',
		) );

	}
}

// Get all tags and custom posts

add_action( 'wp_ajax_nnr_dis_con_get_posts', 'nnr_dis_con_get_posts_v1');
add_action( 'wp_ajax_nnr_dis_con_get_terms', 'nnr_dis_con_get_terms_v1');

/**
 * Get all Posts in post type
 *
 * @access public
 * @static
 * @return void
 */
function nnr_dis_con_get_posts_v1() {

	echo json_encode( apply_filters('nnr_dis_con_get_posts_v1', get_posts('posts_per_page=-1&post_type=' . $_POST['post_type']) ) );

	die(); // this is required to terminate immediately and return a proper response
}

/**
 * Get all Terms by Taxonomy
 *
 * @access public
 * @static
 * @return void
 */
function nnr_dis_con_get_terms_v1() {

	echo json_encode( apply_filters('nnr_dis_con_get_terms_v1', get_terms($_POST['taxonomy'], 'orderby=count&hide_empty=0') ) );

	die(); // this is required to terminate immediately and return a proper response
}

endif;