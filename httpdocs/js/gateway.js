$(document).ready(function() {

    /*
    * gateway slideshow
    */
    $('#gatewaySlides').cycle({
        fx:         'fade',
        prev:       '#prev',
        next:       '#next',
        timeout:    0, 
        speed:      400,
        after: onAfter
    });

    function onAfter(curr,next,opts) {
        var $slides = $(this).parent().children();
        var index = $slides.index(this);
        var $nextSlide;
        if (index == (opts.slideCount - 1))
            $nextSlide = $slides.eq(0);
        else if (opts.slideCount == 0)
            $nextSlide = $slides.eq(opts.slideCount-1);
        else
            $nextSlide = $slides.eq(index+1);
        var selectedTitle = $(this).find('h1').text();
        $('#upNext').html($nextSlide.find('h1').text());
        $('#psGatewayNavTable div').each(function() {
            $(this).removeClass('gatewayHere');
        });
        $("#psGatewayNavTable div[title='"+selectedTitle+"']").toggleClass('gatewayHere');
        $('colgroup').each(function() {
            $(this).removeClass('psgColHover');
            $(this).addClass('psgColNorm');
        });
        $("colgroup").eq($(".gatewayHere").parent().index()).removeClass("psgColNorm");
        $("colgroup").eq($(".gatewayHere").parent().index()).addClass("psgColHover");
    }
    
    $('#psGatewayNavTable div').hover(
        function () {
            $('#psGatewayNavTable div').each(function() {
                $(this).removeClass('gatewayHere');
                $("colgroup").eq($(this).parent().index()).removeClass("psgColHover");
                $("colgroup").eq($(this).parent().index()).addClass("psgColNorm");
            });
            $(this).addClass('gatewayHere');
        }, 
        function () {
            $(this).removeClass('gatewayHere');
        }
    );


    $('#psGatewayNavTable div').click(function() { 
        // cycle to the corresponding slide
        var $slideNum =  $(this).attr('rel');
        $('#gatewaySlides').cycle(parseInt($slideNum)); 
        return false; 
    });
    
    $("#psGatewayNavTable").delegate('td','mouseover mouseleave', function(e) {
        if (e.type == 'mouseover') {
            $("colgroup").eq($(this).index()).removeClass("psgColNorm");
            $("colgroup").eq($(this).index()).addClass("psgColHover");
        } else {
            $("colgroup").eq($(this).index()).removeClass("psgColHover");
            $("colgroup").eq($(this).index()).addClass("psgColNorm");
        }
    });
    
});