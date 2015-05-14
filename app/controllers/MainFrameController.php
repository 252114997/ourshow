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
		return View::make('cover');
	}
}