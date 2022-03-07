$(document).ready(function() {
    
    /*
    * index slideshow
    */
    if($("#slides > div").size() == 1 ){
        $('#slides div').show();
    } else {
        $('#slides').cycle({
            fx:         'fade',
            prev:       '#prev',
            next:       '#next',
            timeout:    10000, 
            speed:      400,
            pager:      '#slideNav'
        });
    }
    
    if($("#bookSlides > div").size() == 1 ){
        $('#bookSlides div').show();
    } else {
        $('#bookSlides').cycle({
            fx:         'fade',
            prev:       '#prev',
            next:       '#next',
            timeout:    10000, 
            speed:      400
        });
    }
    
    
});