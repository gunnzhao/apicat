$(function(){
    var simplemde = new SimpleMDE({
        autofocus: true,
        element: $("#markdown-editor")[0],
        forceSync: true,
        hideIcons: ["guide"],
        indentWithTabs: false,
        initialValue: "Hello world!",
        insertTexts: {
            horizontalRule: ["", "\n\n-----\n\n"],
            image: ["![](http://", ")"],
            link: ["[", "](http://)"],
            table: ["", "\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n"],
        },
        parsingConfig: {
            allowAtxHeaderWithoutSpace: true,
            strikethrough: false,
            underscoresBreakWords: true,
        },
        placeholder: "Type here...",
        promptURLs: true,
        renderingConfig: {
            singleLineBreaks: false,
            codeSyntaxHighlighting: true,
        },
        shortcuts: {
            drawTable: "Cmd-Alt-T"
        },
        showIcons: ["code", "table"],
        spellChecker: false,
        styleSelectedText: false,
        tabSize: 4,
    });

    $('#create').click(function() {
        var pro_key = $('#pro_key').val();
        var pid = $('#pid').val();
        var cid = $('#cid').val();

        if (!pro_key || !pid || !cid) {
            alert('无法创建文档');
            return false;
        }

        var title = $('#title').val();
        if (!title) {
            alert('请输入文档名称');
            return false;
        }
        var markdown_text = simplemde.value();
        if (!markdown_text) {
            alert('请输入文档内容');
            return false;
        }
        var html_text = simplemde.markdown(markdown_text);

        $.post('/markdown/do_add', {'pid': pid, 'cid': cid, 'title': title, 'markdown_text': markdown_text, 'html_text': html_text}, function(res) {
            if (res.status == 0) {
                location.href = '/project?pro_key=' + pro_key + '&doc_id=' + res.data.doc_id;
            } else {
                $(this).prop('disabled', false);
                alert(res.msg);
            }
        });
    });
});