jQuery(function ($) {
    var file_frame;

    jQuery('.upload_image_button').live('click', function (event) {

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery(this).data('uploader_title'),
            button: {
                text: jQuery(this).data('uploader_button_text'),
            },
            multiple: true  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function () {
            // We set multiple to false so only get one image from the uploader
            $('#img-wrapper').html('');
            var files = [];
            var selection = file_frame.state().get('selection');
            selection.map( function( attachment ) {
                attachment = attachment.toJSON();
                
                $('#img-wrapper').append('<span data-id="' + attachment.id + '" title="' + attachment.title + '"><img src="' + attachment.url + '" alt="" /></span>');
                
                files.push(attachment.url);
            });
            $('#img').val(files);
            $('#img_count').val(files.length);
        });

        // Finally, open the modal
        file_frame.open();
    });
});
   