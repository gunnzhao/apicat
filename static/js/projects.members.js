$(function(){
    $('#invite').click(function() {
        $.post('/projects/add_member', {'pid': $('input[name="pid"]').val(), 'email': $('input[name="email"]').val()}, function(res) {
            if (res.status == 0) {
                alert('已发送邀请');
            } else {
                alert(res.msg);
            }
        });
    });
});