@extends('tpl.layout')

@section('css')

  <link rel="stylesheet" type="text/css" href="{{ asset('css/cover.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/timeline.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/comment.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bounce.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/picture-wall.css') }}">

@stop


@section('body')

    <div class="site-background"></div>

    <div class="cover-continer" >

          <div class="cover-inner" onclick="shuffleBackground();" >
            <h1 class="cover-heading">见证我们爱情</h1>
            <p class="lead">欢迎xxxx参加新郎新娘的婚礼</p>
            <p class="lead">时间：2015-05-03 11:00</p>
            <p class="lead">地址：北京市长安街北京饭店 <a href="http://todo.com">查看地图</a></p>

          </div>

          <div class="footer-info">
            <a href="#timeline_continer" class="icon-button bounce" title="下拉显示更多"
              onclick="$(this).hide();" >
              <span class="glyphicon glyphicon-triangle-bottom" ></span>
            </a>
          </div>
    </div>

    <div id="timeline_continer" class="container">
        <div class="page-header">
            <h1 id="timeline">时光机</h1>
        </div>
        <ul class="timeline">

            @foreach ($param['ablums'] as $index => $ablum) 
              <li id="ablum_{{ $ablum['id'] }}" class="{{ ($index%2) ? 'timeline-inverted' : '' }}">
                
                <div class=" timeline-badge {{ $ablum['likeit'] ? 'heart-like' : 'heart-unlike' }}" 
                    data-toggle="tooltip" data-placement="top" 
                    title="{{ count($ablum['likes']) }} 人表示很赞"
                    onclick='likeAblum(this, {{ $ablum["id"] }});' >
                  <i class="glyphicon glyphicon-heart" ></i>
                </div>

                <div class="timeline-panel">
                  <div class="timeline-heading">
                    <h4 class="timeline-title">{{ $ablum['title'] }}</h4>
                  </div>

                  <div class="timeline-body" onclick="showPictureWall({{ $ablum['id'] }});">
                    <img src='{{ URL::to("/get-picture")."/".$ablum["picture_id"]["id"] }}' style="width:100%;" 
                      class="{{ rand(0,1) ? 'img-circle' : 'img-rounded' }}" />
                    @if ($ablum['caption'])
                      <p class="caption">{{ $ablum['caption'] }}</p>
                    @endif
                  </div>

                  <div class="container usercomment">
                      <div class="text-center">
                        <div class="input-group">
                            <input type="text" class="form-control input-sm" placeholder="我也说一句..." name='comment'
                              onkeydown="if (event.keyCode == 13) $(this).next('span').click();" />
                            <span class="input-group-btn" onclick="addComment(this, {{ $ablum['id'] }} );">     
                              <a class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-comment"></span>评论</a>
                            </span>
                        </div>

                        <hr/>
                        <ul class="ourshow-commmentlist"
                          data-options="url:'{{ URL::to('/get-comments').'/'.$ablum['id'] }}'" 
                        ></ul>

                      </div>
                  </div>
                </div>
              </li>
            @endforeach

        </ul>
    </div>

    <div id="footer_info" class="mastfoot mastfoot-ext">
        <p>由<a > WS & TT </a>提供强劲技术支持</p>
    </div>


    <div id="picture_player" class="picplayer" style="display:none;">
        <div class="picplayer-content" onclick="showPicplayerControl(this); return false;">
            <div class="picplayer-canvas" >
                <div class="item" onclick="hidePictureWall(event);">
                  <ul >
                    <li class="left_img" ><img /></li>
                    <li class="middle_img" ><img /></li>
                    <li class="right_img" ><img /></li>
                  </ul>
                  <div class="info">
                    <h2 class="name">name</h2>
                    <span class="caption">caption</span>
                  </div>
                </div>
            </div>
            <a class="picplayer-control-last" onclick="showPictureWallLast();">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            
            <a class="picplayer-control-next" onclick="showPictureWallNext();">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>

        </div>

    </div>

@stop


@section('js')

<script type="text/javascript">

var picture_wall = new PictureWall();

$(function(){
  shuffleBackground();

  // 相册评论列表
  $('ul.ourshow-commmentlist').each(function(index, elem){
    reloadComment($(elem));
  });

  // 工具栏提示
  $('[data-toggle="tooltip"]').tooltip();

  // 触屏界面中左右滑动时切换图片
  picture_wall.bindTouchEvent();
});


/**
 * @brief 随机更换背景
 */
function shuffleBackground() {
  console.debug("shuffleBackground()");

  var random_backgrounds = {{ json_encode(array_values($param['random_backgrounds'])) }};
  var max_num = random_backgrounds.length;
  var shuffleNum = function() { 
    return Math.floor(Math.random()*max_num); // 0 - max_num 间的随机数
  };
  var index = shuffleNum();
  $('div.site-background').css(
    "background-image", 
    'url( {{ URL::to("/get-background") }}/' + random_backgrounds[index] + '?width=' + $(window).width() + '&height=' + $(window).height() +' )'
  );
}


/**
 * @brief 实现点赞按钮
 */
function doLikeit(button, likeit) {
  if (likeit) {
    button.removeClass('heart-unlike').addClass('heart-like');
  }
  else {
    button.removeClass('heart-like').addClass('heart-unlike');
  }
}

function likeAblum (button, ablum_id) {
  button = $(button);
  var likeit = button.hasClass('heart-like');
  doLikeit(button, !likeit);
  $.post(
    '{{ URL::to("/switch-like") }}' + '/' + ablum_id + '/' + (likeit ? '0' : '1'),   // URL
    JSON.stringify({}), // data
    function(data) {
      var cur_likeit = likeit;
      if (1 != data.status) {
        // 失败
        cur_likeit = !likeit;
      }
      else {
        cur_likeit = data.data.likeit;
      }
      if (cur_likeit != data.data.likeit) {
        doLikeit(button, cur_likeit);
      }
      if (data.status) {
        button.attr('data-original-title', Object.keys(data.data.likes).length + ' 人表示很赞');
        button.tooltip('show');
      }
    },     // callback
    "json" // data type
  );
  return true;
}


/**
 * @brief 实现评论功能
 */
function addComment (button, ablum_id) {
  var comment = $(button).prev('input').val();
  $(button).prev('input').focus();

  $.post(
    '{{ URL::to("/add-comments") }}' + '/' + ablum_id,   // URL
    JSON.stringify({ comment : comment }), // data
    function(data) {
      if (1 != data.status) {
        // 失败
        $().alert('close'); // test TODO
        return;
      }
      $(button).prev('input').val('');
      var commmentlist = $(button).closest('div.text-center').children('ul.ourshow-commmentlist');
      reloadComment($(commmentlist));
    },     // callback
    "json" // data type
  );
  return true;
}

function pageComment(page_button) {
  page_button = $(page_button);
  var commmentlist = page_button.closest('div.text-center').children('ul.ourshow-commmentlist');
  var data_options = eval('({' + commmentlist.attr('data-options') + '})');
  var cur_data_options = eval('({' + page_button.attr('data-options') + '})');
  data_options.pageNumber = cur_data_options.pageNumber;
  data_options.pageSize   = cur_data_options.pageSize;

  var data_options_string = JSON.stringify(data_options);
  data_options_string = $.ltrim(data_options_string, '{');
  data_options_string = $.rtrim(data_options_string, '}');

  // console.debug('param data_options=' +  $.param(data_options));
  console.debug('json  data_options=' +  data_options_string);
  commmentlist.attr('data-options', data_options_string);
  reloadComment(commmentlist);
}

function reloadComment(commmentlist) {
  // commmentlist.html('');
  var data_options = eval('({' + commmentlist.attr('data-options') + '})');
  var request_url = data_options.url;
  if (data_options.pageNumber && data_options.pageSize) {
    request_url += '?' + $.param({ page:data_options.pageNumber, rows:data_options.pageSize});
  }
  $.get(
    request_url,   // URL
    function(data) {
      if (1 != data.status) {
        // fail
        return;
      }
      // success

      // 加载评论
      commmentlist.html('');
      for (var index in data.data.rows) {
        var comment = data.data.rows[index];
        commmentlist.addClass('list-unstyled').addClass('text-left');
        commmentlist.append($('<strong>').addClass('pull-left').html(comment['user_id']['username']));
        commmentlist.append(
          $('<small/>').addClass('pull-right').addClass('text-muted')
            .append($('<span/>').addClass('glyphicon').addClass('glyphicon-time'))
            .append(comment['updated_at']+' 前')
        );
        commmentlist.append('<br/>');
        commmentlist.append($('<li/>').html(comment['text']));
        commmentlist.append('<br/>');
        // console.debug(commmentlist.html());
      }

      // 加载分页按钮
      var page_number = data_options.pageNumber || 1;
      var page_size = data_options.pageSize || 6;
      var page_sum = Math.floor((page_size+data.data.count-1)  / page_size); // 计算页面总数。

      // console.debug('page_number=' + page_number);
      // console.debug('page_size=' + page_size);
      // console.debug('page_sum=' + page_sum);
      // console.debug('data.count=' + data.data.count);
      // console.debug(' ');

      var pagination = $('');
      var class_name = '';
      if (page_sum > 1) {
        pagination = $('<ul/>').addClass('pagination');

        class_name = ((page_number == 1) ? 'disabled' : '');
        // pagination.append($('<li/>').addClass(class_name).html('<a onclick="pageComment(this);" data-options="pageSize:' + page_size + ' ,pageNumber:' + 1 + '" aria-label="Previous"> <span aria-hidden="true">&laquo;</span> </a>'));
        for (var i = 1; i <= page_sum; i++) {
          class_name = ((page_number == i) ? 'active' : '');
          // 为了让每个翻页按钮大小相同，在这里给数值小的文本添加空格进行点位
          var text = i;
          var start = String(i).length;
          var end = String(page_sum).length;
          console.debug('i.length=' + start + ', page_sum.length=' + end);
          for (var space_count = start; space_count <= end; space_count++) {
            text = '&nbsp;' + text + '&nbsp;';
          };

          pagination.append($('<li/>').addClass(class_name).html('<a onclick="pageComment(this);" data-options="pageSize:' + page_size + ' ,pageNumber:' + i + '">' + text + '</a>'));
        };
        class_name = ((page_number == page_sum) ? 'disabled' : '');
        // pagination.append($('<li/>').addClass(class_name).html('<a onclick="pageComment(this);" data-options="pageSize:' + page_size + ' ,pageNumber:' + page_sum + '" aria-label="Next"> <span aria-hidden="true">&raquo;</span> </a>'));
      }
      commmentlist.append($('<nav/>').append(pagination));
    }// callback
  );
}


/**
 * @brief 照片墙功能的事件响应函数
 */
function showPicplayerControl() {
  picture_wall.showPlayerControl();
}

function hidePictureWall(event) {
  picture_wall.hide(event);
}

function showPictureWall(ablum_id) {
  picture_wall.reloadPictureList(ablum_id);
}

function showPictureWallNext() {
  picture_wall.next();
}

function showPictureWallLast() {
  picture_wall.last();
}



/**
 * @brief 常用 CSS ，写成 js 变量，方便复用
 */
function img_transform(x) {
  var css = {
    // '-webkit-transform': 'translateX(' + x + 'px)',
    //    '-moz-transform': 'translateX(' + x + 'px)',
    //     '-ms-transform': 'translateX(' + x + 'px)',
    //      '-o-transform': 'translateX(' + x + 'px)',
    //         'transform': 'translateX(' + x + 'px)'
    '-webkit-transform': 'translate3d(' + x + 'px, 0px, 0px)',
       '-moz-transform': 'translate3d(' + x + 'px, 0px, 0px)',
        '-ms-transform': 'translate3d(' + x + 'px, 0px, 0px)',
         '-o-transform': 'translate3d(' + x + 'px, 0px, 0px)',
            'transform': 'translate3d(' + x + 'px, 0px, 0px)'
  };
  return css;
};
function left_img_pos(offset) {
  return img_transform( -$(window).width() + parseInt(offset) );
};
function middle_img_pos(offset) {
  return img_transform( 0 + parseInt(offset) );
};
function right_img_pos(offset) {
  return img_transform( +$(window).width() + parseInt(offset) );
};
var img_animations = {
  'transition': 'transform 150ms ease 0s'
};
var img_animations_fast = {
  'transition': 'transform 150ms ease 0s'
};
var img_no_animations = {
  'transition': 'transform 0s ease 0s'
};

/**
 * @brief 实现照片墙功能的 js 类
 */
function PictureWall() {
  var parent_div = $('#picture_player');
  var player_control_last = parent_div.find('.picplayer-content > .picplayer-control-last');
  var player_control_next = parent_div.find('.picplayer-content > .picplayer-control-next');
  var picture_list = parent_div.find('.picplayer-content > .picplayer-canvas > .item > ul');
  var picture_info = parent_div.find('.picplayer-content > .picplayer-canvas > .item > .info');

  this._element_parent_div = parent_div;
  this._element_player_control_last = player_control_last;
  this._element_player_control_next = player_control_next;
  this._element_picture_list = picture_list;
  this._element_picture_info = picture_info;

  this._picture_array = [];
  this._picture_info_array = [];
  this._picture_index = 0;

  var timer_ptr = $.timer(
    function() {
      console.debug(new Date().toLocaleString() + ' ontimer timer_ptr');
      player_control_last.addClass('hidden_element').css('pointer-events', 'none');
      player_control_next.addClass('hidden_element').css('pointer-events', 'none');
      picture_info.addClass('hidden_element').css('pointer-events', 'none');
      timer_ptr.stop();
    },
    5 * 1000,
    false
  );
  this._timer_ptr = timer_ptr;
}

/**
 * @brief 绑定 touch* 事件，仅执行一次即可
 */
PictureWall.prototype.bindTouchEvent = function() {
  var this_ptr = this;
  var pic_list = this._element_picture_list;

  $.ontouchevent(this._element_parent_div, function(evt, dir, phase, swipetype, distance){
    var left_img = pic_list.find('li.left_img');
    var middle_img = pic_list.find('li.middle_img');
    var right_img = pic_list.find('li.right_img');

    if (phase == 'start'){ // on touchstart
      console.log("start");
      left_img.css(img_no_animations);
      middle_img.css(img_no_animations);
      right_img.css(img_no_animations);
    }
    else if (phase == 'move') {
      console.log("move");
      if ((dir =='left') || (dir =='right')){ //  on touchmove and if moving left or right
        var offset = distance;
        // < < < 左右 > > > 移动
        left_img.css(left_img_pos(offset));
        middle_img.css(middle_img_pos(offset));
        right_img.css(right_img_pos(offset));
      }
    }
    else if (phase == 'end'){ // on touchend
      console.log("end");
      left_img.css(img_animations);
      middle_img.css(img_animations);
      right_img.css(img_animations);

      // TODO 根据手指滑动速度改变照片切换速度，提高“跟手”感觉
      // TODO 多于1个手指在屏幕上滑动时，不执行照片切换操作
      if (swipetype == 'left' || swipetype == 'right'){ // if a successful left or right swipe is made
        if (dir == 'left') {
          console.log('swipeleft ');
          this_ptr.next();
        }
        else if (dir == 'right') {
          console.log('swiperight ');
          this_ptr.last();
        }
      }
      else if (dir == 'left' || dir == 'right'){
        var max_width = $(window).width() * 0.4; // 移动距离超过40%，即执行照片切换操作
        console.log("distance = " + distance);
        console.log("50% window width = " + max_width);
        if (Math.abs(distance) > max_width) {
          if (dir == 'left') {
            console.log('swipeleft because move over 50%, distance=' + distance);
            this_ptr.next();
          }
          else if (dir == 'right') {
            console.log('swiperight because move over 50%, distance=' + distance);
            this_ptr.last();
          }
        }
        else {
          console.info("showPictureWallReset() 1 ...");
          this_ptr.reset();
        }
      }
      else {
        console.info("showPictureWallReset() 2 ...");
        this_ptr.reset();
      }
    }
  }); // end ontouch

}


/**
 * @brief 更新照片列表
 */
PictureWall.prototype.reloadPictureList = function (ablum_id) {
  var this_ptr = this;
  this._ablum_id = ablum_id;

  $.get(
    '{{ URL::to("/get-pictures") }}' + '/' + ablum_id,           // URL
    null, // data
    function(data) {
      if (data.status) {
        console.debug("get-pictures ok! ablum_id=" + ablum_id);
       if (data.data.rows.length > 0) {
          // $('body').css('overflow', 'hidden');
          $("body > div[id='picture_player']").show();
          $("body > div[id!='picture_player']").hide();
          this_ptr.init(data.data.rows);
        }

      }
      else {
        console.debug("get-pictures fail!");
      }
    },     // callback
    "json" // data type
  );
}

/**
 * @brief 初始化照片墙
 */
PictureWall.prototype.init = function (picture_id_array) {
  console.log("initPirtureWall() 1");
  this._picture_array = [];
  this._picture_info_array = [];
  this._picture_index = 0;

  var this_ptr = this;
  picture_id_array.forEach(function(entry) {
      this_ptr._picture_array.push('{{ URL::to("/get-picture") }}' + '/' + entry.picture_id);
      this_ptr._picture_info_array.push(entry);
      console.log(this_ptr._picture_array[this_ptr._picture_array.length-1]);
  });

  // TODO 将pic_list改为成员变量
  var pic_list = this._element_picture_list;
  pic_list.empty();

  var src_param = '?width=' + $(window).width() + '&height=' + $(window).height();
  var index = null;
  if (null !== (index = this.getLastPictureIndex())) {
    pic_list.append(
      $('<li class="left_img"><img src="' +this._picture_array[index]+src_param+ '"></li>')
        .css(left_img_pos(0))
    );
  }
  if (null !== (index = this.getCurrentPictureIndex())) {
    pic_list.append(
      $('<li class="middle_img"><img src="' +this._picture_array[index]+src_param+ '"></li>')
        .css(middle_img_pos(0))
    );
    var pic_info = this._element_picture_info;
    pic_info.find('.name').html(this._picture_info_array[this._picture_index].name);
    pic_info.find('.caption').html(this._picture_info_array[this._picture_index].caption);
  }
  if (null !== (index = this.getNextPictureIndex())) {
    pic_list.append(
      $('<li class="right_img"><img src="' +this._picture_array[index]+src_param+ '"></li>')
        .css(right_img_pos(0))
    );
  }

  this.showOrHidePlayerControl();
  this.showPlayerControl();
  console.log("initPirtureWall() 3");
}

/**
 * @brief 隐藏照片墙
 */
PictureWall.prototype.hide = function (event) {

  console.debug("hidePictureWall() target=" + ($(event.target).prop("tagName")));// $(event).target.get(0).tagName);

  if ( this._element_player_control_last.hasClass('hidden_element')
    || this._element_player_control_next.hasClass('hidden_element')
    || this._element_picture_info.hasClass('hidden_element')
  ) {
    this.showPlayerControl();
    return false;
  }

  if ($(event.target).prop("tagName").toLowerCase() != 'img') {
    return;
  }

  $("body > div[id!='picture_player']").show();
  $("body > div[id='picture_player']").hide();

  location.href = '#ablum_' + this._ablum_id ;
}


/**
 * @brief 获取当前照片索引，返回 null 表示获取失败
 */
PictureWall.prototype.getCurrentPictureIndex = function () {
  var index = this._picture_index;
  if (index >= this._picture_array.length) {
    return null;
  }
  if (index < 0) {
    return null;
  }
  return index;
}
PictureWall.prototype.getNextPictureIndex = function () {
  var index = this._picture_index+1;
  if (index >= this._picture_array.length) {
    return null;
  }
  return index;
}
PictureWall.prototype.getLastPictureIndex = function () {
  var index = this._picture_index-1;
  if (index < 0) {
    return null;
  }
  return index;
}

/**
 * @brief 执行切换照片的操作
 */
PictureWall.prototype.next = function () {
  console.debug("next()");
  var right_index = null;
  if (null === (right_index = this.getNextPictureIndex())) {
    this.reset();
    return;
  }
  this._picture_index = right_index;

  var pic_info = this._element_picture_info;
  var pic_list = this._element_picture_list;
  var left_img = pic_list.find('li.left_img');
  var middle_img = pic_list.find('li.middle_img');
  var right_img = pic_list.find('li.right_img');

  var src_param = '?width=' + $(window).width() + '&height=' + $(window).height();
  if (!right_img.length) {
    // 如果没找到 right_img 标签
    right_img = $("<li class='right_img' ><img src='"+this._picture_array[ right_index ]+src_param+"' ></li>")
    middle_img.after(right_img.css($.extend(right_img_pos(0),img_no_animations)));
  }

  // < < < 向左移动
  left_img.remove();
  middle_img.removeClass('middle_img').css($.extend(left_img_pos(0),img_animations))
    .addClass('left_img');
  right_img.removeClass('right_img').css($.extend(middle_img_pos(0),img_animations))
    .addClass('middle_img');
  if (null !== (right_index = this.getNextPictureIndex())) {
    var new_right_img = $("<li class='right_img' ><img src='"+this._picture_array[ right_index ]+src_param+"' ></li>")
    right_img.after(new_right_img.css($.extend(right_img_pos(0),img_no_animations)));
  }

  pic_info.find('.name').html(this._picture_info_array[this._picture_index].name);
  pic_info.find('.caption').html(this._picture_info_array[this._picture_index].caption);

  this.showOrHidePlayerControl();
}

PictureWall.prototype.last = function () {
  console.debug("last()");  
  var left_index = null;
  if (null === (left_index = this.getLastPictureIndex())) {
    this.reset();
    return;
  }
  this._picture_index = left_index;

  var pic_info = this._element_picture_info;
  var pic_list = this._element_picture_list;
  var left_img = pic_list.find('li.left_img');
  var middle_img = pic_list.find('li.middle_img');
  var right_img = pic_list.find('li.right_img');
  var src_param = '?width=' + $(window).width() + '&height=' + $(window).height();
  if (!left_img.length) {
    // 如果没找到 left_img 标签
    left_img = $("<li class='left_img' ><img src='"+this._picture_array[ left_index ]+src_param+"' ></li>")
    middle_img.before(left_img.css($.extend(left_img_pos(0),img_no_animations)));
  }

  // > > > 向右移动
  if (null !== (left_index = this.getLastPictureIndex())) {
    var new_left_img = $("<li class='left_img' ><img src='"+this._picture_array[ left_index ]+src_param+"' ></li>")
    left_img.before(new_left_img.css($.extend(left_img_pos(0),img_no_animations)));
  }
  left_img.removeClass('left_img').css($.extend(middle_img_pos(0),img_animations))
    .addClass('middle_img');
  middle_img.removeClass('middle_img').css($.extend(right_img_pos(0),img_animations))
    .addClass('right_img');
  right_img.remove();

  pic_info.find('.name').html(this._picture_info_array[this._picture_index].name);
  pic_info.find('.caption').html(this._picture_info_array[this._picture_index].caption);

  this.showOrHidePlayerControl();
}

PictureWall.prototype.reset = function () {
  console.debug("reset()");
  var pic_list = this._element_picture_list;
  var left_img = pic_list.find('li.left_img');
  var middle_img = pic_list.find('li.middle_img');
  var right_img = pic_list.find('li.right_img');

  this.showOrHidePlayerControl();
  
  // 图像归位
  var offset = 0;
  left_img.css(left_img_pos(offset));
  middle_img.css(middle_img_pos(offset));
  right_img.css(right_img_pos(offset));
}

/**
 * @brief 显示、隐藏 控制按钮
 */
PictureWall.prototype.showPlayerControl = function () {
  console.debug("showPlayerControl()");

  this._element_player_control_last
    .css('pointer-events', 'auto')
    .removeClass('hidden_element');
  this._element_player_control_next
    .css('pointer-events', 'auto')
    .removeClass('hidden_element');
  this._element_picture_info
    .css('pointer-events', 'auto')
    .removeClass('hidden_element');

  // 使用定时器，将左右按钮隐藏
  this._timer_ptr.stop();
  this._timer_ptr.play();
}
PictureWall.prototype.showOrHidePlayerControl = function () {
  // 如果没有 前/后一张 则隐藏相关按钮
  if (null == this.getLastPictureIndex()) {
    this._element_player_control_last.hide();
  }
  else {
    this._element_player_control_last.show();
  }
  // 如果没有 前/后一张 则隐藏相关按钮
  if (null == this.getNextPictureIndex()) {
    this._element_player_control_next.hide();
  }
  else {
    this._element_player_control_next.show();
  }
}


;(function($) {

  /**
   * @brief 扩展jQuery函数，用于去除字符串左右的指定字符
   * 
   * 参考 http://www.javascriptkit.com/javatutors/touchevents3.shtml
   */
  $.ontouchevent = function(el, callback) {
    var touchsurface = $(el);
    var dir;
    var swipeType;
    var startX;
    var startY;
    var distX;
    var distY;
    var threshold = 150; //required min distance traveled to be considered swipe
    var restraint = 100; // maximum distance allowed at the same time in perpendicular direction
    var allowedTime = 500; // maximum time allowed to travel that distance
    var elapsedTime;
    var startTime;
    var handletouch = callback || function(evt, dir, phase, swipetype, distance){};
   
    // touchsurface.off('touchstart').off('touchmove').off('touchend');
    touchsurface
      .on('touchstart', function(e){
        e = e.originalEvent;
        var touchobj = e.changedTouches[0];
        dir = 'none';
        swipeType = 'none';
        dist = 0;
        startX = touchobj.pageX;
        startY = touchobj.pageY;
        startTime = new Date().getTime(); // record time when finger first makes contact with surface
        handletouch(e, 'none', 'start', swipeType, 0); // fire callback function with params dir="none", phase="start", swipetype="none" etc
        // e.preventDefault(); // TODO 在此处阻止 touchstart 事件的默认动作，会影响其他元素的 click 事件。所以暂时注释这里，以后弄清原理 
      })
      .on('touchmove', function(e){
        e = e.originalEvent;
        var touchobj = e.changedTouches[0];
        distX = touchobj.pageX - startX; // get horizontal dist traveled by finger while in contact with surface
        distY = touchobj.pageY - startY; // get vertical dist traveled by finger while in contact with surface
        if (Math.abs(distX) > Math.abs(distY)){ // if distance traveled horizontally is greater than vertically, consider this a horizontal movement
            dir = (distX < 0)? 'left' : 'right';
            handletouch(e, dir, 'move', swipeType, distX); // fire callback function with params dir="left|right", phase="move", swipetype="none" etc
        }
        else{ // else consider this a vertical movement
            dir = (distY < 0)? 'up' : 'down';
            handletouch(e, dir, 'move', swipeType, distY); // fire callback function with params dir="up|down", phase="move", swipetype="none" etc
        }
        e.preventDefault(); // prevent scrolling when inside DIV
        // touchmove 事件中必须执行 preventDefault() , 否则ipad safari 中会导致图像失去 transition 中的动画效果。具体原因可能是此事件引发 去除动画效果的代码执行。
      })
      .on('touchend', function(e){
        e = e.originalEvent;
        var touchobj = e.changedTouches[0];
        elapsedTime = new Date().getTime() - startTime; // get time elapsed
        var left_right_speed = Math.abs(distX/elapsedTime);
        var up_down_speed = Math.abs(distY/elapsedTime);
        console.log("left/right speed=" + left_right_speed);
        console.log("up/down speed=" + up_down_speed);
        if (left_right_speed>=0.3 && Math.abs(distY) <= restraint) {
          swipeType = dir;
        }
        else if (up_down_speed>=0.3 && Math.abs(distX) <= restraint) {
          swipeType = dir;
        }
        // Fire callback function with params dir="left|right|up|down", phase="end", swipetype=dir etc:
        handletouch(e, dir, 'end', swipeType, (dir =='left' || dir =='right')? distX : distY);
        // e.preventDefault();
      })
      ;
  };

  /**
   * @brief 扩展jQuery函数，用于去除字符串左右的指定字符
   */
  $.ltrim = function(str, char_trim) {
    var start = 0;
    var stop = str.length;
    while (str[start] == char_trim) {
      start++;
    }
    return str.substring(start, stop);
  };
  $.rtrim = function(str, char_trim) {
    var start = 0;
    var stop = str.length;
    while (str[stop-1] == char_trim) {
      stop--;
    }
    return str.substring(start, stop);
  };
})(jQuery);

</script>
@stop
