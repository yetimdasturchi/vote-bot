(function ( $ ) {
	window.emojiPicker = new EmojiPicker({
		emojiable_selector: '[data-emojiable=true]',
        assetsPath: '/assets/libs/emoji-picker/lib/img/',
        popupButtonClasses: 'fa fa-smile-o',
        onShow: function () {
	       	var emg = setInterval(() => {
		    	if ($('.emoji-menu:visible').length >= 1) {
					var Left = parseInt($('.emoji-menu:visible').css('left'))
					Top = parseInt($('.emoji-menu:visible').css('top'));
					$('.emoji-menu:visible').css({ left: (Left - 50) + 'px', top: (Top - 220) + 'px' }).addClass('visible');
					clearInterval(emg);
				}
			}, 20);
		},
		onHide: function () {
			$('.emoji-menu').removeClass('visible');
		}
	});

	var chat_request_is_sending = false;
	var chat_request = function( url, data, callback ) {
		if ( chat_request_is_sending ) return;

		$.ajax({
			url: url,
            type: "POST",
            data: data,
            dataType: 'json',
            beforeSend: function() {
            	chat_request_is_sending = true;
            },
            complete: function() {
            	chat_request_is_sending = false;
            },
            success: function(res){
            	res = objToFunc( res );
                if ( res.status ) {
                  
                  if ( res.hasOwnProperty('messages') ) {
                    $.each(res.messages, function (key, val) {
                      $.growl.warning({ title:"", message: val, location: "tr"});
                    });
                  }

                  if ( res.hasOwnProperty('_callback') ) {
                    res._callback();
                  }

                  if ( callback != undefined ) {
                  	callback(res);
                  }
                }
            },
            error: function(e) {
            	if (e.status == 400) {
                  e.responseJSON = objToFunc( e.responseJSON );
                  if ( !e.responseJSON.status ) {
                    if ( e.responseJSON.hasOwnProperty('messages') ) {
                      $.each(e.responseJSON.messages, function (key, val) {
                        $.growl.error({ title:"", message: val, location: "tr"});
                      });
                    }

                    if ( e.responseJSON.hasOwnProperty('_callback') ) {
                      e.responseJSON._callback();
                    }
                  }
                }
            }
        });
	}

	var show_chat_window = function( url ) {
		chat_request(url, {query: ''}, function(res) {
			$('.chat-placeholder').attr("style", "display: none!important");;
			$('.chat-window').html( res.html );
			$('.chat-window').show();
			window.emojiPicker.discover();
		});
	}

	$( document ).on('keyup',function(evt) {
    	if (evt.keyCode == 27) {
       		$('.chat-placeholder').removeAttr("style");
			$('.chat-window').html('');
			$('.chat-window').hide();
    	}
	});

	$( document ).on('click', '.chat-emoji-picker', function(e) {
		e.preventDefault();
    	$('.emoji-picker-icon').trigger('click');


    	//$('.emoji-menu').css({top: ($(this).offset().top - 360) + 'px'});
	});

	$(document).one('focus.textarea', 'textarea.chat-input', function(){
		var savedValue = this.value;
        this.value = '';
        this.baseScrollHeight = this.scrollHeight;
        this.value = savedValue;
    }).on('keydown.textarea', 'textarea.chat-input', function(e){
    	if (e.keyCode === 13 && e.ctrlKey) {
    		alert('submit');
    		return;
    	}
    }).on('input.textarea', 'textarea.chat-input', function(e){
    	var minRows = this.getAttribute('data-min-rows')|0, rows;
        this.rows = minRows;
        rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 17);
        this.rows = minRows + rows;
    });

	$(document).on('keydown', '.chat-search-box input', function(e) {
		var query = $(this).val();
		var url = $(this).data('url');
		
		var send_request = false;
		if ( query.length == 0 ) {
			send_request = false;
		}else if ( e.which == 13 ) {
			send_request = true;
		}

		if ( send_request ) {
			chat_request(url, {query: query}, function(res) {
				if ( res.hasOwnProperty('data') ) {
					$('#chats-datalist').html('');
                    $.each(res.data, function (key, val) {
                    	$('#chats-datalist').append(`<option value="${val.chat_id}" data-url="${val.url}">${val.text}</option>`);
                	});
				}
			});
		}
	});

	$('.chat-search-box input').on('input', function() {
    	var val = this.value;
    	var url = url;
    	var $that = $(this);
    	if($('#chats-datalist option').filter(function(){
    		url = $(this).data('url');
        	return this.value.toUpperCase() === val.toUpperCase();        
    	}).length) {
        	$('#chats-datalist').html('');
        	$that.val('');
        	show_chat_window( url );
    	}
  	});

  	$(document).on('click', '[data-chat]', function(e) {
		var chat_id = $(this).attr('data-chat');
		show_chat_window( chat_id );
	});
})(jQuery);