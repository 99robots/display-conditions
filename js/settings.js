jQuery(document).ready(function($){

	/* ============================================================================
	 *
	 * Hide and Show Display On settings
	 *
	 =========================================================================== */

	// Hide all settings

	$('.' + nnr_display_conditions_data.prefix + 'trigger-sitewide').hide();

	if (!$('#' + nnr_display_conditions_data.prefix + 'trigger-sitewide').prop('checked')) {
	  	$('.' + nnr_display_conditions_data.prefix + 'trigger-sitewide').show();
  	}

  	$('#' + nnr_display_conditions_data.prefix + 'trigger-sitewide').change(function(){

	  	// Hide all settings

		$('.' + nnr_display_conditions_data.prefix + 'trigger-sitewide').hide();

		if (!$(this).prop('checked')) {
		  	$('.' + nnr_display_conditions_data.prefix + 'trigger-sitewide').show();
	  	}

  	});

	/* ============================================================================
	 *
	 * Selectize - Posts
	 *
	 =========================================================================== */

	$.each(nnr_display_conditions_data.post_types, function(index, value) {

		// Hide and show the settings

		$('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-type-' + value).change(function() {

		  	$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-id-' + value).hide();
		  	$('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-' + value + ' .' + nnr_display_conditions_data.prefix + 'trigger-taxonomy-id').hide();
		  	$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-all-' + value).hide();

			if ( $(this).val() == 'specific' ) {
				$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-id-' + value).show();
			}

			if ( $(this).val() == 'specific_' + $(this).find(":selected").data('taxonomy') ) {
				$('.' + nnr_display_conditions_data.prefix + 'trigger-taxonomy-id-' + $(this).find(":selected").data('taxonomy')).show();
			}

			if ( $(this).val() != 'none' ) {
				$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-all-' + value).show();
			}
		});

		$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-id-' + value).hide();
		$('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-' + value + ' .' + nnr_display_conditions_data.prefix + 'trigger-taxonomy-id').hide();
	  	$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-all-' + value).hide();

		if ( $('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-type-' + value).val() == 'specific' ) {
			$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-id-' + value).show();
		}

		if ( $('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-type-' + value).val() != 'none' ) {
			$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-all-' + value).show();
		}

		var tax = $('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-type-' + value).find(":selected").attr('data-taxonomy');

		if ( typeof(tax) != 'undefined' ) {

			$('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-' + value + ' .' + nnr_display_conditions_data.prefix + 'trigger-taxonomy-id').hide();

			if ( $('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-type-' + value).val() == 'specific_' + tax ) {
				$('.' + nnr_display_conditions_data.prefix + 'trigger-taxonomy-id-' + tax).show();
			}

		}

		if ( $('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-type-' + value).val() != 'none' ) {
			$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-all-' + value).show();
		}

		$.post(nnr_display_conditions_data.ajaxurl, {'action': 'nnr_dis_con_get_posts', 'post_type': value}, function(response) {

			response = $.parseJSON(response);

			// Create an easy interface for selecting tags

		  	var post_options = [];
		  	var post_type = '';

		  	$.each(response, function( index, value ) {
			  	post_options.push({id: value.ID, name: value.post_title});
			  	post_type = value.post_type;
			});

			$('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-exclude-' + post_type).selectize({
			  	plugins: ['remove_button'],
			  	valueField: 'id',
				labelField: 'name',
				searchField: 'name',
			  	options: post_options,
		  	});

			$('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-id-' + post_type).selectize({
			  	plugins: ['remove_button'],
			  	valueField: 'id',
				labelField: 'name',
				searchField: 'name',
			  	options: post_options,
		  	});

		});
	});

	/* ============================================================================
	 *
	 * Selectize - Taxonomies
	 *
	 =========================================================================== */

	$.each(nnr_display_conditions_data.taxonomies, function(index, value) {

		$.post(nnr_display_conditions_data.ajaxurl, {'action': 'nnr_dis_con_get_terms', 'taxonomy': value.name}, function(response) {

			response = $.parseJSON(response);

			// Create an easy interface for selecting tags

		  	var term_options = [];
		  	var taxonomy = '';

		  	$.each(response, function( index, value ) {
			  	term_options.push( {id: value.term_id, name: value.name} );
			  	taxonomy = value.taxonomy;
			});

			$('#' + nnr_display_conditions_data.prefix + 'trigger-taxonomy-exclude-' + taxonomy).selectize({
			  	plugins: ['remove_button'],
			  	valueField: 'id',
				labelField: 'name',
				searchField: 'name',
			  	options: term_options,
		  	});

		  	$('#' + nnr_display_conditions_data.prefix + 'trigger-taxonomy-id-' + taxonomy).selectize({
			  	plugins: ['remove_button'],
			  	valueField: 'id',
				labelField: 'name',
				searchField: 'name',
			  	options: term_options,
		  	});
		});

	});

	/* ============================================================================
	 *
	 * Selectize - Referrer Domain
	 *
	 =========================================================================== */

	if ( $('#' + nnr_display_conditions_data.prefix + 'trigger-referrer-domain').length != 0 ) {

		var referrer_options = [
			{id: 't.co'},
			{id: 'www.facebook.com'},
			{id: 'plus.url.google.com'},
			{id: 'www.linkedin.com'},
		];

		var temp_referrer_options = $('#' + nnr_display_conditions_data.prefix + 'trigger-referrer-domain').attr('data-urls').split(',');

		$.each(temp_referrer_options, function( index, value ) {
		  	referrer_options.push({id: value});
		});

		$('#' + nnr_display_conditions_data.prefix + 'trigger-referrer-domain').selectize({
			plugins: ['remove_button'],
			create: true,
			valueField: 'id',
			labelField: 'id',
			searchField: 'id',
			options: referrer_options,
		});

	 	// URL Referrer

		$("." + nnr_display_conditions_data.prefix + "trigger-referrer-domain").hide();
		$("." + nnr_display_conditions_data.prefix + "trigger-referrer-" + $("#" + nnr_display_conditions_data.prefix + "trigger-referrer-type").val()).show();

	 	$("#" + nnr_display_conditions_data.prefix + "trigger-referrer-type").change(function() {
	 		$("." + nnr_display_conditions_data.prefix + "trigger-referrer-domain").hide();
	 		$("." + nnr_display_conditions_data.prefix + "trigger-referrer-" + $(this).val()).show();

	 	});

	}

 	/* ============================================================================
	 *
	 * User Roles
	 *
	 =========================================================================== */

	$("." + nnr_display_conditions_data.prefix + "trigger-users").hide();
	$("." + nnr_display_conditions_data.prefix + "trigger-users-" + $("#" + nnr_display_conditions_data.prefix + "trigger-users").val()).show();

 	$("#" + nnr_display_conditions_data.prefix + "trigger-users").change(function() {
 		$("." + nnr_display_conditions_data.prefix + "trigger-users").hide();
 		$("." + nnr_display_conditions_data.prefix + "trigger-users-" + $(this).val()).show();

 	});

});