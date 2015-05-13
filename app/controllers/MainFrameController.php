<?php

class MainFrameController extends BaseController {

	public function index()
	{		
		$company = DB::table('tb_global_settings')
						->where('name', 'company')
						->first();

		$product = DB::table('tb_product')
						->join('tb_global_settings', function($join) {
							$join->on('tb_product.name', '=', 'tb_global_settings.value')
								 ->where('tb_global_settings.name', '=', 'product');
						})
						->select('tb_product.description')
						->first();

		return View::make('index')
				->with('product', $product ? $product->description : '')
				->with('company', $company ? $company->value : '');
	}

	const ROOT_MENU_ID = '0';

	/**
	 * @brief 以指定ID的菜单为根，构造菜单子树。
	 * 
	 * 当前登录用户无权限的菜单不会出现在树中
	 * @return json tree
	 */
	public function buildSubMenus($pid) {
		$account_id = Auth::user()->id;

		$query_builder = DB::table('tb_menus')
			->select(
				'tb_menus.id as id', 'tb_menus.parent_id as parent_id', 
				'tb_menus.title as title', 'tb_menus.icon as icon' ,
				'tb_menus.route as route'
			)
			->where('tb_menus.display', '=', 1)
			->orderBy('menuorder', 'asc')
			->distinct();
		if (!in_array($account_id, SysAccountController::$PROTECT_ID_ARRAY)) {
			$query_builder->where('tb_privilege.account_id', '=', $account_id)
						  ->join('tb_privilege', 'tb_menus.id', '=', 'tb_privilege.module_id');
		}
		$submenus = $query_builder->get();

		$menuarr = array();
		foreach($submenus as $m) {
			$menuarr[] = array(
				'id'         => $m->id,
				'pid'        => $m->parent_id,
				'text'       => $m->title,
				'iconCls'    => $m->icon,
				'attributes' => array('url' => $m->route));
		}

		$data = CommonFunc::buildTree($menuarr, $pid);
		return Response::json($data);
	}

	/**
	 * @brief 获取指定ID的子菜单。以数组形势，仅返回儿子菜单，孙子及后辈菜单不会返回
	 * 
	 * 当前登录用户无权限的菜单不会出现在数组中
	 * @return array
	 */
	static public function getSubMenus($pid) {
		$account_id = Auth::user()->id;

		$query_builder = DB::table('tb_menus')
			->select(
				'tb_menus.id as id', 'tb_menus.parent_id as parent_id', 
				'tb_menus.title as title', 'tb_menus.icon as icon',
				'tb_menus.route as route'
			)
			->where('tb_menus.parent_id', '=', $pid)
			->where('tb_menus.display', '=', 1)
			->orderBy('menuorder', 'asc')
			->distinct();
		if (!in_array($account_id, SysAccountController::$PROTECT_ID_ARRAY)) {
			$query_builder->where('tb_privilege.account_id', '=', $account_id)
						  ->join('tb_privilege', 'tb_menus.id', '=', 'tb_privilege.module_id');
		}
		$menus = $query_builder->get();
		return $menus;
	}

	/**
	 * @brief 根据菜单路由地址获取菜单ID
	 * 
	 * @return id
	 */
	static public function getMenuidByRoute($route) {
		$menu_id = 0;
		if (Schema::hasTable('tb_menus')) {
			$menu_info = DB::table('tb_menus')->where('route', '=', $route)->first();
			if (null != $menu_info) {
				$menu_id = $menu_info->id;
			}
		}
		return $menu_id;
	}

	/**
	 * @brief 验证用户是否有指定菜单的权限
	 * 
	 * @param  $module_id:  菜单（模块）ID, tb_menus 表的 id 字段
	 * @param  $right_name: 权限名称,       tb_privilege_right 表的 name 字段
	 * @return true:有权限  false:无权限
	 */
	static public function validateMenuRight($module_id, $right_name) {
		// 当前登录账户的ID
		$account_id = Auth::user()->id;
		if (in_array($account_id, SysAccountController::$PROTECT_ID_ARRAY)) {
			return true;
		}

		// 读取编辑权限的ID
		$right_id = DB::table('tb_privilege_right')->where('name', '=', $right_name)->pluck('id');

		$data = DB::table('tb_privilege')
			->where('account_id', '=', $account_id)
			->where('right_id',   '=', $right_id)
			->where('module_id',  '=', $module_id)
			->first();
		if (null == $data) {
			return false;
		}
		return true;
	}

	public function messages()
	{
		return View::make('dashboard.message');
	}

	public function getMessageData()
	{
		$datas = array(
			array('msg_name' => '磁盘满1', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高2', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高3', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高4', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高5', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => '磁盘满6', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高7', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高8', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高9', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高10', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => '磁盘满11', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高12', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高13', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高14', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高15', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => '磁盘满16', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高17', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高18', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高19', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),
			array('msg_name' => 'CPU高20', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),			
			array('msg_name' => 'CPU高21', 'msg_type' => '系统告警', 'msg_desc' => '磁盘使用率超过90%'),			
		);
		$page = intval(Input::get('page', '1'));
		$rows = intval(Input::get('rows', '10'));
		$offset = ($page - 1) * $rows;
		$count = count($datas);

		$items = array();
		for ($i = $offset, $j = 0; $j < $rows && $i < $count; $i++, $j++)
		{
			$items[] = array(
				'msg_name' => $datas[$i]['msg_name'],
				'msg_type' => $datas[$i]['msg_type'],
				'msg_desc' => $datas[$i]['msg_desc']);
		}
		$data['total'] = $count;
		$data['rows'] = $items;	
		return Response::json($data);
	}
}