<?php

/**
 * 该类包含了一些常用的函数
 */
class CommonFunc 
{
	static public function buildTree($tree, $pid, $closed = false, $filter_keyword = null, &$filter_match = null, $root_id = 0) 
	{
		$data = array();

		foreach($tree as $m) {
			if ($m['pid'] === $pid) { // must use === ，以兼容pid为字符串的情况。（当$pid为 "123-457" 一类的字符串时， 使用 $m['pid'] == $pid 比较会出现不正常的现象）
				$match = 0;
				$tmp = self::buildTree($tree, $m['id'], $closed, $filter_keyword, $match, $root_id);
				if ($tmp) {
					$m['children'] = $tmp;
					if ($closed && $pid != $root_id) // collapsed not root
						$m['state'] = 'closed';
				} else {
					$m['leaf'] = 1;
				}

				// 没有设置过滤条件
				if (!isset($filter_keyword)) {
					$data[] = $m;
					continue;
				}

				// 设置了过滤关键字，children匹配到了，或者自己匹配到了
				if ($match || (false !== strpos($m['text'], $filter_keyword))) {
					$filter_match = 1;
					$data[] = $m;
				}
			}
		}

		return $data;
	}


	static public function buildPrivTree($tree, $pid, $priv = null, $closed = false, $filter_keyword = null, &$filter_match = null) 
	{
		$data = array();

		foreach($tree as $m) {
			if ($m['pid'] == $pid) {
				$match = 0;
				$tmp = self::buildPrivTree($tree, $m['id'], $priv, $closed, $filter_keyword, $match);
				if ($tmp) {
					$m['children'] = $tmp;
					if ($closed)
						$m['state'] = 'closed';
				} else {
					$m['leaf'] = 1;
				}

				if (array_key_exists($m['id'], $priv)) {
					foreach($priv[$m['id']] as $k => $v) {
						$m[$k] = $v;
					}
				} 

				// 没有设置过滤条件
				if (!isset($filter_keyword)) {
					$data[] = $m;
					continue;
				}

				// 设置了过滤关键字，children匹配到了，或者自己匹配到了
				if ($match || (false !== strpos($m['text'], $filter_keyword))) {
					$filter_match = 1;
					$data[] = $m;
				}
			}
		}

		return $data;
	}


	static public function psvg($data,$name='',$width='',$height=''){
		global $mobile;
		$output = $offset = $count = array();
		$r = $tnum = '';
		$size = array('x'=>'720','y'=>'240');
		if($width>270) $size['x'] = $width;
		if($height>90) $size['y'] = $height;
		$zero = array('x'=>'40','y'=>'');
		$zero['y'] = 30;
		$main = array('x'=>$size['x']-2*$zero['x']+20,'y'=>$size['y']-$zero['y']-$zero['x']+20);
		$axis = array('left'=>$zero['x']-8,'bottom'=>$zero['y']+15+$main['y']);
		$count['x'] = count($data)-1;
		$offset['x'] = round($main['x']/$count['x'],2);
		$max = $tnum = 0;
		foreach($data as $d){
			if($d[1]>$max) $max = $d[1];
		}
		$count['y'] = 5;
		$offset['y'] = ceil($main['y']/$count['y']);
		$tnum = ceil($max/$count['y']);
		$tnum = ceil($tnum/5)*5;
		$max = $tnum*$count['y'];
		for($i=0;$i<=$count['x'];$i++){
			$names['x'][$i] = array('x'=>$axis['bottom'],'y'=>($zero['x']+$main['x']-$i*$offset['x']),'name'=>$data[($count['x']-$i)][0]);
			if($names['x'][$i]['y']<$zero['x']) $names['x'][$i]['y'] = $zero['x'];
		}
		for($i=$count['y'];$i>=0;$i--){
			$names['y'][$i] = array('x'=>$axis['left'],'y'=>($zero['y']+$main['y']-$i*$offset['y']),'name'=>$tnum*$i);
		}
		foreach(array_keys($data) as $k){
			$tx = $ty = 0;
			$tx = $count['x']-$k;
			$ty = $zero['y']+$main['y']-ceil(($data[$k][1]/$max)*$main['y']);
			$names['p'][$k] = array('x'=>$names['x'][$tx]['y'],'y'=>$ty,'name'=>$data[$k][2],'link'=>$data[$k][3],'count'=>$data[$k][1]);
		}
		foreach(array_keys($names['x']) as $k){
			$k = $count['x']-$k;
			$t = $names['x'][$k];
			$output['x'][$k] = '<text x="'.$t['y'].'" y="'.$t['x'].'">'.$t['name'].'</text>';
		}
		foreach(array_keys($names['y']) as $k){
			$k = $count['y']-$k;
			$t = $names['y'][$k];
			$output['y'][$k] = '<text x="'.$t['x'].'" y="'.($t['y']+4).'">'.$t['name'].'</text>';
		}
		foreach(array_keys($names['p']) as $k){
			$t = $names['p'][$k];
			if(empty($t['count'])) $t['count'] = 0;
			if(!empty($t['link'])) $output['p'][$k] = '<a xlink:href="'.$t['link'].'" xlink:title="'.$t['name'].'"><use x="'.$t['x'].'" y="'.$t['y'].'" xlink:href="#link"><title>'.$t['count'].'</title></use></a>';
			else $output['p'][$k] = '<use x="'.$t['x'].'" y="'.$t['y'].'" xlink:href="#null"><title>'.$t['count'].'</title></use>';
		}
		$output['gird'] = $output['back'] = $output['line'] = '';
		foreach(array_keys($names['y']) as $k){
			$t = $names['y'][$k];
			$output['gird'] = ' '.$t['y'].'h'.$main['x'].'M'.$zero['x'].$output['gird'];
		}
		$output['gird'] = 'M'.$zero['x'].$output['gird'];
		foreach(array_keys($names['x']) as $k){
			$t = $names['x'][$k];
			$output['gird'].= ' '.$zero['y'].'v'.$main['y'].'M'.$t['y'];
		}
		$output['back'] = 'M'.$zero['x'].' '.($zero['y']+$main['y']).'L';
		foreach(array_keys($names['p']) as $k){
			$t = $names['p'][$k];
			$output['back'].= $t['x'].' '.$t['y'].' ';
			$output['line'].= $t['x'].' '.$t['y'].' ';
		}
		$output['gird'] = $output['gird'].' '.$zero['y'].'v'.$main['y'];
		$output['back'].='L'.($zero['x']+$main['x']).' '.($zero['y']+$main['y']).'z';
		$output['line'] = 'M'.$output['line'];
		$output['xaxis'] = 'M'.($zero['x']-2).' '.($zero['y']+$main['y']).'h'.($main['x']+4);
		$output['yaxis'] = 'M'.$zero['x'].' '.($zero['y']-2).'v'.($main['y']+4);
		$r.= '<svg width="'.$size['x'].'" height="'.$size['y'].'" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">'."\r\n";
		$r.= '<defs>'."\r\n";
		$r.= '<clipPath id="main"><rect x="'.$zero['x'].'" y="'.$zero['y'].'" width="'.$main['x'].'" height="'.$main['y'].'" /></clipPath>'."\r\n";
		$r.= '<symbol><circle id="link" cursor="pointer" stroke="#ff9900" stroke-width="1.5" fill="#ffffff" r="5" /></symbol>'."\r\n";
		$r.= '<symbol><circle id="null" cursor="pointer" stroke="#ff9900" stroke-width="1.5" fill="#ffffff" r="5" /></symbol>'."\r\n";
		$r.= '<linearGradient id="back" x1="0" x2="0" y1="0" y2="100%"><stop offset="0%" stop-color="#f3f8fa" /><stop offset="100%" stop-color="#BCDEEF" /></linearGradient>'."\r\n";
		$r.= '</defs>'."\r\n";
		$r.= '<rect width="100%" height="100%" fill="#FFFFFF" stroke-width="0" />'."\r\n";
		if(!empty($name)){
			if($mobile=='mobile') $r.= '<text font-size="13px" text-anchor="middle" fill="#333333" x="'.($main['x']/2+$zero['x']).'" y="'.($zero['y']/2).'">'.$name.'</text>'."\r\n";
			else $r.= '<text font-size="15px" text-anchor="middle" fill="#333333" x="'.($main['x']/2+$zero['x']).'" y="'.($zero['y']/2).'">'.$name.'</text>'."\r\n";
		}
		$r.= '<g clip-path="url(#main)">'."\r\n";
		$r.= '<path stroke="none" fill="url(#back)" d="'.$output['back'].'" />'."\r\n";
		$r.= '<path stroke="#FF9900" fill="none" stroke-width="1.5" d="'.$output['line'].'" />'."\r\n";
		$r.= '</g>'."\r\n";
		$r.= '<path d="'.$output['gird'].'" stroke="#b3b3b3" stroke-dasharray="5,5" stroke-width="0.5" />'."\r\n";
		$r.= '<g stroke-width="1" stroke="#A0A0A0">'."\r\n";
		$r.= '<path d="'.$output['xaxis'].'" />'."\r\n";
		$r.= '<path d="'.$output['yaxis'].'" />'."\r\n";
		$r.= '</g>'."\r\n";
		if($mobile=='mobile') $r.= '<g font-size="10px" fill="#777777">'."\r\n";
		else $r.= '<g font-size="12px" fill="#777777">'."\r\n";
		$r.= '<g text-anchor="end">'."\r\n".implode("\r\n",$output['y'])."\r\n";
		$r.= '</g>'."\r\n";
		$r.= '<g text-anchor="middle">'."\r\n".implode("\r\n",$output['x'])."\r\n";
		$r.= '</g>'."\r\n";
		$r.= '</g>'."\r\n".implode("\r\n",$output['p'])."\r\n";
		$r.= '</svg>'."\r\n";
		return $r;
	}	
}