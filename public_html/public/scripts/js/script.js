$(function() {
    const back2top = $("#btn-back2top");
    $(window).scroll(() => {
        $(window).scrollTop() > 200 ? back2top.fadeIn() : back2top.fadeOut();
    });
    back2top.click(() => {
        animateScroll(0);
    });
    $(window).scroll();
});