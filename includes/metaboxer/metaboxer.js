jQuery(document).ready( function($) {




/**
 * Add list functionality.
 *
 * @since 1.0.0
 */	
$('.mtphr-members-metaboxer-list').each( function(index) {

	// Set the field order
	mtphr_members_metaboxer_lists_set_order( $(this) );
	
	// Add sorting to the items
	mtphr_members_metaboxer_lists_set_sortable( $(this) );
});

// List - add sorting to the items
function mtphr_members_metaboxer_lists_set_sortable( $list ) {

	$list.sortable( {
		handle: '.mtphr-members-metaboxer-list-item-handle',
		items: '.mtphr-members-metaboxer-list-item',
		axis: 'y',
		helper: function(e, tr) {
	    var $originals = tr.children();
	    var $helper = tr.clone();
	    $helper.children().each(function(index) {
	      // Set helper cell sizes to match the original sizes
	      $(this).width($originals.eq(index).width())
	    });
	    return $helper;
	  },
	  update: function( event, ui ) {
			
			// Set the field order
			mtphr_members_metaboxer_lists_set_order( $(this) );
		}
	});	
}

// List - set the list item order
function mtphr_members_metaboxer_lists_set_order( $list ) {

	// Set the order of the items
	$list.find('.mtphr-members-metaboxer-list-item').each( function(i) {
		
		$(this).find('.mtphr-members-metaboxer-list-structure-item').each( function(e) {
			
			var base = $(this).attr('base');
			var field = $(this).attr('field');
			$(this).find('input,textarea,select').attr('name', base+'['+i+']['+field+']');
		});
	});
	
	// Hide the delete if only one element
	if( $list.find('.mtphr-members-metaboxer-list-item').length == 1 ) {
		
		$list.find('.mtphr-members-metaboxer-list-item-handle,.mtphr-members-metaboxer-list-item-delete').hide();
	}
}

// List - add item click
$('.mtphr-members-metaboxer-list-item-add').live( 'click', function(e) {
	e.preventDefault();

	// Create a new item with blank content
	var $parent = $(this).parents('.mtphr-members-metaboxer-list-item');
	var $new = $parent.clone(true).hide();
	$new.find('input,textarea,select').removeAttr('value').removeAttr('checked').removeAttr('selected');
	$parent.after($new);
	$new.fadeIn();
	
	// Set the field order
	mtphr_members_metaboxer_lists_set_order( $(this).parents('.mtphr-members-metaboxer-list') );
	
	// Show the handles
	$(this).parents('.mtphr-members-metaboxer-list').find('.mtphr-members-metaboxer-list-item-handle,.mtphr-members-metaboxer-list-item-delete').show();
	
	// Set the focus to the new input
	var inputs = $new.find('input,textarea,select');
	$(inputs[0]).focus();
});

// List - delete item click
$('.mtphr-members-metaboxer-list-item-delete').live( 'click', function(e) {
	e.preventDefault();
	
	// Fade out the item
	$(this).parents('.mtphr-members-metaboxer-list-item').fadeOut( function() {
		
		// Get the list
		var $list = $(this).parents('.mtphr-members-metaboxer-list');
		
		// Remove the item
		$(this).remove();
		
		// Set the field order
		mtphr_members_metaboxer_lists_set_order( $list );
	});
});

// List -  handle hover
$('.mtphr-members-metaboxer-list-item-handle').live( 'hover', function() {
	mtphr_members_metaboxer_lists_set_sortable( $(this).parents('.mtphr-members-metaboxer-list') );
});




});