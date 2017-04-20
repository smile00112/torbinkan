jQuery(document).ready(function(){
  $(".oform input, .oform textarea").focus(function(){
    if ($(this).attr("value") == $(this).attr("title"))
      $(this).attr("value", "")
  });

  $(".oform input, .oform textarea").blur(function(){
    if ($(this).attr("value") == "")
      $(this).attr("value", $(this).attr("title"))
  });
});