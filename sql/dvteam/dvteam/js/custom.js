(function($) {
    "use strict";
    /* CUSTOM SCROLLBAR */
    if ($(window).width() > 1024) {
        $(document).ready(function () {
            "use strict";
            $("body").find(".dv-panel").mCustomScrollbar({
                scrollInertia: 500,
                autoHideScrollbar: true,
                theme: "light",
                advanced: {
                    updateOnContentResize: true
                }
            });
        });
    }

    /* REMOVE HOVER EFFECT ON TOUCH DEVICES */
    $(document).ready(function () {
        if ("ontouchstart" in document.documentElement) {
            $('.dv-member-name, .dv-member-info, .dv-member-desc, img, .dv-member-zoom').addClass('rmveffect');
        } else {
            /* CUSTOM ZOOM ANIMATION */
            $("body").find(".dvteamgrid figure").hover(
                function () {
                    "use strict";
                    $(this).find('.dv-member-zoom').removeClass('dv-zoomout');
                    $(this).find('.dv-member-zoom').addClass('dv-zoomin');
                }, function () {
                    "use strict";
                    $(this).find('.dv-member-zoom').removeClass('dv-zoomin');
                    $(this).find('.dv-member-zoom').addClass('dv-zoomout');
                }
            );
        }
    });

    /* SKILLS */
    $("body").find('.dvskillbar').each(function () {
        $(this).find('.dvskillbar-bar').css('width', $(this).attr('data-percent'));
    });

    /* POPUP WINDOWS */
    $(document).ready(function() {
        $("body").find('.popup-with-zoom-anim').magnificPopup({
            type: 'inline',
            fixedContentPos: false,
            fixedBgPos: true,
            overflowY: 'auto',
            closeBtnInside: true,
            preloader: false,
            midClick: true,
            removalDelay: 300,
            mainClass: 'my-mfp-zoom-in'
        });
    });

    $('.dv-panel').find(".close-dv-panel-bt").on('click', function () {
        $(this).parent().parent().find("iframe").attr('src', $(this).parent().parent().find("iframe").attr('src'));
    });

    /* PANELS */
    $(document).ready(function () {
        $("body").find(".dv-panel").each(function () {
            var id = $(this).data('trigger');
            var side = $(this).data('side');
            $('#' + id).panelslider({
                side: side,
                clickClose: true,
                duration: 200
            });
        });
        $("body").find(".close-dv-panel-bt").on( "click", function() {
            $.panelslider.close();
        });
    });

    /* SLIDEIZLE */
    $(document).ready(function ($) {
        $("body").find('[data-slidizle]').slidizle();
    });

    /* CAROUSEL */
    $(document).ready(function () {
        $("body").find('.dv-carousel').each(function () {
            var spacing = $(this).data('dvspacing');
            var columns = $(this).data('dvcolumns');
            var duration = $(this).data('dvduration');
            var authoheight = 0;
            var responsive = 0;
            var autoplay = $(this).data('dvautoplay');
            var rtl = dvteam_vars.rtl;

            if ((columns != '4') && (columns != '3') && (columns != '2')) {
                authoheight = 1;
            }
            if (columns == '4') {
                responsive = {
                    480: {
                        items: 1
                    },
                    640: {
                        items: 2
                    },
                    900: {
                        items: 4
                    }
                }
            }
            if (columns == '3') {
                responsive = {
                    480: {
                        items: 1
                    },
                    640: {
                        items: 2
                    },
                    900: {
                        items: 3
                    }
                }
            }
            if (columns == '2') {
                responsive = {
                    480: {
                        items: 1
                    },
                    640: {
                        items: 1
                    },
                    900: {
                        items: 2
                    }
                }
            }

            $(this).owlCarousel({
                items: parseInt(columns),
                margin: parseInt(spacing),
                dots: false,
                rtl: parseInt(rtl),
                autoplay: parseInt(autoplay),
                autoplayTimeout: parseInt(duration),
                autoplayHoverPause: true,
                autoHeight: parseInt(authoheight),
                smartSpeed: 800,
                navText: [' ', ' '],
                nav: true,
                loop: parseInt(autoplay),
                responsive: responsive                                                    
            });
            $(this).css('visibility','visible');
    });

    /* WOOKMARK */

    $("body").find('.dvwookmark').each(function () {
        var grid = $(this);
        var rtl = parseInt(dvteam_vars.rtl);
        var align = $(this).data('dvalign');
        var offset = $(this).data('dvoffset');
        var itemwidth = $(this).data('dvwidth');
        var direction = 'left';
        if (rtl === 1) {
            direction = 'right';
        }
        grid.imagesLoaded( function() {
            grid.wookmark({
                itemWidth: parseInt(itemwidth),
                autoResize: true,
                resizeDelay: 500,
                direction: direction,
                align: align,
                container: grid,
                offset: parseInt(offset),
                outerOffset: 0,
                fillEmptySpace: false,
                flexibleWidth: '100%'
            });
            $("body").find('.dvwookmark').css('visibility','visible');
        });
    });

    /* WOOKMARK FILTERABLE */

    $("body").find('.dvteam-filterable').each(function () {
        var wookmark;
        var grid = $(this).find('.dvwookmark-filterable');
        var filters = $(this).find('.dvfilters');
        var filter = filters.find('li');
        var rtl = parseInt(dvteam_vars.rtl);
        var align = grid.data('dvalign');
        var offset = grid.data('dvoffset');
        var itemwidth = grid.data('dvwidth');
        var direction = 'left';
        if (rtl === 1) {
            direction = 'right';
        }
        grid.imagesLoaded( function() {
            wookmark = new Wookmark(grid[0], {
                itemWidth: parseInt(itemwidth),
                autoResize: true,
                resizeDelay: 500,
                direction: direction,
                align: align,
                container: grid,
                offset: parseInt(offset),
                outerOffset: 0,
                fillEmptySpace: false,
                flexibleWidth: '100%'
            });
            grid.css('visibility','visible');
            grid.find('li').addClass('dvgrid-animate');
        });
        var onClickFilter = function (event) {
            var $item = $(event.currentTarget),
                itemActive = $item.hasClass('gridactive');
                if (!itemActive) {
                    filter.removeClass('gridactive');
                    itemActive = true;
                } else {
                    itemActive = false;
                }
                $item.toggleClass('gridactive');
                wookmark.filter(itemActive ? [$item.data('filter')] : []);
            }
        filters.on('click.wookmark-filter', 'li', onClickFilter);
    });

    $(window).on('resize orientationchange', function () {
        $('body').find('.dvwookmark-filterable li').removeClass('dvgrid-animate');
    });
        
});   
})(jQuery);