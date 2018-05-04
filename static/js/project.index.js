$(function(){
    $('.api-cate li').click(function() {
        $(this).next().toggle();
        var folder = $(this).children('.cate-title').children('span').attr('class');
        if (folder == 'icon-folder-close-alt') {
            $(this).children('.cate-title').children('span').attr('class', 'icon-folder-open-alt');
        } else {
            $(this).children('.cate-title').children('span').attr('class', 'icon-folder-close-alt');
        }
    });

    $('#create-cate').click(function() {
        $('.create-cate-input').show();
    });
    $('.create-cate-input input').keydown(function(e) {
        if(e.keyCode==13){
            alert(1);
            $('.create-cate-input').hide();
        }
    });

    $('.cate-node').mouseover(function() {
        $(this).children('.cate-icon').show();
    });
    $('.cate-node').mouseout(function() {
        $(this).children('.cate-icon').hide();
    });

    // https://github.com/Dorious/jquery-numberedtextarea
    $('#request_example').numberedtextarea();
    $('#return_success').numberedtextarea();
    $('#return_fail').numberedtextarea();

    var body_params = [];
    $('input[name="body_names"]').keydown(function() {add_bodyline($(this))});

    function add_bodyline(click_obj) {
        var param_num = click_obj.parents('tr').index();
        if ($.inArray(param_num, body_params) < 0) {
            body_params.push(param_num);
            var param_html = click_obj.parents('tr').html();
            click_obj.parents('table').append('<tr>' + param_html + '</tr>');
            click_obj.parents('tr').find('.field-cancel').html('<a href="javascript:void(0);">x</a>');

            // 注册新的事件
            var last_obj = $('table tr input[name="body_names"]').last();
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
    $('input[name="header_names"]').keydown(function() {add_headerline($(this))});

    function add_headerline(click_obj) {
        var param_num = click_obj.parents('tr').index();
        if ($.inArray(param_num, header_params) < 0) {
            header_params.push(param_num);
            var param_html = click_obj.parents('tr').html();
            click_obj.parents('table').append('<tr>' + param_html + '</tr>');
            click_obj.parents('tr').find('.field-cancel').html('<a href="javascript:void(0);">x</a>');

            // 注册新的事件
            var last_obj = $('table tr input[name="header_names"]').last();
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
    $('input[name="return_names"]').keydown(function() {add_returnline($(this))});

    function add_returnline(click_obj) {
        var param_num = click_obj.parents('tr').index();
        if ($.inArray(param_num, return_params) < 0) {
            return_params.push(param_num);
            var param_html = click_obj.parents('tr').html();
            click_obj.parents('table').append('<tr>' + param_html + '</tr>');
            click_obj.parents('tr').find('.field-cancel').html('<a href="javascript:void(0);">x</a>');

            // 注册新的事件
            var last_obj = $('table tr input[name="return_names"]').last();
            last_obj.keydown(function() {add_returnline(last_obj)});
            var cancel_obj = click_obj.parents('tr').find('.field-cancel').children('a');
            cancel_obj.click(function() {del_returnline(cancel_obj)});
        }
    }

    function del_returnline(click_obj) {
        return_params.pop();
        click_obj.parents('tr').remove();
    }
});