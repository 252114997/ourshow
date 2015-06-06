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

	public function carousel() {
		return View::make('carousel');
	}

	public function cover() {
		$user_id = Cookie::get('user_id');
		$user_id = 1; // TODO test
		$page = intval(Input::get('page', '1'));
		$rows = intval(Input::get('rows', '10'));

		$total = tb_ablums::select()->count();
		$offset = ($page - 1) * $rows;

		$items = tb_ablums::select()
			->orderBy('created_at', 'asc')
			->skip($offset)
			->take($rows)
			->get()
			->toArray();

		foreach ($items as $key => &$value) {
			$value['picture_id'] = tb_pictures::find($value['picture_id'])->toArray();
			$value['likes'] = self::getLikesOfAblum($value['id']);
			$value['likeit'] = self::isLikeIt($value['id'], $user_id);
			// $value['comments'] = self::getCommentsOfAblum($value['id'], 1, 10); // TODO 分页
		}

		return View::make('cover')
			->with('param', $items);
	}

	public function addComments($ablum_id) {
		$user_id = Cookie::get('user_id');
		$user_id = 1; // TODO test

		$input = Input::json();
		$comment = $input->get('comment');

		if ('' == $comment) {
			return;
		}

		tb_posts::create(array(
			'ablum_id' => $ablum_id,
			'user_id' => $user_id,
			'text' => $comment,
		));

		return Response::json(array('status' => 1, 'msg' => 'success', 'data' => null));
	}

	public function getComments($ablum_id) {

		$page = intval(Input::get('page', '1'));
		$rows = intval(Input::get('rows', '10'));

		$items = self::getCommentsOfAblum($ablum_id, $page, $rows);

		return Response::json(array('status' => 1, 'msg' => 'success', 'data' => $items));
	}

	public function switchLike($ablum_id, $likeit) {
		$user_id = Cookie::get('user_id');
		$user_id = 1; // TODO test

		if (1 == $likeit) {// like it
			tb_like::firstOrCreate(array('user_id' => $user_id, 'ablum_id' => $ablum_id ));
		}
		else {
			tb_like::where('user_id', $user_id)->where('ablum_id', $ablum_id)->delete();
		}
		$items = array(
			'likes' => self::getLikesOfAblum($ablum_id),
			'likeit' => self::isLikeIt($ablum_id, $user_id)
		);

		return Response::json(array('status' => 1, 'msg' => 'success', 'data' => $items));
	}

	public function testp() {
		return View::make('test');
	}

	static public function getLikesOfAblum($ablum_id) {
		$items = tb_like::where('ablum_id', $ablum_id)->get()->toArray();
		foreach ($items as $key => &$value) {
			$value['username'] = tb_users::where('id', $value['user_id'])->pluck('username');
		}
		return $items;
	}
	static public function isLikeIt($ablum_id, $user_id) {
		return tb_like::where('ablum_id', $ablum_id)->where('user_id', $user_id)->count();
	}

	static public function getCommentsOfAblum($ablum_id, $page, $rows) {

		$query_builder = tb_posts::select()->where('ablum_id', $ablum_id);
		$total = $query_builder->count();
		$offset = ($page - 1) * $rows;

		$items = $query_builder
			->orderBy('updated_at', 'desc')
			->skip($offset)
			->take($rows)
			->get()
			->toArray();

		foreach ($items as $key => &$value) {
			$value['user_id'] = tb_users::find($value['user_id'])->toArray();

			$updated_at = new DateTime($value['updated_at']);
			$now = new DateTime();
			$value['updated_at'] = FormatFunc::time($now->getTimestamp() - $updated_at->getTimestamp());
		}

		return $items;
	}

}