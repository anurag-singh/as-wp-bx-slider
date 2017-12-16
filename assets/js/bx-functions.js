// Ref - https://bxslider.com/examples/auto-show-start-stop-controls/
// Ref - https://bxslider.com/options/
jQuery(document).ready(function ($) {
    $('.bxslider').bxSlider({
        auto: true,
        autoControls: false,
        stopAutoOnClick: true,
        pager: false,
        captions: true,
        slideWidth: 600
    });
});