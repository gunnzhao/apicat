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
        var markdown_text = simplemde.value();
        var html_text = simplemde.markdown(testPlain);
        alert(html_text);
    });
});