$(function(){
    $('.api-cate li').click(function() {
        $(this).next().toggle();
    });

    // https://github.com/Dorious/jquery-numberedtextarea
    $('#request_example').numberedtextarea();
    $('#return_success').numberedtextarea();
    $('#return_fail').numberedtextarea();
});