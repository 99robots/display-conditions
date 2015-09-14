jQuery(document).ready(function($){

	/* ============================================================================
	 *
	 * Hide and Show Display On settings
	 *
	 =========================================================================== */

	// Hide all settings

	$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type').hide();
	$('.' + nnr_display_conditions_data.prefix + 'trigger-category').hide();
	$('.' + nnr_display_conditions_data.prefix + 'trigger-tag').hide();

	if ($('#' + nnr_display_conditions_data.prefix + 'trigger-display-on').val() == 'category') {
	  	$('.' + nnr_display_conditions_data.prefix + 'trigger-category').show();
  	} else if ($('#' + nnr_display_conditions_data.prefix + 'trigger-display-on').val() == 'tag') {
	  	$('.' + nnr_display_conditions_data.prefix + 'trigger-tag').show();
  	} else {
	  	$('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-' + $('#' + nnr_display_conditions_data.prefix + 'trigger-display-on').val()).show();
  	}

  	$('#' + nnr_display_conditions_data.prefix + 'trigger-display-on').change(function(){

	  	// Hide all settings

		$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type').hide();
		$('.' + nnr_display_conditions_data.prefix + 'trigger-category').hide();
		$('.' + nnr_display_conditions_data.prefix + 'trigger-tag').hide();

		if ($(this).val() == 'category') {
		  	$('.' + nnr_display_conditions_data.prefix + 'trigger-category').show();
	  	} else if ($(this).val() == 'tag') {
		  	$('.' + nnr_display_conditions_data.prefix + 'trigger-tag').show();
	  	} else {
		  	$('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-' + $(this).val()).show();
	  	}

  	});

	/* ============================================================================
	 *
	 * Selectize - Categories
	 *
	 =========================================================================== */

	$.post(nnr_display_conditions_data.ajaxurl, {'action': 'nnr_dis_con_get_categories'}, function(response) {

		response = $.parseJSON(response);

		// Create an easy interface for selecting tags

	  	var tag_options = [];

	  	$.each(response, function( index, value ) {
		  	tag_options.push( {id: value.term_id, name: value.name} );
		});

	  	$('#' + nnr_display_conditions_data.prefix + 'trigger-category-id').selectize({
		  	plugins: ['remove_button'],
		  	valueField: 'id',
			labelField: 'name',
			searchField: 'name',
		  	options: tag_options,
	  	});
	});

	// Category

	$('#' + nnr_display_conditions_data.prefix + 'trigger-category-all').click(function() {
		if ($(this).is(':checked')) {
			$('#' + nnr_display_conditions_data.prefix + 'trigger-category-id-div').hide();
		} else{
			$('#' + nnr_display_conditions_data.prefix + 'trigger-category-id-div').show();
		}
	});

	if ($('#' + nnr_display_conditions_data.prefix + 'trigger-category-all').is(':checked')) {
		$('#' + nnr_display_conditions_data.prefix + 'trigger-category-id-div').hide();
	}

	/* ============================================================================
	 *
	 * Selectize - Tags
	 *
	 =========================================================================== */

	$.post(nnr_display_conditions_data.ajaxurl, {'action': 'nnr_dis_con_get_tags'}, function(response) {

		response = $.parseJSON(response);

		// Create an easy interface for selecting tags

	  	var tag_options = [];

	  	$.each(response, function( index, value ) {
		  	tag_options.push( {id: value.term_id, name: value.name} );
		});

	  	$('#' + nnr_display_conditions_data.prefix + 'trigger-tag-id').selectize({
		  	plugins: ['remove_button'],
		  	valueField: 'id',
			labelField: 'name',
			searchField: 'name',
		  	options: tag_options,
	  	});

	  	$('#' + nnr_display_conditions_data.prefix + 'recent-posts-tag').selectize({
		  	plugins: ['remove_button'],
		  	valueField: 'id',
			labelField: 'name',
			searchField: 'name',
		  	options: tag_options,
	  	});
	});

	/* ============================================================================
	 *
	 * Selectize - Posts
	 *
	 =========================================================================== */

  	$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-all').change(function() {

	  	$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-id').hide();

		if ( !$(this).is(':checked') ) {
			$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-id-' + $('#' + nnr_display_conditions_data.prefix + 'trigger-display-on').val()).show();
		}
	});

	$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-id').hide();

	if ( !$('#' + nnr_display_conditions_data.prefix + 'trigger-post-type-all-' + $('#' + nnr_display_conditions_data.prefix + 'trigger-display-on').val()).is(':checked')) {
		$('.' + nnr_display_conditions_data.prefix + 'trigger-post-type-id-' + $('#' + nnr_display_conditions_data.prefix + 'trigger-display-on').val()).show();
	}

	$('#' + nnr_display_conditions_data.prefix + 'trigger-display-on option').each(function() {

		$.post(nnr_display_conditions_data.ajaxurl, {'action': 'nnr_dis_con_get_posts', 'post_type': $(this).val()}, function(response) {

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
	 * Selectize - Referrer Domain
	 *
	 =========================================================================== */

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