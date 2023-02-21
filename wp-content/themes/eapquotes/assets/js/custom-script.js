jQuery(document).ready(function($){
    $("#ourwork").owlCarousel({
  autoplay: true,
  margin:30,
  loop: true,
   nav: true,
   navText: ["<img src='https://eapquotes.com/wp-content/uploads/2022/09/Eap-Quotes-Slider-left-icon.png'>","<img src='https://eapquotes.com/wp-content/uploads/2022/09/Eap-Quotes-Slider-right-icon.png'>"],
  responsive: {
    0: {
      items: 1
    },

    600: {
      items: 3
    },

    1024: {
      items: 4
    },

    1366: {
      items: 4
    }
  }
   });
});

jQuery(document).ready(function($){
    $("#client-say").owlCarousel({
  autoplay: true,
  margin:30,
  loop: true,
   nav: true,
   navText: ["<img src='https://eapquotes.com/wp-content/uploads/2022/09/Eap-Quotes-Slider-left-icon.png'>","<img src='https://eapquotes.com/wp-content/uploads/2022/09/Eap-Quotes-Slider-right-icon.png'>"],
  responsive: {
    0: {
      items: 1,
      autoHeight: true,
    },

    600: {
      items: 1,
      autoHeight: true,
    },

    1024: {
      items: 1
    },

    1366: {
      items: 1
    }
  }
   });
});

jQuery(document).ready(function($){
    $("#inner-client-say").owlCarousel({
  autoplay: true,
  margin:30,
  loop: true,
   nav: true,
   navText: ["<img src='https://eapquotes.com/wp-content/uploads/2022/09/Eap-Quotes-Slider-left-icon.png'>","<img src='https://eapquotes.com/wp-content/uploads/2022/09/Eap-Quotes-Slider-right-icon.png'>"],
  responsive: {
    0: {
      items: 1
    },

    600: {
      items: 2
    },

    1024: {
      items: 2
    },

    1366: {
      items: 2
    }
  }
   });
});

    jQuery(document).ready(function($){
        var offset = 100;
        var speed = 250;
        var duration = 500;
        $(window).scroll(function(){
            if ($(this).scrollTop() < offset) {
                $('#scroll_to_top') .fadeOut(duration);
            } else {
                $('#scroll_to_top') .fadeIn(duration);
            }
        });
        $('#scroll_to_top').on('click', function(){
            $('html, body').animate({scrollTop:0}, speed);
            return false;
        });
    });