<script type="text/javascript">

/***********************************************
* Local Time script Dynamic Drive
* This notice MUST stay intact for legal use
***********************************************/

var weekdaystxt=["周日", "周一", "周二", "周三", "周四", "周五", "周六"]

function showLocalTime(container, servermode, offsetMinutes){
	if (!document.getElementById || !document.getElementById(container)) return
	this.container = document.getElementById(container)
	var servertimestring = (servermode == "server-php")
		? '<?php print date("F d, Y H:i:s", time())?>' 
		: (servermode=="server-ssi")? '<!--#config timefmt="%B %d, %Y %H:%M:%S"--><!--#echo var="DATE_LOCAL" -->' : '<%= Now() %>'
	
	this.localtime=this.serverdate=new Date(servertimestring)
	this.localtime.setTime(this.serverdate.getTime()+offsetMinutes*60*1000) //add user offset to server time
	
	this.updateTime()
	this.updateContainer()
}

showLocalTime.prototype.updateTime = function(){
	var thisobj = this
	this.localtime.setSeconds(this.localtime.getSeconds()+1)
	setTimeout(function(){thisobj.updateTime()}, 1000) //update time every second
}

showLocalTime.prototype.updateContainer = function() {
	var thisobj = this
	var hour = this.localtime.getHours()
	var minutes = this.localtime.getMinutes()
	var seconds = this.localtime.getSeconds()
	var dayofweek = weekdaystxt[this.localtime.getDay()]
	
	this.container.innerHTML = dayofweek + ' ' + formatField(hour)+":"+formatField(minutes)+":"+formatField(seconds);
	setTimeout(function(){thisobj.updateContainer()}, 1000) //update container every second
}

function formatField(num, isHour) {
	if (typeof isHour != "undefined") { //if this is the hour field
		var hour = (num > 12) ? num-12 : num
		return (hour == 0)? 12 : hour
	}
	return (num<=9)? "0"+num : num //if this is minute or sec field
}
</script>

<div class="frame-south" data-options="region:'south',border:false,collapsible:false" style="height:24px;">
	<table>
		<tr>
			<td>
				当前用户：{{ Auth::user()->username }} | 当前时间：<span id="timecontainer"></span> 
				<script type="text/javascript">
					new showLocalTime("timecontainer", "server-php", 0, "xx")
				</script>
			</td>
			<td class="pull-right">
				{{$company}}公司版权所有 <span style="font-family:arial;">&copy;</span> {{date('Y')}}
			</td>
		</tr>
	</table>
</div>


