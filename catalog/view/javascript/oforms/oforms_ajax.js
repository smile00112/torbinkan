function oformsAjax(form_id,product_id) {
    var form_id = form_id;
    $.ajax({
        url: 'index.php?route=information/form/ajax_form',
		type: 'post',
		data: 'form_id=' + form_id + '&product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			if(json['form'] ) {
			     $('head').append('<link href="catalog/view/theme/default/stylesheet/oforms.css" type="text/css" rel="stylesheet" />');
                 $.getScript('catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js');
                 $.getScript('catalog/view/javascript/oforms/oforms_module.js');
                 $.getScript('catalog/view/javascript/oforms/jquery.validate.js');
                 $.getScript('catalog/view/javascript/oforms/additional-methods.js');
                 $.getScript('catalog/view/javascript/oforms/jquery.form.js');
                 $.getScript('index.php?route=information/form/valid_lang');
                 $.getScript('index.php?route=information/form/valid_js_module&form_id='+form_id+'&fb=0');
                 $.getScript('index.php?route=information/form/dt_lang'); 
                 
			     
                 var maskHeight = $(document).height();
                 var maskWidth = $(window).width();
                 $('body').prepend('<div id="form-mask" onclick="closeForm()"></div>');
                 $('#form-mask').css({
			         position:'absolute',
                     left:0,
                     top:0,
                     'z-index':9000,
                     'background-color':'#000',
                     width:maskWidth,
                     height:maskHeight
			     }).fadeIn(1000).fadeTo("slow",0.8);
                 $('body').prepend('<div id="form-dialog">'+json['form']+'</div>');
                 $('#form-dialog .box-heading').append('<span id="close-form" onclick="closeForm()"></span>');
                 $('#form-dialog .box-heading').css({
                    position: 'relative'
                 });
                 $('#close-form').css({
                    position: 'absolute',
                    right: '5px',
                    top: '1px',
                    width: '29px',
                    height: '29px',
                    cursor: 'pointer',
                    background: 'url("catalog/view/theme/default/image/close-form.png")'
                 });
                 var winH = $(window).height();
                 var winW = $(window).width();
			     $('#form-dialog').css({
			         position:'absolute',
                     left:winW/2-268,
                     top:$(document).scrollTop() + 100,
                     width:'536px',
                     height:'400px',
                     'z-index':9999
			     }).fadeIn(2000);
			     
			}		
				
		}
        
    });
}

function closeForm() {
    $('#form-mask').remove();
    $('#form-dialog').remove();
}