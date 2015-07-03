<div id="mainarea" data-options="region:'center'">
	<div id="pagetabs" class="easyui-tabs" data-options="tabPosition:'top',fit:true,border:false,plain:false,tools:'#tab-tools'">
		<div title="系统状态" data-options="iconCls:'icon-home',href:'/dashboard'"></div>
	</div>
</div>

<div id="tabsMenu" class="easyui-menu" style="width:160px;"> 
	<div name="close">关闭本标签页</div> 
	<div name="other">关闭其他标签页</div> 
	<div name="all">关闭所有标签页</div>
</div>

<div id="tab-tools" data-options="border:false">
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-reload',border:false" onclick="reloadTab()"></a>
</div>