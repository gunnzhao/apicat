$(function(){
    $('#invite').click(function() {
        $('#invite').prop('disabled', true);
        $.post('/projects/add_member', {'pid': $('input[name="pid"]').val(), 'email': $('input[name="email"]').val()}, function(res) {
            if (res.status == 0) {
                alert('已发送邀请');
                $('input[name="email"]').val('');
                $('#invite').prop('disabled', false);
            } else {
                alert(res.msg);
                $('#invite').prop('disabled', false);
            }
        });
    });

    $('.del-member').click(function() {
        if (!confirm('确定要将此成员从项目中移除吗？')) {
            return;
        }
        var _self = $(this);
        $.post('/projects/del_member', {'pid': $('input[name="pid"]').val(), 'uid': _self.parents('div').data('index')}, function(res) {
            if (res.status == 0) {
                _self.parents('div[class="members"]').hide();
            } else {
                alert(res.msg);
            }
        });
    });
});