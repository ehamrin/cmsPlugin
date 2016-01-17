(function($) {
    var TextSlider = function(element, options) {

        var slider = $(element),
            settings = $.extend({}, $.fn.textSlider.defaults, options),

            vars = {
                use_pause_time: null,
                current_slide: 0,
                old_slide: 0,
                total_slides: 0,
                is_sliding: false,
                slides: [],
                timer: null
            },

        // Find our slider children
            kids = slider.children();
        kids.each(function() {
            var child = $(this),
                link = '';

            if (child.hasClass('slide')) {
                vars.total_slides++;
                vars.slides.push(child);

                if (vars.total_slides > 1) {
                    child.hide();
                }
            }
        });

        settings.original_pause_time = settings.pause_time;

        // @private
        function startAutoNextTimer() {
            if (settings.auto_next && typeof vars.slides[vars.current_slide] !== 'undefined') {
                settings.pause_time = vars.slides[vars.current_slide].data('pause') * 1000 || settings.original_pause_time;
                vars.timer = setTimeout(nextSlide, settings.pause_time);
            }
        }

        // @private
        function stopAutoNextTimer() {
            if (settings.auto_next && vars.timer !== null) {
                clearTimeout(vars.timer);
                vars.timer = null;
            }
        }

        // @private
        function onAnimationComplete() {
            // Hide old slide after timeout
            vars.slides[vars.old_slide].hide();
            vars.is_sliding = false;
        }

        // @private
        function showSlide(slide, direction) {
            slider.trigger('textSlider.beforeshow', slide);

            switch (settings.effect) {
                case 'slide':
                    slide
                        .css('left', (direction > 0 ? '-100%' : '100%'))
                        .show()
                        .stop()
                        .animate({'left': 0}, settings.slide_duration, 'swing', onAnimationComplete);
                    break;

                case 'fade':
                    slide
                        .stop()
                        .fadeIn(settings.slide_duration, onAnimationComplete);
                    break;
            }

            setTimeout(function() {
                slider.trigger('textSlider.aftershow', slide);
            }, settings.slide_duration);

            // Update correct breadcrumb to be current
            if (settings.show_breadcrumbs) {
                $('.textslider-breadcrumb.current', slider).removeClass('current');
                $('.textslider-breadcrumb:eq(' + vars.current_slide + ')', slider).addClass('current');
            }
        }

        // @private
        function hideSlide(slide, direction) {
            slider.trigger('textSlider.beforehide', slide);

            switch (settings.effect) {
                case 'slide':
                    slide
                        .css('left', 0)
                        .stop()
                        .animate({'left': (direction > 0 ? '-100%' : '100%')}, settings.slide_duration, 'swing', onAnimationComplete);
                    break;

                case 'fade':
                    slide
                        .stop()
                        .fadeOut(settings.slide_duration, onAnimationComplete);
                    break;
            }

            setTimeout(function() {
                slider.trigger('textSlider.afterhide', slide);
            }, settings.slide_duration);
        }

        // @public
        function nextSlide() {
            if (!vars.is_sliding && vars.total_slides > 1) {

                stopAutoNextTimer();

                vars.old_slide = vars.current_slide;
                vars.is_sliding = true;

                vars.current_slide++;
                if (vars.current_slide === vars.total_slides) {
                    vars.current_slide = 0;
                }

                // Show new slide
                showSlide(vars.slides[vars.current_slide], 1);

                // Hide old slide
                hideSlide(vars.slides[vars.old_slide], -1);

                startAutoNextTimer();
            }
        }

        // @public
        function previousSlide() {
            if (!vars.is_sliding && vars.total_slides > 1) {

                stopAutoNextTimer();

                vars.old_slide = vars.current_slide;
                vars.is_sliding = true;

                vars.current_slide--;
                if (vars.current_slide < 0) {
                    vars.current_slide = vars.total_slides - 1;
                }

                // Show new slide
                showSlide(vars.slides[vars.current_slide], -1);

                // Hide old slide
                hideSlide(vars.slides[vars.old_slide], 1);

                startAutoNextTimer();
            }
        }

        // @public
        function gotoSlide(index) {
            if (!vars.is_sliding && vars.total_slides > 1) {

                stopAutoNextTimer();

                vars.old_slide = vars.current_slide;
                vars.is_sliding = true;
                vars.current_slide = index;

                // Show new slide
                showSlide(vars.slides[vars.current_slide], (index > vars.old_slide ? -1 : 1));

                // Hide old slide
                hideSlide(vars.slides[vars.old_slide], (index > vars.old_slide ? 1 : -1));

                startAutoNextTimer();
            }
        }

        // @public
        function getOrSetSetting(property, value) {
            if (typeof value === 'undefined') {
                return settings[property];
            } else {
                settings[property] = value;
                return this;
            }
        }

        /*
         * Initiate
         */

        slider.addClass('textslider');
        slider.wrapInner('<div class="textslider-inner"></div>');

        // Add navigation buttons
        if (settings.show_nav) {
            slider.append('<a href="#" class="textslider-prev">Föregående</a><a href="#" class="textslider-next">Nästa</a>');
        }

        // Add breadcrumbs buttons
        if (settings.show_breadcrumbs) {
            var i,
                breadcrumbs = $('<div class="textslider-breadcrumbs" />');

            for (i = 0; i < vars.total_slides; i++) {
                breadcrumbs.append('<a href="#" class="textslider-breadcrumb' + (i === 0 ? ' current' : '') + '" data-index="' + i + '">' + (i + 1) + '</a>');
            }

            slider.append(breadcrumbs);
        }

        // Add event listeners for navigation
        slider
            .on('click', '.textslider-next', function(e) {
                e.preventDefault();
                nextSlide();
            })
            .on('click', '.textslider-prev', function(e) {
                e.preventDefault();
                previousSlide();
            })
            .on('click', '.textslider-breadcrumb', function(e) {
                e.preventDefault();
                gotoSlide($(e.target).data('index'));
            })
            .on('mouseenter', function(e) {
                if (settings.pause_onhover && vars.timer !== null) {
                    stopAutoNextTimer();
                }
            })
            .on('mouseleave', function(e) {
                if (vars.timer === null) {
                    startAutoNextTimer();
                }
            });

        // Start the "auto-next-slide"
        startAutoNextTimer();

        // Trigger events for first visible slide!
        setTimeout(function() {
            slider.trigger('textSlider.beforeshow', vars.slides[0]);
            slider.trigger('textSlider.aftershow', vars.slides[0]);
        }, 10);

        // Public API
        this.next = nextSlide;
        this.previous = previousSlide;
        this.goTo = gotoSlide;
        this.setting = getOrSetSetting;
    };

    $.fn.textSlider = function(options) {
        return this.each(function(key, value){
            var element = $(this);
            // Return early if this element already has a plugin instance
            if (element.data('textSlider')) {
                return element.data('textSlider');
            }

            // Pass options to plugin constructor
            var textSlider = new TextSlider(this, options);
            // Store plugin object in this element's data
            element.data('textSlider', textSlider);
        });
    };

    // Default settings
    $.fn.textSlider.defaults = {
        auto_next: false,
        pause_time: 3000,
        slide_duration: 400,
        show_nav: true,
        show_breadcrumbs: false,
        effect: 'slide',
        pause_onhover: false
    };
})(jQuery);

$(document).ready(function(){

    $('.slider').textSlider({
        auto_next: true,
        show_nav: false,
        effect: 'fade'
    });

    $(".fancybox").fancybox({
        openEffect	: 'elastic',
        closeEffect	: 'elastic',

        helpers : {
            title : {
                type : 'inside'
            }
        }
    });

    var fancyPDF = $(".fancybox-pdf");
    fancyPDF.each(function(index, element)
    {
        $(element).attr('data-fancybox-type', 'iframe');
    });

    fancyPDF.fancybox({
        openEffect	: 'elastic',
        closeEffect	: 'elastic',
        iframe : {
            scrolling : 'no',
            preload: false
        },
        scrolling : 'no',
        scrollOutside: false
    });
});