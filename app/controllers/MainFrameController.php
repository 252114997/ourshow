<?php

class MainFrameController extends BaseController {

	/**
	 * @brief 返回background图片，可指定参数进行缩放
	 * @return image
	 */
	public function getBackground($filename) {
		$directory = 'img/background/';
		$path = $directory.$filename;
		if (!file_exists($path)) {
			return;
		}

		$param = array();
		$param['path'] = $path;
		$param['height'] = intval(Input::get('height', 320));
		$param['width']  = intval(Input::get('width', 320));
		$param['use_cache'] = intval(Input::get('cache', 1));

		return self::returnCacheImage($param);
	}


	/**
	 * @brief 返回照片，可指定参数进行缩放
	 * @return image
	 */
	public function getPicture($picture_id) {
		$item = tb_pictures::find($picture_id);
		if (null == $item) {
			// not found;
			return;
		}

		$param = array();
		$param['path'] = $item['path'];
		$param['height'] = intval(Input::get('height', 320));
		$param['width']  = intval(Input::get('width', 320));
		$param['use_cache'] = intval(Input::get('cache', 1));

		return self::returnCacheImage($param);
	}

	public function login()	{
		if (UserAuthController::isLogin()) {
			return Redirect::to('/welcome');
		}

		if (Request::isMethod('get')) {
			$user_id = Input::get('id', null);
			$user_info = tb_users::where('id', $user_id)->first();
			if (null == $user_info) {
				return View::make('login')
					->with('deny_info', '需要邀请码啊喂！请点击 短信/微信/QQ 中的链接地址访问！');
			}
			// 使用免登录金牌
			if (true !== UserAuthController::login($user_id, null)) {
				return  Response::make(View::make('login'))
					->withCookie(Cookie::make('user_id', $user_id));
			}
			return Redirect::to('/welcome');
		}

		if (Request::isMethod('post')) {
			// 使用邀请码登录
			$token = Input::get('token');
			$user_id = Cookie::get('user_id');

			$error_info = UserAuthController::login($user_id, $token);
			if (true !== $error_info) {
				return Redirect::to('/login')
					->with('error_info', $error_info)
					->withInput();
			}
			return Redirect::to('/welcome');
		}

		return Response::make('此页面只能用GET/POST方法访问!', 404);
	}

	public function welcome() {
		$user_id = UserAuthController::getLoginUserid();
		// $user_id = 1; // TODO test
		$page = intval(Input::get('page', '1'));
		$rows = intval(Input::get('rows', '6'));

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

		// 随机播放图片
		$directory = 'img/background';
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));

		return View::make('welcome')
			->with(
				'param', 
				array(
					'ablums' => $items, 
					'random_backgrounds' => $scanned_directory,
				)
			);
	}


	public function carousel() {
		return View::make('carousel');
	}

	public function cover() {
		$user_id = UserAuthController::getLoginUserid();
		// $user_id = 1; // TODO test
		$page = intval(Input::get('page', '1'));
		$rows = intval(Input::get('rows', '6'));

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
		$user_id = UserAuthController::getLoginUserid();

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
		$rows = intval(Input::get('rows', '6'));

		$items = self::getCommentsOfAblum($ablum_id, $page, $rows);

		return Response::json(array('status' => 1, 'msg' => 'success', 'data' => $items));
	}

	public function switchLike($ablum_id, $likeit) {
		$user_id = UserAuthController::getLoginUserid();

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

		return array('rows' => $items, 'count' => $total);
		// return $items;
	}

	// $param = array();
	// $param['path'] = $item['path'];
	// $param['height'] = $max_length;
	// $param['width']  = $max_length;
	// $param['use_cache'] = intval(Input::get('cache', 1));
	static public function returnCacheImage($param) {
		$max_length = max($param['height'], $param['width']);
		$length_array = array(320, 640, 770, 1024);
		foreach ($length_array as $key => $value) {
			if ($max_length <= $value) {
				$max_length = $value;
				break;
			}
		}
		$param['height'] = $max_length;
		$param['width']  = $max_length;

		Util::log_debug(json_encode($param));

		$content_datas = array();
		if ($param['use_cache']) {
			Util::log_debug("use_cache!");
			$content_datas = Cache::rememberForever(json_encode($param), function() use ($param) {
				// 使用cache提高性能
				Util::log_debug("use_cache! in cache first get");
				$content = self::scaleImageFileToBlob($param);
				return array(
					'last_modified_time' => time(),
					'etag' => md5($content),
					'content' => $content,
				);
			});
		}
		else {
			$content = self::scaleImageFileToBlob($param);
			$content_datas = array(
				'last_modified_time' => time(),
				'etag' => md5($content),
				'content' => $content,
			);
		}

		// 请求重复内容，使用304跳转，提高速度
		$http_header = array(
			"Last-Modified" => $content_datas['last_modified_time'],
			"Etag" => $content_datas['etag'],
		);
		Util::log_debug(" HTTP_IF_MODIFIED_SINCE = ".Request::header('If-Modified-Since'));
		Util::log_debug(" HTTP_IF_NONE_MATCH = ".Request::header('If-None-Match'));
		if (@strtotime(Request::header('If-Modified-Since')) == $content_datas['last_modified_time'] || 
			trim(Request::header('If-None-Match')) == $content_datas['etag']) { 
			Util::log_debug(" http 304 ");
			return Response::make('', 304, $http_header);
			// return App::abort(304);
		}

		Util::log_debug(" http 200 ");
		$http_header += array(
			"Content-Type" => image_type_to_mime_type(exif_imagetype($param['path'])), //"image/x-png",
			"Content-Length" => strlen($content_datas['content']),
		);
		return Response::make($content_datas['content'], 200, $http_header);
	}

	/**
	 * @brief 返回经过缩放后的图片数据
	 *
	 * reference: http://php.net/manual/zh/function.imagejpeg.php
	 */
	static public function scaleImageFileToBlob($param) {
	    $source_pic = $param['path'];
	    $max_width  = $param['width'];
	    $max_height = $param['height'];

	    list($width, $height, $image_type) = getimagesize($source_pic);

	    switch ($image_type)
	    {
	        case 1: $src = imagecreatefromgif($source_pic); break;
	        case 2: $src = imagecreatefromjpeg($source_pic);  break;
	        case 3: $src = imagecreatefrompng($source_pic); break;
	        default: return '';  break;
	    }

	    $x_ratio = $max_width / $width;
	    $y_ratio = $max_height / $height;

	    if( ($width <= $max_width) && ($height <= $max_height) ){
	        $tn_width = $width;
	        $tn_height = $height;
	        }elseif (($x_ratio * $height) < $max_height){
	            $tn_height = ceil($x_ratio * $height);
	            $tn_width = $max_width;
	        }else{
	            $tn_width = ceil($y_ratio * $width);
	            $tn_height = $max_height;
	    }

	    $tmp = imagecreatetruecolor($tn_width,$tn_height);

	    /* Check if this image is PNG or GIF, then set if Transparent*/
	    if(($image_type == 1) OR ($image_type==3))
	    {
	        imagealphablending($tmp, false);
	        imagesavealpha($tmp,true);
	        $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
	        imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
	    }
	    imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

	    /*
	     * imageXXX() only has two options, save as a file, or send to the browser.
	     * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
	     * So I start the output buffering, use imageXXX() to output the data stream to the browser, 
	     * get the contents of the stream, and use clean to silently discard the buffered contents.
	     */
	    ob_start();

	    switch ($image_type)
	    {
	        case 1: imagegif($tmp); break;
	        case 2: imagejpeg($tmp, NULL, 75);  break; // best quality
	        case 3: imagepng($tmp, NULL, 8); break; // no compression
	        default: echo ''; break;
	    }

	    $final_image = ob_get_contents();

	    ob_end_clean();

	    return $final_image;
	}

}