$ = $ || jQuery;
$(document).ready(function(){
  console.log("deadant");
  var topSpacingBase = $('#wpadminbar').length == 0 ? 0 : 32;
  $("#header").sticky({
    topSpacing: topSpacingBase + 0
  });

  $("#wpp-list-sticky").parent().sticky({
    topSpacing: topSpacingBase + 62
  });

  $('.sidebar-holder div:first-child').sticky({
    topSpacing: topSpacingBase + 62
  });

  // ADD ASIDE FIX ON SCROLL
  // $("#post-sidebar-content").sticky({
  //   topSpacing: topSpacingBase + 62
  // });
})
