$(function(){
    var send_verify_code = function(email) {
        alert(email);
        return true;
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

        if (send_verify_code(email_addr)) {
            buttonCountdown($(this), 1000 * 60);
        }
    });
});