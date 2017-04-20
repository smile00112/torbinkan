var lang = {
	/*'process': 'Обработка...',
	'send': 'Отправить',*/
	'error_form_id': 'Неизвестный идентификатор формы!'
};

var application_context = '.quick-order-pro';

$(document).ready(function() {
	
	var product_context = '.product-info';
	
	checkRequired();
	
	$(product_context + " textarea, " + product_context + " input, " + product_context + " select", product_context).live('change', function(){
		
		if ($('.product-info-block .options', application_context).length == 0 && $('.total', application_context).length == 0) return;
		
		var data = product_context + ' input[type=\'text\'], ' + product_context + ' input[type=\'hidden\'], ' + product_context + ' input[type=\'radio\']:checked, ' + product_context + ' input[type=\'checkbox\']:checked, ' + product_context + ' select, ' + product_context + ' textarea';
	
		ajax.send({
			data: $(data),
			a: 'p',
			success: function(json) {
				
				if (json['status'] == 'ok') {
					
					$(application_context).each(function(){
						
						var context = this;
						
						var show_option = $('.product-info-block .options', context).length;
						var show_total = $('.total', context).length;
				
						if (json.hasOwnProperty('total') && show_total) { // show total
							$('.total b', context).html(json['total']);
						}
						
						if ($(context).hasClass('sidebar')) {
							var quantity = json['quantity'];
						} else {
							var quantity = json['quantity'] + ' шт.';
						}
						
						$('.product-info-block .quantity', context).html(quantity);
									
						if (json['options'] && show_option) {
							
							var html = '';
								
							for (var option in json['options']) {
								html += '<li>- <small>' + json['options'][option]['name'] + ': ' + json['options'][option]['value'] + '</small></li>';
							}
							
							$('.product-info-block .options', context).html(html);
							
						}
						
					});
				}
			}
		});
			
	});
	
	if($().mask) {
		$('input[type="text"][data-mask]', application_context).each(function(){
			$(this).mask($(this).attr('data-mask'));
		})
	}
	
	$('.captcha-wrapper a.captcha', application_context).click(function(event){
		
		event.preventDefault();
		
		var self = this;
		var context = $(self).closest(application_context);
		var form_id = $('input:hidden[name=form_id]', context).val();
		
		if (form_id) {
			
			$.ajax({
				url: "index.php?route=module/quick_order_pro/captcha&a=reload&key=" + form_id,
				dataType: 'json',
				cache: false,
				success: function(data) {
					$(self).prev('img').attr('src', decodeURI(data['url']));
				}
				
			});
			
		} else {
			alert(lang['error_form_id']);
		}
		
	});
	
	$('button.disabled', application_context).click(function(event) {
		
		var button = $(this).parent();
		var form = $(this).closest('form');
		var errors = getErrorElements(form);
		
		if (errors.length) {
			
			$(errors[0][0]).focus();
			
			$('.row.button .error', form).remove();
			$('.row.button', form).append('<span class="error">' + getErrorEmptyFieldsMessage(errors.length) + '</span>');
		}
		
	});
	
	$('a.js.req').live('click', function(){
		
		var errors = getErrorElements($(this).closest('form'));
		
		if (errors.length) {
			$(errors[0][0]).focus();
		}
		
	});
	
	$('form', application_context).submit(function(event) {
		
		event.preventDefault();
		
		var context = $(this).closest(application_context);
		var position = $(context).hasClass('content') ? 'content' : 'sidebar';
		
		if ($(this).data('disabled') === true || $('.button button', context).hasClass('disabled')) return;
		
		var form_info = $('input[type=\'text\'], input[type=\'password\'], input[type=\'hidden\'], input[type=\'radio\']:checked, input[type=\'checkbox\']:checked, textarea, select', context);
		
		var product_info = $('input[type=\'text\'], input[type=\'hidden\'], input[type=\'radio\']:checked, input[type=\'checkbox\']:checked, select, textarea', product_context);
		
		var data = jQuery.param(form_info) + '&' + jQuery.param(product_info, true);
		
		var button = $('form', context).find('button:submit');
		
		ajax.send({
			data: data,
			a: 'o',
			beforeSend: function() {
				
				clearNotification()
				
				$('.error, .error-bubble', context).remove();
				$('.notification', context).empty();
				$('input, textarea', context).removeClass('error-item');
				
				if ($(button).data('origin') == undefined) {
					$(button).data('origin', $(button).text());
				}
				
				$('form', context).data('disabled', true);
				$(button).addClass('disabled').find('span').text($(button).data('process'));
			},
			complete: function() {
				$('form', context).data('disabled', false);
				$(button).removeClass('disabled').find('span').text($(button).data('origin'));
			},
			success: function(json) {
				
				if (json['status'] == 'ok') {
					
					if (json['redirect']) {
						location = json['redirect'];
					}
					
					if (json['success']) {
						clearForm($('form', context));
						showMessage('success', json['success'], context);
					}
					
				} else if (json['status'] == 'error') {
					
					if (json['error']['warning']) {
						showMessage('warning', json['error']['warning'], context);
					}
					
					if (json['error']['system']) {
						showMessage('warning', json['error']['system'], context);
					}
					
					if (json['error'].hasOwnProperty('fields')) {
					
						$('.error', context).remove();
					
						for (i in json['error']['fields']) {
							
							var field = $('*[id^=field-edit-' + i + ']', context);
							
							if ($(field).length) {
								
								if (position == 'content') {
									var message = '<span class="error-bubble"><span class="error-message">' + json['error']['fields'][i] + '</span><br /><span class="arrow-border"></span><span class="arrow"></span></span>';
								} else {
									var message = '<span class="error">' + json['error']['fields'][i] + '</span>';
								}
								
								$(field).after(message);
							}
						}
					}
				}
			}
		});
		
	});
	
	$(application_context + ' input, ' + application_context + ' select, ' + application_context + ' textarea').live('keyup blur change', function(){
		checkRequired($(this).closest(application_context));
	});
	
});

function getErrorEmptyFieldsMessage(count) {
	return 'Осталось <a href="javascript:void(0);" class="js req">заполнить</a> <span class="count-errors">' + declination(count, ['поле', 'поля', 'полей']) + '</span>.';
}

function declination(number, titles) {  
	var cases = [2, 0, 1, 1, 1, 2];
	return number + " " + titles[(number%100 > 4 && number %100 < 20) ? 2 : cases[Math.min(number%10, 5)]];  
}

function checkRequired(context) {
	
	if (!context) {
		context = application_context;
	}
	
	var errors = getErrorElements(context);
	var error_message = $('form', context).find('div.row.button .error');
	
	if (errors.length == 0) {
		$(error_message).remove();
		$('form', context).find('button:submit').removeClass('disabled');
	} else {
		if ($(error_message).length) {
			$('.count-errors', error_message).html(declination(errors.length, ['поле', 'поля', 'полей']));
		}
		
		$('form', context).find('button:submit').addClass('disabled');
	}
	
}

function getErrorElements(context) {
	
	var error = [];
	
	$('.rows .row', context).each(function(){
		
		if ($('span.required', this).length) {
			
			if ($('input:text', this).length) {
				var item = $('input:text', this);
			} else if ($('textarea', this).length) {
				var item = $('textarea', this);
			} else if ($('input:password', this).length) {
				var item = $('input:password', this);
			} else if ($('select', this).length) {
				var item = $('select', this);
			} else if ($('input:radio', this).length) {
				var item = $('input:radio', this);
			} else if ($('input:checkbox', this).length) {
				var item = $('input:checkbox', this);
			}
			
			if ($(item).is(':checkbox') && !$(item).is(':checked')) {
				error.push(item);
			} else if ($(item).is(':radio') && !$(item).is(':checked')) {
				error.push(item);
			} else if ($(item).val() == '') {
				error.push(item);
			} else {
				$('.error, .error-bubble', this).remove();
			}
		}
	});
	
	return error;
}

/**
 * clear form after submit
 */
function clearForm(context) {
	if ($(context).length) {
		$('input, textarea, select', context).each(function(){
			
			if ($(this).is('select')) {
				$('option:selected', this).each(function(){
					this.selected=false;
				})
			} else if ($(this).is('input:radio') || $(this).is('input:checkbox')) {
				$(this).attr('checked','').removeAttr('checked');
			} else {
				$(this).val('');
			}
		});
	}
}

/**
 * clear main message 
 */
function clearNotification() {
	$('.success, .warning, .attention, .information').remove();
}

/**
 * show main message: success, notocation, warning...
 */
function showMessage(type, message, context) {
	
	if (!type) {
		type = 'success';
	}
	
	var driver = new messageController(context).showMessage(type, message);
}

function messageController(parent) {
	
	clearNotification();
	
	this.showMessage = function(type, message){
	
		if ($('#notification').length) {
			
			var context = '#notification';
			
			$(context).html('<div class="' + type + '" style="display: none;">' + message + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
			$('.' + type, context).fadeIn('slow');
			
		} else if (parent && $('.notification', parent).length) {
			$('.notification', parent).html('<div class="' + type + '">' + message + '</div>');
		} else {
			alert(message);
		}
	}
}

var ajax = {
	disabled: null,
	clear: function() {
		this.disabled = null;
	},
	send: function (data) {
		
		var defaults = {
			url: 'index.php?route=module/quick_order_pro/ajax',
			type: 'POST',
			async: true,
			dataType: 'json',
			processData: true,
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				showMessage('warning', textStatus);
			}
		}
		
		var options = $.extend(defaults, data);
		
		if (!options.hasOwnProperty('a')) {
			return;
		}
		
		if (!options.hasOwnProperty('data')) {
			return;
		}
		
		// Convert data if not already a string 
		if (options.processData && typeof options.data !== "string") { 
			if (typeof options.processData === "function") { 
				options.data = options.processData(options.data) 
			}else{ 
				options.data = jQuery.param(options.data); 
			} 
		} 
		 
		options.data += '&a=' + options.a;
		
		options.success = function (response) {
			
			if (data.hasOwnProperty('success') && typeof data.success === 'function') {
				data.success(response);
			}
			
		}
		
		return $.ajax(options).responseText;
	}
}