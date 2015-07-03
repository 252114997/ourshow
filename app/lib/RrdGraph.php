<?php
/**
 * @brief 绘制rrd图的类
 *
 * TODO 此文件重复代码很多，可以优化，
 */
class RrdGraph 
{
	static public function summaryApp($param) {
		$l7prot = $param['l7prot'];
		$l7name = $param['l7name'];
		$height = $param['height'];
		$width  = $param['width'];

		$rrd = '';
		$appl_sum = '';
		$graph_title = '';
		$color= array('#FF66CC','#FF6600','#660000','#CC00FF','#6600FF','#0000CC','#00CC00','#660066','#6699FF','#00FFCC');

		if($l7prot!=Protocol::L7PROTOCOL_ALL){
			$rrd  .= ' DEF:appl_in=/opt/rrd/protocol/'.$l7prot.'.rrd:inByte:AVERAGE ';
			$rrd  .= ' DEF:appl_out=/opt/rrd/protocol/'.$l7prot.'.rrd:outByte:AVERAGE ';
			$appl_sum .= ' CDEF:appl_up_sum=appl_out,8,* ';
			$appl_sum .= ' CDEF:appl_down_sum=appl_in,8,* ';
			$graph_title = $l7name;
		}
		else {
			$rrd .= ' DEF:sum_in_tcp=/opt/rrd/summary/summary.rrd:inTcpByte:AVERAGE ';
			$rrd .= ' DEF:sum_in_udp=/opt/rrd/summary/summary.rrd:inUdpByte:AVERAGE ';
			$rrd .= ' DEF:sum_out_tcp=/opt/rrd/summary/summary.rrd:outTcpByte:AVERAGE ';
			$rrd .= ' DEF:sum_out_udp=/opt/rrd/summary/summary.rrd:outUdpByte:AVERAGE ';

			$appl_sum .= ' CDEF:appl_up_sum=sum_out_tcp,sum_out_udp,+,8,* ';
			$appl_sum .= ' CDEF:appl_down_sum=sum_in_tcp,sum_in_udp,+,8,* ';
			$graph_title = '所有应用';
		}

		$cmd_str = '/usr/local/WholetonTM/rrdtool/bin/rrdtool graph - '.
			'--imgformat=PNG '.
			'--start=-1800 '.
			'--end=-0 '.
			'--title="['.$graph_title.']30分钟流量趋势图" '.
			'--rigid '.
			'--border=1 '.
			'--base=1000 '.
			'--height='.$height.' '.
			'--width='.$width.' '.
			'--alt-autoscale-max '.
			'--full-size-mode '.
			'--lower-limit=0 '.
			'--vertical-label="上下行速率 (单位:bps)" '.
			'--slope-mode '.
			'--font TITLE:10:"DejaVu Sans Mono" '.
			'--font AXIS:8:"DejaVu Sans Mono" '.
			'--font LEGEND:8:"DejaVu Sans Mono" '.
			'--font UNIT:8:"DejaVu Sans Mono" '.$rrd.$appl_sum.
		//	'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			'COMMENT:"方向" '.
			'COMMENT:"当前" '.
			'COMMENT:"平均" '.
			'COMMENT:"最大\j" '.
		//	'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			
			'AREA:appl_down_sum#00CF00FF:" 下行流量\t" '.
			'GPRINT:appl_down_sum:LAST:"%6.2lf %sbps\t" '.
			'GPRINT:appl_down_sum:AVERAGE:"%6.2lf %sbps\t" '.
			'GPRINT:appl_down_sum:MAX:"%6.2lf %sbps\j" '.
			'LINE1:appl_up_sum#002A97FF:" 上行流量\t" '.
			'GPRINT:appl_up_sum:LAST:"%6.2lf %sbps\t" '.
			'GPRINT:appl_up_sum:AVERAGE:"%6.2lf %sbps\t" '.
			'GPRINT:appl_up_sum:MAX:"%6.2lf %sbps\j" '.
		//	'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			'COMMENT:"更新时间\: $(date \'+%Y-%m-%d %H\:%M\:%S\')\r" '
		;

		$cmd_str = \Util::urlLoophole($cmd_str);
		\Util::log_debug($cmd_str);

		return shell_exec($cmd_str);
	}

	static public function summaryUser($param) {
		$lan_ip = $param['lan_ip'];
		$user_name = $param['user_name'];
		$height = $param['height'];
		$width  = $param['width'];

		$rrd = '';
		$user_sum = '';
		$graph_title = '';
		$color= array('#FF66CC','#FF6600','#660000','#CC00FF','#6600FF','#0000CC','#00CC00','#660066','#6699FF','#00FFCC');

		if($lan_ip!=""){
			$rrd  .= ' DEF:user_in=/opt/rrd/user/'.$lan_ip.'.rrd:inByte:AVERAGE ';
			$rrd  .= ' DEF:user_out=/opt/rrd/user/'.$lan_ip.'.rrd:outByte:AVERAGE ';
			$user_sum .= ' CDEF:user_up_sum=user_out,8,* ';
			$user_sum .= ' CDEF:user_down_sum=user_in,8,* ';
			$graph_title = $user_name;
		}
		else {
			$rrd .= ' DEF:sum_in_tcp=/opt/rrd/summary/summary.rrd:inTcpByte:AVERAGE ';
			$rrd .= ' DEF:sum_in_udp=/opt/rrd/summary/summary.rrd:inUdpByte:AVERAGE ';
			$rrd .= ' DEF:sum_out_tcp=/opt/rrd/summary/summary.rrd:outTcpByte:AVERAGE ';
			$rrd .= ' DEF:sum_out_udp=/opt/rrd/summary/summary.rrd:outUdpByte:AVERAGE ';

			$user_sum .= ' CDEF:user_up_sum=sum_out_tcp,sum_out_udp,+,8,* ';
			$user_sum .= ' CDEF:user_down_sum=sum_in_tcp,sum_in_udp,+,8,* ';
			$graph_title = '所有用户';
		}

		$cmd_str = '/usr/local/WholetonTM/rrdtool/bin/rrdtool graph - '.
			'--imgformat=PNG '.
			'--start=-1800 '.
			'--end=-0 '.
			'--title="['.$graph_title.']30分钟流量趋势图" '.
			'--rigid '.
			'--border=1 '.
			'--base=1000 '.
			'--height='.$height.' '.
			'--width='.$width.' '.
			'--alt-autoscale-max '.
			'--full-size-mode '.
			'--lower-limit=0 '.
			'--vertical-label="上下行速率 (单位:bps)" '.
			'--slope-mode '.
			'--font TITLE:10:"DejaVu Sans Mono" '.
			'--font AXIS:8:"DejaVu Sans Mono" '.
			'--font LEGEND:8:"DejaVu Sans Mono" '.
			'--font UNIT:8:"DejaVu Sans Mono" '.$rrd.$user_sum.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			'COMMENT:"方向" '.
		    'COMMENT:"当前" '.
		    'COMMENT:"平均" '.
		    'COMMENT:"最大\j" '.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			
			'AREA:user_down_sum#00CF00FF:" 下行流量\t" '.
			'GPRINT:user_down_sum:LAST:"%6.2lf %sbps\t" '.
			'GPRINT:user_down_sum:AVERAGE:"%6.2lf %sbps\t" '.
			'GPRINT:user_down_sum:MAX:"%6.2lf %sbps\j" '.
			'LINE1:user_up_sum#002A97FF:" 上行流量\t" '.
			'GPRINT:user_up_sum:LAST:"%6.2lf %sbps\t" '.
			'GPRINT:user_up_sum:AVERAGE:"%6.2lf %sbps\t" '.
			'GPRINT:user_up_sum:MAX:"%6.2lf %sbps\j" '.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			'COMMENT:"更新时间\: $(date \'+%Y-%m-%d %H\:%M\:%S\')\r" '
		;

		$cmd_str = \Util::urlLoophole($cmd_str);
		\Util::log_debug($cmd_str);

		return shell_exec($cmd_str);
	}

	static public function analysisAll($param) {
		$height = $param['height'];
		$width  = $param['width'];

		$band_file = "/etc/bandwidth";
		$band = 1;
		$y = '';
		if(file_exists($band_file)){
			$band_value = parse_ini_file($band_file);
			foreach($band_value as $k=>$v){
				if($k=="maxbandwidth"){
					$band = $v;
				}
			}
		}
		$img_type = $param['img_type'];
		$start_time = strtotime($param['start_time'])==""?600: (time()- strtotime($param['start_time']));
		$end_time = strtotime($param['end_time'])=="" || strtotime($param['end_time'])<0?0:(time()- strtotime($param['end_time']));

		if(\TrafficAnalysisController::IMAGE_TYPE_UPDOWN_RATE == $img_type){
			$in_tcp = 'inTcpByte';
			$out_tcp = 'outTcpByte';
			$in_udp = 'inUdpByte';
			$out_udp = 'outUdpByte';
			$in_name = ' 下行速率';
			$out_name = ' 上行速率';
			$ps = "bps";
			$title_name = "上下行速率";
			$y_name = "速率";
		}
		else if(\TrafficAnalysisController::IMAGE_TYPE_UPDOWN_PKT_RATE == $img_type){
			$in_tcp = 'inTcpPkt';
			$out_tcp = 'outTcpPkt';
			$in_udp = 'inUdpPkt';
			$out_udp = 'outUdpPkt';
			$in_name = ' 下行包速率';
			$out_name = ' 上行包速率';
			$ps = "pps";
			$title_name = "上下行包速率";
			$y_name = "速率";
		}
		else if(\TrafficAnalysisController::IMAGE_TYPE_CREATE_DESTROY_RATE == $img_type){
			$in_tcp = 'createTcpFlow';
			$out_tcp = 'deleteTcpFlow';
			$in_udp = 'createUdpFlow';
			$out_udp = 'deleteUdpFlow';
			$in_name = ' 拆除连接速率';
			$out_name = ' 创建连接速率';
			$ps = "fps";
			$title_name = "连接创建/拆除速率";
			$y_name = "速率";
		}
		else if(\TrafficAnalysisController::IMAGE_TYPE_UTILIZATION_RATE == $img_type){
			$in_tcp = 'inTcpByte';
			$out_tcp = 'outTcpByte';
			$in_udp = 'inUdpByte';
			$out_udp = 'outUdpByte';
			$in_name = ' 带宽利用率';
			$ps = "%";
			$title_name = "带宽利用率";
			$y_name = "带宽利用率";
			$y = ' --units-exponent 0 ';
		}
		$rrd = '';
		$line = '';
		$sum_cdef = '';
		$rrd .= ' DEF:sum_in_tcp=/opt/rrd/summary/summary.rrd:'.$in_tcp.':AVERAGE ';
		$rrd .= ' DEF:sum_in_udp=/opt/rrd/summary/summary.rrd:'.$in_udp.':AVERAGE ';

		$rrd .= ' DEF:sum_out_tcp=/opt/rrd/summary/summary.rrd:'.$out_tcp.':AVERAGE ';
		$rrd .= ' DEF:sum_out_udp=/opt/rrd/summary/summary.rrd:'.$out_udp.':AVERAGE ';

		if(\TrafficAnalysisController::IMAGE_TYPE_UTILIZATION_RATE != $img_type){
			if(\TrafficAnalysisController::IMAGE_TYPE_UPDOWN_RATE == $img_type){
				$sum_cdef .= ' CDEF:sum_in=sum_in_tcp,sum_in_udp,+,8,* ';
				$sum_cdef .= ' CDEF:sum_out=sum_out_tcp,sum_out_udp,+,8,* ';
			}else{
				$sum_cdef .= ' CDEF:sum_in=sum_in_tcp,sum_in_udp,+ ';
				$sum_cdef .= ' CDEF:sum_out=sum_out_tcp,sum_out_udp,+ ';
			}
			$line = 'AREA:sum_in#00CF00FF:"'.$in_name.'\t" '.
					'GPRINT:sum_in:LAST:"%6.2lf %s'.$ps.'\t" '.
					'GPRINT:sum_in:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
					'GPRINT:sum_in:MAX:"%6.2lf %s'.$ps.'\j" '.
					'LINE1:sum_out#002A97FF:"'.$out_name.'\t" '.
					'GPRINT:sum_out:LAST:"%6.2lf %s'.$ps.'\t" '.
					'GPRINT:sum_out:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
					'GPRINT:sum_out:MAX:"%6.2lf %s'.$ps.'\j" ';
		}else{
			$sum_cdef .= ' CDEF:sum_flow=sum_in_tcp,sum_in_udp,sum_out_tcp,sum_out_udp,+,+,+,'.$band.',/,100,* ';
			$line = 'AREA:sum_flow#00CF00FF:"'.$in_name.'\t" '.
					'GPRINT:sum_flow:LAST:"%6.2lf %'.$ps.'\t" '.
					'GPRINT:sum_flow:AVERAGE:"%6.2lf %'.$ps.'\t" '.
					'GPRINT:sum_flow:MAX:"%6.2lf %'.$ps.'\j" ';
		}

		$cmd_str = '/usr/local/WholetonTM/rrdtool/bin/rrdtool graph - '.
			'--imgformat=PNG '.
			'--start=-'.$start_time.' '.
			'--end=-'.$end_time.' '.
			'--title="'.$title_name.'" '.
			'--rigid '.
			'--border=1 '.
			'--base=1000 '.
			'--height='.$height.' '.
			'--width='.$width.' '.
			'--full-size-mode '.
			'--alt-autoscale-max '.$y.
			'--lower-limit=0 '.
			'--vertical-label="'.$y_name.' (单位: '.$ps.')" '.
			'--slope-mode '.
			'--font TITLE:10:"DejaVu Sans Mono" '.
			'--font AXIS:8:"DejaVu Sans Mono" '.
			'--font LEGEND:8:"DejaVu Sans Mono" '.
			'--font UNIT:8:"DejaVu Sans Mono" '.$rrd.$sum_cdef.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			'COMMENT:"方向" '.
			'COMMENT:"当前" '.
			'COMMENT:"平均" '.
		    'COMMENT:"最大\j" '.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.	
			$line.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			'COMMENT:"更新时间\: $(date \'+%Y-%m-%d %H\:%M\:%S\')\r" '
		;

		$cmd_str = \Util::urlLoophole($cmd_str);
		\Util::log_debug($cmd_str);

		return shell_exec($cmd_str);
	}

	static public function analysisApplication($param) {
		$height = $param['height'];
		$width  = $param['width'];
		$l7name = $param['l7name'];

		$y = '';
		$in = '';
		$out = '';
		$rrd = '';
		$line = '';
		$appl_sum = '';
		$y_name = '';
		$graph_title = '';
		$band_file = "/etc/bandwidth";
		$band = 1;
		if(file_exists($band_file)){
			$band_value = parse_ini_file($band_file);
			foreach($band_value as $k=>$v){
				if($k=="maxbandwidth"){
					$band = $v;
				}
			}
		}
		
		$l7prot = $param['l7prot'];
		$img_type = $param['img_type'];
		$start_time = strtotime($param['start_time'])==""?600: (time()- strtotime($param['start_time']));
		$end_time = strtotime($param['end_time'])==""?0:(time()- strtotime($param['end_time']));

		if(\TrafficAnalysisController::IMAGE_TYPE_UPDOWN_RATE == $img_type){
			$in = 'inByte';
			$out = 'outByte';
			$in_tcp = 'inTcpByte';
			$out_tcp = 'outTcpByte';
			$in_udp = 'inUdpByte';
			$out_udp = 'outUdpByte';
			$in_name = ' 下行速率';
			$out_name = ' 上行速率';
			$ps = "bps";
			$title_name = "上下行速率";
			$y_name = "速率";
		}
		else if(\TrafficAnalysisController::IMAGE_TYPE_UPDOWN_PKT_RATE == $img_type){
			$in = 'inPkt';
			$out = 'outPkt';
			$in_tcp = 'inTcpPkt';
			$out_tcp = 'outTcpPkt';
			$in_udp = 'inUdpPkt';
			$out_udp = 'outUdpPkt';
			$in_name = ' 下行包速率';
			$out_name = ' 上行包速率';
			$ps = "pps";
			$title_name = "上下行包速率";
			$y_name = "速率";
		}
		else if(\TrafficAnalysisController::IMAGE_TYPE_CREATE_DESTROY_RATE == $img_type){
			$flow = 'flow';
			$in_tcp = 'createTcpFlow';
			$out_tcp = 'deleteTcpFlow';
			$in_udp = 'createUdpFlow';
			$out_udp = 'deleteUdpFlow';
			$in_name = ' 拆除连接速率';
			$out_name = ' 创建连接速率';
			$flow_name ='连接数';
			$ps = "fps";
			$title_name = "连接创建/拆除速率";
			$y_name = "速率";
			$y = ' --units-exponent 0 ';

		}
		else if(\TrafficAnalysisController::IMAGE_TYPE_UTILIZATION_RATE == $img_type){
			$in = 'inByte';
			$out = 'outByte';
			$in_tcp = 'inTcpByte';
			$out_tcp = 'outTcpByte';
			$in_udp = 'inUdpByte';
			$out_udp = 'outUdpByte';
			$in_name = ' 带宽利用率';
			$ps = "%";
			$title_name = "带宽利用率";
			$y_name = "带宽利用率";
			$y = ' --units-exponent 0 ';
		}
		if(\TrafficAnalysisController::IMAGE_TYPE_UTILIZATION_RATE != $img_type){
			if($l7prot!=Protocol::L7PROTOCOL_ALL){
				if(\TrafficAnalysisController::IMAGE_TYPE_CREATE_DESTROY_RATE != $img_type){
					$rrd  .= ' DEF:appl_in=/opt/rrd/protocol/'.$l7prot.'.rrd:'.$in.':AVERAGE ';
					$rrd  .= ' DEF:appl_out=/opt/rrd/protocol/'.$l7prot.'.rrd:'.$out.':AVERAGE ';
					$appl_sum .= ' CDEF:appl_up_sum=appl_out,8,* ';
					$appl_sum .= ' CDEF:appl_down_sum=appl_in,8,* ';
					$graph_title = $l7name;
					$line = 'AREA:appl_down_sum#00CF00FF:" '.$in_name.'\t" '.
							'GPRINT:appl_down_sum:LAST:"%6.2lf %s'.$ps.'\t" '.
							'GPRINT:appl_down_sum:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
							'GPRINT:appl_down_sum:MAX:"%6.2lf %s'.$ps.'\j" '.
							'LINE1:appl_up_sum#002A97FF:" '.$out_name.'\t" '.
							'GPRINT:appl_up_sum:LAST:"%6.2lf %s'.$ps.'\t" '.
							'GPRINT:appl_up_sum:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
							'GPRINT:appl_up_sum:MAX:"%6.2lf %s'.$ps.'\j" ';

				}else{
					$rrd  .= ' DEF:appl_flow=/opt/rrd/protocol/'.$l7prot.'.rrd:'.$flow.':AVERAGE ';
					$graph_title = $l7name;
					$line = 'AREA:appl_flow#00CF00FF:" '.$flow_name.'\t" '.
						'GPRINT:appl_flow:LAST:"%6.2lf %s'.$ps.'\t" '.
						'GPRINT:appl_flow:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
						'GPRINT:appl_flow:MAX:"%6.2lf %s'.$ps.'\j" ';
				}
			}
			else {
				$rrd .= ' DEF:sum_in_tcp=/opt/rrd/summary/summary.rrd:'.$in_tcp.':AVERAGE ';
				$rrd .= ' DEF:sum_in_udp=/opt/rrd/summary/summary.rrd:'.$in_udp.':AVERAGE ';
				$rrd .= ' DEF:sum_out_tcp=/opt/rrd/summary/summary.rrd:'.$out_tcp.':AVERAGE ';
				$rrd .= ' DEF:sum_out_udp=/opt/rrd/summary/summary.rrd:'.$out_udp.':AVERAGE ';

				$appl_sum .= ' CDEF:appl_up_sum=sum_out_tcp,sum_out_udp,+,8,* ';
				$appl_sum .= ' CDEF:appl_down_sum=sum_in_tcp,sum_in_udp,+,8,* ';
				$graph_title = '所有应用';
				$line = 'AREA:appl_down_sum#00CF00FF:" '.$in_name.'\t" '.
						'GPRINT:appl_down_sum:LAST:"%6.2lf %s'.$ps.'\t" '.
						'GPRINT:appl_down_sum:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
						'GPRINT:appl_down_sum:MAX:"%6.2lf %s'.$ps.'\j" '.
						'LINE1:appl_up_sum#002A97FF:" '.$out_name.'\t" '.
						'GPRINT:appl_up_sum:LAST:"%6.2lf %s'.$ps.'\t" '.
						'GPRINT:appl_up_sum:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
						'GPRINT:appl_up_sum:MAX:"%6.2lf %s'.$ps.'\j" ';
			}
		}else {
			if($l7prot!=Protocol::L7PROTOCOL_ALL){
				$rrd  .= ' DEF:appl_in=/opt/rrd/protocol/'.$l7prot.'.rrd:'.$in.':AVERAGE ';
				$rrd  .= ' DEF:appl_out=/opt/rrd/protocol/'.$l7prot.'.rrd:'.$out.':AVERAGE ';
				$appl_sum .= ' CDEF:appl_sum=appl_in,appl_out,+,'.$band.',/,100,* ';
				$graph_title = $l7name;
			}
			else {
				$rrd .= ' DEF:sum_in_tcp=/opt/rrd/summary/summary.rrd:'.$in_tcp.':AVERAGE ';
				$rrd .= ' DEF:sum_in_udp=/opt/rrd/summary/summary.rrd:'.$in_udp.':AVERAGE ';
				$rrd .= ' DEF:sum_out_tcp=/opt/rrd/summary/summary.rrd:'.$out_tcp.':AVERAGE ';
				$rrd .= ' DEF:sum_out_udp=/opt/rrd/summary/summary.rrd:'.$out_udp.':AVERAGE ';

				$appl_sum .= ' CDEF:appl_sum=sum_in_tcp,sum_in_udp,sum_out_tcp,sum_out_udp,+,+,+,'.$band.',/,100,* ';
				$graph_title = '所有应用';
			}
			$line= 	'AREA:appl_sum#00CF00FF:" '.$in_name.'\t" '.
					'GPRINT:appl_sum:LAST:"%6.2lf %'.$ps.'\t" '.
					'GPRINT:appl_sum:AVERAGE:"%6.2lf %'.$ps.'\t" '.
					'GPRINT:appl_sum:MAX:"%6.2lf %'.$ps.'\j" ';
		}

		$cmd_str = '/usr/local/WholetonTM/rrdtool/bin/rrdtool graph - '.
			'--imgformat=PNG '.
			'--start=-'.$start_time.' '.
			'--end=-'.$end_time.' '.
			'--title="['.$graph_title.']'.$title_name.'" '.
			'--rigid '.
			'--border=1 '.
			'--base=1000 '.
			'--height='.$height.' '.
			'--width='.$width.' '.
			'--alt-autoscale-max '.$y.
			'--full-size-mode '.
			'--lower-limit=0 '.
			'--vertical-label="'.$y_name.' (单位:'.$ps.')" '.
			'--slope-mode '.
			'--font TITLE:10:"DejaVu Sans Mono" '.
			'--font AXIS:8:"DejaVu Sans Mono" '.
			'--font LEGEND:8:"DejaVu Sans Mono" '.
			'--font UNIT:8:"DejaVu Sans Mono" '.$rrd.$appl_sum.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			'COMMENT:"方向" '.
			'COMMENT:"当前" '.
			'COMMENT:"平均" '.
		    'COMMENT:"最大\j" '.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			$line.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			'COMMENT:"更新时间\: $(date \'+%Y-%m-%d %H\:%M\:%S\')\r" '
		;

		$cmd_str = \Util::urlLoophole($cmd_str);
		\Util::log_debug($cmd_str);

		return shell_exec($cmd_str);
	}

	static public function analysisUser($param) {
		$height = $param['height'];
		$width  = $param['width'];

		$rrd = '';
		$line= '';
		$in = '';
		$out = '';
		$y = '';
		$y_name='';
		$graph_title = '';
		$user_sum = '';
		$band_file = "/etc/bandwidth";
		$band = 1;
		if(file_exists($band_file)){
			$band_value = parse_ini_file($band_file);
			foreach($band_value as $k=>$v){
				if($k=="maxbandwidth"){
					$band = $v;
				}
			}
		}
		$lanip = $param['lan_ip'];
		$img_type = $param['img_type'];
		$start_time = strtotime($param['start_time'])==""?600: (time()- strtotime($param['start_time']));
		$end_time = strtotime($param['end_time'])=="" || strtotime($param['end_time'])<0?0:(time()- strtotime($param['end_time']));
		if(\TrafficAnalysisController::IMAGE_TYPE_UPDOWN_RATE == $img_type){
			$in = 'inByte';
			$out = 'outByte';
			$in_tcp = 'inTcpByte';
			$out_tcp = 'outTcpByte';
			$in_udp = 'inUdpByte';
			$out_udp = 'outUdpByte';
			$in_name = ' 下行速率';
			$out_name = ' 上行速率';
			$ps = "bps";
			$title_name = "上下行速率";
			$y_name = "速率";
		}
		else if(\TrafficAnalysisController::IMAGE_TYPE_UPDOWN_PKT_RATE == $img_type){
			$in = 'inPkt';
			$out = 'outPkt';
			$in_tcp = 'inTcpPkt';
			$out_tcp = 'outTcpPkt';
			$in_udp = 'inUdpPkt';
			$out_udp = 'outUdpPkt';
			$in_name = ' 下行包速率';
			$out_name = ' 上行包速率';
			$ps = "pps";
			$title_name = "上下行包速率";
			$y_name = "速率";
		}
		else if(\TrafficAnalysisController::IMAGE_TYPE_CREATE_DESTROY_RATE == $img_type){
			$flow = 'flow';
			$flow_name ='连接数';
			$in_tcp = 'createTcpFlow';
			$out_tcp = 'deleteTcpFlow';
			$in_udp = 'createUdpFlow';
			$out_udp = 'deleteUdpFlow';
			$in_name = ' 拆除连接速率';
			$out_name = ' 创建连接速率';
			$ps = "fps";
			$title_name = "连接创建/拆除速率";
			$y_name = "速率";
			$y = ' --units-exponent 0 ';
		}
		else if(\TrafficAnalysisController::IMAGE_TYPE_UTILIZATION_RATE == $img_type){
			$in = 'inByte';
			$out = 'outByte';
			$in_tcp = 'inTcpByte';
			$out_tcp = 'outTcpByte';
			$in_udp = 'inUdpByte';
			$out_udp = 'outUdpByte';
			$in_name = ' 带宽利用率';
			$ps = "%";
			$title_name = "带宽利用率";
			$y_name = "带宽利用率";
			$y = ' --units-exponent 0 ';
		}
		if(\TrafficAnalysisController::IMAGE_TYPE_UTILIZATION_RATE != $img_type){
			if($lanip!=""){
				if(\TrafficAnalysisController::IMAGE_TYPE_CREATE_DESTROY_RATE != $img_type){
					$rrd  .= ' DEF:user_in=/opt/rrd/user/'.$lanip.'.rrd:'.$in.':AVERAGE ';
					$rrd  .= ' DEF:user_out=/opt/rrd/user/'.$lanip.'.rrd:'.$out.':AVERAGE ';
					$user_sum .= ' CDEF:user_up_sum=user_out,8,* ';
					$user_sum .= ' CDEF:user_down_sum=user_in,8,* ';
					$graph_title = $lanip ;
					$line = 'AREA:user_down_sum#00CF00FF:" '.$in_name.'\t" '.
							'GPRINT:user_down_sum:LAST:"%6.2lf %s'.$ps.'\t" '.
							'GPRINT:user_down_sum:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
							'GPRINT:user_down_sum:MAX:"%6.2lf %s'.$ps.'\j" '.
							'LINE1:user_up_sum#002A97FF:" '.$out_name.'\t" '.
							'GPRINT:user_up_sum:LAST:"%6.2lf %s'.$ps.'\t" '.
							'GPRINT:user_up_sum:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
							'GPRINT:user_up_sum:MAX:"%6.2lf %s'.$ps.'\j" ';
				}else{
					$rrd  .= ' DEF:user_flow=/opt/rrd/user/'.$lanip.'.rrd:'.$flow.':AVERAGE ';
					$graph_title = $lanip ;
					$line = 'AREA:user_flow#00CF00FF:" '.$flow_name.'\t" '.
							'GPRINT:user_flow:LAST:"%6.2lf %s'.$ps.'\t" '.
							'GPRINT:user_flow:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
							'GPRINT:user_flow:MAX:"%6.2lf %s'.$ps.'\j" ';
				}
			}
			else {
				$rrd .= ' DEF:sum_in_tcp=/opt/rrd/summary/summary.rrd:'.$in_tcp.':AVERAGE ';
				$rrd .= ' DEF:sum_in_udp=/opt/rrd/summary/summary.rrd:'.$in_udp.':AVERAGE ';
				$rrd .= ' DEF:sum_out_tcp=/opt/rrd/summary/summary.rrd:'.$out_tcp.':AVERAGE ';
				$rrd .= ' DEF:sum_out_udp=/opt/rrd/summary/summary.rrd:'.$out_udp.':AVERAGE ';
				$user_sum .= ' CDEF:user_up_sum=sum_out_tcp,sum_out_udp,+,8,* ';
				$user_sum .= ' CDEF:user_down_sum=sum_in_tcp,sum_in_udp,+,8,* ';
				$graph_title = '所有用户';
				$line = 'AREA:user_down_sum#00CF00FF:" '.$in_name.'\t" '.
						'GPRINT:user_down_sum:LAST:"%6.2lf %s'.$ps.'\t" '.
						'GPRINT:user_down_sum:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
						'GPRINT:user_down_sum:MAX:"%6.2lf %s'.$ps.'\j" '.
						'LINE1:user_up_sum#002A97FF:" '.$out_name.'\t" '.
						'GPRINT:user_up_sum:LAST:"%6.2lf %s'.$ps.'\t" '.
						'GPRINT:user_up_sum:AVERAGE:"%6.2lf %s'.$ps.'\t" '.
						'GPRINT:user_up_sum:MAX:"%6.2lf %s'.$ps.'\j" ';
			}
		}else{
			if($lanip!=""){
				$rrd  .= ' DEF:user_in=/opt/rrd/user/'.$lanip.'.rrd:'.$in.':AVERAGE ';
				$rrd  .= ' DEF:user_out=/opt/rrd/user/'.$lanip.'.rrd:'.$out.':AVERAGE ';
				$user_sum .= ' CDEF:user_sum=user_out,user_in,+,'.$band.',/,100,* ';
				$graph_title = $lanip ;
			}
			else {
				$rrd .= ' DEF:sum_in_tcp=/opt/rrd/summary/summary.rrd:'.$in_tcp.':AVERAGE ';
				$rrd .= ' DEF:sum_in_udp=/opt/rrd/summary/summary.rrd:'.$in_udp.':AVERAGE ';
				$rrd .= ' DEF:sum_out_tcp=/opt/rrd/summary/summary.rrd:'.$out_tcp.':AVERAGE ';
				$rrd .= ' DEF:sum_out_udp=/opt/rrd/summary/summary.rrd:'.$out_udp.':AVERAGE ';

				$user_sum .= ' CDEF:user_sum=sum_in_tcp,sum_in_udp,sum_out_tcp,sum_out_udp,+,+,+,'.$band.',/,100,* ';
				$graph_title = '所有用户';
			}	
			$line = 'AREA:user_sum#00CF00FF:" '.$in_name.'\t" '.
					'GPRINT:user_sum:LAST:"%6.2lf %'.$ps.'\t" '.
					'GPRINT:user_sum:AVERAGE:"%6.2lf %'.$ps.'\t" '.
					'GPRINT:user_sum:MAX:"%6.2lf %'.$ps.'\j" ';

		}

		$cmd_str = '/usr/local/WholetonTM/rrdtool/bin/rrdtool graph - '.
			'--imgformat=PNG '.
			'--start=-'.$start_time.' '.
			'--end=-'.$end_time.' '.
			'--title="['.$graph_title.']'.$title_name.'" '.
			'--rigid '.
			'--border=1 '.
			'--base=1000 '.
			'--height='.$height.' '.
			'--width='.$width.' '.
			'--alt-autoscale-max '.$y.
			'--full-size-mode '.
			'--lower-limit=0 '.
			'--vertical-label="'.$y_name.' (单位:'.$ps.')" '.
			'--slope-mode '.
			'--font TITLE:10:"DejaVu Sans Mono" '.
			'--font AXIS:8:"DejaVu Sans Mono" '.
			'--font LEGEND:8:"DejaVu Sans Mono" '.
			'--font UNIT:8:"DejaVu Sans Mono" '.$rrd.$user_sum.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			'COMMENT:"方向" '.
			'COMMENT:"当前" '.
			'COMMENT:"平均" '.
			'COMMENT:"最大\j" '.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			$line.
			//'COMMENT:"-----------------------------------------------------------------------------------------\r" '.
			'COMMENT:"更新时间\: $(date \'+%Y-%m-%d %H\:%M\:%S\')\r" '
		;
		
		$cmd_str = \Util::urlLoophole($cmd_str);
		\Util::log_debug($cmd_str);

		return shell_exec($cmd_str);
	}
}

