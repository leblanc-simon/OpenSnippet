$(function() {
    // Visual effect
    $(".toggle-sidebar").click(function () {
        $('body').toggleClass("show-sidebar");
    });

    $(".extends").click(function(){
        $(this).parents('section').find('.code').toggleClass('not-all');
    });
});