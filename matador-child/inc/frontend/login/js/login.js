var $ = jQuery.noConflict();
function clean_site_after_login() {
        $.ajax({
            type: 'post',
            url: defineURL('ajaxurl'),
            data: {
                action: 'refresh_after_login'
            },
            success: function (response) {
                if ($('#top-main-menu').length) {
                    $('#top-main-menu').html(response.menu);
                }
                jrrnyCheckLikeArr = response.liked;
                jrrnyCheckFollowedArr = response.followed;


                if ($('#header_btn').length) {
                    if ($('#header_btn').hasClass('login_modal')) {
                        $('#header_btn').removeClass('login_modal');
                    }
                }
                if ($('#signup_btn').length) {
                    if (!$('#signup_btn').hasClass('disabled')) {
                        $('#signup_btn').addClass('disabled');
                    }
                }
                if ($('#signin_btn').length) {
                    if (!$('#signin_btn').hasClass('disabled')) {
                        $('#signin_btn').addClass('disabled');
                    }
                }
                if ($('.login_modal').length) {
                    $('.login_modal').removeClass('login_modal');
                }               
                
                if ($('.fb-login').length) {
                    if (!$('.fb-login').hasClass('disabled')) {
                        $('.fb-login').addClass('disabled');
                    }
                }
                if ($('.tw-login').length) {
                    if (!$('.tw-login').hasClass('disabled')) {
                        $('.tw-login').addClass('disabled');
                    }
                }
                if ($('.meta-item-like').length) {
                    if ($('.meta-item-like').hasClass('disabled')) {
                        $('.meta-item-like').removeClass('disabled');
                    }
                    if ($('.meta-item-like').hasClass('login_modal')) {
                        $('.meta-item-like').removeClass('login_modal');
                    }
                    $('.meta-item-like').each(function () {
                        jrrny_check_like($(this));
                    });
                }
                if ($('.meta-item-follow').length) {                    
                    $('.meta-item-follow').each(function () {
                        jrrny_check_followed($(this));
                    });
                }
                
                if ($('#wizzard').length) {
                    clean_wizzard();
                }
                
            }
        });
    };

jQuery(document).ready(function ($) {
    var top_upload = false;
    
    $(document).on('click', '.login_modal', function (event) {
        
        event.preventDefault();
        
        if (!$(this).hasClass('disabled')) {
            if ($(this).data('form')) {
                top_upload = '#top_upload';
            }
            else {
                top_upload = false;
            }
            $.ajax({
                type: 'post',
                url: defineURL('ajaxurl'),
                data: {
                    action: 'get_modal',
                    modal: 'login_form_modal',
                    current_url: defineURL('current_url')
                },
                success: function (response) {
                    if ($('#login_form_wrapper').length) {
                        $('#login_form_wrapper').remove();
                    }
                    $('body').append(response.msg);
                    $('#login_form_wrapper').modal('show');
                    //window.location = '#login-form';
                }
            });
        }
    });

    $(document).on('click', '#login_btn', function (event) {
        event.preventDefault();
        $('#login_form').submit();
    });

    $(document).on('submit', '#login_form', function (event) {
        event.preventDefault();
        $(".user-message").remove();
        var validate = new Validate('#login_form', ["#login_form #email", "#login_form #password"], ".form-group");
        validate.messages.pass = "<i class=\"err-message user-message\">(Provided password is incorrect!)</i>";
        validate.messages.email = "<i class=\"err-message user-message\">(Provided email is incorrect!)</i>";
        var that = $(this);
        if (validate.validated()) {
            var email = $("#login_form #email");
            var password = $("#login_form #password");
            var jrrny = {};
            jrrny.seted = true;
            if ($("input[name=place]").length && $("input[name=place]").val() !== "") {
                var place = $("input[name=place]").val();
                jrrny.place = place;
            }
            if ($("input[name=activity]").length && $("input[name=activity]").val() !== "") {
                var activity = $("input[name=activity]").val();
                jrrny.activity = activity;
            }
            var contest = $('#contest').val();
            var referral_url = $('#referral_url').val();
            var referral_user_id = $('#referral_user_id').val();
            $.ajax({
                type: 'post',
                url: defineURL('ajaxurl'),
                data: {
                    action: 'login_user',
                    redirect: $("#redirect").val(),
                    user: {
                        email: email.val(),
                        password: password.val(),
                    },
                    jrrny: jrrny,
                    contest: contest,
                    referral_user_id: referral_user_id,
                    referral_url: referral_url
                },
                beforeSend: function () {
                    $("#login_btn .processing-icon")
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
                },
                complete: function () {
                    $("#login_btn .processing-icon")
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
                },
                success: function (response) {
                    if (response.status == 'ok') {

                        $('#login_form_wrapper').modal('hide');
                        clean_site_after_login();
                        
                        if (contest && !$('#wizzard').length) {
                            if ($('#contest_sharre_modal').length) {
                                $('#contest_sharre_modal').remove();
                            }
                            $('body').append(response.modal);
                            $('#contest_sharre_modal').modal('show');
                        }
                        else if (top_upload) {
                            $(top_upload).submit();
                        }

                    }
                    else {
                        if (response.status == 'fail') {
                            email.after("<i class=\"err-message user-message\">" + response.msg + "</i>");
                        } else {
                            email.after("<i class=\"err-message user-message\">Undefined eror.!</i>");
                        }
                    }
                }
            });
        }
    });
    $(this).find('input').keypress(function (e) {
        if (e.which == 10 || e.which == 13) {
            $(this).submit();
        }
    });

    $(document).on("click", "#signup_btn", function (event) {
        event.preventDefault();
        if (!$(this).hasClass('disabled')) {
            $("#signup_form").submit();
        }
    });
    $(document).on("submit", "#signup_form", function (event) {
        event.preventDefault();
        $(".user-message").remove();
        var validate = new Validate("#signup_form", [
            "#signup_form #email",
            "#signup_form #password",
            "#signup_form #username"
        ], ".input-group");
        var that = $(this);
        if (validate.validated()) {
            var track_signup = $('#track_signup').val();
            var contest = $('#contest').val();
            var referral_user_id = $('#referral_user_id').val();
            var referral_url = $('#referral_url').val();
            $.ajax({
                type: 'post',
                url: defineURL('ajaxurl'),
                data: {
                    action: 'signup_user',
                    //user: {
                        email: $('#signup_form #email').val(),
                        password: $('#signup_form #password').val(),
                        username: $('#signup_form #username').val(),
                    //},
                    track_signup: track_signup,
                    contest: contest,
                    referral_user_id: referral_user_id,
                    referral_url: referral_url
                },
                beforeSend: function () {
                    $("#signup_btn .processing-icon")
                        .removeClass('hide fa-check')
                        .addClass('rotating fa-refresh');
                },
                complete: function () {
                    $("#signup_btn .processing-icon")
                        .removeClass('rotating fa-refresh')
                        .addClass('hide fa-check');
                },
                success: function (response) {
                    if (response.status == 'ok') {
                        $('#signup_form')[0].reset();
                        $.ajax({
                            type: 'post',
                            url: defineURL('ajaxurl'),
                            data: {
                                action: 'get_modal',
                                modal: 'signup_modal'
                            },
                            success: function (rsp) {
                                if (contest && !$('#wizzard').length) {
                                    if ($('#contest_sharre_modal').length) {
                                        $('#contest_sharre_modal').remove();
                                    }
                                    $('body').append(rsp.modal);
                                    $('#contest_sharre_modal').modal('show');
                                }
                                else if(!contest && !$('#wizzard').length){
                                    if ($('#successfully_signup').length) {
                                        $('#successfully_signup').remove();
                                    }
                                    $('body').append(rsp.msg);
                                    $('#successfully_signup').modal('show');
                                }
                                fbq('track', 'CompleteRegistration');
                                clean_site_after_login();
                                var taboola_tracking = '<img src="//trc.taboola.com/jrrny-sc/log/3/action?name=SignUp&item-url=' + defineURL('home') + '" width="0" height="0" />';
                                $('body').append(taboola_tracking);
                            }
                        });
                    } else if (response.type && response.status == 'fail') {
                        $("#signup_form #" + response.type).after("<i class=\"err-message user-message\">(" + response.msg + "!)</i>");
                        $("#signup_btn").removeClass('disabled');
                    } else {
                        $("#signup_form #password").after("<i class=\"err-message user-message\">(Undefined error!)</i>");
                        $("#signup_btn").removeClass('disabled');
                    }
                }
            });
        }
    });

});