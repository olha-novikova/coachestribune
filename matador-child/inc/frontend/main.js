jQuery(document).ready(function ($) {
    var deleteJrrnyAjax = function (postId) {
        $.ajax({
            url: defineURL('ajaxurl'),
            method: "post",
            data: {
                action: "delete_post",
                "post-id": postId
            },
            success: function (response) {
                if (response.status == "ok") {
                    var redirect = true;
                    $('[id^="post-' + response['post-id'] + '"]').each(function () {
                        $(this).remove();
                        redirect = false;
                    });
                    if (redirect) {
                        var html = '';
                        html += '<div id="jrrnyDelMsg"  class="modal" tabindex="-1" role="dialog">';
                        html += '<div class="modal-dialog">';
                        html += '<div class="modal-content">';
                        html += '<div class="modal-header">';
                        html += '<h4 class="modal-title">Jrrny successfully removed</h4>';
                        html += '</div>';
                        html += '<div class="modal-footer">';
                        html += '<button id="jrrny-del-msg-ajax-btn" type="button" class="btn btn-primary">OK</button>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        $('body').append(html);
                        $('#jrrnyDelMsg').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    }
                } else {
                    console.log(response.msg);
                }
            }
        });
    };

    $("body").on("click", "#jrrny-del-msg-ajax-btn", function (e) {
        e.preventDefault();
        $('#jrrnyDelMsg').modal('hide');
        $('#jrrnyDelMsg').remove();
        window.location.href = defineURL('home');
    });

    $("body").on("click", ".meta-item-like", function (event) {
        event.preventDefault();
        var jrrny = $(this).attr("data-on-post");
        var author = $(this).attr('data-author');
        var that = $(this);
        if (!that.hasClass('login_modal')) {
            $.ajax({
                url: defineURL('ajaxurl'),
                method: "post",
                data: {
                    action: "like_the_jrrny",
                    jrrny: jrrny,
                    "event": "like",
                    author: author
                },
                success: function (response) {
                    if (response.liked == "liked" && response.quantity > 0) {
                        that.parent('span').addClass("liked");
                        that.find(".likes-quant").text(response.quantity);
                        that.find(".likes-quant").show();
                        that.find('span.like-text').html('unlike');
                    } else if (response.liked == "unliked") {
                        that.parent('span').removeClass("liked");
                        that.find(".likes-quant").text(response.quantity);
                        if (response.quantity <= 0) {
                            that.find(".likes-quant").hide();
                        }
                        that.find('span.like-text').html('like');
                    }
                }
            });
        }
    });

    $("body").on("click", ".meta-item-follow", function (event) {
        event.preventDefault();
        var user_id = $(this).attr('data-author');
        var following = $(this).attr('data-following');
        var that = $('.meta-item-follow');
        var action = 'follow';
        if (following > 0) {
            action = 'unfollow';
        }
        if (!$(this).hasClass('disabled')) {
            $.ajax({
                url: defineURL('ajaxurl'),
                method: "post",
                data: {
                    action: action,
                    'user-id': user_id
                },
                success: function (response) {
                    if (response.status == "ok") {
                        that.each(function () {
                            that_user_id = $(this).attr('data-author');
                            if (user_id === that_user_id) {
                                $(this).addClass("disabled");
                                $(this).attr('data-following', response.following);
                                $(this).html(response.text);
                            }
                        });
                        if (action === 'unfollow') {
                            $('#jrrny-author-' + user_id).fadeOut("slow", function () {
                                $(this).remove();
                            });
                        }
                    }
                }
            });
        }
    });

    $('body').on('click', '#login-out-lnk', function (event) {
        event.preventDefault();
        $('#jrrny_lostpass_modal').modal('hide');
        if (!$(this).hasClass('login_modal')) {
            $.ajax({
                url: defineURL('ajaxurl'),
                type: 'post',
                data: {
                    action: 'login_out_user',
                    "event": "_header_top_login_out_user"
                },
                success: function (response) {
                    if (response && typeof $.parseJSON(response) == 'object') {
                        var result = $.parseJSON(response);
                        if (result["loggedin"] == "no") {
                            window.location.href = defineURL('home') + "#login-form";
                        } else {
                            window.location.href = defineURL('home');
                        }
                    }
                }
            });
        }
    });

    $("body").on("click", ".jrrny-delete-post", function (event) {
        event.preventDefault();

        var postId = $(this).attr("data-on-post");
        var html = '';
        html += '<div id="jrrnyDelConfirm"  class="modal" tabindex="-1" role="dialog">';
        html += '<div class="modal-dialog">';
        html += '<div class="modal-content">';
        html += '<div class="modal-header">';
        html += '<h4 class="modal-title">Are you sure?</h4>';
        html += '</div>';
        html += '<div class="modal-footer">';
        html += '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        html += '<button id="jrrny-del-ajax-btn" type="button" class="btn btn-primary">Delete</button>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        $('body').append(html);
        $('#jrrnyDelConfirm').modal('show');
        $('#jrrny-del-ajax-btn').click(function (e) {
            e.preventDefault();
            deleteJrrnyAjax(postId);
            $('#jrrnyDelConfirm').modal('hide');
            $('#jrrnyDelConfirm').remove();
        });

    });

    //Show login form
    /*if(window.location.hash == '#login-form'){
     setTimeout(function(){
     $('#login-out-lnk').trigger( "click" );
     }, 2000);
     }*/

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    });
});
var jrrny_check_like = function (postLike) {
    var postId = postLike.data('on-post');
    if ($.inArray(postId.toString(), window.jrrnyCheckLikeArr) != -1) {
        postLike.parent('span').addClass('liked');
        postLike.find('.like-text').html('unlike');
    } else {
        postLike.parent('span').removeClass('liked');
        postLike.find('.like-text').html('like');
    }
};
var jrrny_check_followed = function (postFollow) {
    var authorId = postFollow.data('author');
    if ($.inArray(authorId.toString(), window.jrrnyCheckFollowedArr) != -1) {
        postFollow.data('following', 1);
        postFollow.html('following');
    } else {
        postFollow.data('following', 0);
        postFollow.html('follow');
    }
};
