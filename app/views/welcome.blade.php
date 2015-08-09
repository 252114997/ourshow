@extends('tpl.layout')

@section('css')

  <link rel="stylesheet" type="text/css" href="{{ asset('css/cover.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/timeline.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/comment.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bounce.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/ouershow.css') }}">

@stop


@section('body')

    <div class="site-background"></div>

    <div class="cover-continer-my" >

          <div class="cover-inner-my" onclick="shuffleBackground();" >
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

                  <div class="timeline-body" onclick="showPictureWall(this, {{ $ablum['id'] }});">
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


    <div id="picture_player" class="picplayer boxline" style="display:none;">
        <div class="picplayer-content boxline" onclick="showPicplayerControl(this); return false;">
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
            <a class="picplayer-control-last" data-liid="" onclick="showPictureWallLast(this);">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            
            <a class="picplayer-control-next" data-liid="" onclick="showPictureWallNext(this);">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>

        </div>

    </div>

@stop


@section('js')

<script type="text/javascript">

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

$(function(){
  shuffleBackground();

  // 相册评论列表
  $('ul.ourshow-commmentlist').each(function(index, elem){
    reloadComment($(elem));
  });

  // 工具栏提示
  $('[data-toggle="tooltip"]').tooltip();

  // // 触屏界面中左右滑动时切换图片

  var el = document.getElementById('picture_player'); // reference gallery's main DIV container
  ontouch(el, function(evt, dir, phase, swipetype, distance){
    var left_img = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > ul > li.left_img');
    var middle_img = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > ul > li.middle_img');
    var right_img = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > ul > li.right_img');

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
          showPictureWallNext();
        }
        else if (dir == 'right') {
          console.log('swiperight ');
          showPictureWallLast();
        }
        else {
          console.info("!!!!!!!!!never go here...");
          showPictureWallReset();
        }
      }
      else if (dir == 'left' || dir == 'right'){
        var max_width = $(window).width() * 0.4; // 移动距离超过40%，即执行照片切换操作
        console.log("distance = " + distance);
        console.log("50% window width = " + max_width);
        if (Math.abs(distance) > max_width) {
          if (dir == 'left') {
            console.log('swipeleft because move over 50%, distance=' + distance);
            showPictureWallNext();
          }
          else if (dir == 'right') {
            console.log('swiperight because move over 50%, distance=' + distance);
            showPictureWallLast();
          }
        }
        else {
          console.info("showPictureWallReset() 1 ...");
          showPictureWallReset();
        }
      }
      else {
        console.info("showPictureWallReset() 2 ...");
        showPictureWallReset();
      }
    }
  }); // end ontouch

  // $('#picture_player')
  // .on("swipeleft", function(){
  //   // $('#picture_player .picplayer-control-next').trigger('click');
  //   console.log('swipeleft');
  // })
  // .on("swiperight", function(){
  //   // $('#picture_player .picplayer-control-last').trigger('click');
  //   console.log('swiperight');
  // })
  // .on('vmousemove', function(e){
  //   console.log('vmousemove=' + e.pageX + ", " + e.pageY);
  //   var css_text = "translate(" + e.pageX + "px, 50%)";
  //   $('#picture_player .picplayer-canvas > div.item > .image').css({
  //     "-moz-transform":css_text,
  //     "-webkit-transform":css_text,
  //     "-o-transform":css_text,
  //     "-ms-transform":css_text,
  //     "transform":css_text 
  //       // "-webkit-transform":"translate(100px,100px)",
  //       // "-ms-transform":"translate(100px,100px)",
  //       // "transform":"translate(100px,100px)"
  //   });
  // });

});

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

var timer_ptr = $.timer(
  function() {
    console.debug(new Date().toLocaleString() + ' ontimer timer_ptr');
    $('#picture_player > .picplayer-content > .picplayer-control-last').addClass('hidden_element').css('pointer-events', 'none');
    $('#picture_player > .picplayer-content > .picplayer-control-next').addClass('hidden_element').css('pointer-events', 'none');
    $('#picture_player > .picplayer-content > .picplayer-canvas > .item > .info').addClass('hidden_element').css('pointer-events', 'none');
    timer_ptr.stop();
  },
  5 * 1000,
  false
);
var current_album_id = null;
var picture_info_array = [];
var picture_array = [
  // '{{ URL::to("/get-picture")."/1" }}',
  // '{{ URL::to("/get-picture")."/2" }}',
  // '{{ URL::to("/get-picture")."/3" }}',
  // '{{ URL::to("/get-picture")."/4" }}'
]; 
var picture_index = 0;
function getCurrentPictureIndex() {
  var index = picture_index;
  if (index >= picture_array.length) {
    return null;
  }
  if (index < 0) {
    return null;
  }
  return index;
}
function getLastPictureIndex() {
  var index = picture_index-1;
  if (index < 0) {
    return null;
  }
  return index;
}
function getNextPictureIndex() {
  var index = picture_index+1;
  if (index >= picture_array.length) {
    return null;
  }
  return index;
}

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
  // console.log('img_transform, x=' + x);
  // console.log('css=' + JSON.stringify(css));
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
  'transition': '150ms'
  // 'transition': 'transform 150ms ease 0s'
};
var img_animations_fast = {
  'transition': '150ms'
};
var img_no_animations = {
  // 'transition': 'transform 0s ease 0s'
  'transition': '0ms'
};

function showPicplayerControl() {
  console.debug("showPicplayerControl()");

    $('#picture_player > .picplayer-content > .picplayer-control-last')
      .css('pointer-events', 'auto')
      .removeClass('hidden_element');
    $('#picture_player > .picplayer-content > .picplayer-control-next')
      .css('pointer-events', 'auto')
      .removeClass('hidden_element');
    $('#picture_player > .picplayer-content > .picplayer-canvas > .item > .info')
      .css('pointer-events', 'auto')
      .removeClass('hidden_element');

    // 使用定时器，将左右按钮隐藏
    timer_ptr.stop();
    timer_ptr.play();
}

function showOrHidePicplayerControl() {
  // 如果没有 前/后一张 则隐藏相关按钮
  if (null == getLastPictureIndex()) {
    $('#picture_player .picplayer-control-last').hide();
  }
  else {
    $('#picture_player .picplayer-control-last').show();
  }
  // 如果没有 前/后一张 则隐藏相关按钮
  if (null == getNextPictureIndex()) {
    $('#picture_player .picplayer-control-next').hide();
  }
  else {
    $('#picture_player .picplayer-control-next').show();
  }
}

/**
 * @brief 显示、隐藏 照片墙（浏览大图）
 */
function hidePictureWall(event) {

  console.debug("hidePictureWall() target=" + ($(event.target).prop("tagName")));// $(event).target.get(0).tagName);

  if ( $('#picture_player .picplayer-control-last').hasClass('hidden_element')
    || $('#picture_player .picplayer-control-next').hasClass('hidden_element')
    || $('#picture_player > .picplayer-content > .picplayer-canvas > .item > .info').hasClass('hidden_element')
  ) {
    showPicplayerControl();
    return false;
  }

  if ($(event.target).prop("tagName").toLowerCase() != 'img') {
    return;
  }

  $("body > div[id!='picture_player']").show();
  $("body > div[id='picture_player']").hide();

  location.href = '#ablum_' + current_album_id;

}

function showPictureWall(timeline_body, ablum_id) {
  console.debug("showPictureWall()");

  showPictureWallTimelineItem(ablum_id);
}

function showPictureWallNext(picplayer_control) {
  console.debug("showPictureWallNext()");
  var right_index = null;
  if (null === (right_index = getNextPictureIndex())) {
    showPictureWallReset();
    return;
  }
  picture_index = right_index;

  var img_info = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > .info');
  var pic_list = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > ul');
  var left_img = pic_list.find('li.left_img');
  var middle_img = pic_list.find('li.middle_img');
  var right_img = pic_list.find('li.right_img');
  console.debug("typeof right_img=", (typeof right_img));
  var src_param = '?width=' + $(window).width() + '&height=' + $(window).height();
  if (!right_img.length) {
    // 如果没找到 right_img 标签
    right_img = $("<li class='right_img' ><img src='"+picture_array[ right_index ]+src_param+"' ></li>")
    middle_img.after(right_img.css($.extend(right_img_pos(0),img_no_animations)));
  }

  // < < < 向左移动
  left_img.remove();
  middle_img.removeClass('middle_img').css($.extend(left_img_pos(0),img_animations))
    .addClass('left_img')
    .addClass('animations');
  right_img.removeClass('right_img').css($.extend(middle_img_pos(0),img_animations))
    .addClass('middle_img')
    .addClass('animations');
  img_info.find('.name').html(picture_info_array[picture_index].name);
  img_info.find('.caption').html(picture_info_array[picture_index].caption);

  if (null !== (right_index = getNextPictureIndex())) {
    var new_right_img = $("<li class='right_img' ><img src='"+picture_array[ right_index ]+src_param+"' ></li>")
    right_img.after(new_right_img.css($.extend(right_img_pos(0),img_no_animations)));
  }

  showOrHidePicplayerControl();
}

function showPictureWallLast(picplayer_control) {
  console.debug("showPictureWallLast()x");  
  var left_index = null;
  if (null === (left_index = getLastPictureIndex())) {
    showPictureWallReset();
    return;
  }
  picture_index = left_index;

  var img_info = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > .info');
  var pic_list = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > ul');
  var left_img = pic_list.find('li.left_img');
  var middle_img = pic_list.find('li.middle_img');
  var right_img = pic_list.find('li.right_img');
  var src_param = '?width=' + $(window).width() + '&height=' + $(window).height();
  if (!left_img.length) {
    // 如果没找到 left_img 标签
    left_img = $("<li class='left_img' ><img src='"+picture_array[ left_index ]+src_param+"' ></li>")
    middle_img.before(left_img.css($.extend(left_img_pos(0),img_no_animations)));
  }
  console.debug("left_img=", left_img.html());
  console.debug("middle_img=", middle_img.html());

  // > > > 向右移动
  if (null !== (left_index = getLastPictureIndex())) {
    var new_left_img = $("<li class='left_img' ><img src='"+picture_array[ left_index ]+src_param+"' ></li>")
    left_img.before(new_left_img.css($.extend(left_img_pos(0),img_no_animations)));
  }
  left_img.removeClass('left_img').css($.extend(middle_img_pos(0),img_animations))
    .addClass('middle_img')
    .addClass('animations');
  middle_img.removeClass('middle_img').css($.extend(right_img_pos(0),img_animations))
    .addClass('right_img')
    .addClass('animations');
  right_img.remove();
  img_info.find('.name').html(picture_info_array[picture_index].name);
  img_info.find('.caption').html(picture_info_array[picture_index].caption);

  showOrHidePicplayerControl();
}
function showPictureWallReset() {
  console.info("showPictureWallReset()");
  var left_img = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > ul > li.left_img');
  var middle_img = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > ul > li.middle_img');
  var right_img = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > ul > li.right_img');
  var offset = 0;

  showOrHidePicplayerControl();
  
  // 图像归位
  left_img.css(left_img_pos(offset));
  middle_img.css(middle_img_pos(offset));
  right_img.css(right_img_pos(offset));
}

function showPictureWallTimelineItem(ablum_id) {
  $.get(
    '{{ URL::to("/get-pictures") }}' + '/' + ablum_id,           // URL
    null, // data
    function(data) {
      if (data.status) {
        console.debug("get-pictures ok! ablum_id=" + ablum_id);
        console.debug('data.data.rows.length=' + data.data.rows.length);
        current_album_id = ablum_id;
       if (data.data.rows.length > 0) {
          // $('body').css('overflow', 'hidden');
          $("body > div[id='picture_player']").show();
          $("body > div[id!='picture_player']").hide();
          initPirtureWall(data.data.rows);
        }

      }
      else {
        console.debug("get-pictures fail!");
      }
    },     // callback
    "json" // data type
  );
}

function initPirtureWall(picture_id_array) {
  console.log("initPirtureWall() 1");
  picture_array = [];
  picture_info_array = [];
  picture_id_array.forEach(function(entry) {
      picture_array.push('{{ URL::to("/get-picture") }}' + '/' + entry.picture_id);
      picture_info_array.push(entry);
      console.log(picture_array[picture_array.length-1]);
  });
  picture_index = 0;
  var pic_list = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > ul');
  pic_list.empty();

  var src_param = '?width=' + $(window).width() + '&height=' + $(window).height();
  var index = null;
  if (null !== (index = getLastPictureIndex())) {
    pic_list.append(
      $('<li class="left_img"><img src="' +picture_array[index]+src_param+ '"></li>')
        .css(left_img_pos(0))
    );
  }
  if (null !== (index = getCurrentPictureIndex())) {
    pic_list.append(
      $('<li class="middle_img"><img src="' +picture_array[index]+src_param+ '"></li>')
        .css(middle_img_pos(0))
    );
    var img_info = $('#picture_player > .picplayer-content > .picplayer-canvas > div.item > .info');
    img_info.find('.name').html(picture_info_array[picture_index].name);
    img_info.find('.caption').html(picture_info_array[picture_index].caption);
  }
  if (null !== (index = getNextPictureIndex())) {
    pic_list.append(
      $('<li class="right_img"><img src="' +picture_array[index]+src_param+ '"></li>')
        .css(right_img_pos(0))
    );
  }
  showOrHidePicplayerControl();
  showPicplayerControl();
  console.log("initPirtureWall() 3");
}

/**
 * @brief http://www.javascriptkit.com/javatutors/touchevents3.shtml
 */
function ontouch(el, callback){
 // return;
    var touchsurface = el,
    dir,
    swipeType,
    startX,
    startY,
    distX,
    distY,
    threshold = 150, //required min distance traveled to be considered swipe
    restraint = 100, // maximum distance allowed at the same time in perpendicular direction
    allowedTime = 500, // maximum time allowed to travel that distance
    elapsedTime,
    startTime,
    handletouch = callback || function(evt, dir, phase, swipetype, distance){}
 
    touchsurface.addEventListener('touchstart', function(e){
        var touchobj = e.changedTouches[0]
        dir = 'none'
        swipeType = 'none'
        dist = 0
        startX = touchobj.pageX
        startY = touchobj.pageY
        startTime = new Date().getTime() // record time when finger first makes contact with surface
        handletouch(e, 'none', 'start', swipeType, 0) // fire callback function with params dir="none", phase="start", swipetype="none" etc
        // e.preventDefault() // TODO 在此处阻止 touchstart 事件的默认动作，会影响其他元素的 click 事件。所以暂时注释这里，以后弄清原理 
 
    }, false)
 
    touchsurface.addEventListener('touchmove', function(e){
        var touchobj = e.changedTouches[0]
        distX = touchobj.pageX - startX // get horizontal dist traveled by finger while in contact with surface
        distY = touchobj.pageY - startY // get vertical dist traveled by finger while in contact with surface
        if (Math.abs(distX) > Math.abs(distY)){ // if distance traveled horizontally is greater than vertically, consider this a horizontal movement
            dir = (distX < 0)? 'left' : 'right'
            handletouch(e, dir, 'move', swipeType, distX) // fire callback function with params dir="left|right", phase="move", swipetype="none" etc
        }
        else{ // else consider this a vertical movement
            dir = (distY < 0)? 'up' : 'down'
            handletouch(e, dir, 'move', swipeType, distY) // fire callback function with params dir="up|down", phase="move", swipetype="none" etc
        }
        e.preventDefault() // prevent scrolling when inside DIV
        // touchmove 事件中必须执行 preventDefault() , 否则ipad safari 中会导致图像失去 transition 中的动画效果。具体原因可能是此事件引发 去除动画效果的代码执行。
    }, false)
 
    touchsurface.addEventListener('touchend', function(e){
        var touchobj = e.changedTouches[0]
        elapsedTime = new Date().getTime() - startTime // get time elapsed
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
        // if (elapsedTime <= allowedTime){ // first condition for awipe met
        //     if (Math.abs(distX) >= threshold && Math.abs(distY) <= restraint){ // 2nd condition for horizontal swipe met
        //         swipeType = dir // set swipeType to either "left" or "right"
        //     }
        //     else if (Math.abs(distY) >= threshold && Math.abs(distX) <= restraint){ // 2nd condition for vertical swipe met
        //         swipeType = dir // set swipeType to either "top" or "down"
        //     }
        // }
        // Fire callback function with params dir="left|right|up|down", phase="end", swipetype=dir etc:
        handletouch(e, dir, 'end', swipeType, (dir =='left' || dir =='right')? distX : distY)
        // e.preventDefault()
    }, false)
}
</script>

<script type="text/javascript">

/**
 * @brief 扩展jQuery函数，用于去除字符串左右的指定字符
 */
;(function($) {
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
