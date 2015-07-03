

/**
 * @brief 集合常用 增加、编辑、删除 等操作的 js 类
 * @param add_again true(default):在增加完成后，会提示是否继续增加数据;  false:不提示;
 *
 */
function BaseController(args) {
	this._parent_div = args.parent_div;
	this._field_id_name = args.field_id_name;
	this._field_name_name = args.field_name_name;

	this._url_detail = args.url_detail;
	this._url_addmodify = args.url_addmodify;
	this._url_del = args.url_del;
	this._url_switch = args.url_switch;
	this._url_up_priority = args.url_up_priority;
	this._url_down_priority = args.url_down_priority;
	this._url_apply_policy = args.url_apply_policy || "{{URL::to('/policy/apply-policy')}}";

	this._dialog_title_type = args.dialog_title;
	this._dialog_width = args.dialog_width;
	this._dialog_height = args.dialog_height;
	this._add_again = (undefined === args.add_again) ? true : args.add_again;

	this._on_submit_func = args.on_submit_func; // 若不使用此参数请不要传递（或传undefined），否则会使form页面默认的验证操作失效
	
	//console.debug("in BaseController.js title=" + this._dialog_title_type + ",add_again=" + this._add_again);
}

/**
 * @brief 设置 table 所在 div 的 jquery 对象，必须传递此参数
 *
 * @param parent_div 示例 Singleton.time_obj().setParentdiv($("# $param manage_div"));
 *
 * 一般在调用 addmodify() switchPolicy() del() 等函数前必须传递此参数
 */
BaseController.prototype.setParentdiv = function (parent_div) {
	this._parent_div = parent_div;
}

BaseController.prototype.addmodifyOnCombobox = function (combobox_control) {
	var combobox_userid = $(combobox_control).parent().children("input.easyui-combobox");
	var data_id = combobox_userid.combobox('getValue');
	this.addmodifyBase({
		data_id:       data_id,
		on_close_func: function(){
			combobox_userid.combobox('reload');
		}
	});
}

/**
 * @brief 添加、修改
 * @param index  -1：表示添加   >=0：表示要修改的数据，所在行号
 */
BaseController.prototype.addmodify = function (index, on_close_function) {
	var manage_table = this._parent_div.find("table.easyui-datagrid");
	var data_id = 0;
	var cb_on_close_func = on_close_function;

	if (index>=0) {
		var row = manage_table.datagrid('getRows')[index];
		data_id = row[this._field_id_name];
	}
	this.addmodifyBase({
		data_id: data_id,
		on_close_func: function(){
			manage_table.datagrid('reload');
			if (undefined !== cb_on_close_func) {
				cb_on_close_func();
			}
		}
	});
};


/**
 * @brief 添加、修改
 * @param data_id  数据ID，可以是整型、字符串
 */
BaseController.prototype.addmodifyById = function (data_id) {
	var manage_table = this._parent_div.find("table.easyui-datagrid");

	this.addmodifyBase({
		data_id: data_id,
		on_close_func: function(){
			manage_table.datagrid('reload');
		}
	});
};


/**
 * @brief 添加、修改 
 * 
 */
BaseController.prototype.addmodifyBase = function (args) {
	var data_id = args.data_id;
	var on_close_func = args.on_close_func;

	var dialog_title = "title";
	var dialog_title_type = this._dialog_title_type;
	var dialog_width = fixWidth(this._dialog_width, 0.9);
	var dialog_height = fixHeight(this._dialog_height, 0.9);
	var dialog_href = this._url_detail;
	var dialog_submiturl = this._url_addmodify;
	var field_name_name = this._field_name_name;
	var add_again = this._add_again;
	var on_submit_func = this._on_submit_func;

	if (data_id != 0) {
		dialog_title = "编辑";
		dialog_href += '/' + data_id;
		dialog_submiturl += '/' + data_id;
	}
	else {
		dialog_title = "新增";
	}

	var addmodify_dlg_div = $('<div style="width:100%;padding:10px 5px 0 5px"></div>');
	addmodify_dlg_div.appendTo(this._parent_div);

	addmodify_dlg_div.dialog({
		title:dialog_title + dialog_title_type,
		width:dialog_width,
		height:dialog_height,
		cache:false,
		modal:true,
		resizable:true,  
		doSize:true,
		closable:true,
		href:dialog_href,
		onLoad:function(){
 			addmodify_dlg_div.find("input[name='"+field_name_name+"']").focus();
		},
		onClose:function(){
			addmodify_dlg_div.dialog('destroy');
			on_close_func();
		},
		buttons:[{
			text:'保存',
			iconCls:'icon-ok',
			handler:function() {
				var myform = addmodify_dlg_div.children("form")[0];
				
				$(myform).form('submit', {
					url: dialog_submiturl,
					onSubmit: on_submit_func,
					success: function(data) {
						var data = eval('(' + data + ')');  // change the JSON string to javascript object
						if (data.status) {
							if (add_again) {
								// 重复添加
							 	if (data_id == 0) {
							 		// 新增
								 	$.messager.confirm('提示', '新增操作成功！是否继续添加？', function(r) {
								 		if (r) {
								 			var name_field = addmodify_dlg_div.find("input[name='"+field_name_name+"']");
								 			name_field.val("");
								 			name_field.focus();
								 		}
								 		else {
											addmodify_dlg_div.dialog('close');
								 		}
								 	}) ;
								}
								else {
									// 编辑
									addmodify_dlg_div.dialog('close');
								}
							}
							else {
								// 不重复添加
								addmodify_dlg_div.dialog('close');								
							}
						 }
						 else {
						 	$.messager.alert("提示", data.msg, "error");
						 }
					}
				});
			}
		}, {
			text:'取消',
			iconCls:'icon-cancel',
			handler:function() {
				addmodify_dlg_div.dialog('close');
			}
		}
		]
	});
};

/**
 * @brief 启用禁用策略，支持多选
 */
BaseController.prototype.switchPolicy = function (enable) {
	this.postSelectionIdset(this._url_switch + '/' + enable);
};

/**
 * @brief 删除，支持多选删除
 */
BaseController.prototype.del = function () {
	this.postSelectionIdset(this._url_del);
};

/**
 * @brief 上调优先级，支持多选
 */
BaseController.prototype.upPriority = function () {
	this.postSelectionIdset(this._url_up_priority);
};

/**
 * @brief 下调优先级，支持多选
 */
BaseController.prototype.downPriority = function () {
	this.postSelectionIdset(this._url_down_priority);
};

/**
 * @brief 应用生效
 */
BaseController.prototype.applyPolicy = function () {
	var url_apply_policy = this._url_apply_policy; // must use local var
	$.post(
		url_apply_policy, 	// URL
		JSON.stringify(""), // data
		function(data) {
			if (data.status) {
				$.messager.show({
					title:'提示',
					msg:'<h3>操作成功</h3>',
					timeout:5000,
					showType:'show',//null,slide,fade,show. Defaults to slide.
					icon:"success"
				});
			}
			else {
				$.messager.alert('提示','操作失败！' + data.msg, 'error');
			}
		},     // callback
		"json" // data type
	);
};

/**
 * @brief 批量发送 easyui-datagrid 选中的ID到指定的URL
 */
BaseController.prototype.postSelectionIdset = function (post_url) {
	var url_to_post = post_url;
	var manage_table = this._parent_div.find("table.easyui-datagrid");
	var rows_selected = manage_table.datagrid('getSelections');
	var rows_to_post = [];

	for (var i=0; i<rows_selected.length; i++) {
		rows_to_post.push(rows_selected[i][this._field_id_name]);
	}

	this.postIdset(url_to_post, rows_to_post);
};

/**
 * @brief 批量发送ID到指定的URL
 */
BaseController.prototype.postIdset = function (post_url, post_id_set) {
	var manage_table = this._parent_div.find("table.easyui-datagrid");
	if (post_id_set.length > 0) {
		$.post(
			post_url, 				  // URL
			JSON.stringify(post_id_set), // data
			function(data) {
				if (data.status) {
					manage_table.datagrid('reload');
				}
				else {
					$.messager.alert('提示','操作失败！' + data.msg, 'error');
				}
			},     // callback
			"json" // data type
		);
	}
};
