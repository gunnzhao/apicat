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

    $('#create-cate').click(function() {
        $('.create-cate-input').show();
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
                        append_html += '<ul class="dropdown-menu"><li><a href="#">编辑</a></li><li><a href="#">删除</a></li></ul>';
                        append_html += '</div></li>';
                        append_html += '<li style="display:none;"><ul class="apis"><li><a href="#" class="btn btn-default btn-xs">创建接口</a></li></ul></li>';
                        $('.api-cate').append(append_html);
                        $('.create-cate-input').hide();
                        _self.val('');
                        bind_apilist($('.api-cate').children('.cate-node').last().children('.cate-title'));
                        bind_setting($('.api-cate').children('.cate-node').last());
                    } else {
                        alert(res.msg);
                    }
                }
            });
        }
    });
});