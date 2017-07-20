var $ = jQuery.noConflict();
jQuery(document).ready(function () {
    jQuery("#commentform #button").on('click', function (e) {
        if (jQuery("#comment").val().length === 0) {
            //console.log(jQuery("#userName").html());
            jQuery("#commentUserName").html(jQuery("#userName").html());
            e.preventDefault();
        }
    });
    jQuery("#sport-jrrny, #tip-jrrny").blur(function () {
        if (jQuery("#sport-jrrny").val().length !== 0 && jQuery("#tip-jrrny").val().length !== 0) {
            jQuery("#journey-preview").removeAttr('disabled');
            jQuery("#journey-preview").on('click', function (i) {
                i.preventDefault();

                jQuery("#previewTitle").html(jQuery("#sport-jrrny").val());
                jQuery("#whereStay").html(jQuery("#tip-jrrny").val());
                jQuery("#newTitle").html(jQuery("#tip_title").val());

                if (jQuery('#wp-story-wrap').length) {
                    wp_story_editor = tinyMCE.activeEditor.getContent();
                    jQuery('#story').val(wp_story_editor);
                }
                jQuery("#previewStory").html(jQuery("#story").val());
                //jQuery("#previewStay").html(jQuery("#hotel-name").val());
                jQuery("#previewEmbedLink").html(jQuery("#video-link").val());
                jQuery("#previewInsiderlLink").html(jQuery("#insider-tip").val());
                jQuery("#previewHotelLink").html(jQuery("#tip-source").val());

                //All images
                var tn_array = Array();
                jQuery('#jrrny-images-dropzone img').each(function () {
                    tn_array.push(jQuery(this).attr('src'));
                });
                var data = '';
                jQuery.each(tn_array, function (index, val) {
                    data = data + '<img src="' + val + '" alt="hoteld images"> ';
                    jQuery("#imagesForJrrny").html(data);
                });
                //End
            });

        } else {
            jQuery("#journey-preview").attr('disabled', 'disabled');
        }
    });
    //Getting Hotel Image

    jQuery("#hotel-featured-image").change(function () {
        readURL(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                jQuery('#imgForHotel').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    jQuery(".file-input-unset").click(function () {
        jQuery("#imgForHotel").attr('src', '');
    });
    //End

    jQuery("#jrrny-submit-preview").click(function (y) {
        jQuery("#journey-data-process").click();
        setTimeout(function () {
            console.log("innnnn");
            jQuery("#previewModal .close").click();
        }, 1000);
    });

    if (jQuery('#beta-now').length)
        jQuery('#beta-now').modal('show');
    document.cookie = "beta-now=true";

});

jQuery(document).ready(function () {
    jQuery("#journey-data-process").click(function () {
        if (jQuery('#wp-story-wrap').length) {
            wp_story_editor = tinyMCE.activeEditor.getContent();
            jQuery('#story').val(wp_story_editor);

        }
        if (jQuery("#sport-jrrny").val().length === 0) {
            jQuery("#sport-jrrny").css("border", "1px solid red");
        } else {
            jQuery("#sport-jrrny").css("border", "none");
        }

        if (jQuery("#tip-jrrny").val().length === 0) {
            jQuery("#tip-jrrny").css("border", "1px solid red");
        } else {
            jQuery("#tip-jrrny").css("border", "none");
        }
        if (jQuery("#tip_title").val().length === 0) {
            jQuery("#tip_title").css("border", "1px solid red");
        } else {
            jQuery("#tip_title").css("border", "none");
        }
        if (jQuery("#story").val().length === 0) {
            jQuery("#story").css("border", "1px solid red");
            if (jQuery('#wp-story-wrap').length) {
                $('#wp-story-wrap').css("border", "1px solid red");
            }
        } else {
            jQuery("#story").css("border", "none");
            if (jQuery('#wp-story-wrap').length) {
                $('#wp-story-wrap').css("border", "none");
            }
        }
    });

    var current_location = window.location;
    $('.navigation select option').each(function () {
        if ($(this).val() == current_location) {
            $(".navigation select").find('option').attr("selected",false) ;
            $(this).attr('selected', 'selected');
        }
    });
    $(document).on('change', ".navigation select", function ()
    {
        window.location = $(this).find("option:selected").val();
    });
});

