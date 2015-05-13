
/**
 * @brief 判断数组中是否包含指定元素
 */
function isInArray(item, item_array) {
	if (null == item_array || null == item) {
		return false;
	}
	for (var i = 0; i < item_array.length ; i++) {
		if (item == item_array[i]) {
			return true;
		}
	}
	return false;
}

/**
 * @brief 修正窗体宽度
 * @param expectSize: 期望尺寸。在浏览器窗口合适时返回这样的大小
 * @param percent：比例，传入类似0.9这样的小数。在浏览器窗口比期望值小时，返回指定比例的大小
 */
function fixWidth(expectSize, percent) {
	if (document.body.clientWidth > expectSize) {
		return expectSize;
	}
	return document.body.clientWidth * percent;
}

/**
 * @brief 修正窗体高度
 * @param expectSize: 期望尺寸。在浏览器窗口合适时返回这样的大小
 * @param percent：比例，传入类似0.9这样的小数。在浏览器窗口比期望值小时，返回指定比例的大小
 */
function fixHeight(expectSize, percent) {
	if (document.body.clientHeight > expectSize) {
		return expectSize;
	}
	return document.body.clientHeight * percent;
}


/**
 * @brief 弹出确认窗口
 */
function confirmDialog(args) {
	var title = args.title || "提示";
	var content = args.content || "确定执行此操作？";
	var ok_text = args.ok_text || "确定";
	var cancel_text = args.cancel_text || "取消";
	var success_status = (undefined === args.success_status) ? 1 : args.success_status;
	var success_text = (undefined === args.success_text) ? '<h3>操作成功</h3>' : args.success_text;
	var fail_text = (undefined === args.fail_text) ? '操作失败！' : args.fail_text;
	var post_param = (undefined === args.post_param) ? '' : args.post_param;
	var confirm = (undefined === args.confirm) ? true : args.confirm;
	var dialog_width = (undefined === args.dialog_width) ? 350 : args.dialog_width;
	var dialog_height = (undefined === args.dialog_height) ? 150 : args.dialog_height;

	var submit_url = args.submit_url;
	var parent_div = args.parent_div;
	var handle_function = function() {
		$.post(
			submit_url, 	// URL
			JSON.stringify(post_param), // data
			function(data) {
				if (success_status == data.status) {
					$.messager.show({
						title:'提示',
						msg:success_text,
						timeout:5000,
						showType:'show',//null,slide,fade,show. Defaults to slide.
						icon:"success"
					});
				}
				else {
					$.messager.alert('提示',fail_text + data.msg, 'error');
				}
			},     // callback
			"json" // data type
		);
		dialog_div.dialog('close');
	};

	if (!confirm) {
		handle_function();
		return;
	}

	var dialog_div = $('<div style="width:100%;padding:10px 5px 0 5px"><h4>' + content + '</h4></div>');
	dialog_div.appendTo(parent_div);

	dialog_div.dialog({
		title: title,
		width:dialog_width,
		height:dialog_height,
		cache:false,
		modal:true,
		resizable:true,  
		doSize:true,
		closable:true,
		buttons:[{
			text:ok_text,
			iconCls:'icon-ok',
			handler:handle_function
		}, {
			text: cancel_text,
			iconCls:'icon-cancel',
			handler:function() {
				dialog_div.dialog('close');
			}
		}
		]
	});
}

/**
 * @brief 提交表单
 */
function submitForm(ui_control) {
	var myform = $(ui_control).closest('form');
	$(myform).form('submit', {
		success:function(msgdata) {
			var msgdata = eval('(' + msgdata + ')');  // change the JSON string to javascript object
			if (msgdata.status) {
				$.messager.show({
					title:'提示',
					msg:'<h3>保存成功</h3>',
					timeout:5000,
					showType:'show',//null,slide,fade,show. Defaults to slide.
					icon:"success"
				});
			 }
			 else {
			 	$.messager.alert('提示','操作失败！' + msgdata.msg, 'error');
			 }
		}
	});
}