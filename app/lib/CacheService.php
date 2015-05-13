<?php

class CacheService
{
	static public function getProcotolCache()
	{
		return Cache::remember('protocols', 10, function() {
			$entries = DB::table('tb_protocol')->get();

			$data = array();
			foreach($entries as $e) {
				$data[$e->id] = $e;
			}

			return $data;
		});
	}
}