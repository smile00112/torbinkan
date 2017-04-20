(function($){
     $.fn.extend({  
         mobilemenu: function() {       
            return this.each(function() {
            	
            	var $ul = $(this);
            	
				if($ul.data('accordiated'))
					return false;
													
				$.each($ul.find('li>div'), function(){
					$(this).data('accordiated', true);
					$(this).hide();
				});
				
				$.each($ul.find('span.head'), function(){
					$(this).click(function(e){
						activate(this);
						return void(0);
					});
				});
				
				var active = (location.hash)?$(this).find('a[href=' + location.hash + ']')[0]:'';

				if(active){
					activate(active, 'toggle');
					$(active).parents().show();
				}
				
				function activate(el,effect){
					$(el).parent('li').toggleClass('active').siblings().removeClass('active').children('ul, div').slideUp('fast');
					$(el).siblings('ul, div')[(effect || 'slideToggle')]((!effect)?'fast':null);
				}
				
            });
        } 
    }); 
})($);

$(document).ready(function () {
	
		enquire.register("only screen and (max-width: 990px)", {
		match : function() {
			
	       $('.menu').attr('class','menu1');
	       $('#ma-mobilemenu').css('display','none');
	       $('li.parent div').css('margin-left', '0px');		
           $("ul.mobilemenu li.parent").each(function(){
           $(this).append('<span class="head"><a href="javascript:void(0)"></a></span>');
           });
	
	$('ul.mobilemenu').mobilemenu();
	
	$("ul.mobilemenu li.active").each(function(){
		$(this).children().next("ul").css('display', 'block');
	});
	
		},
		 unmatch : function() {
			 $('.menu1').attr('class','menu');
			 $('#ma-mobilemenu').css('display','block');
			 $('li.parent div, .custombox > div, #topmenuaccount, #topbrand').removeAttr('style');
			 $('li.parent div ul').removeAttr('style');
			 $('li.parent div.topmenu').each(function(index, element) {
		         var menu = $('.menu').offset();
		         var dropdown = $(this).parent().offset();
		         i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('.menu').outerWidth());
		         if (i > 0) {
			     $(this).css('margin-left', '-' + (i + 5) + 'px');
		         }
	             });
              $('.head').remove();
		 }
});
});
(function($){
	$.fn.liMenuRespEasy = function(params){
		var p = $.extend({
			type:'button' //'button', 'select'
		}, params);
		return this.each(function(){
			menuWrap = $(this);
			if(p.type == 'button'){
				var btnNav = $('<div>').addClass('btnNav').css({opacity:'0.7'});
				var btnNavIcon = $('<span>').addClass('btnNavIcon');
				btnNavIcon.clone().appendTo(btnNav);
				btnNavIcon.clone().appendTo(btnNav);
				btnNavIcon.clone().appendTo(btnNav);
				menuWrap.before(btnNav);
				btnNav.on('click',function(){
					menuWrap.slideToggle('fast');
					return false;
				});
			}
			
		});
	};
})(jQuery);
$(function(){
	$('.navButton').liMenuRespEasy({
		type:'button' //'button', 'select'	
	});
});