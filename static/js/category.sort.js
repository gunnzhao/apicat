$(function(){
    $('.cates').sortable({
        items: 'li',
        update: function(event, ui) {
            var pid = $('#pid').val();
            var cid = ui.item.data('index');
            var position = ui.item.index() + 1;
            $.post('/category/update_order', {'pid': pid, 'cid': cid, 'position': position});
        }
    });
});