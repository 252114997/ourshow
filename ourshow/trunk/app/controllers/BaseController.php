<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	// TODO 更改下面的 static 为 public 
	static protected $LOG_TAG = "TODO override";
	static protected $LOG_SEPARATOR = " ";                 // 以空格分隔日志内容
	static protected $DBMODEL_CLASSNAME = "TODO override"; // 用于操作数据库的 models 类名
	static protected $PRIMARY_KEY_NAME = "TODO override";  // 主键字段名
	static protected $NAME_FIELD_NAME = "TODO override";   // 名称字段名
	static protected $SWITCH_FIELD_NAME = "TODO override"; // 启用、禁用字段名
	static protected $PRIORITY_FIELD_NAME = "TODO override";//表示优先级的字段名 
	static protected $PROTECT_ID_ARRAY = array();          // 受保护的数据ID。这些ID的数据不允许修改

	/**
	 * @brief 返回数据列表
	 * @return json
	 */
	public function getListData() {
		return $this->getOrderListData(false);
	}

	/**
	 * @brief 返回策略的数据列表,可按优先级排序显示
	 * @return json
	 */
	public function getOrderListData($order_data = true) {
		$db_model_class = static::$DBMODEL_CLASSNAME;

		$page = intval(Input::get('page', '1'));
		$rows = intval(Input::get('rows', '10'));

		$total = $db_model_class::select()->count();

		$offset = ($page - 1) * $rows;

		if ($order_data) {
			$items = $db_model_class::select()
							->orderBy(static::$PRIORITY_FIELD_NAME, 'asc')
							->skip($offset)
							->take($rows)
							->get()
							->toArray();
		}
		else {
			$items = $db_model_class::select()
							->skip($offset)
							->take($rows)
							->get()
							->toArray();			
		}

		$formatted_data['total'] = $total;
		$formatted_data['rows'] = $this->formatListData($items);

		return Response::json($formatted_data);
	}

	/**
	 * @brief 返回所有数据（没有分页）
	 * @return json
	 */
	public function getListDataAll() {
		$db_model_class = static::$DBMODEL_CLASSNAME;

		$items = $db_model_class::select(
			static::$PRIMARY_KEY_NAME, 
			static::$NAME_FIELD_NAME
		)->get()->toArray();

		return Response::json($items);
	}

	/**
	 * @brief 返回所有数据（没有分页）
	 * @return json
	 *
	 * 默认增加一条 "请选择xxx" 的数据
	 */
	public function getListDataDefaultTodo() {
		$db_model_class = static::$DBMODEL_CLASSNAME;

		$items = $db_model_class::select(
			static::$PRIMARY_KEY_NAME, 
			static::$NAME_FIELD_NAME
		)->get()->toArray();

		$items[] = array(
			static::$PRIMARY_KEY_NAME => 0,
			static::$NAME_FIELD_NAME => ("请选择".static::$LOG_TAG)
		);

		return Response::json($items);
	}


	/**
	 * @brief 解析出详细信息，以json数组形势返回客户端
	 * @return json
	 */
	protected function formatListData($items) {
		// to override
		return $items;
	}

	/**
	 * @brief 增加、修改数据
	 * @return false: 操作失败  array()：操作成功，返回插入数据库的数据
	 */
	public function updateOrCreate($id, $values) 
	{
		return self::updateOrCreateStatic($id, $values);
	}
	static public function updateOrCreateStatic($id, $values) {
		$dbmodel_classname = static::$DBMODEL_CLASSNAME;
		if (in_array($id, static::$PROTECT_ID_ARRAY)) {
			// 跳过受保护的数据，比如 系统账户 admin 禁止删除
			return $dbmodel_classname::find($id)->toArray();
		}

		$primary_key_name = static::$PRIMARY_KEY_NAME;
		$name_field_name = static::$NAME_FIELD_NAME;
		$switch_field_name = static::$SWITCH_FIELD_NAME;
		$priority_field_name = static::$PRIORITY_FIELD_NAME;

		$cur_item = $dbmodel_classname::updateOrCreate(
			array($primary_key_name => $id), 
			$values
		)->toArray();

		if (self::$PRIORITY_FIELD_NAME != static::$PRIORITY_FIELD_NAME) {
			// 优先级字段被覆盖，则认为当前类支持设置优先级
			if (!isset($cur_item[$priority_field_name]) || 0 == $cur_item[$priority_field_name]) {
				// 无优先级的数据，将其设置为最小优先级（一般会在新增数据时执行到此处）
				$cur_item = $dbmodel_classname::updateOrCreate(
					array($primary_key_name    => $cur_item[$primary_key_name]), 
					array($priority_field_name => self::getNextPriority())
				)->toArray();
			}
		}

		Util::writeOperationLog(
			($id ? "修改" : "添加")
				.static::$LOG_SEPARATOR.static::$LOG_TAG 
				.static::$LOG_SEPARATOR.$cur_item[$name_field_name]
			, static::$LOG_SEPARATOR.json_encode($cur_item)
		);

		return $cur_item;
	}

	/**
	 * @brief 删除前被 del() 调用，用于删除策略前做些准备处理
	 * @return json
	 */
	public function beforeDel($items) {
		// to override
		return true;
	}

	/**
	 * @brief 删除
	 * @return json
	 */
	public function del() {
		$db_model_class = static::$DBMODEL_CLASSNAME;

		$item_array = array();
		$items = Input::json();

		foreach($items as $item) {
			if (in_array($item, static::$PROTECT_ID_ARRAY)) {
				// 跳过受保护的数据，比如 系统账户 admin 禁止删除
				continue;
			}
			$item_array[] = $item;
		}
		if (count($item_array) == 0) {
			return Response::json(array('status' => 1, 'msg' => "skip"));
		}

		$this->beforeDel($item_array);

		$deleted_items = $db_model_class::whereIn(static::$PRIMARY_KEY_NAME, $item_array)
					->get()
					->toArray();
		$ret = $db_model_class::destroy($item_array);

		if ($ret) {
			$deleted_string = array();
			foreach ($deleted_items as $item) {
				$deleted_string[] = $item[static::$NAME_FIELD_NAME];
			}

			Util::writeOperationLog(
					"删除"
						.static::$LOG_SEPARATOR.static::$LOG_TAG
						.static::$LOG_SEPARATOR.implode(", ", $deleted_string)
					, static::$LOG_SEPARATOR.json_encode($deleted_items)
			);

			$this->apply();
		}

		return Response::json(array('status' => 1, 'msg' => "success"));
	}


	/**
	 * @brief 检查名称是否唯一
	 * @return true:是唯一的; false:有重复;
	 */
	public function checkUnique($id = null) {
		return $this->checkUniqueField($id, static::$NAME_FIELD_NAME);
	}
	public function checkUniqueField($id, $filed_name) {
		$db_model_class = static::$DBMODEL_CLASSNAME;

		$name = Input::get($filed_name, '');
		$id = is_null($id) ? 0 : $id;

		$item = $db_model_class::where($filed_name, '=', $name)
					->where(static::$PRIMARY_KEY_NAME, '!=', $id)
					->first()
					;

		if ($item) {
			return 'false';
		}
		else {
			return 'true';
		}
	}


	/**
	 * @brief 启用禁用策略
	 * @return json
	 */
	public function switchPolicy($enable) {
		$db_model_class = static::$DBMODEL_CLASSNAME;

		$item_array = array();
		$items = Input::json();

		foreach($items as $item) {
			if (in_array($item, static::$PROTECT_ID_ARRAY)) {
				// 跳过受保护的数据，比如 系统账户 admin 禁止删除
				continue;
			}
			$item_array[] = $item;
		}
		if (count($item_array) == 0) {
			return Response::json(array('status' => 1, 'msg' => "skip"));
		}

		$db_model_class::whereIn(static::$PRIMARY_KEY_NAME, $item_array)
						->update( array(static::$SWITCH_FIELD_NAME =>($enable ? 1 : 0)) );

		$this->apply();

		return Response::json(array(
				'status' => 1
				, 'msg' => "success"
			)
		);
	}

	/**
	 * @brief 返回最大优先级
	 * @return json
	 */
	static public function getNextPriority() {
		$db_model_class = static::$DBMODEL_CLASSNAME;

		$item = $db_model_class::select(static::$PRIORITY_FIELD_NAME)
						->orderBy(static::$PRIORITY_FIELD_NAME, 'desc')
						->first();
		if ($item === null) {
			return 1;
		}
		else {
			$priority_field_name = static::$PRIORITY_FIELD_NAME;
			$item = $item->toArray();
			return $item[$priority_field_name] + 1;
		}
	}
  
	/**
	 * @brief 批量上调指定ID的优先级
	 * @param 包含策略ID的json array 
	 * @return json
	 *
	 * 此接口需要保证输入ID中，优先级从高到底排列（priority越小，优先级越高）
	 */
	public function upPriorityChunk() {
		$item_array = array();
		$items = Input::json();

		foreach($items as $item) {
			if (in_array($item, static::$PROTECT_ID_ARRAY)) {
				// 跳过受保护的数据，比如 系统账户 admin 禁止删除
				continue;
			}
			if (true !== ($msg = $this->upPriority($item))) {
				return Response::json(array(
					"status" => 0,
					"msg" => $msg
				));
			}
		}

		$this->apply();

		$data = array();
		$data['status'] = 1;
		$data['msg'] = "success";
		return Response::json($data);
	}

	/**
	 * @brief 批量下调指定ID的优先级
	 * @param 包含策略ID的json array 
	 * @return json
	 *
	 * 此接口需要保证输入ID中，优先级从高到底排列（priority越小，优先级越高）
	 */
	public function downPriorityChunk() {		
		$item_array = array();
		$items = Input::json()->all();

		// 将输入ID反序，以保证从优先级最低的开始，逐个向下调整优先级
		foreach(array_reverse($items) as $item) {
			if (in_array($item, static::$PROTECT_ID_ARRAY)) {
				// 跳过受保护的数据，比如 系统账户 admin 禁止删除
				continue;
			}
			if (true !== ($msg = $this->downPriority($item))) {
				return Response::json(array(
					"status" => 0,
					"msg" => $msg
				));
			}
		}

		$this->apply();

		$data = array();
		$data['status'] = 1;
		$data['msg'] = "success";
		return Response::json($data);
	}

	/**
	 * @brief 上调指定ID的优先级
	 * @return json
	 */
	private function upPriority($id) {
		$db_model_class = static::$DBMODEL_CLASSNAME;
		$priority_field_name = static::$PRIORITY_FIELD_NAME;
		$primary_key_name = static::$PRIMARY_KEY_NAME;

		$policy = $db_model_class::find($id);

		if (null == $policy) {
			return "Not found policy id=".$id." !";
		}
		$policy = $policy->toArray();
		if (!$policy[$priority_field_name]) {
			 $policy[$priority_field_name] = self::getNextPriority();
		}

		// 找到比 $id 优先级高的第一个策略
		$up_policy = $db_model_class::select()
						->where($priority_field_name, '<', $policy[$priority_field_name])
						->orderBy($priority_field_name, 'desc')
						->first();
		if (null == $up_policy) {
			return "Not found policy priority<".$id." !";
		}

		$up_policy = $up_policy->toArray();

		if (in_array($up_policy[$primary_key_name], static::$PROTECT_ID_ARRAY)) {
			// 跳过受保护的数据，比如 系统账户 admin 禁止删除
			return true;
		}

		if (   (0 == $up_policy[$priority_field_name]) 
			|| ($policy[$priority_field_name] == $up_policy[$priority_field_name]) 
		) {
			 $up_policy[$priority_field_name] = $policy[$priority_field_name] + 1;
		}

		// 交换两个策略的优先级
		$db_model_class::updateOrCreate(
			array($primary_key_name    => $up_policy[$primary_key_name]), 
			array($priority_field_name => $policy[$priority_field_name])
		);
		$db_model_class::updateOrCreate(
			array($primary_key_name    => $policy[$primary_key_name]), 
			array($priority_field_name => $up_policy[$priority_field_name])
		);
		return true;
	}

	/**
	 * @brief 下调指定ID的优先级
	 * @return json
	 */
	private function downPriority($id) {
		$db_model_class = static::$DBMODEL_CLASSNAME;
		$priority_field_name = static::$PRIORITY_FIELD_NAME;
		$primary_key_name = static::$PRIMARY_KEY_NAME;

		$policy = $db_model_class::find($id);

		if (null == $policy) {
			return "Not found policy id=".$id." !";
		}
		$policy = $policy->toArray();
		if (!$policy[$priority_field_name]) {
			 $policy[$priority_field_name] = self::getNextPriority();
		}

		// 找到比 $id 优先级低的第一个策略
		$down_policy = $db_model_class::select()
						->where($priority_field_name, '>', $policy[$priority_field_name])
						->orderBy($priority_field_name, 'asc')
						->first();
		if (null == $down_policy) {
			return "Not found policy priority>".$id." !";
		}
		$down_policy = $down_policy->toArray();

		if ($policy[$primary_key_name] == $down_policy[$primary_key_name]) {
			return true;
		}

		if (in_array($down_policy[$primary_key_name], static::$PROTECT_ID_ARRAY)) {
			// 跳过受保护的数据，比如 系统账户 admin 禁止删除
			return true;
		}

		// 防止数据库出现优先级不正确的数据
		if (   (0 == $down_policy[$priority_field_name]) 
			|| ($policy[$priority_field_name] == $down_policy[$priority_field_name]) 
		) {
			 $down_policy[$priority_field_name] = $policy[$priority_field_name] - 1;
		}

		// 交换两个策略的优先级
		$db_model_class::updateOrCreate(
			array($primary_key_name    => $down_policy[$primary_key_name]), 
			array($priority_field_name => $policy[$priority_field_name])
		);
		$db_model_class::updateOrCreate(
			array($primary_key_name    => $policy[$primary_key_name]), 
			array($priority_field_name => $down_policy[$priority_field_name])
		);
		return true;
	}

	/**
	 * @brief 改动数据后，执行本函数 应用生效
	 * @return json
	 */
	public function apply() {
		Util::log_debug('apply() in BaseController');
		return Util::reloadTritonIpMac();
	}
}
 