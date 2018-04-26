$(function(){
    $('.api-cate li').click(function() {
        $(this).next().toggle();
    });

    // https://github.com/Dorious/jquery-numberedtextarea
    $('#request_example').numberedtextarea();
    $('#return_success').numberedtextarea();
    $('#return_fail').numberedtextarea();

    var params = [];
    $('input[name="body_names"]').keyup(function() {add_newline($(this))});

    function add_newline(click_obj) {
        var param_num = click_obj.parents('tr').index();
        if ($.inArray(param_num, params) < 0) {
            params.push(param_num);
            var param_html = click_obj.parents('tr').html();
            click_obj.parents('table').append('<tr>' + param_html + '</tr>');
            var last_obj = $('table tr input[name="body_names"]').last();
            last_obj.keyup(function() {add_newline(last_obj)});
        }
    }
});