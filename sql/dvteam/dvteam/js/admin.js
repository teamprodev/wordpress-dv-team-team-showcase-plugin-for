(function($) {
    "use strict";
    var $subtitle = $("#wpbody-content").find(".cmb-type-title");
    $subtitle.on("click", function () {
        $(this).find("h3").toggleClass('opened');
        $(this).nextUntil(".cmb-type-title").toggle();
    });
    $(document).ready(function() {
        $subtitle.nextUntil(".cmb-type-title").hide();
    });
})(jQuery);