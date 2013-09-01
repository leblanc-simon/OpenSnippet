jQuery.fn.selectText = function(){
    var doc = document
        , element = this[0]
        , range, selection
    ;
    if (doc.body.createTextRange) {
        range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        selection = window.getSelection();        
        range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
};

$(function() {
    // Visual effect
    $(".toggle-sidebar").click(function () {
        $('body').toggleClass("show-sidebar");
    });

    $(".extends").click(function(){
        $(this).parents('section').find('.code').toggleClass('not-all');
    });

    $(".copy").click(function(){
       $(this).parents('section').find('.code').removeClass('not-all');
       $(this).parents('section').find('.code pre').selectText();
    })
});