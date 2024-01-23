/*
Template Name: Eatsome - Restaurant & Food Delivery Mobile Template
Author: Askbootstrap
Author URI: https://themeforest.net/user/askbootstrap
Version: 0.1
*/

/*
- Sidebar Nav
- Plus minus button
- Slider
*/

(function ($) {
    "use strict"; // Start of use strict

    // Sidebar Nav
    var $main_nav = $('#main-nav');
    var $toggle = $('.toggle');
    var defaultOptions = {
        disableAt: false,
        customToggle: $toggle,
        levelSpacing: 40,
        navTitle: '',
        levelTitles: true,
        levelTitleAsBack: true,
        pushContent: '#container',
        insertClose: 2
    };
    var Nav = $main_nav.hcOffcanvasNav(defaultOptions)

    // Plus minus button
    $(document).on('click', '.minus', function() {
        const $input = $(this).parent().find('.box');
        let count = parseInt($input.val(), 10) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.trigger('change');
        return false;
    });

    $(document).on('click', '.plus', function() {
        const $input = $(this).parent().find('.box');
        $input.val(parseInt($input.val(), 10) + 1);
        $input.trigger('change');
        return false;
    });

    // Slider
    $('.multipleitems').slick({
        infinite: true,
        slidesToShow: parseInt("2", 10),
        slidesToScroll: parseInt("1", 10),
        autoplay:true,
        arrows:false,
    });

})(jQuery);
