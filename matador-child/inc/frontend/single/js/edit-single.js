jQuery(document).ready(function ($) {

    $("#tip-jrrny").chained("#sport-jrrny");
    var dz_additional_img = "<div class=\"dz-additional\">";
    dz_additional_img += "<i title=\"Remove\" class=\"fa fa-remove dz-remove-image dz-addit-elem\"></i>";
    dz_additional_img += "<i title=\"Repeat\" class=\"fa fa-repeat dz-repeat-image dz-addit-elem\"></i>";
    dz_additional_img += "<i title=\"Undo\" class=\"fa fa-undo fa-1 dz-undo-image dz-addit-elem\"></i>";
    dz_additional_img += "</div>";

    var dz_additional_vid = "<div class=\"dz-additional\">";
    dz_additional_vid += "<i title=\"Remove\" class=\"fa fa-remove dz-remove-video dz-addit-elem\"></i>";
    dz_additional_vid += "</div>";

    var dzSetThumbClick = function () {
        $(this).addClass('thumb-selected');
        $("#jrrny-images-dropzone").find('.dz-set-thumb').not($(this)).removeClass('thumb-selected');

        var imageId = $(this).parents('.dz-preview').data('image_id');
        $('#jrrny-main-image-id').val(imageId);
    };
    var dzRemoveClick = function () {
        var imageId = $(this).parents('.dz-preview').data('image_id');
        var fileObj = $(this).parents('.dz-preview');
        //Send ajax to remove
        $.ajax({
            url: defineURL("stylesheet_dir") + "/jrrny-remove-image.php",
            type: 'POST',
            data: {
                'image-id': imageId
            },
            success: function (response) {
                $('input#jrrny-image-' + imageId).remove();
                fileObj.remove();
            }
        });
    };
    var dzRemoveVideoClick = function () {
        var videoId = $(this).parents('.dz-preview').data('video_id');
        var fileObj = $(this).parents('.dz-preview');
        //Send ajax to remove
        $.ajax({
            url: defineURL("stylesheet_dir") + "/jrrny-remove-video.php",
            type: 'POST',
            data: {
                'video-id': videoId
            },
            success: function (response) {
                $('input#jrrny-video-' + videoId).remove();
                fileObj.remove();
            }
        });
    };
    var dzRemovehClick = function () {
        var imageId = $(this).parents('.dz-preview').data('image_id');
        var fileObj = $(this).parents('.dz-preview');
        //Send ajax to remove
        $.ajax({
            url: defineURL("stylesheet_dir") + "/jrrny-remove-image.php",
            type: 'POST',
            data: {
                'image-id': imageId
            },
            success: function (response) {
                $('input#jrrny-himage-' + imageId).remove();
                fileObj.remove();
            }
        });
    };
    var dzRepeatClick = function () {
        var imageId = $(this).parents('.dz-preview').data('image_id');
        var fileObj = $(this).parents('.dz-preview');
        //Send ajax to remove
        $.ajax({
            url: defineURL("stylesheet_dir") + "/jrrny-repeat-image.php",
            type: 'POST',
            data: {
                'image-id': imageId
            },
            success: function (response) {
                if (response.status == "ok") {
                    fileObj.find('img').attr('src', response.url);
                } else {
                    console.log(response.msg);
                }
            }
        });
    }
    var dzUndoClick = function () {
        var imageId = $(this).parents('.dz-preview').data('image_id');
        var fileObj = $(this).parents('.dz-preview');
        //Send ajax to remove
        $.ajax({
            url: defineURL("stylesheet_dir") + "/jrrny-undo-image.php",
            type: 'POST',
            data: {
                'image-id': imageId
            },
            success: function (response) {
                if (response.status == "ok") {
                    fileObj.find('img').attr('src', response.url);
                } else {
                    console.log(response.msg);
                }
            }
        });
    }
    $("body").on("submit", "#form-journey", function (event) {
        event.preventDefault();
        if ($('#wp-story-wrap').length) {
            wp_story_editor = tinyMCE.activeEditor.getContent();
            $('#story').val(wp_story_editor);
        }
        
        var toValidate = ["#sport-jrrny", "#tip-jrrny", "#tip_title", "#story", '#jrrny-images-dropzone'];
        if ($('#video-link').val()) {
            toValidate = ["#sport-jrrny", "#tip-jrrny", "#tip_title", "#story", '#video-link'];
        }

        var validate = new Validate("#form-journey", toValidate, "div.form-group");     
        
        var that = $(this);        
        var i = 0;
        
        var files = $(jrrny_images_dropzone.element).find('.dz-preview');
        if (files.length > 0) {
            files.each(function (e) {
                var imageId = $(files[e]).data("image_id");
                $('input#jrrny-image-' + imageId).attr('name', 'images[' + e + ']');
                if (i === 0) {
                    $('#jrrny-main-image-id').val(imageId);
                }
                i++;
            });
        }
        var video_files = $(jrrny_video_dropzone.element).find('.dz-preview');
        if (video_files.length > 0) {
            video_files.each(function (e) {
                var videoId = $(video_files[e]).data("video_id");
                $('input#jrrny-video-' + videoId).attr('name', 'video[' + e + ']');

            });
        }
        
        if (validate.validated()) {
            $.ajax({
                url: defineURL("stylesheet_dir") + "/edit-jrrny.php",
                type: 'POST',
                data: $('#form-journey').serialize(),
                beforeSend: function () {
                    //Turn on processing
                    $("#journey-data-process .processing-icon").removeClass('hide fa-check').addClass('rotating fa-refresh');
                },
                success: function (response) {
                    $("#journey-data-process .processing-icon")
                            .removeClass('rotating fa-refresh')
                            .addClass('fa-check');
                    if (response.status == 'ok') {
                        window.location = response.permalink;
                    }                    
                    else if(response.msg){
                        $("#journey-data-process .processing-icon").addClass('hide');
                        alert(response.msg);
                    }
                    

                }
            });
        }
    });

    $("body").on("click", "#form-journey #journey-data-process", function (event) {
        event.preventDefault();
        $("#form-journey").submit();
    });

    Dropzone.autoDiscover = false;
    var jrrny_images_dropzone = new Dropzone("#jrrny-images-dropzone", {
        init: function () {
            $('body').append("<input type=\"hidden\" value=\"\" id=\"dz-addit-params\" />");
        },
        uploadMultiple: false,
        url: defineURL("stylesheet_dir") + "/jrrny-images-upload.php",
        dictDefaultMessage: "Drag and Drop Images of what you did",
        autoProcessQueue: true,
        parallelUploads: 1,
        acceptedFiles: "image/jpeg,image/jpg,image/png,image/gif",
        maxFilesize: 8,
        maxFiles: 12,
        init: function () {
            $("#jrrny-images-dropzone").sortable({
                items: "div.dz-image-preview",
                opacity: 0.7,
                revert: true,
                cursor: "move",
                tolerance: "pointer",
                distance: -120,
                delay: 10,
                zIndex: 9999,
                appendTo: document.body

            });
            $("#jrrny-images-dropzone").disableSelection();
            var images = $('#form-journey input[name="images[]"]');
            var dzThis = this;
            images.each(function (i) {
                var image = $(this);
                var imgId = image.val();
                var mockFile = {
                    status: Dropzone.ADDED,
                    url: image.data('url'),
                    name: i + '_image',
                    accepted: true
                };
                // Call the default addedfile event handler
                dzThis.emit("addedfile", mockFile);
                // And optionally show the thumbnail of the file:
                dzThis.emit("thumbnail", mockFile, image.data('url'));
                dzThis.emit("complete", mockFile);
                dzThis.files.push(mockFile);
                $(mockFile.previewElement).append(dz_additional_img);
                $(mockFile.previewElement).find('.dz-set-thumb ').click(dzSetThumbClick);
                if ($('#jrrny-main-image-id').val() === imgId) {
                    $(mockFile.previewElement).find('.dz-set-thumb').addClass('thumb-selected');
                }
                $(mockFile.previewElement).find('.dz-remove-image').click(dzRemoveClick);
                $(mockFile.previewElement).find('.dz-repeat-image').click(dzRepeatClick);
                $(mockFile.previewElement).find('.dz-undo-image').click(dzUndoClick);
                $(mockFile.previewElement).find('.dz-size').hide();
                $(mockFile.previewElement).data('image_id', imgId);

            });
        }
    });

    var jrrny_video_dropzone = new Dropzone("#jrrny-video-dropzone", {
        init: function () {
            $('body').append("<input type=\"hidden\" value=\"\" id=\"dz-addit-params\" />");
        },
        uploadMultiple: false,
        url: defineURL("stylesheet_dir") + "/jrrny-video-upload.php",
        dictDefaultMessage: "Drag and Drop Video of what you did",
        autoProcessQueue: true,
        parallelUploads: 1,
        acceptedFiles: "video/mp4,video/webm,video/ogg,video/quicktime",
        maxFilesize: 100,
        maxFiles: 1,
        init: function () {
            $("#jrrny-video-dropzone").sortable({
                items: "div.dz-image-preview",
                opacity: 0.7,
                revert: true,
                cursor: "move",
                tolerance: "pointer",
                distance: -120,
                delay: 10,
                zIndex: 9999,
                appendTo: document.body

            });
            $("#jrrny-video-dropzone").disableSelection();
            var videos = $('#form-journey input[name="video[]"]');
            var dzThis = this;
            videos.each(function (i) {
                var video = $(this);
                var vidId = video.val();
                var mockFile = {
                    status: Dropzone.ADDED,
                    url: video.data('url'),
                    name: i + '_video',
                    accepted: true
                };
                // Call the default addedfile event handler
                dzThis.emit("addedfile", mockFile);
                // And optionally show the thumbnail of the file:
                dzThis.emit("thumbnail", mockFile, video.data('url'));
                dzThis.emit("complete", mockFile);
                dzThis.files.push(mockFile);
                $(mockFile.previewElement).append(dz_additional_vid);

                $(mockFile.previewElement).find('.dz-image img').attr('src', '/wp-content/themes/matador-child/images/video.png');
                $(mockFile.previewElement).find('.dz-remove-video').click(dzRemoveVideoClick);
                $(mockFile.previewElement).find('.dz-size').hide();
                $(mockFile.previewElement).data('video_id', vidId);

            });
        }
    });

    jrrny_video_dropzone.on("error", function (file, errorMessage, xhr) {
        alertify.error(errorMessage, 5);
        var remove_file = setTimeout(function () {
            jrrny_video_dropzone.removeFile(file);
            clearTimeout(remove_file);
        }, 5000);
    });
    jrrny_images_dropzone.on("error", function (file, errorMessage, xhr) {
        alertify.error(errorMessage, 5);
        var remove_file = setTimeout(function () {
            jrrny_images_dropzone.removeFile(file);
            clearTimeout(remove_file);
        }, 5000);
    });
    jrrny_images_dropzone.on("addedfile", function (file) {
        $("#jrrny-images-dropzone").sortable({
            items: "div.dz-image-preview",
            opacity: 0.7,
            revert: true,
            cursor: "move",
            tolerance: "pointer",
            distance: -120,
            delay: 10,
            zIndex: 9999,
            appendTo: document.body

        });
        $("#jrrny-images-dropzone").disableSelection();
        if (file.type.match(/image\/+(jpeg|jpg|png|gif)/ig)) {
            $(file.previewElement).append(dz_additional_img);
        }
        if ($(file.previewElement).find('.dz-additional').length) {
            var _this_dz_additional = $(file.previewElement).find('.dz-additional');
            _this_dz_additional.find('.dz-set-thumb').click(function () {
                //$(this).addClass('thumb-selected');
                //$("#jrrny-images-dropzone").find('.dz-set-thumb').not($(this)).removeClass('thumb-selected');
                //$('input#dz-addit-params').val(file.name);
            });
        }
        //Blocked sending form
        $('#journey-data-process').prop('disabled', true);
    });
    jrrny_video_dropzone.on("addedfile", function (file) {
        $("#jrrny-video-dropzone").sortable({
            items: "div.dz-video-preview",
            opacity: 0.7,
            revert: true,
            cursor: "move",
            tolerance: "pointer",
            distance: -120,
            delay: 10,
            zIndex: 9999,
            appendTo: document.body

        });
        $("#jrrny-video-dropzone").disableSelection();
        if (file.type.match(/video\/+(mp4|webm|ogg|3gp|mov)/ig)) {
            $(file.previewElement).append(dz_additional_vid);
        }
        if ($(file.previewElement).find('.dz-additional').length) {
            var _this_dz_additional = $(file.previewElement).find('.dz-additional');
            _this_dz_additional.find('.dz-set-thumb').click(function () {
            });
        }
        //Blocked sending form
        $('#journey-data-process').prop('disabled', true);
    });
    jrrny_images_dropzone.on("success", function (file, responseText) {
        if (responseText.status === 'ok') {
            var imgId = responseText.img_id;
            $('#form-journey').append('<input id="jrrny-image-' + imgId + '" type="hidden" name="images[]" value="' + imgId + '" />');
            $(file.previewElement).data('image_id', imgId);
            $(file.previewElement).find('.dz-set-thumb').click(dzSetThumbClick);
            $(file.previewElement).find('.dz-remove-image').click(dzRemoveClick);
            $(file.previewElement).find('.dz-repeat-image').click(dzRepeatClick);
            $(file.previewElement).find('.dz-undo-image').click(dzUndoClick);

            //Change src
            $(file.previewElement).find('img').attr('src', responseText.url);
            //Add first image as main
            if ($('#jrrny-main-image-id').val() == "") {
                $('#jrrny-main-image-id').val(imgId);
            }
        } else if (responseText.status === 'fail') {
            $(file.previewElement).find('.dz-additional .dz-set-thumb').html(responseText.msg);
        }
        //Unblock sending form
        $('#journey-data-process').prop('disabled', false);
    });
    jrrny_video_dropzone.on("success", function (file, responseText) {
        if (responseText.status === 'ok') {
            var videoId = responseText.video_id;
            console.log(videoId);
            $('#form-journey').append('<input id="jrrny-video-' + videoId + '" type="hidden" name="video[]" value="' + videoId + '" />');
            $(file.previewElement).data('image_id', videoId);
            $(file.previewElement).find('.dz-set-thumb').click(dzSetThumbClick);
            $(file.previewElement).find('.dz-remove-video').click(dzRemoveVideoClick);

            //Change src
            $(file.previewElement).find('img').attr('src', '/wp-content/themes/matador-child/images/video.png');

        } else if (responseText.status === 'fail') {
            $(file.previewElement).find('.dz-additional .dz-set-thumb').html(responseText.msg);
        }
        //Unblock sending form
        $('#journey-data-process').prop('disabled', false);
    });

});