@extends('tpl.layout')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/default/style.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/portal.css') }}">

@stop

@section('title'){{$product}}@stop

@section('js')
<script src="{{ asset('js/jquery.timer.js') }}"></script>
<script src="{{ asset('js/utility.js') }}"></script>
<script src="{{ asset('js/BaseController.js') }}"></script>
<script src="{{ asset('js/TimerController.js') }}"></script>
<script type="text/javascript">

function reloadTab() {
	var curTab = $('#pagetabs').tabs('getSelected'); 
	curTab.panel('refresh', curTab.panel('options').href);	
}

function CloseTab(curTabTitle, type) {
	if (undefined === type) {
		type = 'close';
	}
	if ('close' === type) {
		$('#pagetabs').tabs('close', curTabTitle);
		return;
	}

	var allTabs = $('#pagetabs').tabs('tabs');
	var toCloseTabs = [];

	$.each(allTabs, function() {
		var opt = $(this).panel('options');

		if (opt.closable && opt.title != curTabTitle && type === 'other') {
			toCloseTabs.push(opt.title);
		} 
		else if (opt.closable && type === 'all') {
			toCloseTabs.push(opt.title);
		}
	});

	for (var i = 0; i < toCloseTabs.length; i++) {
		$('#pagetabs').tabs('close', toCloseTabs[i]);
	}
}

function AddTab(text, url) {
	if ($('#pagetabs').tabs('exists', text)) {
		$('#pagetabs').tabs('select', text);
	} else if (url != '') {
		while ($('#pagetabs').tabs('tabs').length>10) {
			// 最多保留10个tab
			$('#pagetabs').tabs('close', 1);
		}
		$('#pagetabs').tabs('add', {
			title: text,
			href: url,
			closable: true,
			cache: false
		});
	}
}

var tab_event_callback = {
	'select' : [], // 选中 tab 标签时，执行的事件
	'unselect' : [], // 离开 tab 标签时，执行的事件
	'close' : []
};
function AddEventOnCurtab(cb_func, type) {
	var tab = $('#pagetabs').tabs('getSelected');
	var index = $('#pagetabs').tabs('getTabIndex',tab);
	tab_event_callback[type][index] = cb_func;
	// console.debug('AddEventOnCurtab title=' + tab + ',index=' + index + ',type=' + type +',cb_func=' + cb_func);
}
function DelEvent(index) {
	tab_event_callback['select'].splice(index, 1);
	tab_event_callback['unselect'].splice(index, 1);
	tab_event_callback['close'].splice(index, 1);
	// console.debug('DelEvent title=' + title);
}
function OnTabSelectEvent(index) {
	var cb_func = tab_event_callback['select'][index];
	if (undefined != cb_func) {
		cb_func();
	}
}
function OnTabUnselectEvent(index) {
	var cb_func = tab_event_callback['unselect'][index];
	if (undefined != cb_func) {
		cb_func();
	}
}
function OnTabCloseEvent(index) {
	var cb_func = tab_event_callback['close'][index];
	if (undefined != cb_func) {
		cb_func();
	}
}

$(function(){
	$('.nav-menu-tree').tree({
		onClick:function(node) {
			AddTab(node.text, node.attributes.url);
		}
	});

	$('#pagetabs').tabs({
		onContextMenu: function(e, title) {
			e.preventDefault();
			$('#tabsMenu').menu('show', {
				left:e.pageX,
				top:e.pageY
			}).data('tabTitle', title);
		},
		onSelect: function(title, index) {
			// console.debug('onSelect title=' + title + ',index=' + index);
			OnTabSelectEvent(index);
		},
		onUnselect: function(title, index) {
			// console.debug('onUnselect title=' + title + ',index=' + index);
			OnTabUnselectEvent(index);
		},
		onClose: function(title, index) {
			// console.debug('onClose title=' + title + ',index=' + index);
			OnTabCloseEvent(index);
			DelEvent(index);
		}
	});

	$('#tabsMenu').menu({
		onClick: function(item) {
			var curTabTitle = $(this).data('tabTitle');
			CloseTab(curTabTitle, item.name);
		}
	});

	// test by ws TODO delete
	// AddTab("测试", '{{ URL::to("/sysconfig/account") }}' );
});


/**
 * @brief 全局变量，实现 javascript 的单例模式
 */
var Singleton = (function() {
	var _user_obj = undefined;
	var _time_obj = undefined;
	var _protocol_obj = undefined;

	return {
		userObj: function() {
			if (undefined === _user_obj) {
				_user_obj = new BaseController({
					field_id_name:   "UserID", 
					field_name_name: "ComputerName",

					url_detail:    "{{URL::to('/object/user-detail')}}",
					url_addmodify: "{{URL::to('/object/user-addmodify')}}",
					url_del:       "{{URL::to('/object/user-del')}}", 

					dialog_title: "用户对象", 
					dialog_width:  440,
					dialog_height: 400,
					add_again:     true,

					on_submit_func: function(){
						var isValid = $(this).form('validate');
						if (!isValid) {
							return isValid;
						}

						// 提交前格式输入内容 org_id_array
						var nodes = $('#org_tree_users').tree('getChecked');
						if (null != nodes) {
							var org_id_array_str = "";
							for (var i = nodes.length - 1; i >= 0; i--) {
								if ({{TBOrganization::TYPE_GROUP}} == nodes[i].attributes.user_type) {
									continue;
								}
								org_id_array_str += nodes[i].id;
								org_id_array_str += "\n";
							};
							$('#org_id_array').val(org_id_array_str);
						}
						return isValid;
					}
				});
			}
			return _user_obj;
		},
		timeObj: function() {
			if (undefined === _time_obj) {
				_time_obj = new BaseController({
					field_id_name:   "TimeID", 
					field_name_name: "TimeName",

					url_detail:    "{{URL::to('/object/time-detail')}}",
					url_addmodify: "{{URL::to('/object/time-addmodify')}}",
					url_del:       "{{URL::to('/object/time-del')}}", 

					dialog_title: "时间对象", 
					dialog_width:  460,
					dialog_height: 360,
					add_again:     true
				});
			}
			return _time_obj;
		},
		protocolObj: function() {
			if (undefined === _protocol_obj) {
				_protocol_obj = new BaseController({
					field_id_name: "ProtocolID", 
				  field_name_name: "ProtocolName",
				       url_detail: "{{URL::to('/object/protocol/object-detail')}}",
				    url_addmodify: "{{URL::to('/object/protocol/object-addmodify')}}",
				          url_del: "{{URL::to('/object/protocol/object-del')}}", 
					 dialog_title: "应用对象", 
					 dialog_width: 440,
					dialog_height: 400,
					    add_again: true,
				   on_submit_func: function() {
						var isValid = $(this).form('validate');
							if (!isValid) {
							return isValid;
							}
						// 提交前格式输入内容 protocol_id_array
						var nodes = $('#protocol_tree').tree('getChecked');
							if (null != nodes) {
								var id_array_str = "";
								for (var i = nodes.length - 1; i >= 0; i--) {
									if (null != nodes[i].leaf && 1 == nodes[i].leaf) {
										id_array_str += nodes[i].id;
										id_array_str += ",";
									}
								};
								$('#protocol_id_array').val(id_array_str);
							}
						return isValid;
					}
				});
			}
			return 	_protocol_obj;	
		}
	}
}());

/**
 * @brief 格式化单位
 *
 * 利用全局变量，实现 javascript 的单例模式
 */
var FormatUnit = (function() {
	var _format = '{{ Config::get("ui.rate_unit_format") }}';
	var _dot = parseInt({{ Config::get('ui.decimal_digits', '2') }});

	return {
		// 包速率
		packetRate: function(value) {
			return this.decimal(value, _dot) + " pps";
		},

		// 速率
		rate: function(value) {
			if (!_format || _format === 'byte') {
				return this.byteRate(value, _dot);
			} else if (_format === 'bit') {
				return this.bitRate(value, _dot);
			}
		},
		bitRate: function(value, dot) {
			var unit_array = [' bps', ' Kbps', ' Mbps', ' Gbps', ' Tbps'];
			return this.baseFormat(value*8, unit_array, 1024, dot);
		},
		byteRate: function(value, dot) {
			var unit_array = [' Bps', ' KBps', ' MBps', ' GBps', ' TBps'];
			return this.baseFormat(value, unit_array, 1024, dot);
		},

		// 大小
		size: function(value) {
			if (!_format || _format === 'byte') {
				return this.byteSize(value, _dot);
			} else if (_format === 'bit') {
				return this.bitSize(value, _dot);
			}
		},
		bitSize: function(value, dot) {
			var unit_array = [' b', ' Kb', ' Mb', ' Gb', ' Tb'];
			return this.baseFormat(value*8, unit_array, 1024, dot);
		},
		byteSize: function(value, dot) {
			var unit_array = [' B', ' KB', ' MB', ' GB', ' TB'];
			return this.baseFormat(value, unit_array, 1024, dot);
		},

		// 数字
		number: function(value) {
			var unit_array = ['', ' 万', ' 亿'];
			return this.baseFormat(value, unit_array, 10000, _dot);
		},

		// 时间
		time: function (value) {
			var days    = Math.floor(value / (60*60*24));	
			var value   = value % (60*60*24);
			var hours   = Math.floor(value / (60*60));
			var value   = value % (60*60);
			var minutes = Math.floor(value / (60));
			var value   = value % (60);
			var seconds = Math.floor(value);
			
			var str = '';
			if (days > 0)
				str += days+"天";
			if (hours > 0)
				str += hours+"小时";
			if (minutes > 0)
				str += minutes+"分";
			if (seconds > 0)
				str += seconds+"秒";

			if (str == '')
				str += "0 秒";
			
			return str;
		},

		baseFormat: function(value, unit_array, unit, dot) {
			if (undefined === dot) {
				dot = 2;
			}
			// Math.pow(2,3); //表示 2^3 = 8
			var i = 0;
			for (i in unit_array) {
				i = parseInt(i);
				if (value < Math.pow(unit, i+1)) {
					break;
				}
			}
			return this.decimal(value/(Math.pow(unit, i)), dot) + unit_array[i];
		},

		decimal: function (num,v) {
			//num表示要四舍五入的数,v表示要保留的小数位数。
			var vv = Math.pow(10,v);
			var dec = Math.round(num*vv)/vv;
			return dec;
		}
	}
}());

// console.debug("FormatUnit.value=" + FormatUnit.rate(1000.12345));
// console.debug("FormatUnit.value=" + FormatUnit.rate(1000.00345));
// console.debug("FormatUnit.value=" + FormatUnit.rate(1000*1000));
// console.debug("FormatUnit.value=" + FormatUnit.rate(1000*1000*1000));
// console.debug("FormatUnit.value=" + FormatUnit.number(1000*1000*1000*1000));
// console.debug("FormatUnit.value=" + FormatUnit.number(1000*1000*1000*1000*1000));

</script>
@stop