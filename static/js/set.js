// -------------------------------------------------------------------
// markItUp!
// -------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// -------------------------------------------------------------------
// MarkDown tags example
// http://en.wikipedia.org/wiki/Markdown
// http://daringfireball.net/projects/markdown/
// -------------------------------------------------------------------
// Feel free to add more tags
// -------------------------------------------------------------------
mySettings = {
	previewParserPath:	'',
	onShiftEnter:		{keepDefault:false, openWith:'\n\n'},
	markupSet: [
		{name:'First Level Heading', key:'1', placeHolder:'请输入标题...', closeWith:function(markItUp) { return miu.markdownTitle(markItUp, '=') } },
		{name:'Second Level Heading', key:'2', placeHolder:'请输入标题...', closeWith:function(markItUp) { return miu.markdownTitle(markItUp, '-') } },
		{name:'Heading 3', key:'3', openWith:'### ', placeHolder:'请输入标题...' },
		{name:'Heading 4', key:'4', openWith:'#### ', placeHolder:'请输入标题...' },
		{name:'Heading 5', key:'5', openWith:'##### ', placeHolder:'请输入标题...' },
		{name:'Heading 6', key:'6', openWith:'###### ', placeHolder:'请输入标题...' },
		{separator:'---------------' },		
		{name:'Bold', key:'B', openWith:'**', closeWith:'**'},
		{name:'Italic', key:'I', openWith:'_', closeWith:'_'},
		{separator:'---------------' },
		{name:'Bulleted List', openWith:'- ' },
		{name:'Numeric List', openWith:function(markItUp) {
			return markItUp.line+'. ';
		}},
		{separator:'---------------'},	
        {name:'Save', className:'save', beforeInsert:function(markItUp) { miu.save(markItUp) } },
        {name:'Load', className:'load', beforeInsert:function(markItUp) { miu.load(markItUp) } },
		{separator:'---------------'},
	]
}

// mIu nameSpace to avoid conflict.
miu = {
	markdownTitle: function(markItUp, char) {
		heading = '';
		n = $.trim(markItUp.selection||markItUp.placeHolder).length;
		for(i = 0; i < n; i++) {
			heading += char;
		}
		return '\n'+heading;
	},

    save: function(markItUp) {
        var data = encodeURIComponent(markItUp.textarea.value); // Thx Gregory LeRoy
        $.post("../include/quicksave/save.php", "data="+data, function(response) {
                if(response === "MIU:OK") {
                    alert("输入内容已经保存!");
                }
            }
        ); 
    },
	
    load: function(markItUp) {
        $.get("../include/quicksave/load.php", function(response) {
                if(response === "MIU:EMPTY") {
                    alert("没有已保存的内容TAT");
                } else {
                    var ok = confirm("载入之前保存的内容吗？");
                    if (!ok) {
                        return false;
                    }
                    markItUp.textarea.value = response;
                }
            }
        );
    }      
}