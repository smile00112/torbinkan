<script>
  <!-- Start of Intercom Script -->
  window.intercomSettings = {
    app_id: "suoo0awq",
    store_domain: "<?php echo $_SERVER['HTTP_HOST'] ?>",
    name: "<?php echo $user_name ?>", // Full name
    email: "<?php echo $user_email ?>", // Email address
    import_profiles: "<?php echo count($saved_settings) ?>",
  };

  //create intercom object
  (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic("reattach_activator");ic("update",intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement("script");s.type="text/javascript";s.async=true;s.src="https://widget.intercom.io/widget/rtb0b21o";var x=d.getElementsByTagName("script")[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent("onload",l);}else{w.addEventListener("load",l,false);}}})()

  //declare intercom event tracker helper
  /*
   * Sends Intercom Event based on element intercom-tracked attr
   *
   * @param Event e The event from click
   *
   * @return boolean True, to allow cascade of event through DOM
  */
  function intercomTrack(e) {
    if (!$(e.target).attr('intercom-tracked'))
      var event = $(e.target).parent().attr('intercom-tracked');
    else
      var event = $(e.target).attr('intercom-tracked');

    if (event)
      Intercom('trackEvent', event);

    return true;
  }

  //bind itercom element click event to intercom helper
  $(document).ready(function () {$("#content").on('click', '[intercom-tracked]', intercomTrack);});
  <!-- End of Intercom Script -->
</script>
