$(function(){
    var wait = $(".second").html();
    var url = $('h4 a').attr('href');
    timeOut();
    function timeOut() {
        if(wait != 0) {
            setTimeout(function() {
                $('.second').text(--wait);
                timeOut();
            }, 1000);
        } else {
            location.href = url;
        }
    }
});