$(function(){
    $('#add').click(function() {
        $(this).html('确认...');
        $(this).prop('disabled', true);

        $.ajax({
            type: 'post',
            url: '/projects/do_add',
            data: {'title': $('#title').val(), 'authority': $('#authority option:selected').val(), 'description': $('#description').val()},
            async: false,
            timeout: 3000,
            success: function(res) {
                if (res.status == 0) {
                    location.href = '/projects';
                } else {
                    $('#err-msg').html(res.msg);
                    $('#err-msg').show();
                    $('#add').html('确认');
                    $('#add').prop('disabled', false);
                }
            },
            error: function(res) {
                $('#err-msg').html('请求出错，请重试。');
                $('#err-msg').show();
                $('#add').html('确认');
                $('#add').prop('disabled', false);
            }
        });
    });

    $('#createProjectModal').on('hide.bs.modal', function () {
        $('#title').val('');
        $('#authority option[value="0"]').prop('selected', true);
        $('#description').val('');
        $('#err-msg').hide();
        $('#add').html('确认');
        $('#add').prop('disabled', false);
    })
});