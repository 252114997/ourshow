<div data-options="iconCls:'icon-tip',region:'west',title:'&nbsp;导航菜单',split:true,minWidth:120,maxWidth:300" style="width:200px;background-color:#F00;">
	<div class="easyui-accordion" data-options="fit:true,border:false">
		@foreach($menus as $m)
			<div title="&nbsp;{{ $m->title }}" data-options="iconCls:'{{ $m->icon }}'">
				<div class="easyui-panel" data-options="fit:true,border:false">
					<ul class="easyui-tree nav-menu-tree" data-options="url:'{{ URL::to('/submenu', $m->id) }}',method:'get',animate:true"></ul>
				</div>
			</div>
		@endforeach
	</div>
</div>
