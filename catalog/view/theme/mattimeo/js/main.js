 $(document).ready(function() {
 
//Top menu	
	 $('div.topmenu_theme').removeAttr('style');
	
//Addition images

      $(".owl-addimage").owlCarousel({
      navigation : true,
      pagination : false,
      singleItem : true,
      });

	 
// Categories module 
	$(".box-category .accordeon_plus").each(function(index, element) {
	
		if($(this).parent().hasClass('cat-active') == true){
			$(this).addClass('open');
			$(this).next().addClass('active');
		}	
	});
	$(".box-category .accordeon_plus").click(function(){ 
		if($(this).next().is(':visible') == false) {
			$('.box-category .accordeon_subcat').slideUp(300, function(){
				$(this).removeClass('active');
				$('.accordeon_plus').removeClass('open');
			});
		}
		if($(this).hasClass('open') == true) {
			$(this).next().slideUp(300, function(){
				$(this).removeClass('active');
				$(this).prev().removeClass('open');
			});
		}else{
			$(this).next().slideDown(300, function(){
				$(this).addClass('active');
				$(this).prev().addClass('open');
			});
		}
	}); 
	
//Page of Categories	
	$(".accordeon_description .accordeon_plus").each(function(index, element) {
	
		if($(this).parent().hasClass('cat-active') == true){
			$(this).addClass('open');
			$(this).next().addClass('active');
		}	
	});
	$(".accordeon_description .accordeon_plus").click(function(){ 
		if($(this).next().is(':visible') == false) {
			$('.accordeon_description .view').slideUp(300, function(){
				$(this).removeClass('active');
				$('.accordeon_plus').removeClass('open');
			});
		}
		if($(this).hasClass('open') == true) {
			$(this).next().slideUp(300, function(){
				$(this).removeClass('active');
				$(this).prev().removeClass('open');
			});
		}else{
			$(this).next().slideDown(300, function(){
				$(this).addClass('active');
				$(this).prev().addClass('open');
			});
		}
	}); 
	
	
//full slidershow
if ($("div").is(".full_container")){	
   $(".main.topmain").css("padding",0);
}

		   
//plus mines button in qty

		  var elm = $('#htop');
				  function spin( vl ) {
					elm.val( parseInt( elm.val(), 10 ) + vl );
				  }
				  $('#increase').click( function() { spin( 1 );  } );
				  $('#decrease').click( function() { if (elm.val () > 0 ){spin( -1 ); } });	   
		   
// success
	$("body").click(function(e) {
	if($(e.target).closest('#notification .success').length==0) $('#notification .success').remove();
     });
	 
//mobile menu
$('.btn-navbar').click(function() {
		
		var chk = 0;
		if ( $('#navbar-inner').hasClass('navbar-inactive') && ( chk==0 ) ) {
			$('#navbar-inner').removeClass('navbar-inactive');
			$('#navbar-inner').addClass('navbar-active');
			$('#ma-mobilemenu').css('display','block');
			chk = 1;
		}
		if ($('#navbar-inner').hasClass('navbar-active') && ( chk==0 ) ) {
			$('#navbar-inner').removeClass('navbar-active');
			$('#navbar-inner').addClass('navbar-inactive');			
			$('#ma-mobilemenu').css('display','none');
			chk = 1;
		}
			
	});    
	
	
//no responsive
	       enquire.register("only screen and (min-width: 1170px)", {
			  match : function() {
            $(".full_container").removeClass("fixwidth"); 

			  }
		    }).register("only screen and (max-width: 1169px)", {
			  match : function() {
           $(".full_container").addClass("fixwidth");

			  }
		    }); 	

//move panels
	
	$("#box-facebook .icon-facebook").toggle(function(){
		$("#box-facebook").animate({right:'280px'},500);}, function() {
		$("#box-facebook").animate({right:'0px'},500);
	});
	
	$("#box-twitter .icon-twitter").toggle(function(){
		$("#box-twitter").animate({right:'280px'},500);}, function() {
		$("#box-twitter").animate({right:'0px'},500);
	});
	
		$("#box-vkt .icon-vkt").toggle(function(){
		$("#box-vkt").animate({right:'280px'},500);}, function() {
		$("#box-vkt").animate({right:'0px'},500);
	});   
	
 $(".sb-icon-search").click(function() {
	 $(this).css({'display':'none'});
	  $('.sb-search-submit').css({ 'display':'block'});
	   $('.sb-search-input').css({'display':'block'});
     });

	 
	  $(document).click( function(event){
      if( $(event.target).closest(".sb-search").length ) 
        return;
        $('.sb-icon-search').removeAttr('style');
		$('.sb-search-input').removeAttr('style');
		$('.sb-search-submit').removeAttr('style');
      event.stopPropagation();
    });
	 

	});
	      