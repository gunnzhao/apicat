$(function(){
    function bind_cates(obj) {
        obj.click(function() {
            var folder = $(this).children('.cate-title i').attr('class');
            if (folder == 'icon-folder-close-alt') {
                $('.cate-title').children('i').attr('class', 'icon-folder-close-alt');
                $('.docs').hide();
                $(this).children('.cate-title i').attr('class', 'icon-folder-open-alt');
            } else {
                $(this).children('.cate-title i').attr('class', 'icon-folder-close-alt');
            }
            $(this).parent().children('.docs').toggle();
        });
    }
    bind_cates($('.cate-title'));

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
            var pro_key = $('.project-name').data('prokey');
            $.ajax({
                type: 'post',
                url: '/category/do_add',
                data: {'pid': $('#pid').val(), 'title': _self.val()},
                async: false,
                success: function(res) {
                    if (res.status == 0) {
                        var append_html = '<li class="list-group-item cates-node">';
                        append_html += '<p class="cate-title"><i class="icon-folder-close-alt"></i> ';
                        append_html += _self.val();
                        append_html += '</p>';
                        append_html += '<ul class="list-unstyled docs" style="display:none;">';
                        append_html += '<li class="docs-node">';
                        append_html += '<div class="btn-group">';
                        append_html += '<a href="/api_doc/add?pro_key=' + pro_key + '&cate_id=' + res.data.cid + '" class="btn btn-default btn-xs">+ 新建文档</a>';
                        append_html += '<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        append_html += '<i class="icon-angle-down"></i>';
                        append_html += '</button>';
                        append_html += '<ul class="dropdown-menu">';
                        append_html += '<li><a href="/api_doc/add?pro_key=' + pro_key + '&cate_id=' + res.data.cid + '">新建API文档</a></li>';
                        append_html += '<li><a href="/markdown/add?pro_key=' + pro_key + '&cate_id=' + res.data.cid + '">新建Markdown文档</a></li>';
                        append_html += '</ul></div></li>';
                        $('.cates').append(append_html);
                        $('.create-cate-input').hide();
                        _self.val('');
                        bind_cates($('.cates').children('.cates-node').last().children('.cate-title'));
                    } else {
                        alert(res.msg);
                    }
                }
            });
        }
    });

    $('#go-edit').click(function() {
        var url = $(this).attr('href');
        var doc_id = url.split('&doc_id=').pop();
        var pid = $('#pid').val();
        var can_edit = true;

        $.ajax({
            type: 'post',
            url: '/project/check_edit',
            data: {'pid': pid, 'doc_id': doc_id},
            async: false,
            success: function(res) {
                if (res.status != 0) {
                    alert(res.msg);
                    can_edit = false;
                }
            }
        });

        if (!can_edit) {
            return can_edit;
        }
    });

    $('#go-search').click(function() {
        var pro_key = $('.project-name').data('prokey');
        var keyword = $('#keyword').val();
        if (!keyword) {
            return false;
        }
        location.href = '/doc_search?pro_key=' + pro_key + '&keyword=' + keyword;
    });
    $('#keyword').keydown(function(e){
        if (e.keyCode == 13) {
            $('#go-search').click();
        }
    });

    $('.quit-project').click(function() {
        $('#quitProjectModal').modal('toggle');
    });
    $('#quit-project').click(function() {
        $.ajax({
            type: 'post',
            url: '/project/quit',
            data: {'pid': $('#pid').val()},
            async: false,
            success: function(res) {
                if (res.status == 0) {
                    $('#quitProjectModal').modal('toggle');
                    location.href = '/projects';
                } else {
                    alert(res.msg);
                }
            }
        });
    });

    $('#go-del').click(function() {
        $('#delDocModal').modal('toggle');
    });
    $('#del-api-doc').click(function() {
        del_doc('api_doc');
    });
    $('#del-markdown-doc').click(function() {
        del_doc('markdown');
    });
    function del_doc(uri)
    {
        var url = $('#go-edit').attr('href');
        var doc_id = url.split('&doc_id=').pop();
        var pid = $('#pid').val();
        var pro_key = $('.project-name').data('prokey');

        $.post('/' + uri + '/do_del', {'pid': pid, 'doc_id': doc_id}, function(res) {
            if (res.status == 0) {
                location.href = '/project?pro_key=' + pro_key;
            } else {
                alert(res.msg);
            }
        });
    }
});
