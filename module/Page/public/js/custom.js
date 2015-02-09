(function($) {
 "use strict";

    // Page Preloader
    $(window).load(function() {
        $(".loader").delay(300).fadeOut();
        $(".animationload").delay(600).fadeOut("slow");
    });


// DM Top
    jQuery('.top').click(function(){
        jQuery('html, body').animate({scrollTop: '0px'}, 800);
        return false;
    });
    // TOOLTIP
    $('.social, .entry').tooltip({
      selector: "[data-toggle=tooltip]",
      container: "body"
    })


     $('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    autoPlay: true,
    responsiveClass:true,
    responsive:{
        0:{
            items:1,
            nav:true,
        },
        600:{
            items:3,
            nav:false,
        },
        1000:{
            items:5,
            nav:true,
            loop:true
        }
    }
})

})(jQuery);