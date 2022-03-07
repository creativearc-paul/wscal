$(document).ready(function() {

    /**
    * photo tour
    */
    var galleries = $('.ad-gallery').adGallery({
        thumb_opacity: 0.5, // Opacity that the thumbs fades to/from, (1 removes fade effect)
        // Note that this effect combined with other effects might be resource intensive
        // and make animations lag
        start_at_index: 0, // Which image should be displayed at first? 0 is the first image
        // to be placed somewhere else than on top of the image
        animate_first_image: false, // Should first image just be displayed, or animated in?
        animation_speed: 400, // Which ever effect is used to switch images, how long should it take?
        display_next_and_prev: true, // Can you navigate by clicking on the left/right on the image?
        display_back_and_forward: true, // Are you allowed to scroll the thumb list?
        scroll_jump: 0, // If 0, it jumps the width of the container
        slideshow: {
            enable: true,
            autostart: true,
            speed: 7000,
            stop_on_scroll: true // Should the slideshow stop if the user scrolls the thumb list?
        },
        effect: 'fade', // or 'slide-vert', 'resize', 'fade', 'none' or false
        enable_keyboard_move: true, // Move to next/previous image with keyboard arrows?
        cycle: true, // If set to false, you can't go from the last image to the first, and vice versa
        // All callbacks has the AdGallery objects as 'this' reference
        callbacks: {
            // Executes right after the internal init, can be used to choose which images
            // you want to preload
            // This gets fired right after the new_image is fully visible
            afterImageVisible: function() {
                // For example, preload the next image
                var context = this;
                this.loading(true);
                this.preloadImage(this.current_index + 1,
                    function() {
                        // This function gets executed after the image has been loaded
                        context.loading(false);
                    }
                );
            }
        }
    });
    
});
