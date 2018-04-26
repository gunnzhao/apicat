$(function(){
    $('.api-cate li').click(function() {
        $(this).next().toggle();
    });

    // https://github.com/Dorious/jquery-numberedtextarea
    $('#request_example').numberedtextarea();
    $('#return_success').numberedtextarea();
    $('#return_fail').numberedtextarea();

    var params = [];
    $('input[name="body_names"]').keydown(function() {add_newline($(this))});

    function add_newline(click_obj) {
        var param_num = click_obj.parents('tr').index();
        if ($.inArray(param_num, params) < 0) {
            params.push(param_num);
            var param_html = click_obj.parents('tr').html();
            click_obj.parents('table').append('<tr>' + param_html + '</tr>');
            click_obj.parents('tr').find('.field-cancel').html('<a href="javascript:void(0);">x</a>');

            // 注册新的事件
            var last_obj = $('table tr input[name="body_names"]').last();
            last_obj.keydown(function() {add_newline(last_obj)});
            var cancel_obj = click_obj.parents('tr').find('.field-cancel').children('a');
            cancel_obj.click(function() {del_line(cancel_obj)});
        }
    }

    function del_line(click_obj) {
        params.pop();
        click_obj.parents('tr').remove();
    }
});