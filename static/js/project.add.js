$(function(){
    // https://github.com/Dorious/jquery-numberedtextarea
    $('#request_example').numberedtextarea();
    $('#response_success').numberedtextarea();
    $('#response_fail').numberedtextarea();


    // 创建API部分
    var body_params = [];
    $('input[name="body_names[]"]').keydown(function() {add_bodyline($(this))});

    function add_bodyline(click_obj) {
        var param_num = click_obj.parents('tr').index();
        if ($.inArray(param_num, body_params) < 0) {
            body_params.push(param_num);
            var param_html = click_obj.parents('tr').html();
            click_obj.parents('table').append('<tr>' + param_html + '</tr>');
            click_obj.parents('tr').find('.field-cancel').html('<a href="javascript:void(0);">x</a>');

            // 注册新的事件
            var last_obj = $('table tr input[name="body_names[]"]').last();
            last_obj.keydown(function() {add_bodyline(last_obj)});
            var cancel_obj = click_obj.parents('tr').find('.field-cancel').children('a');
            cancel_obj.click(function() {del_bodyline(cancel_obj)});
        }
    }

    function del_bodyline(click_obj) {
        body_params.pop();
        click_obj.parents('tr').remove();
    }

    var header_params = [];
    $('input[name="header_names[]"]').keydown(function() {add_headerline($(this))});

    function add_headerline(click_obj) {
        var param_num = click_obj.parents('tr').index();
        if ($.inArray(param_num, header_params) < 0) {
            header_params.push(param_num);
            var param_html = click_obj.parents('tr').html();
            click_obj.parents('table').append('<tr>' + param_html + '</tr>');
            click_obj.parents('tr').find('.field-cancel').html('<a href="javascript:void(0);">x</a>');

            // 注册新的事件
            var last_obj = $('table tr input[name="header_names[]"]').last();
            last_obj.keydown(function() {add_headerline(last_obj)});
            var cancel_obj = click_obj.parents('tr').find('.field-cancel').children('a');
            cancel_obj.click(function() {del_headerline(cancel_obj)});
        }
    }

    function del_headerline(click_obj) {
        header_params.pop();
        click_obj.parents('tr').remove();
    }

    var return_params = [];
    $('input[name="response_names[]"]').keydown(function() {add_returnline($(this))});

    function add_returnline(click_obj) {
        var param_num = click_obj.parents('tr').index();
        if ($.inArray(param_num, return_params) < 0) {
            return_params.push(param_num);
            var param_html = click_obj.parents('tr').html();
            click_obj.parents('table').append('<tr>' + param_html + '</tr>');
            click_obj.parents('tr').find('.field-cancel').html('<a href="javascript:void(0);">x</a>');

            // 注册新的事件
            var last_obj = $('table tr input[name="response_names[]"]').last();
            last_obj.keydown(function() {add_returnline(last_obj)});
            var cancel_obj = click_obj.parents('tr').find('.field-cancel').children('a');
            cancel_obj.click(function() {del_returnline(cancel_obj)});
        }
    }

    function del_returnline(click_obj) {
        return_params.pop();
        click_obj.parents('tr').remove();
    }

    $('#method li a').click(function() {
        var method_arr = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
        var method = $(this).html();
        var method_val = method_arr.indexOf(method) + 1;

        $('input[name="method"]').val(method_val);
        $('#method').parents('.input-group-btn').children('button').html(method + ' <span class="caret"></span>');
    });

    $('#create').click(function() {
        var title = $('input[name="title"]').val();
        if (!title) {
            alert('请输入接口名称');
            $('input[name="title"]').focus();
            return;
        }
        var url = $('input[name="url"]').val();
        if (!url) {
            alert('请输入接口URL');
            $('input[name="url"]').focus();
            return;
        }
        var pro_key = $('.project-name').data('prokey');

        var body_names = $('input[name="body_names[]"]').serialize();

        var form_data = $('#api-doc').serializeArray();

        $('.header_musts').each(function() {
            if ($(this).prop('checked')) {
                form_data.push({"name": "header_musts[]", "value": "1"});
            } else {
                form_data.push({"name": "header_musts[]", "value": "0"});
            }
        });
        $('.body_musts').each(function() {
            if ($(this).prop('checked')) {
                form_data.push({"name": "body_musts[]", "value": "1"});
            } else {
                form_data.push({"name": "body_musts[]", "value": "0"});
            }
        });

        $.post('/project/do_add', form_data, function(res) {
            if (res.status == 0) {
                location.href = '/project?pro_key=' + pro_key + '&doc_id=' + res.data.doc_id;
            } else {
                alert(res.msg);
            }
        });
    });
});