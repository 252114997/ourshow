<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/



// 初始化菜单、主题等
View::composers(array(
	'MenuComposer' => 'frame.west',
	// place other composer here
));

// 后台登录
Route::any('/login', array('before' => 'guest', 'uses' => 'LoginController@login'));
Route::get('/password_remind', 'RemindersController@getRemind');
Route::post('/password_remind', 'RemindersController@postRemind');
Route::get('/password_reset/{token}', 'RemindersController@getReset');
Route::post('/password_reset', 'RemindersController@postReset');

//
// 所有需要账号验证的页面
// 可以理解为，在request进入匿名函数中的每个路由前，都会执行一次名为 'auth' 的代码 (Route::filter('auth', function(){});
//
Route::group(array('before' => 'auth'), function() {

	// TODO 给每个模块的菜单添加权限 验证 
	// Route::group(array('before' => 'privilege_view:'.$menu_id), function() {

	// 首页入口
	Route::get('/', 'MainFrameController@index');

	Route::get('/system_messages', 'MainFrameController@messages');
	Route::get('/dashboard/message-data', 'MainFrameController@getMessageData');

	// 获取左侧菜单
	Route::get('/submenu/{id}', 'MainFrameController@buildSubMenus');

	// 退出登录
	Route::any('/logout', 'LoginController@logout');

	//
	// 桌面
	//

	// 桌面首页
	Route::get('/dashboard', 'DashboardController@index');

	// 保存面板布局
	Route::post('/dashboard/layout-save', 'DashboardController@layoutSave');

	// 系统负载
	Route::get('/dashboard/sys-load', 'DashboardSysLoadController@getView');
	Route::get('/dashboard/sys-load-state', 'DashboardSysLoadController@getState');

	// 系统流量
	Route::get('/dashboard/sys-traffic', 'DashboardSysTrafficController@getView');
	// 应用top 10
	Route::get('/dashboard/appl-list', 'DashboardApplListController@getView');
	// 用户top 10
	Route::get('/dashboard/user-list', 'DashboardUserListController@getView');

	// 系统的网口信息
	Route::get('/dashboard/sys-interface', 'DashboardSysInterfaceController@getView');
	// 系统网口信息数据
	Route::get('/dashboard/sys-interface-data', 'DashboardSysInterfaceController@getListData');
	// 系统网口的配置
	Route::any('/dashboard/sys-interface-config', 'DashboardSysInterfaceController@config');

	Route::get('/dashboard/sys-version', 'DashboardSysVersionController@getView');
	Route::get('/dashboard/sys-version-data', 'DashboardSysVersionController@getListData');

	Route::get('/dashboard/sys-status', 'DashboardSysStatusController@getView');
	Route::get('/dashboard/sys-status-data', 'DashboardSysStatusController@getListData');
	
	// TODO fix /dashboard/audit-xxxx-data to AuditXxxxxx@getListDataDetail
	
	Route::get('/dashboard/audit-web', 'DashboardAuditWebController@getView');
	Route::get('/dashboard/audit-web-data', 'AuditWeb@getListDataDetail');
	
	Route::get('/dashboard/audit-search', 'DashboardAuditSearchController@getView');
	Route::get('/dashboard/audit-search-data', 'AuditSearch@getListData');
	
	Route::get('/dashboard/audit-mail', 'DashboardAuditMailController@getView');
	Route::get('/dashboard/audit-mail-data', 'AuditMail@getListData');

	Route::get('/dashboard/audit-upload', 'DashboardAuditUploadController@getView');
	Route::get('/dashboard/audit-upload-data', 'AuditUpload@getListData');

	Route::get('/dashboard/audit-im', 'DashboardAuditImController@getView');
	Route::get('/dashboard/audit-im-data', 'AuditImLogin@getListData');

	Route::get('/dashboard/audit-webpost', 'DashboardAuditWebPostController@getView');
	Route::get('/dashboard/audit-webpost-data', 'AuditWebPost@getListData');

	Route::get('/dashboard/audit-game', 'DashboardAuditGameController@getView');
	Route::get('/dashboard/audit-game-data', 'AuditGame@getListData');

	Route::get('/dashboard/audit-audiovideo', 'DashboardAuditAudioVideoController@getView');
	Route::get('/dashboard/audit-audiovideo-data', 'AuditAudioVideo@getListData');

	Route::get('/dashboard/audit-financestock', 'DashboardAuditFinanceStockController@getView');
	Route::get('/dashboard/audit-financestock-data', 'AuditFinanceStock@getListData');

	Route::get('/dashboard/audit-p2pdown', 'DashboardAuditP2pDownController@getView');
	Route::get('/dashboard/audit-p2pdown-data', 'AuditP2pDown@getListData');

	Route::get('/dashboard/filter-content', 'DashboardFilterContentController@getView');
	Route::get('/dashboard/filter-content-data', 'DashboardFilterContentController@getListData');

	Route::get('/dashboard/filter-alarm', 'DashboardFilterAlarmController@getView');
	Route::get('/dashboard/filter-alarm-data', 'DashboardFilterAlarmController@getListData');

	//
	// 实时状态
	//
	Route::get('/realstatus/active-app', 'ActiveAppController@getView');
	Route::get('/realstatus/active-app-list', 'ActiveAppController@getListData');
	Route::get('/realstatus/active-app-chart', 'ActiveAppController@getChart');
	Route::get('/realstatus/active-app-detail', 'ActiveAppDetailController@getView');
	Route::get('/realstatus/active-app-detail-list', 'ActiveAppDetailController@getListData');

	Route::get('/realstatus/active-user', 'ActiveUserController@getView');
	Route::get('/realstatus/active-user-list', 'ActiveUserController@getListData');
	Route::get('/realstatus/active-user-chart', 'ActiveUserController@getChart');
	Route::get('/realstatus/active-user-detail', 'ActiveUserDetailController@getView');
	Route::get('/realstatus/active-user-detail-list', 'ActiveUserDetailController@getListData');
	Route::post('/realstatus/active-user-trigger-event', 'ActiveUserController@triggerEvent');

	Route::get('/realstatus/traffic-analysis', 'TrafficAnalysisController@getView');
	Route::get('/realstatus/traffic-analysis-chart', 'TrafficAnalysisController@getChart');

	Route::get('/realstatus/traffic-statistic-user', 'TrafficStatisticUserController@getView');
	Route::get('/realstatus/traffic-statistic-user-list', 'TrafficStatisticUserController@getListDataUser');
	Route::get('/realstatus/traffic-statistic-user-list-auth', 'TrafficStatisticUserController@getListDataAuthuser');

	Route::get('/realstatus/traffic-statistic-prot', 'TrafficStatisticProtController@getView');
	Route::get('/realstatus/traffic-statistic-prot-list', 'TrafficStatisticProtController@getListData');

	//
	// 网络配置
	//
	Route::post('/network/scanip', 'UtilController@scanip');
	if (1) {
		// 接入模式
		$menu_id = MainFrameController::getMenuidByRoute('/network/workmode');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/workmode', 'WorkmodeController@index');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/workmode-save-params', 'WorkmodeController@saveParameters');
		});

		$menu_id = MainFrameController::getMenuidByRoute('/network/monitornet');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/monitornet', 'NetworkController@getViewMonitornet');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/monitornet-save-params', 'NetworkController@saveParametersMonitornet');
		});

		$menu_id = MainFrameController::getMenuidByRoute('/network/dns');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/dns', 'NetworkController@getViewDns');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/dns-save-params', 'NetworkController@saveParametersDns');
		});

		// 策略路由
		$menu_id = MainFrameController::getMenuidByRoute('/network/tacticsroutes');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/tacticsroutes', 'TacticsroutesController@getView');
			Route::get('/network/tacticsroutes-list', 'TacticsroutesController@getOrderListData');
			Route::get('/network/tacticsroutes-detail/{id?}', 'TacticsroutesController@getDetailView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/tacticsroutes-addmodify/{id?}', 'TacticsroutesController@addmodify');
			Route::post('/network/tacticsroutes-del', 'TacticsroutesController@del');
			Route::post('/network/tacticsroutes-up-priority', 'TacticsroutesController@upPriorityChunk');
			Route::post('/network/tacticsroutes-down-priority', 'TacticsroutesController@downPriorityChunk');
			Route::post('/network/tacticsroutes-switch/{enable}', 'TacticsroutesController@switchPolicy');
		});

		// 静态路由
		$menu_id = MainFrameController::getMenuidByRoute('/network/staticroutes');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/staticroutes', 'StaticroutesController@getView');
			Route::get('/network/staticroutes-list', 'StaticroutesController@getListData');
			Route::get('/network/staticroutes-detail', 'StaticroutesController@getDetailView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/staticroutes-addmodify', 'StaticroutesController@addmodify');
			Route::post('/network/staticroutes-del', 'StaticroutesController@del');
		});

		// VLAN
		$menu_id = MainFrameController::getMenuidByRoute('/network/vlan');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/vlan', 'VlanController@getView');
			Route::get('/network/vlan-list', 'VlanController@getListData');
			Route::get('/network/vlan-detail', 'VlanController@getDetailView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/vlan-addmodify', 'VlanController@addmodify');
			Route::post('/network/vlan-del', 'VlanController@del');
		});

		// DHCP
		$menu_id = MainFrameController::getMenuidByRoute('/network/dhcp-leases');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/dhcp-leases', 'DhcpController@getViewLeases');
			Route::get('/network/dhcp-config', 'DhcpController@getViewConfig');
			Route::get('/network/dhcp-leases-list', 'DhcpController@getListDataLeases');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/dhcp-config-save-params', 'DhcpController@saveParametersConfig');
		});

		// IP绑定MAC
		$menu_id = MainFrameController::getMenuidByRoute('/network/ipbindmac');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/ipbindmac', 'IpbindmacController@getView');
			Route::get('/network/ipbindmac-list', 'IpbindmacController@getListData');
			Route::get('/network/ipbindmac-detail', 'IpbindmacController@getDetailView');
			Route::get('/network/ipbindmac-exclude', 'IpbindmacController@getExcludeView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/ipbindmac-addmodify', 'IpbindmacController@addmodify');
			Route::post('/network/ipbindmac-del', 'IpbindmacController@del');
			//Route::get('/network/ipbindmac-import', 'IpbindmacController@getImportView');
			Route::post('/network/ipbindmac-exclude-addmodify', 'IpbindmacController@addmodifyExclude');
			Route::post('/network/ipbindmac-switch/{enable}', 'IpbindmacController@switchPolicy');
			Route::post('/network/ipbindmac-switch-all', 'IpbindmacController@switchIpbindmac');
		});

		// IP绑定CID 计算机ID
		$menu_id = MainFrameController::getMenuidByRoute('/network/ipbindcid');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/ipbindcid', 'IpbindcidController@getView');
			Route::get('/network/ipbindcid-list', 'IpbindcidController@getListData');
			Route::get('/network/ipbindcid-detail', 'IpbindcidController@getDetailView');
			Route::post('/network/ipbindcid-cid-list', 'IpbindcidController@getListDataCid');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/ipbindcid-addmodify', 'IpbindcidController@addmodify');
			Route::post('/network/ipbindcid-del', 'IpbindcidController@del');
			Route::post('/network/ipbindcid-switch/{enable}', 'IpbindcidController@switchPolicy');
			Route::post('/network/ipbindcid-switch-all', 'IpbindcidController@switchIpbindcid');
		});

		// SNMP
		$menu_id = MainFrameController::getMenuidByRoute('/network/snmp-server');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/snmp-server', 'SnmpController@getViewServer');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/snmp-server-save-params', 'SnmpController@saveParametersServer');
		});
		$menu_id = MainFrameController::getMenuidByRoute('/network/snmp-client');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/snmp-client', 'SnmpController@getViewClient');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/snmp-client-save-params', 'SnmpController@saveParametersClient');
		});

		// Anti fake ip
		$menu_id = MainFrameController::getMenuidByRoute('/network/anti-fakeip');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/anti-fakeip', 'AntifakeipController@getView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/anti-fakeip-save-params', 'AntifakeipController@saveParameters');
		});

		// 3G 上网卡
		$menu_id = MainFrameController::getMenuidByRoute('/network/mobilenetwork');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/network/mobilenetwork', 'NetworkController@getViewMobilenetwork');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/network/mobilenetwork-save-params', 'NetworkController@saveParametersMobilenetwork');
		});
	}

	// 防火墙
	//基本设置
	$menu_id=MainFrameController::getMenuidByRoute('/firewall/baseset');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/firewall/baseset', 'FirewallController@getView');	
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/firewall/baseset-save-params', 'FirewallController@saveParameters');
	});
	// 端口映射
	$menu_id=MainFrameController::getMenuidByRoute('/firewall/portmap');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/firewall/portmap', 'PortmapController@getView');	
		Route::get('/firewall/portmap-list', 'PortmapController@getListData');	
		Route::get('/firewall/portmap-detail/{id?}', 'PortmapController@getDetailView');	
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/firewall/portmap-addmodify/{id?}', 'PortmapController@addmodify');
		Route::post('/firewall/portmap-del', 'PortmapController@del');
		Route::post('/firewall/portmap-switch/{enable}', 'PortmapController@switchPolicy');
	});

	// IP映射
	$menu_id=MainFrameController::getMenuidByRoute('/firewall/ipmap');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/firewall/ipmap', 'IPmapController@getView');	
		Route::get('/firewall/ipmap-list', 'IPmapController@getListData');	
		Route::get('/firewall/ipmap-detail/{id?}', 'IPmapController@getDetailView');	
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/firewall/ipmap-addmodify/{id?}', 'IPmapController@addmodify');
		Route::post('/firewall/ipmap-del', 'IPmapController@del');
		Route::post('/firewall/ipmap-switch/{enable}', 'IPmapController@switchPolicy');
	});
	// 
	// 用户管理
	//
	$menu_id = MainFrameController::getMenuidByRoute('/user/organization');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/user/organization', 'OrgController@index');
		Route::get('/user/searchgroups/{keyword?}', 'OrgController@searchGroups');
		Route::get('/user/searchmembers/{keyword}', 'OrgController@searchMembers');
		Route::get('/user/subgroups', 'OrgController@getOrgSubGroups');
		Route::get('/user/submembers/{pid}', 'OrgController@getOrgSubMembers');
		Route::get('/user/detail-member/{id?}', 'OrgController@getUserDetailMember');
		Route::get('/user/detail-group/{id?}' , 'OrgController@getUserDetailGroup');
		Route::get('/user/getMembersTree/{login_type?}', 'OrgController@getOrgMembersTree');
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/user/delete', 'OrgController@del');
		Route::post('/user/addmodify-member/{id?}', 'OrgController@addmodifyUserMember');
		Route::post('/user/addmodify-group/{id?}' , 'OrgController@addmodifyUserGroup');
		Route::post('/user/check-unique/{id?}', 'OrgController@checkUserUnique');

		Route::get('/user/import-users-view', 'OrgController@importUsersView');
		Route::post('/user/import-users', 'OrgController@importUsers');
		Route::get('/user/export-users', 'OrgController@exportUsers');
	});

	// 
	// 用户认证策略
	// 
	$menu_id = MainFrameController::getMenuidByRoute('/policy/auth');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/policy/auth', 'UserAuthController@index');
		Route::get('/policy/auth-list', 'UserAuthController@getOrderListData');
		Route::get('/policy/auth-detail/{id?}', 'UserAuthController@getPolicyDetailView');
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/policy/auth-addmodify/{id?}', 'UserAuthController@addmodifyPolicy');
		Route::post('/policy/auth-del', 'UserAuthController@del');
		Route::post('/policy/auth-switch/{enable}', 'UserAuthController@switchPolicy');
		Route::post('/policy/auth-up-priority', 'UserAuthController@upPriorityChunk');
		Route::post('/policy/auth-down-priority', 'UserAuthController@downPriorityChunk');
	});

	$menu_id = MainFrameController::getMenuidByRoute('/policy/auth-params');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/policy/auth-params', 'UserAuthController@getAuthParamView');
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/policy/auth-save-params', 'UserAuthController@saveAuthParams');
	});

	$menu_id = MainFrameController::getMenuidByRoute('/policy/auth-params-radius');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/policy/auth-params-radius', 'RadiusAuthController@getParametersView');
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/policy/auth-save-params-radius', 'RadiusAuthController@saveParameters');
	});

	$menu_id = MainFrameController::getMenuidByRoute('/policy/auth-params-ad');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/policy/auth-params-ad', 'ActiveDirectoryAuthController@getParametersView');
		Route::post('/policy/auth-params-ad-sync', 'ActiveDirectoryAuthController@synchronizeAccounts');
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/policy/auth-save-params-ad', 'ActiveDirectoryAuthController@saveParameters');
	});

	$menu_id = MainFrameController::getMenuidByRoute('/policy/auth-params-ldap');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/policy/auth-params-ldap', 'LdapAuthController@getParametersView');
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/policy/auth-save-params-ldap', 'LdapAuthController@saveParameters');
	});

	//应用生效
	Route::post('/policy/apply-policy', 'UtilController@applyPolicy');
	Route::post('/policy/apply-iptables', 'UtilController@applyIptables');

	//
	// 对象设置
	//
	if (1) {
		// 用户对象
		$menu_id = MainFrameController::getMenuidByRoute('/object/user');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/object/user', 'UserObjectController@index');
			Route::get('/object/user-list', 'UserObjectController@getListData');
			Route::get('/object/user-detail/{id?}', 'UserObjectController@getDetailView');
			Route::get('/object/user-list-all', 'UserObjectController@getListDataAll');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/object/user-addmodify/{id?}', 'UserObjectController@addmodify');
			Route::post('/object/user-del', 'UserObjectController@del');
			Route::post('/object/user-check-unique/{id?}', 'UserObjectController@checkUnique');
		});

		// 时间对象
		$menu_id = MainFrameController::getMenuidByRoute('/object/time');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/object/time', 'TimeObjectController@index');
			Route::get('/object/time-list', 'TimeObjectController@getListData');
			Route::get('/object/time-detail/{id?}', 'TimeObjectController@getDetailView');
			Route::get('/object/time-list-all', 'TimeObjectController@getListDataAll');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/object/time-addmodify/{id?}', 'TimeObjectController@addmodify');
			Route::post('/object/time-del', 'TimeObjectController@del');
			Route::post('/object/time-check-unique/{id?}', 'TimeObjectController@checkUnique');
		});

		// 应用对象
		$menu_id = MainFrameController::getMenuidByRoute('/object/protocol/object');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/object/protocols/{keyword?}', 'ProtocolController@getProtocols');
			Route::get('/object/protocol/protocol-tree/{keyword?}', 'ProtocolController@getProtocols');
			Route::get('/object/protocol/object', 'ProtocolObjectController@index');
			Route::get('/object/protocol/object-list', 'ProtocolObjectController@getListData');
			Route::get('/object/protocol/object-detail/{id?}', 'ProtocolObjectController@getDetailView');
			Route::get('/object/protocol/object-list-all', 'ProtocolObjectController@getListDataAll');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/object/protocol/object-addmodify/{id?}', 'ProtocolObjectController@addmodify');
			Route::post('/object/protocol/object-del', 'ProtocolObjectController@del');
			Route::post('/object/protocol/object-check-unique/{id?}', 'ProtocolObjectController@checkUnique');
		});

		// 带宽通道
		$menu_id = MainFrameController::getMenuidByRoute('/object/bandwidth-tree');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/object/bandwidth-tree', 'BandwidthTreeController@getView');
			Route::get('/object/bandwidth-tree-searchgroups/{keyword?}', 'BandwidthTreeController@searchGroups');
			Route::get('/object/bandwidth-tree-detail/{id?}' , 'BandwidthTreeController@getDetailView');
			Route::get('/object/bandwidth-tree-submembers/{pid}', 'BandwidthTreeController@getSubMembers');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/object/bandwidth-tree-addmodify/{id?}', 'BandwidthTreeController@addmodify');
			Route::post('/object/bandwidth-tree-del', 'BandwidthTreeController@del');
		});

		// 线路带宽
		$menu_id = MainFrameController::getMenuidByRoute('/object/bandwidth-virtual');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/object/bandwidth-virtual', 'BandwidthVirtualController@getView');
			Route::get('/object/bandwidth-virtual-list', 'BandwidthVirtualController@getListData');
			Route::get('/object/bandwidth-virtual-list-all', 'BandwidthVirtualController@getListDataAll');
			Route::get('/object/bandwidth-virtual-detail/{id?}', 'BandwidthVirtualController@getDetailView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/object/bandwidth-virtual-addmodify/{id?}', 'BandwidthVirtualController@addmodify');
			Route::post('/object/bandwidth-virtual-del', 'BandwidthVirtualController@del');
		});

		// 准入规则
		if (1) {
			// 准入规则 os
			$menu_id = MainFrameController::getMenuidByRoute('/object/term-rule/os');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/object/term-rule/os', 'TermRuleOsController@index');
				Route::get('/object/term-rule/os-list', 'TermRuleOsController@getListData');
				Route::get('/object/term-rule/os-detail/{id?}', 'TermRuleOsController@getDetailView');
				Route::get('/object/term-rule/os-list-all', 'TermRuleOsController@getListDataDefaultTodo');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/object/term-rule/os-addmodify/{id?}', 'TermRuleOsController@addmodify');
				Route::post('/object/term-rule/os-del', 'TermRuleOsController@del');
				Route::post('/object/term-rule/os-check-unique/{id?}', 'TermRuleOsController@checkUnique');
			});

			// 准入规则 process
			$menu_id = MainFrameController::getMenuidByRoute('/object/term-rule/process');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/object/term-rule/process', 'TermRuleProcessController@index');
				Route::get('/object/term-rule/process-list', 'TermRuleProcessController@getListData');
				Route::get('/object/term-rule/process-detail/{id?}', 'TermRuleProcessController@getDetailView');
				Route::get('/object/term-rule/process-list-all', 'TermRuleProcessController@getListDataDefaultTodo');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/object/term-rule/process-addmodify/{id?}', 'TermRuleProcessController@addmodify');
				Route::post('/object/term-rule/process-del', 'TermRuleProcessController@del');
				Route::post('/object/term-rule/process-check-unique/{id?}', 'TermRuleProcessController@checkUnique');
			});

			// 准入规则 file
			$menu_id = MainFrameController::getMenuidByRoute('/object/term-rule/file');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/object/term-rule/file', 'TermRuleFileController@index');
				Route::get('/object/term-rule/file-list', 'TermRuleFileController@getListData');
				Route::get('/object/term-rule/file-detail/{id?}', 'TermRuleFileController@getDetailView');
				Route::get('/object/term-rule/file-list-all', 'TermRuleFileController@getListDataDefaultTodo');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/object/term-rule/file-addmodify/{id?}', 'TermRuleFileController@addmodify');
				Route::post('/object/term-rule/file-del', 'TermRuleFileController@del');
				Route::post('/object/term-rule/file-check-unique/{id?}', 'TermRuleFileController@checkUnique');
			});

			// 准入规则 netcard
			$menu_id = MainFrameController::getMenuidByRoute('/object/term-rule/netcard');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/object/term-rule/netcard', 'TermRuleNetcardController@index');
				Route::get('/object/term-rule/netcard-list', 'TermRuleNetcardController@getListData');
				Route::get('/object/term-rule/netcard-detail/{id?}', 'TermRuleNetcardController@getDetailView');
				Route::get('/object/term-rule/netcard-list-all', 'TermRuleNetcardController@getListDataDefaultTodo');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/object/term-rule/netcard-addmodify/{id?}', 'TermRuleNetcardController@addmodify');
				Route::post('/object/term-rule/netcard-del', 'TermRuleNetcardController@del');
				Route::post('/object/term-rule/netcard-check-unique/{id?}', 'TermRuleNetcardController@checkUnique');
			});

			// 准入规则 regtable
			$menu_id = MainFrameController::getMenuidByRoute('/object/term-rule/regtable');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/object/term-rule/regtable', 'TermRuleRegtableController@index');
				Route::get('/object/term-rule/regtable-list', 'TermRuleRegtableController@getListData');
				Route::get('/object/term-rule/regtable-detail/{id?}', 'TermRuleRegtableController@getDetailView');
				Route::get('/object/term-rule/regtable-list-all', 'TermRuleRegtableController@getListDataDefaultTodo');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/object/term-rule/regtable-addmodify/{id?}', 'TermRuleRegtableController@addmodify');
				Route::post('/object/term-rule/regtable-del', 'TermRuleRegtableController@del');
				Route::post('/object/term-rule/regtable-check-unique/{id?}', 'TermRuleRegtableController@checkUnique');
			});

			// 准入规则 schedule
			$menu_id = MainFrameController::getMenuidByRoute('/object/term-rule/schedule');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/object/term-rule/schedule', 'TermRuleScheduleController@index');
				Route::get('/object/term-rule/schedule-list', 'TermRuleScheduleController@getListData');
				Route::get('/object/term-rule/schedule-detail/{id?}', 'TermRuleScheduleController@getDetailView');
				Route::get('/object/term-rule/schedule-list-all', 'TermRuleScheduleController@getListDataDefaultTodo');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/object/term-rule/schedule-addmodify/{id?}', 'TermRuleScheduleController@addmodify');
				Route::post('/object/term-rule/schedule-del', 'TermRuleScheduleController@del');
				Route::post('/object/term-rule/schedule-check-unique/{id?}', 'TermRuleScheduleController@checkUnique');
			});

			// 准入规则 screenshot
			$menu_id = MainFrameController::getMenuidByRoute('/object/term-rule/screenshot');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/object/term-rule/screenshot', 'TermRuleScreenshotController@index');
				Route::get('/object/term-rule/screenshot-list', 'TermRuleScreenshotController@getListData');
				Route::get('/object/term-rule/screenshot-list-all', 'TermRuleScreenshotController@getListDataDefaultTodo');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::get('/object/term-rule/screenshot-detail/{id?}', 'TermRuleScreenshotController@getDetailView');
				Route::post('/object/term-rule/screenshot-addmodify/{id?}', 'TermRuleScreenshotController@addmodify');
				Route::post('/object/term-rule/screenshot-del', 'TermRuleScreenshotController@del');
				Route::post('/object/term-rule/screenshot-check-unique/{id?}', 'TermRuleScreenshotController@checkUnique');
			});

			// 准入规则 combine
			$menu_id = MainFrameController::getMenuidByRoute('/object/term-rule/combine');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/object/term-rule/combine', 'TermRuleCombineController@index');
				Route::get('/object/term-rule/combine-list', 'TermRuleCombineController@getListData');
				Route::get('/object/term-rule/combine-detail/{id?}', 'TermRuleCombineController@getDetailView');
				Route::get('/object/term-rule/combine-list-all', 'TermRuleCombineController@getListDataDefaultTodo');
				Route::get('/object/term-rule/combine-list-all-rule', 'TermRuleCombineController@getListDataAllRule');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/object/term-rule/combine-addmodify/{id?}', 'TermRuleCombineController@addmodify');
				Route::post('/object/term-rule/combine-del', 'TermRuleCombineController@del');
				Route::post('/object/term-rule/combine-check-unique/{id?}', 'TermRuleCombineController@checkUnique');
			});
		}
	}

	//
	// 策略管理
	//
	if (1) {
		// 准入策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/access-policy');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/access-policy','AccessPolicyController@index');
			Route::get('/policy/access-policy-list','AccessPolicyController@getOrderListData');
			Route::get('/policy/access-policy-detail/{id?}','AccessPolicyController@getAccessPolicyDetailView');
			Route::get('/policy/access-policy-all-rule','AccessPolicyController@getAllRule');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/policy/access-policy-addmodify/{id?}', 'AccessPolicyController@addModifyAccess');
			Route::post('/policy/access-policy-del', 'AccessPolicyController@del');
			Route::post('/policy/access-policy-up-priority', 'AccessPolicyController@upPriorityChunk');
			Route::post('/policy/access-policy-down-priority', 'AccessPolicyController@downPriorityChunk');
			Route::post('/policy/access-policy-switch/{enable}', 'AccessPolicyController@switchPolicy');
			Route::post('/policy/access-apply-policy', 'AccessPolicyController@accessApply');
		});

		//短信认证策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/sms-policy');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/sms-policy', 'SmsPolicyController@index');
			Route::get('/policy/sms-policy-list', 'SmsPolicyController@getOrderListData');
			Route::get('/policy/sms-policy-detail/{id?}', 'SmsPolicyController@getSmsPolicyDetailView');
			
			Route::post('/policy/sms-policy-deploy','SmsPolicyController@smsDeploy');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::get('/policy/sms-policy-addmodify/{id?}', 'SmsPolicyController@addModifySms');
			Route::post('/policy/sms-policy-del', 'SmsPolicyController@del');
			Route::post('/policy/sms-policy-switch/{enable}', 'SmsPolicyController@switchPolicy');
			Route::post('/policy/sms-policy-up-priority', 'SmsPolicyController@upPriorityChunk');
			Route::post('/policy/sms-policy-down-priority', 'SmsPolicyController@downPriorityChunk');

			Route::post('/policy/sms-deploy-set','SmsPolicyController@smsDeploySet');
			Route::post('/policy/sms-apply-policy', 'SmsPolicyController@smsApply');
		});

		//微信认证策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/wechat-policy');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/wechat-policy', 'WeChatPolicyController@index');
			Route::get('/policy/wechat-policy-list', 'WeChatPolicyController@getOrderListData');
			Route::get('/policy/wechat-policy-detail/{id?}', 'WeChatPolicyController@getWeChatPolicyDetailView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::get('/policy/wechat-policy-addmodify/{id?}', 'WeChatPolicyController@addModifyWeChat');
			Route::post('policy/wechat-policy-del','WeChatPolicyController@del');
			Route::post('/policy/wechat-policy-switch/{enable}', 'WeChatPolicyController@switchPolicy');
			Route::post('/policy/wechat-policy-up-priority', 'WeChatPolicyController@upPriorityChunk');
			Route::post('/policy/wechat-policy-down-priority', 'WeChatPolicyController@downPriorityChunk');
			Route::post('/policy/wechat-apply-policy','WeChatPolicyController@weChatApply');
		});

		//终端外设策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/terminal-policy');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/terminal-policy', 'TerminalPolicyController@index');
			Route::get('/policy/terminal-policy-list', 'TerminalPolicyController@getOrderListData');
			Route::get('/policy/terminal-policy-detail/{id?}', 'TerminalPolicyController@getTerminalPolicyDetailView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::get('/policy/terminal-policy-addmodify/{id?}', 'TerminalPolicyController@addModifyTerminal');
			Route::post('/policy/terminal-policy-del', 'TerminalPolicyController@del');
			Route::post('/policy/terminal-policy-switch/{enable}', 'TerminalPolicyController@switchPolicy');
			Route::post('/policy/terminal-policy-up-priority', 'TerminalPolicyController@upPriorityChunk');
			Route::post('/policy/terminal-policy-down-priority', 'TerminalPolicyController@downPriorityChunk');
		});

		//病毒防御策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/antivirus-policy');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/antivirus-policy', 'AntivirusPolicyController@index');
			Route::get('/policy/antivirus-policy-list', 'AntivirusPolicyController@getListData');
			Route::get('/policy/antivirus-policy-detail/{id?}', 'AntivirusPolicyController@getAntivirusPolicyDetailView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::get('/policy/antivirus-policy-addmodify/{id?}', 'AntivirusPolicyController@addModifyAntivirus');
			Route::post('/policy/antivirus-policy-del', 'AntivirusPolicyController@del');
			Route::post('/policy/antivirus-policy-switch/{enable}', 'AntivirusPolicyController@switchPolicy');
		});

		//流控策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/traffic-control-policy');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/traffic-control-policy', 'TrafficControlPolicyController@index');
			Route::get('/policy/traffic-control-policy-list', 'TrafficControlPolicyController@getOrderListData');
			Route::get('/policy/traffic-control-policy-detail/{id?}', 'TrafficControlPolicyController@getTrafficControlPolicyDetailView');
			Route::get('/policy/traffic-control-protocol-list', 'ProtocolObjectController@getProtocolListData');
			Route::get('/policy/traffic-control-action-list', 'TrafficControlPolicyController@getActionListData');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::get('/policy/traffic-control-policy-addmodify/{id?}', 'TrafficControlPolicyController@addModifyTrafficControlPolicy');
			Route::post('/policy/traffic-control-policy-del', 'TrafficControlPolicyController@del');
			Route::post('/policy/traffic-control-policy-switch/{enable}', 'TrafficControlPolicyController@switchPolicy');
			Route::post('/policy/traffic-control-policy-up-priority', 'TrafficControlPolicyController@upPriorityChunk');
			Route::post('/policy/traffic-control-policy-down-priority', 'TrafficControlPolicyController@downPriorityChunk');
			Route::post('/policy/traffic-control-policy-action/{enable}', 'TrafficControlPolicyController@actionPolicy');
		});

		//配额策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/quota-policy');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/quota-policy', 'QuotaPolicyController@index');
			Route::get('/policy/quota-policy-list', 'QuotaPolicyController@getOrderListData');
			Route::get('/policy/quota-policy-detail/{id?}', 'QuotaPolicyController@getQuotaPolcyDetailView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::get('/policy/quota-policy-addmodify/{id?}', 'QuotaPolicyController@addModifyQuota');
			Route::post('/policy/quota-policy-del', 'QuotaPolicyController@del');
			Route::post('/policy/quota-policy-switch/{enable}', 'QuotaPolicyController@switchPolicy');
			Route::post('/policy/quota-policy-up-priority', 'QuotaPolicyController@upPriorityChunk');
			Route::post('/policy/quota-policy-down-priority', 'QuotaPolicyController@downPriorityChunk');
		});

		//免监控策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/free-monitor');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/free-monitor', 'FreeMonitorPolicyController@index');
			Route::get('/policy/free-monitor-ip-policy', 'FreeMonitorPolicyController@getIPView');
			Route::get('/policy/free-monitor-user-policy', 'FreeMonitorPolicyController@getUserView');
			Route::get('/policy/free-monitor-user-policy-list', 'FreeMonitorPolicyController@getListData');
			Route::get('/policy/free-monitor-user-policy-detail', 'FreeMonitorPolicyController@getUserDetailView');
			Route::get('/policy/free-monitor-app-policy', 'FreeMonitorPolicyController@indexApp');
			Route::get('/policy/free-monitor-app-policy-list', 'FreeMonitorPolicyController@getAppView');
			Route::get('/policy/free-monitor-app-policy-detail', 'FreeMonitorPolicyController@getAppDetailView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/policy/free-monitor-ip-policy-addmodify', 'FreeMonitorPolicyController@addModifyIP');
			Route::get('/policy/free-monitor-user-policy-addmodify/{id?}', 'FreeMonitorPolicyController@addModifyUser');
			Route::post('/policy/free-monitor-user-policy-switch/{enable}', 'FreeMonitorPolicyController@switchPolicy');
			Route::post('/policy/free-monitor-user-policy-apply-policy', 'FreeMonitorPolicyController@applyUser');
			Route::post('/policy/free-monitor-user-policy-del', 'FreeMonitorPolicyController@del');
			Route::post('/policy/free-monitor-app-policy-addmodify', 'FreeMonitorPolicyController@addModifyApp');
			Route::post('/policy/free-monitor-app-policy-del', 'FreeMonitorPolicyController@delApp');
		});

		//SSL审计策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-ssl-policy');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/audit-ssl-policy', 'AuditSslPolicyController@getView');
			Route::get('/policy/audit-ssl-policy-list', 'AuditSslPolicyController@getOrderListData');
			Route::get('/policy/audit-ssl-policy-detail/{id?}', 'AuditSslPolicyController@getDetailView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/policy/audit-ssl-policy-addmodify/{id?}', 'AuditSslPolicyController@addModify');
			Route::post('/policy/audit-ssl-policy-del', 'AuditSslPolicyController@del');
			Route::post('/policy/audit-ssl-policy-switch/{enable}', 'AuditSslPolicyController@switchPolicy');
			Route::post('/policy/audit-ssl-policy-up-priority', 'AuditSslPolicyController@upPriorityChunk');
			Route::post('/policy/audit-ssl-policy-down-priority', 'AuditSslPolicyController@downPriorityChunk');
			Route::post('/policy/audit-ssl-apply-policy','AuditSslPolicyController@applyAudit');

			Route::post('/policy/audit-ssl-switch-all', 'AuditSslPolicyController@switchAll');
		});

		//审计策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-policy');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/audit-policy', 'AuditPolicyController@getView');
			Route::get('/policy/audit-policy-list', 'AuditPolicyController@getOrderListData');
			Route::get('/policy/audit-policy-detail/{id?}', 'AuditPolicyController@getDetailView');
			Route::get('/policy/audit-tree-auditname', 'AuditPolicyController@getAuditNameTree');

			Route::get('/policy/audit-set', 'AuditPolicyController@getViewSet');
			Route::get('/policy/audit-baseset', 'AuditPolicyController@getViewBaseset');
			Route::get('/policy/audit-recordset', 'AuditPolicyController@getViewRecordset');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/policy/audit-policy-addmodify/{id?}', 'AuditPolicyController@addmodify');
			Route::post('/policy/audit-policy-del', 'AuditPolicyController@del');
			Route::post('/policy/audit-policy-switch/{enable}', 'AuditPolicyController@switchPolicy');
			Route::post('/policy/audit-policy-up-priority', 'AuditPolicyController@upPriorityChunk');
			Route::post('/policy/audit-policy-down-priority', 'AuditPolicyController@downPriorityChunk');
			Route::post('/policy/audit-apply-policy','AuditPolicyController@applyAudit');

			Route::post('/policy/audit-baseset-save-params', 'AuditPolicyController@saveParamsBaseset');
			Route::post('/policy/audit-recordset-save-params', 'AuditPolicyController@saveParamsRecordset');
			Route::post('/policy/audit-recordset-truncate/{audit_type}', 'AuditPolicyController@truncateRecord');
		});

		//审计记录
		// web
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-web');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-webtitle');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/audit-record-webtitle', 'AuditWebTitleController@getView');
				Route::get('/policy/audit-record-webtitle-detail', 'AuditWebTitleController@getViewDetail');
				Route::get('/policy/audit-record-webtitle-data', 'AuditWebTitleController@getListData');
				Route::get('/policy/audit-record-webtitle-data-detail', 'AuditWebTitleController@getListDataDetail');
			});
			$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-weburl');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/audit-record-weburl', 'AuditWebUrlController@getView');
				Route::get('/policy/audit-record-weburl-detail', 'AuditWebUrlController@getViewDetail');
				Route::get('/policy/audit-record-weburl-data', 'AuditWebUrlController@getListData');
				Route::get('/policy/audit-record-weburl-data-detail', 'AuditWebUrlController@getListDataDetail');
			});
			$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-webpost');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/audit-record-webpost', 'AuditWebPostController@getView');
				Route::get('/policy/audit-record-webpost-detail', 'AuditWebPostController@getViewDetail');
				Route::get('/policy/audit-record-webpost-data', 'AuditWebPostController@getListData');
				Route::get('/policy/audit-record-webpost-data-detail', 'AuditWebPostController@getListDataDetail');
			});
			$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-search');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/audit-record-search', 'AuditSearchController@getView');
				Route::get('/policy/audit-record-search-detail', 'AuditSearchController@getViewDetail');
				Route::get('/policy/audit-record-search-data', 'AuditSearchController@getListData');
				Route::get('/policy/audit-record-search-data-detail', 'AuditSearchController@getListDataDetail');
			});
			$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-upload');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/audit-record-upload', 'AuditUploadController@getView');
				Route::get('/policy/audit-record-upload-detail', 'AuditUploadController@getViewDetail');
				Route::get('/policy/audit-record-upload-data', 'AuditUploadController@getListData');
				Route::get('/policy/audit-record-upload-data-detail', 'AuditUploadController@getListDataDetail');
			});
		});

		// im
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-im');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-imlogin');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/audit-record-imlogin', 'AuditImLoginController@getView');
				Route::get('/policy/audit-record-imlogin-detail', 'AuditImLoginController@getViewDetail');
				Route::get('/policy/audit-record-imlogin-data', 'AuditImLoginController@getListData');
				Route::get('/policy/audit-record-imlogin-data-detail', 'AuditImLoginController@getListDataDetail');
			});
			$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-imchat');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/audit-record-imchat', 'AuditImChatController@getView');
				Route::get('/policy/audit-record-imchat-detail', 'AuditImChatController@getViewDetail');
				Route::get('/policy/audit-record-imchat-data', 'AuditImChatController@getListData');
				Route::get('/policy/audit-record-imchat-data-detail', 'AuditImChatController@getListDataDetail');
			});
			$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-imfile');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/audit-record-imfile', 'AuditImFileController@getView');
				Route::get('/policy/audit-record-imfile-detail', 'AuditImFileController@getViewDetail');
				Route::get('/policy/audit-record-imfile-data', 'AuditImFileController@getListData');
				Route::get('/policy/audit-record-imfile-data-detail', 'AuditImFileController@getListDataDetail');
			});
			$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-imav');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/audit-record-imav', 'AuditImAvController@getView');
				Route::get('/policy/audit-record-imav-detail', 'AuditImAvController@getViewDetail');
				Route::get('/policy/audit-record-imav-data', 'AuditImAvController@getListData');
				Route::get('/policy/audit-record-imav-data-detail', 'AuditImAvController@getListDataDetail');
			});
		});

		// mail
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-mail');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/audit-record-mail', 'AuditMailController@getView');
			Route::get('/policy/audit-record-mail-detail', 'AuditMailController@getViewDetail');
			Route::get('/policy/audit-record-mail-data', 'AuditMailController@getListData');
			Route::get('/policy/audit-record-mail-data-detail', 'AuditMailController@getListDataDetail');
		});

		// ftp
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-ftp');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			// FTP连接记录
			$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-ftpctl');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/audit-record-ftpctl', 'AuditFtpCtlController@getView');
				Route::get('/policy/audit-record-ftpctl-detail', 'AuditFtpCtlController@getViewDetail');
				Route::get('/policy/audit-record-ftpctl-data', 'AuditFtpCtlController@getListData');
				Route::get('/policy/audit-record-ftpctl-data-detail', 'AuditFtpCtlController@getListDataDetail');
			});
			// FTP命令记录
			$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-ftpcmd');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/audit-record-ftpcmd', 'AuditFtpCmdController@getView');
				Route::get('/policy/audit-record-ftpcmd-detail', 'AuditFtpCmdController@getViewDetail');
				Route::get('/policy/audit-record-ftpcmd-data', 'AuditFtpCmdController@getListData');
				Route::get('/policy/audit-record-ftpcmd-data-detail', 'AuditFtpCmdController@getListDataDetail');
			});
		});

		// Telnet记录 telnet
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-telnet');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/audit-record-telnet', 'AuditTelnetController@getView');
			Route::get('/policy/audit-record-telnet-detail', 'AuditTelnetController@getViewDetail');
			Route::get('/policy/audit-record-telnet-data', 'AuditTelnetController@getListData');
			Route::get('/policy/audit-record-telnet-data-detail', 'AuditTelnetController@getListDataDetail');
		});

		// 财经股票 financestock
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-financestock');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/audit-record-financestock', 'AuditFinanceStockController@getView');
			Route::get('/policy/audit-record-financestock-detail', 'AuditFinanceStockController@getViewDetail');
			Route::get('/policy/audit-record-financestock-data', 'AuditFinanceStockController@getListData');
			Route::get('/policy/audit-record-financestock-data-detail', 'AuditFinanceStockController@getListDataDetail');
		});

		// 网络游戏 game
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-game');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/audit-record-game', 'AuditGameController@getView');
			Route::get('/policy/audit-record-game-detail', 'AuditGameController@getViewDetail');
			Route::get('/policy/audit-record-game-data', 'AuditGameController@getListData');
			Route::get('/policy/audit-record-game-data-detail', 'AuditGameController@getListDataDetail');
		});

		// P2P下载 p2pdown
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-p2pdown');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/audit-record-p2pdown', 'AuditP2pDownController@getView');
			Route::get('/policy/audit-record-p2pdown-detail', 'AuditP2pDownController@getViewDetail');
			Route::get('/policy/audit-record-p2pdown-data', 'AuditP2pDownController@getListData');
			Route::get('/policy/audit-record-p2pdown-data-detail', 'AuditP2pDownController@getListDataDetail');
		});

		// 音视频记录 audiovideo
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-audiovideo');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/audit-record-audiovideo', 'AuditAudioVideoController@getView');
			Route::get('/policy/audit-record-audiovideo-detail', 'AuditAudioVideoController@getViewDetail');
			Route::get('/policy/audit-record-audiovideo-data', 'AuditAudioVideoController@getListData');
			Route::get('/policy/audit-record-audiovideo-data-detail', 'AuditAudioVideoController@getListDataDetail');
		});

		// SSH记录 ssh
		$menu_id = MainFrameController::getMenuidByRoute('/policy/audit-record-ssh');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/audit-record-ssh', 'AuditSshController@getView');
			Route::get('/policy/audit-record-ssh-detail', 'AuditSshController@getViewDetail');
			Route::get('/policy/audit-record-ssh-data', 'AuditSshController@getListData');
			Route::get('/policy/audit-record-ssh-data-detail', 'AuditSshController@getListDataDetail');
		});

		// 过滤策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/filter-url');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/filter-url', 'FilterPolicyUrlController@getView');

			// URL库管理
			$menu_id = MainFrameController::getMenuidByRoute('/policy/filter-url-database');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/filter-url-database', 'UrlClassifyDatabaseController@getView');
				Route::get('/policy/filter-url-database-list', 'UrlClassifyDatabaseController@getListData'); // search
				Route::get('/policy/filter-url-database-detail/{id}', 'UrlClassifyDatabaseController@getDetailView');
				
				Route::get('/policy/filter-url-classify-tree-embedded', 'UrlClassifyDatabaseController@getClassifyMapEmbedded');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/policy/filter-url-database-addmodify/{id}', 'UrlClassifyDatabaseController@addmodify');
				Route::post('/policy/filter-url-database-del', 'UrlClassifyDatabaseController@del');
			});

			// URL智能识别
			$menu_id = MainFrameController::getMenuidByRoute('/policy/filter-url-classifyauto');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/filter-url-classifyauto', 'UrlClassifyAutoController@getView');
				Route::get('/policy/filter-url-classifyauto-list', 'UrlClassifyAutoController@getListData'); // search
				Route::get('/policy/filter-url-classifyauto-detail/{id?}', 'UrlClassifyAutoController@getDetailView');
				Route::any('/policy/filter-url-classifyauto-check-unique/{id?}', 'UrlClassifyAutoController@checkUnique');

				// 智能识别记录查询
				$menu_id_sub = MainFrameController::getMenuidByRoute('/policy/filter-url-classifyauto-result');
				Route::group(array('before' => 'privilege_view:'.$menu_id_sub), function() {
					Route::get('/policy/filter-url-classifyauto-result', 'UrlClassifyAutoResultController@getView');
					Route::get('/policy/filter-url-classifyauto-result-list', 'UrlClassifyAutoResultController@getListData');
					Route::get('/policy/filter-url-classifyauto-result-detail/{id}', 'UrlClassifyAutoResultController@getDetailView');
				});

				// 智能识别结果总览
				$menu_id_sub = MainFrameController::getMenuidByRoute('/policy/filter-url-classifyauto-status');
				Route::group(array('before' => 'privilege_view:'.$menu_id_sub), function() {
					Route::get('/policy/filter-url-classifyauto-status', 'UrlClassifyAutoStatusController@getView');
					Route::get('/policy/filter-url-classifyauto-status-list', 'UrlClassifyAutoStatusController@getListData');
				});
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/policy/filter-url-classifyauto-addmodify/{id?}', 'UrlClassifyAutoController@addmodify');
				Route::post('/policy/filter-url-classifyauto-del', 'UrlClassifyAutoController@del');
				Route::post('/policy/filter-url-classifyauto-result-reset', 'UrlClassifyAutoController@resetClassifyResult');

				// 智能识别记录查询
				$menu_id_sub = MainFrameController::getMenuidByRoute('/policy/filter-url-classifyauto-result');
				Route::group(array('before' => 'privilege_edit:'.$menu_id_sub), function() {
					Route::post('/policy/filter-url-classifyauto-result-addmodify/{id}', 'UrlClassifyAutoResultController@addmodify');
					Route::post('/policy/filter-url-classifyauto-result-del', 'UrlClassifyAutoResultController@del');
				});

				// 智能识别结果总览
				$menu_id_sub = MainFrameController::getMenuidByRoute('/policy/filter-url-classifyauto-status');
				Route::group(array('before' => 'privilege_edit:'.$menu_id_sub), function() {
					Route::post('/policy/filter-url-classifyauto-status-del', 'UrlClassifyAutoStatusController@del');
				});
			});

			// 自定义URL对象
			$menu_id = MainFrameController::getMenuidByRoute('/policy/filter-url-classifydefined');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/filter-url-classifydefined', 'UrlClassifyDefinedController@getView');
				Route::get('/policy/filter-url-classifydefined-list', 'UrlClassifyDefinedController@getListData');
				Route::get('/policy/filter-url-classifydefined-detail/{id?}', 'UrlClassifyDefinedController@getDetailView');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/policy/filter-url-classifydefined-addmodify/{id?}', 'UrlClassifyDefinedController@addmodify');
				Route::post('/policy/filter-url-classifydefined-del', 'UrlClassifyDefinedController@del');
			});

			// URL策略
			$menu_id = MainFrameController::getMenuidByRoute('/policy/filter-url-policy');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/filter-url-policy', 'FilterPolicyUrlController@getView');
				Route::get('/policy/filter-url-policy-list', 'FilterPolicyUrlController@getOrderListData');
				Route::get('/policy/filter-url-policy-detail/{id?}', 'FilterPolicyUrlController@getDetailView');
				Route::get('/policy/filter-url-classify-tree', 'FilterPolicyUrlController@getClassifyTree');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/policy/filter-url-policy-addmodify/{id?}', 'FilterPolicyUrlController@addmodify');
				Route::post('/policy/filter-url-policy-del', 'FilterPolicyUrlController@del');
				Route::post('/policy/filter-url-policy-switch/{enable}', 'FilterPolicyUrlController@switchPolicy');
				Route::post('/policy/filter-url-policy-up-priority', 'FilterPolicyUrlController@upPriorityChunk');
				Route::post('/policy/filter-url-policy-down-priority', 'FilterPolicyUrlController@downPriorityChunk');
			});

			// 过滤的URL
			$menu_id = MainFrameController::getMenuidByRoute('/policy/filter-url-result');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/filter-url-result', 'FilterResultUrlController@getView');
				Route::get('/policy/filter-url-result-detail', 'FilterResultUrlController@getViewDetail');
				Route::get('/policy/filter-url-result-data', 'FilterResultUrlController@getListData');
				Route::get('/policy/filter-url-result-data-detail', 'FilterResultUrlController@getListDataDetail');
			});

		});

		// SSL过滤策略
		$menu_id = MainFrameController::getMenuidByRoute('/policy/filter-ssl');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/policy/filter-ssl', 'FilterPolicySSLController@getView');

			// 自定义SSL对象
			$menu_id = MainFrameController::getMenuidByRoute('/policy/filter-ssl-classifydefined');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/filter-ssl-classifydefined', 'SSLClassifyDefinedController@getView');
				Route::get('/policy/filter-ssl-classifydefined-list', 'SSLClassifyDefinedController@getListData');
				Route::get('/policy/filter-ssl-classifydefined-detail/{id?}', 'SSLClassifyDefinedController@getDetailView');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/policy/filter-ssl-classifydefined-addmodify/{id?}', 'SSLClassifyDefinedController@addmodify');
				Route::post('/policy/filter-ssl-classifydefined-del', 'SSLClassifyDefinedController@del');
			});

			// SSL过滤策略
			$menu_id = MainFrameController::getMenuidByRoute('/policy/filter-ssl-policy');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/filter-ssl-policy', 'SSLFilterPolicyController@getView');
				Route::get('/policy/filter-ssl-policy-list', 'SSLFilterPolicyController@getOrderListData');
				Route::get('/policy/filter-ssl-policy-detail/{id?}', 'SSLFilterPolicyController@getDetailView');
				Route::get('/policy/filter-ssl-classify-tree', 'SSLFilterPolicyController@getClassifyTree');
			});
			Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
				Route::post('/policy/filter-ssl-policy-addmodify/{id?}', 'SSLFilterPolicyController@addmodify');
				Route::post('/policy/filter-ssl-policy-del', 'SSLFilterPolicyController@del');
				Route::post('/policy/filter-ssl-policy-up-priority', 'SSLFilterPolicyController@upPriorityChunk');
				Route::post('/policy/filter-ssl-policy-down-priority', 'SSLFilterPolicyController@downPriorityChunk');
			});

			// 过滤的SSL
			$menu_id = MainFrameController::getMenuidByRoute('/policy/filter-ssl-result');
			Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
				Route::get('/policy/filter-ssl-result', 'SSLFilterResultController@getView');
				Route::get('/policy/filter-ssl-result-detail', 'SSLFilterResultController@getViewDetail');
				Route::get('/policy/filter-ssl-result-data', 'SSLFilterResultController@getListData');
				Route::get('/policy/filter-ssl-result-data-detail', 'SSLFilterResultController@getListDataDetail');
			});
		});
	}

	//
	// 系统管理
	//
	// Route::get('/sysconfig/setting', 'SysConfigController@index');
	// Route::get('/sysconfig/setting-menus', 'SysConfigController@menus');

	//webui 显示
	Route::get('/sysconfig/webui','SysConfigController@webui');

	//邮件订阅
	Route::get('/sysconfig/email', 'SysConfigController@email');
	Route::get('/sysconfig/email-menus', 'SysConfigController@emailMenues');
	Route::get('/sysconfig/event', 'SysConfigController@event');

	//日志配置
	Route::get('/sysconfig/internetAudit', 'SysConfigController@internetAudit');
	Route::get('/sysconfig/contentFiliter', 'SysConfigController@contentFiliter');
	Route::get('/sysconfig/retentionCycle', 'SysConfigController@retentionCycle');

	// Route::get('/sysconfig/time', 'SysConfigController@time');
	// Route::get('/sysconfig/webui', 'SysConfigController@webui');
	// Route::get('/sysconfig/autoboot', 'SysConfigController@autoboot');
	// Route::get('/sysconfig/interHint', 'SysConfigController@interHint');
	// Route::get('/sysconfig/reboot', 'SysConfigController@reboot');
	// Route::get('/sysconfig/telemaintenance', 'SysConfigController@telemaintenance');
	// Route::post('/sysconfig/restart-service', 'SysConfigController@restartTriton');
	// Route::post('/sysconfig/restart-device', 'SysConfigController@restartDevice');

	// 系统账号管理
	$menu_id = MainFrameController::getMenuidByRoute('/sysconfig/account');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/sysconfig/account', 'SysAccountController@getView');
		Route::get('/sysconfig/account-list', 'SysAccountController@getListData');
		Route::any('/sysconfig/account-detail/{id?}', 'SysAccountController@getDetailView');
		Route::any('/sysconfig/account-check-unique/{id?}', 'SysAccountController@checkUnique');
		Route::any('/sysconfig/account-check-unique-email/{id?}', 'SysAccountController@checkUniqueEmail');
		Route::get('/sysconfig/account-menu/{id}', 'SysAccountController@buildMenu');
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/sysconfig/account-addmodify/{id?}', 'SysAccountController@addmodify');
		Route::post('/sysconfig/account-del', 'SysAccountController@del');
		Route::post('/sysconfig/account-switch/{enable}', 'SysAccountController@switchPolicy');
	});

	// 系统角色管理
	$menu_id = MainFrameController::getMenuidByRoute('/sysconfig/account'); // 使用 系统账号管理 菜单的权限
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/sysconfig/role', 'SysAccountRoleController@getView');
		Route::get('/sysconfig/role-list', 'SysAccountRoleController@getOrderListData');
		Route::get('/sysconfig/role-detail/{id?}', 'SysAccountRoleController@getDetailView');
		Route::post('/sysconfig/role-check-unique/{id?}', 'SysAccountRoleController@checkUnique');
		Route::get('/sysconfig/role-list-all/{id?}', 'SysAccountRoleController@getListDataAll');
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/sysconfig/role-addmodify/{id?}', 'SysAccountRoleController@addmodify');
		Route::post('/sysconfig/role-del', 'SysAccountRoleController@del');
		Route::post('/sysconfig/role-up-priority', 'SysAccountRoleController@upPriorityChunk');
		Route::post('/sysconfig/role-down-priority', 'SysAccountRoleController@downPriorityChunk');
	});

	// 冻结账户 
	$menu_id = MainFrameController::getMenuidByRoute('/sysconfig/account-frozen');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/sysconfig/account-frozen', 'SysAccountFrozenController@getView');
		Route::get('/sysconfig/account-frozen-list', 'SysAccountFrozenController@getListData');
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/sysconfig/account-frozen-del', 'SysAccountFrozenController@del');
		Route::post('/sysconfig/account-frozen-save-params', 'SysAccountFrozenController@saveParameters');
	});

	// 邮件服务器 
	$menu_id = MainFrameController::getMenuidByRoute('/sysconfig/mail-server');
	Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
		Route::get('/sysconfig/mail-server', 'MailServerController@getView');
	});
	Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
		Route::post('/sysconfig/mail-server-save-params', 'MailServerController@saveParameters');
	});

	// 系统设置
	if (1) {
		$menu_id = MainFrameController::getMenuidByRoute('/sysconfig/setting/hostname');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/sysconfig/setting/hostname', 'SysSettingController@getViewHostname');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/sysconfig/setting/hostname-save-params', 'SysSettingController@saveParamHostname');
		});

		$menu_id = MainFrameController::getMenuidByRoute('/sysconfig/setting/datetime');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/sysconfig/setting/datetime', 'SysSettingController@getViewDatetime');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/sysconfig/setting/datetime-save-params', 'SysSettingController@saveParamDatetime');
		});

		$menu_id = MainFrameController::getMenuidByRoute('/sysconfig/setting/webui');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/sysconfig/setting/webui', 'SysSettingController@getViewWebui');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/sysconfig/setting/webui-save-params', 'SysSettingController@saveParamWebui');
		});

		$menu_id = MainFrameController::getMenuidByRoute('/sysconfig/setting/onboot');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/sysconfig/setting/onboot', 'SysSettingController@getViewOnboot');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/sysconfig/setting/onboot-save-params', 'SysSettingController@saveParamOnboot');
		});

		$menu_id = MainFrameController::getMenuidByRoute('/sysconfig/setting/securityplug');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/sysconfig/setting/securityplug', 'SysSettingController@getViewSecurityplug');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/sysconfig/setting/securityplug-save-params', 'SysSettingController@saveParamSecurityplug');
		});

		$menu_id = MainFrameController::getMenuidByRoute('/sysconfig/device');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/sysconfig/device', 'SysDeviceController@getView');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/sysconfig/device-reboot', 'SysDeviceController@restartDevice');
			Route::post('/sysconfig/device-restart-service', 'SysDeviceController@restartTriton');
			Route::post('/sysconfig/device-shutdown', 'SysDeviceController@shutdownDevice');
		});

		$menu_id = MainFrameController::getMenuidByRoute('/sysconfig/device-reset-view');
		Route::group(array('before' => 'privilege_view:'.$menu_id), function() {
			Route::get('/sysconfig/device-reset-view', 'SysDeviceController@getViewReset');
		});
		Route::group(array('before' => 'privilege_edit:'.$menu_id), function() {
			Route::post('/sysconfig/device-reset', 'SysDeviceController@resetDevice');
		});

	}

});


//
// 工具类
//
Route::get('/newprocotol', 'UtilController@protocolTableConvert');



//Route::post('/policy/access-policy-addmodify/{id?}', 'AccessPolicyController@assembleAccessRules');
//Route::post('/policy/auth-addmodify/{id?}', 'UserAuthController@addmodifyPolicy');




//
// 测试用例
//
Route::get('/test/{width?}/{height?}', function($width=null, $height=null) {
	$data = array(
		array('Mon',30,'title a','link a'),
		array('Tue',15,'title b','link b'),
		array('Wed',40,'title c','link c'),
		array('Thu',25,'title d','link d'),
		array('Fri',50,'title e','link e'),
		array('Sat',35,'title f','link f'),
		array('Sat',35,'title f','link f'),
		array('Mon',20,'title a','link h')
	);

	return CommonFunc::psvg($data, '', $width, $height);
});

Route::get('bar', function() {
	return View::make('test.bar');
});

Route::get('slide', function() {
	return View::make('test.xslide');
});