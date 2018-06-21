$(function(){
    var img_data = '';

    var clipArea = new bjj.PhotoClip("#clipArea", {
        size: [200, 200], // 截取框的宽和高组成的数组。默认值为[260,260]
        outputSize: [640, 640], // 输出图像的宽和高组成的数组。默认值为[0,0]，表示输出图像原始大小
        //outputType: "jpg", // 指定输出图片的类型，可选 "jpg" 和 "png" 两种种类型，默认为 "jpg"
        file: "#avatar", // 上传图片的<input type="file">控件的选择器或者DOM对象
        view: "#view", // 显示截取后图像的容器的选择器或者DOM对象
        ok: "#clipBtn", // 确认截图按钮的选择器或者DOM对象
        // 开始加载的回调函数。this指向 fileReader 对象，并将正在加载的 file 对象作为参数传入
        loadStart: function(file) {}, 
        // 加载完成的回调函数。this指向图片对象，并将图片地址作为参数传入
        loadComplete: function(src) {}, 
        // 加载失败的回调函数。this指向 fileReader 对象，并将错误事件的 event 对象作为参数传入
        loadError: function(event) {
            alert('图片加载失败，请重试。');
        }, 
        // 裁剪完成的回调函数。this指向图片对象，会将裁剪出的图像数据DataURL作为参数传入
        clipFinish: function(dataURL) {
            img_data = dataURL;
        },
    });

    $("#avatar-file-btn").click(function(){
        $("#avatar").click();
    });

    $('#upload').click(function() {
        $(this).prop('disabled', true);
        if (img_data == '') {
            $('#clipBtn').click();
            if (img_data == '') {
                $(this).prop('disabled', false);
                return;
            }
        }
        $.post('/file_upload/avatar', {'avatar': img_data}, function(res) {
            if (res.status == 0) {
                $('#user-avatar').attr('src', res.data.img);
                $('#changeAvatar').modal('hide');
                $(this).prop('disabled', false);
            } else {
                alert(res.msg);
                $(this).prop('disabled', false);
            }
        });
    });

    $('form').submit(function(){
        $('button[type="submit"]').prop('disabled', true);
    });

    var cities = new Array();
    $.get('/location/all_cities', {}, function(res) {
        if (res.status == 0) {
            cities = res.data.records;
        }
    });

    $('select[name="province"]').change(function() {
        var province_id = $(this).val();
        
        $('select[name="city"]').empty();
        for (var i = 0; i < cities[province_id].length; i++) {
            $('select[name="city"]').append('<option value="' + cities[province_id][i]['id'] + '">' + cities[province_id][i]['name'] + '</option>');
        }
    });
})