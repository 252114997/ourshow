<div id="toparea" class="frame-north" data-options="region:'north',split:false,border:false" style="height:64px;">
	<table>
		<tr>
			<td>
				<h3 style="padding-left:10px">{{$product}}</h3>
			</td>
			<td class="pull-right" style="vertical-align:top">
				<a href="#" id="system-messages" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-collaboration',onClick:SystemMessagesHandler">系统消息</a>
				<a href="#" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-help',onClick:help">技术支持</a>
				<a href="#" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-logout',onClick:logout">退出系统</a>
			</td>
		</tr>
	</table>
</div>

<div id="system_messages_dlg"></div>

<script type="text/javascript">
function SystemMessagesHandler()
{
	$('#system_messages_dlg').dialog({
		title: '系统消息',
		iconCls: 'icon-collaboration',
		width: 600,
		height: 480,
		closed: false,
		maximizable: true,
		resizable: true,
		cache: false,
		href: '{{ URL::to("/system_messages") }}',
		modal: true
	});
} 

function help()
{
	window.location.href = "http://www.wholeton.com";
}

function logout()
{
	window.location.href = "{{ URL::to('/logout') }}";
}
</script>