$(function(){
    var send_verify_code = function($el, email) {
        $el.prop('disabled', true);
        $.ajax({
            type: 'post',
            url: '/email/send_verify_code',
            data: {'email': email},
            async: false,
            success: function(res) {
                if (res.status == 0) {
                    buttonCountdown($el, 1000 * 60);
                } else {
                    alert(res.msg);
                    $el.prop('disabled', false);
                }
            }
        });
    }

    var buttonCountdown = function($el, ms) {
        var text = $el.data("text") || $el.text(), timer = 0;

        $el.prop('disabled', true).addClass('disabled').on('bc.clear', function() {clearTime();});

        (function countdown() {
            var time = showTime(ms);
            $el.text(time + '后失效');
            if (ms <= 0) {
                ms = 0;
                clearTime();
            } else {
                ms -= 1000;
                timer = setTimeout(arguments.callee, 1000);
            }
        })();
        
        function clearTime() {
            clearTimeout(timer);
            $el.prop('disabled', false).removeClass('disabled').text(text);
        }

        function showTime(ms) {
            var ss = Math.floor(ms / 1000);
            return ss + "秒";
        }
    
        return this;
    }

    $('#get_verify_code').click(function() {
        var email_addr = $('input[name="new_email"]').val();
        if (email_addr == '') {
            return;
        }
        send_verify_code($(this), email_addr);
    });

    $('#verify_email').click(function() {
        var _this = $(this);
        _this.prop('disabled', true);
        $.ajax({
            type: 'post',
            url: '/email/send_verify_link',
            data: {'email': $('#user-email').html()},
            async: false,
            success: function(res) {
                if (res.status == 0) {
                    _this.html('邮件已发送');
                } else {
                    alert(res.msg);
                    _this.prop('disabled', false);
                }
            }
        });
    });

    $('form').submit(function(){
        $('button[type="submit"]').prop('disabled', true);
    });
});