$(function(){
    function bind_apilist(obj) {
        obj.click(function() {
            var folder = $(this).children('.cate-title span').attr('class');
            if (folder == 'icon-folder-close-alt') {
                $('.cate-title').children('span').attr('class', 'icon-folder-close-alt');
                $('.apis').parent().hide();
                $(this).children('.cate-title span').attr('class', 'icon-folder-open-alt');
            } else {
                $(this).children('.cate-title span').attr('class', 'icon-folder-close-alt');
            }
            $(this).parent().next().toggle();
        });
    }
    bind_apilist($('.cate-title'));

    function bind_setting(obj) {
        obj.mouseover(function() {
            $(this).children('.cate-icon').show();
        });
        obj.mouseout(function() {
            $(this).children('.cate-icon').hide();
        });
    }
    bind_setting($('.cate-node'));

    function bind_edit(obj) {
        obj.click(function() {
            $('form[name="edit-cate-form"] input[name="cid"]').val($(this).parents('ul').data('cid'));
            $('form[name="edit-cate-form"] input[name="position"]').val($(this).parents('.cate-node').index() / 2);

            var title = $(this).parents('.cate-node').children('.cate-title').text();
            $('form[name="edit-cate-form"] input[name="cate_name"]').val(title.trim());

            $('#editCateModal').modal('toggle');
        });
    }
    bind_edit($('.edit-category'));

    function bind_del(obj) {
        obj.click(function() {
            $('#wantto-del').val($(this).parents('ul').data('cid'));
            $('#wantto-del-position').val($(this).parents('.cate-node').index() / 2);

            var title = $(this).parents('.cate-node').children('.cate-title').text();
            $('#del-cate-title').text('确定删除 ' + title.trim() + '?');
            $('#delCateModal').modal('toggle');
        });
    }
    bind_del($('.del-category'));

    $('#edit-cate').click(function() {
        var data = $('form[name="edit-cate-form"]').serializeArray();
        $.ajax({
            type: 'post',
            url: '/project/edit_category',
            data: {'pid': $('#pid').val(), 'cid': data[0]['value'], 'title': data[1]['value']},
            async: false,
            success: function(res) {
                if (res.status == 0) {
                    var title = $('.cate-node:eq(' + data[2]['value'] + ')').children('.cate-title');
                    var icon = title.children('span').attr('class');
                    title.html('<span class="' + icon + '"></span>&nbsp; ' + data[1]['value']);
                    $('#editCateModal').modal('toggle');
                } else {
                    alert(res.msg);
                }
            }
        });
    });
    function toClickEdit() {
        $('#edit-cate').click();
    }

    $('#del-cate').click(function() {
        $.ajax({
            type: 'post',
            url: '/project/del_category',
            data: {'cid': $('#wantto-del').val()},
            async: false,
            success: function(res) {
                if (res.status == 0) {
                    $('.cate-node:eq(' + $('#wantto-del-position').val() + ')').remove();
                    $('#delCateModal').modal('toggle');
                } else {
                    alert(res.msg);
                }
            }
        });
    });

    $('#create-cate').click(function() {
        $('#create-category').val('');
        $('.create-cate-input').show();
        $('#create-category').focus();
    });
    $('#create-category').blur(function() {
        $('.create-cate-input').hide();
    });
    $('#create-category').keydown(function(e) {
        if(e.keyCode==13){
            var _self = $(this)
            $.ajax({
                type: 'post',
                url: '/project/add_category',
                data: {'pid': $('#pid').val(), 'title': _self.val()},
                async: false,
                success: function(res) {
                    if (res.status == 0) {
                        var append_html = '<li class="cate-node"><div class="cate-title"><span class="icon-folder-close-alt"></span>&nbsp; ';
                        append_html += _self.val();
                        append_html += '</div><div class="dropdown cate-icon" style="display:none">';
                        append_html += '<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
                        append_html += '<span class="icon-cog" type="button"></span>';
                        append_html += '<ul class="dropdown-menu" data-cid="';
                        append_html += res.data.cid;
                        append_html += '"><li><a href="javascript:void(0);" class="edit-category">编辑</a></li><li><a href="javascript:void(0);" class="del-category">删除</a></li></ul>';
                        append_html += '</div></li>';
                        append_html += '<li style="display:none;"><ul class="apis"><li><a href="javascript:void(0);" class="btn btn-default btn-xs">创建接口</a></li></ul></li>';
                        $('.api-cate').append(append_html);
                        $('.create-cate-input').hide();
                        _self.val('');
                        bind_apilist($('.api-cate').children('.cate-node').last().children('.cate-title'));
                        bind_setting($('.api-cate').children('.cate-node').last());
                        bind_edit($('.api-cate').children('.cate-node').last().children('.cate-icon').children('.dropdown-menu').children('li').first());
                        bind_del($('.api-cate').children('.cate-node').last().children('.cate-icon').children('.dropdown-menu').children('li').last());
                    } else {
                        alert(res.msg);
                    }
                }
            });
        }
    });
});