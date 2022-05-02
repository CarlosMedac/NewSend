
$(document).ready(function(){
    $(".enlace").mouseenter(function(){
        $(this).children(".enl_nom").animate({
            width: 'toggle'
        });
    });
    $(".enlace").mouseleave(function(){
        $(this).children(".enl_nom").animate({
            width: 'toggle'
        });
      });
  });