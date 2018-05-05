$(function(){
    function bind_apilist(obj) {
        obj.click(function() {
            var folder = $(this).children('.cate-title').children('span').attr('class');
            if (folder == 'icon-folder-close-alt') {
                $('.cate-node').children('.cate-title').children('span').attr('class', 'icon-folder-close-alt');
                $('.apis').parent().hide();
                $(this).children('.cate-title').children('span').attr('class', 'icon-folder-open-alt');
            } else {
                $(this).children('.cate-title').children('span').attr('class', 'icon-folder-close-alt');
            }
            $(this).next().toggle();
        });
    }
    bind_apilist($('.api-cate li'));

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
                        var append_html = '<li class="cate-node"><span class="cate-title"><span class="icon-folder-close-alt"></span> ';
                        append_html += _self.val();
                        append_html += '</span><span class="icon-cog cate-icon" style="display:none"></span></li>';
                        append_html += '<li style="display:none;"><ul class="apis"><li><a href="#" class="btn btn-default btn-xs">创建接口</a></li></ul></li>';
                        $('.api-cate').append(append_html);
                        $('.create-cate-input').hide();
                        _self.val('');
                        bind_apilist($('.api-cate').children('.cate-node').last());
                    } else {
                        alert(res.msg);
                    }
                }
            });
        }
    });

    $('.cate-node').mouseover(function() {
        $(this).children('.cate-icon').show();
    });
    $('.cate-node').mouseout(function() {
        $(this).children('.cate-icon').hide();
    });
});