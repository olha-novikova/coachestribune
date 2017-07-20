jQuery(document).ready(function ($) {
   
    $(document).on('click', '#remove-account', function (event) { 
        event.preventDefault();   
        
        var html = '';
        html +='<div id="userDelConfirm"  class="modal" tabindex="-1" role="dialog">';
        html +='<div class="modal-dialog">';
        html +='<div class="modal-content">';
        html +='<div class="modal-header">';
        html +='<h4 class="modal-title">Are you sure?</h4>';
        html +='</div>';
        html +='<div class="modal-footer">';
        html +='<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        html +='<button id="user-del-ajax-btn" type="button" class="btn btn-primary">Delete my account&nbsp;<i class="fa processing-icon hide"></i></button>';
        html +='</div>';
        html +='</div>';
        html +='</div>';
        html +='</div>';
        $('body').append(html);
        $('#userDelConfirm').modal('show');
    });
    $(document).on('click', '#user-del-ajax-btn', function (event) { 
        event.preventDefault();           
        
        $.ajax({
            type: 'post',
            url: defineURL('ajaxurl'),
            data: {
                action: 'plc_remove_user'
            },            
            beforeSend: function(){
                    $("#user-del-ajax-btn .processing-icon")
                    .removeClass('hide fa-check')
                    .addClass('rotating fa-refresh');
            },
            complete: function (){
                    $("#user-del-ajax-btn .processing-icon")
                    .removeClass('rotating fa-refresh')
                    .addClass('hide fa-check');
            },
            success: function (response) {
                var result = $.parseJSON(response);
                if(result.status === "success"){                   
                    $('#userDelConfirm').modal('hide');
                    $('#userDelConfirm').remove();
                    window.location.href = defineURL('home');
                }   
                else{
                    $('#userDelConfirm .modal-header').append("We can't delete your account, try agin later or contact us");
                }
            }
        });
        
    });
    $(document).on('click', '.plc-pagination', function (event) {        
        event.preventDefault();   
        var id = $(this).attr('id');
        var button = $('#' + id);
        var atts = button.data('atts');
        var info = button.data('info');
        var paged = button.data('paged');
        var container = button.data('container');  
      
        $.ajax({
            type: 'post',
            url: defineURL('ajaxurl'),
            data: {
                action: 'plc_get_loop',
                atts: atts,
                info: info,
                paged: paged
            },            
            beforeSend: function(){
                    $(".plc-pagination .processing-icon")
                    .removeClass('hide fa-check')
                    .addClass('rotating fa-refresh');
            },
            complete: function (){
                    $(".plc-pagination .processing-icon")
                    .removeClass('rotating fa-refresh')
                    .addClass('hide fa-check');
            },
            success: function (response) {
                if(response.loop){
                    $(container).append(response.loop);
                    $(container + ' .ts-fade-in').each(function() {
                        $(this).removeClass('ts-fade-in');      
                        $(this).css('opacity', 1);                        
                    });
                }               
                button.data('atts', response.atts);
                button.data('info', response.info);
                button.data('paged', response.paged);
                if(response.is_final > 0){
                    button.addClass('hidden');
                }
            }
        });
    });
    
    
	$('#jrrny-edit-profile-form').submit(function (event){
		event.preventDefault();
		var form = $(this);
		$.ajax({
			 	type: 'post',
			 	url: $(this).attr('action'),
			 	data: new FormData(this),
			 	processData: false,
      			contentType: false,
			 	beforeSend: function(){
			 		$("#jrrny-edit-profile-btn .processing-icon")
			 		.removeClass('hide fa-check')
			 		.addClass('rotating fa-refresh');
			 	},
			 	complete: function (){
			 		$("#jrrny-edit-profile-btn .processing-icon")
			 		.removeClass('rotating fa-refresh')
			 		.addClass('hide fa-check');
			 	},
			 	success: function(response) {
					$("#profile-updated").addClass('hidden');
					$('.error-msg').each(function(){
						$(this).remove();
					});
			 			if(response.status == 'ok') {
							var data = form.serializeArray().reduce(function(obj, item) {
								obj[item.name] = item.value;
								return obj;
							}, {});

							var country = $('select option:selected', form).text();
							var location = data.city + ", " + country + "<br />" + "<a href='" + data.url + "'>" + data.url + "</a>";

							$('.location',$("#profile-data-bar")).html(location);
							$('.description',$("#profile-data-bar")).html(data.description);
							$('.name',$("#profile-data-bar")).html(data["first-name"] + ' ' + data["last-name"]);
							$("#jrrny-auhor-edit").modal("hide");
			 			}
			 			else {

			 				var msg = "<span class='error-msg'>" + response.msg + "</span>";
							$(msg).insertAfter("#" + response.type);

			 			}
			 		}
			});

	});

    $('.profile-tab', $("#profile-tabs")).click(function(){
        deleteClassesAndHide();
        $(this).addClass('active');
        var tab = $(this).data('tab');
        $('#tab-' + tab).addClass('visible');
    });

    function deleteClassesAndHide(){
        $('.tab-page', $("#main-container")).each(function(){
            $(this).removeClass('visible');
        });

        $('.profile-tab', $("#profile-tabs")).each(function(){
            $(this).removeClass('active');
        });
    }
	$('#jrrny-edit-header-form').submit(function (event){
		event.preventDefault();
                $('body').append(loader);
		$.ajax({
                    type: 'post',
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                    	$('#jrrny-edit-header-form-error').addClass('hidden');
                    },
                    success: function(response) {
                            if(response.status == 'ok') {
                                    $('#profile-header').attr('style', "background: url(" + response.url + "); background-size: cover;");
                                    $('.fileUpload span').html('Change image');
                                    $('#profile-header').removeClass('no-image');
                                    $('#profile-header').addClass('image');
                            }else {
                            	$('#jrrny-edit-header-form-error').html(response.msg);
                            	$('#jrrny-edit-header-form-error').removeClass('hidden');
                            }
                            $('#loader').remove();
                    },
                    complete: function (){
                            $("#jrrny-edit-header-form #jrrny-edit-header-file").val("");
                    }
		});
    });
    

    $("#jrrny-edit-header-file").change(function (event) {
        event.preventDefault();
        $("#jrrny-edit-header-form").submit();
    });

	$("#jrrny-auhor-edit #avatar-input").change(function (event) {
		event.preventDefault();
		$('#jrrny-edit-avatar-form').submit();
	});

	$('#jrrny-edit-avatar-form').submit(function (event){
		event.preventDefault();
		$.ajax({
			type: 'post',
			url: $(this).attr('action'),
			data: new FormData(this),
			processData: false,
			contentType: false,
			success: function(response) {
				if(response.avatar != ''){
					$('img.avatar',$("#avatar-wrapper")).attr("src",response.avatar);
					$('img.avatar',$("#jrrny-edit-avatar-form")).attr("src",response.avatar);
				}
			},
			complete: function (){
				$("#jrrny-auhor-edit #avatar-input").val("");
			}
		});
	});

    //Follow click
    $('#jrrny-author-follow-btn').click(function(e){
    	var action = ($(this).hasClass('followed')) ? 'unfollow': 'follow';
    	$.ajax({
		 	type: 'post',
		 	url: defineURL('ajaxurl'),
		 	data: {
		 		'user-id': $(this).data('user-id'),
		 		'action': action
		 	},
		 	success: function(response) {
				if(response.status == 'ok') {
					if(action === 'unfollow'){
						$('#jrrny-author-follow-btn').removeClass('followed');
					}else {
						$('#jrrny-author-follow-btn').addClass('followed');
					}
					$('#jrrny-author-follow-btn').html('<i class="fa fa-check"></i>&nbsp;following')
				}
		 	},
		});
    	e.preventDefault();
    });


});