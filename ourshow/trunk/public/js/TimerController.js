

function TimerController(args) {
	this._html_id = args.html_id;
	this._chart_url = args.chart_url;
	this._url_param = args.url_param;

	this._refresh_btn = args.refresh_btn;
	this._search_word = args.search_word;
	this._activetable = args.activetable;
	this._activechart = args.activechart;

	this.refreshConfig(parseInt(args.interval));

	console.debug("in TimerController.js html_id=" + this._html_id);
}

TimerController.prototype.activeDataReload = function () {
	console.debug(new Date().toLocaleString() + ' ontimer html_id=' + this._html_id);
	this.reloadActiveTable();
	this.reloadActiveChart();
}

TimerController.prototype.reloadActiveTable = function () {
	if (undefined === this._activetable) {
		return;
	}

	var search_word = this._search_word;

	this._activetable.datagrid('reload', {
		keyword: search_word.searchbox('getValue')
	});
	this._activetable.datagrid('loaded');
}

TimerController.prototype.reloadActiveChart = function (width, height) {
	if (undefined === this._activechart) {
		return;
	}

	var imgWidth = 100;
	var imgHeight = 100;
	// 这里将 _width 模拟为静态变量使用
	// 如果 未传入 width height 参数，则使用上次的值
	imgWidth = ((undefined === width) ?  this._width : width);
	imgHeight = ((undefined === height) ?  this._height : height);
	this._width = imgWidth;
	this._height = imgHeight;

	if (undefined == this._url_param) {
		this._url_param = {};
	}
	this._url_param.width = imgWidth;
	this._url_param.height = imgHeight;
	this._url_param.rand = Math.random();

	var img_src = this._chart_url + '?' + $.param( this._url_param );
	this._activechart.attr("src", img_src);
}

TimerController.prototype.refreshConfig = function (new_interval) {
	this.refreshInterval(new_interval);
	this.refreshBtnText(new_interval);
}

TimerController.prototype.refreshInterval = function (new_interval) {
	if (new_interval) {
		if (undefined !== this._timer) {
			this._timer.stop();
		}

		var this_ptr = this;
		this._timer = $.timer(
			this.activeDataReload,
			new_interval * 1000,
			true,
			this_ptr
		);
	}
	else {
		this._timer.stop();
	}
}

TimerController.prototype.refreshBtnText = function (new_interval) {
	if (undefined === this._refresh_btn) {
		return;
	}
	if (new_interval) {
		this._refresh_btn.splitbutton({
			text:'刷新间隔: '+new_interval+'秒'
		});
	}
	else {
		this._refresh_btn.splitbutton({
			text:'刷新间隔: 不刷新'
		});
	}
}

TimerController.prototype.stopTimer = function () {
	console.debug("in TimerController.js stop timer html_id=" + this._html_id );
	this._timer.stop();
}

TimerController.prototype.pauseTimer = function () {
	console.debug("in TimerController.js pause timer html_id=" + this._html_id );
	this._timer.pause();
}

TimerController.prototype.playTimer = function () {
	console.debug("in TimerController.js play timer html_id=" + this._html_id );
	this._timer.play();
}


