@extends('tpl.layout')

@section('css')

  <link rel="stylesheet" type="text/css" href="{{ asset('css/cover.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/timeline.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/comment.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bounce.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/picture-wall.css') }}">

@stop


@section('body')

    <div class="progress-wrapper">
      <div class="progress-inner">
        <div class="progress ">
          <div id="page_progress_bar" class="progress-bar progress-bar-info progress-bar-striped" 
            role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 1%;min-width: 2em;">
            1%
          </div>
        </div>
      </div>
    </div>
    <style type="text/css">
      .progress-wrapper {
        /*遮挡背景*/
        position:fixed;
        left: 0px;
        top: 0px;
        z-index: 1000;
        overflow: hidden;
        width: 100%;
        height: 100%;
        background-color: rgb(245, 245, 213); 
      }
      .progress-inner {
        /*垂直居中*/
        height: 100%;
        bottom: 50%;
        -webkit-transform: translateY(50%);
           -moz-transform: translateY(50%);
            -ms-transform: translateY(50%);
             -o-transform: translateY(50%);
                transform: translateY(50%);
      }
    </style>

    <div class="site-background site-background-front" ></div>
    <div class="site-background site-background-back"  ></div>

    <div class="cover-continer" >
          <div class="cover-inner" onclick="doShuffleBackground();" >
            <h1 class="cover-heading">见证爱情</h1>
            <p class="lead">二十年擦肩而过</p>
            <p class="lead">五年朝夕相伴</p>
            <p class="lead">一生所爱</p>
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
                
                <div class="timeline-badge {{ ($index%2) ? 'icon-footprint-left' : 'icon-footprint-right' }}" 
                    data-toggle="tooltip" data-placement="top" 
                    title="{{ $ablum['tips'] }}" ><i class="glyphicon glyphicon-time" ></i>
                </div>

                <div class="timeline-panel hide-caption">
                  <div class="timeline-body">
                    <img src='{{ URL::to("/get-picture")."/".$ablum["picture_id"]["id"] }}' 
                      class="img-rounded" />

                    <div class="description">
                      <h2 class="title">{{ $ablum['title'] }}</h2>
                      <p class="caption">{{ $ablum['caption'] }}</p>
                    </div>

                    <div class="buttons">
                      <a class="btn btn-xs btn-default" 
                          onclick="showPictureWall({{ $ablum['id'] }}, '{{ $ablum['title'] }}');"
                        ><i class="glyphicon glyphicon-blackboard" ></i> 更多</a>
                      <a class="btn btn-xs {{ $ablum['likeit'] ? 'btn-danger' : 'btn-default' }}" 
                          data-toggle="tooltip" data-placement="top" 
                          title="{{ count($ablum['likes']) }} 人表示很赞"
                          onclick='likeAblum(this, {{ $ablum["id"] }}); return false;' 
                        ><i class="glyphicon glyphicon-thumbs-up" ></i> <span>赞({{ count($ablum['likes']) }})</span></a>
                      <a class="btn btn-xs btn-default" 
                          data-toggle="tooltip" data-placement="top" 
                          onclick='toggleButtons(this); return false;' 
                        ><i class="glyphicon glyphicon-comment" ></i> 评论</a>
                    </div>
                  </div>

                  <div class="container usercomment" style="display:none">
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


    <div id="picture_player" tabindex="0" class="picplayer picplayer-animate-start">
        <div class="picplayer-content" onclick="showOrHidePlayerControl(this);">
            <div class="picplayer-canvas" >
                <div class="item" >
                  <ul >
                    <li class="left_img" ><img /></li>
                    <li class="middle_img" ><img /></li>
                    <li class="right_img" ><img /></li>
                  </ul>
                  <div class="info">
                    <div class="page">
                      <span class="current">1</span>/<span class="sum">1</span>
                    </div>
                    <h2 class="name">name</h2>
                    <span class="caption">caption</span>
                  </div>
                </div>
            </div>
            <div class="picplayer-control-header">
              <a class="picplayer-control-close" onclick="hidePictureWall(event);">
                  <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                  <span class="sr-only">Close</span>
              </a>
              <h4 class="picplayer-control-title">title</h4>
            </div>
            <a class="picplayer-control-last" onclick="showPictureWallLast(event);">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="picplayer-control-next" onclick="showPictureWallNext(event);">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            <div class="picplayer-control-comments">
                <div class="text-center">
                  <div class="input-group">
                      <input type="text" class="form-control input-sm" placeholder="我也说一句..." name='comment'
                        onkeydown="if (event.keyCode == 13) $(this).next('span').click();" />
                      <span class="input-group-btn" onclick="addComments();">     
                        <a class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-comment"></span>评论</a>
                      </span>
                  </div>

                  <hr/>
                  <ul class="ourshow-commmentlist"  data-options="url:''" ></ul>

                </div>

                <div class="comments-button">
                  <a class="btn btn-xs btn-default" 
                      data-toggle="tooltip" data-placement="top" 
                      onclick='toggleCommentsButtonInPicplayer(this); return false;' 
                    ><i class="glyphicon glyphicon-comment" ></i> 评论</a>
                </div>
            </div>
        </div>
    </div>

@stop


@section('js')

<script type="text/javascript">

var picture_wall = new PictureWall();

$(function(){

  // 初始化背景
  initBackground();

  // 初始进度条
  initProcessBar();

  // // 相册图片出现时，渐显效果
  // $('.timeline-body').appear();
  // $(document.body).on('appear', '.timeline-body', function(event, $all_appeared_elements) {
  //   $all_appeared_elements.each(function() {
  //     $(this).css(img_transform_y(0));
  //   });
  // });

  // 相册评论列表
  $('ul.ourshow-commmentlist').each(function(index, elem){
    reloadComment($(elem));
  });

  // 工具栏提示
  $('[data-toggle="tooltip"]').tooltip();

  // 触屏界面中左右滑动时切换图片
  picture_wall.bindTouchEvent();

  // 鼠标悬停在图片上，显示点赞按钮
  var timeline_panels = $('.timeline-panel');
  timeline_panels.each(function(index, entry){
    $(entry)
      .mouseenter(function() {
        var current = $(this);
        timeline_panels.each(function(index2, entry2){
          entry2 = $(entry2);
          if (entry2.is(current)) {
            entry2.removeClass('hide-caption');
          }
          else {
            entry2.addClass('hide-caption');
          }
        });
        // console.debug('hide-caption   mouseenter');
      })
      .on('touchstart', function(){
        var current = $(this);
        timeline_panels.each(function(index2, entry2){
          entry2 = $(entry2);
          if (entry2.is(current)) {
            entry2.removeClass('hide-caption');
          }
          else {
            entry2.addClass('hide-caption');
          }
        });
        // console.debug('hide-caption   touchstart');
      });
  });

});


// 保证每次更换的背景不重复
var random_backgrounds_bak = {{ json_encode(array_values($param['backgrounds'])) }};
var random_backgrounds = $.extend(true, [], random_backgrounds_bak);
var random_background_next_url = "";
var random_background_current_url = "";

/**
 * @brief 随机更换背景
 */
function initBackground() {
  var scale_now = 1.1;
  $('div.site-background')
    .css(img_transform_scale(scale_now))
    .css('text-indent', scale_now);

  shuffleBackground();
  shuffleBackground();
  var background_front = $('div.site-background-front');
  background_front.css("background-image", 'url( ' + random_background_current_url  + ' )');

  // 取消自动开始放大的效果
  // $.timer(function() {
  //   var background_front = $('div.site-background-front');
  //   background_front.animate({textIndent: 1.2}, {
  //     duration: 20*1000, 
  //     step: function(scale_now, fx) {
  //       $(this).css(img_transform_scale(scale_now));
  //     }
  //   });
  // }).once(0);
}
function shuffleBackground() {
  // console.debug("shuffleBackground()");
  var max_num = random_backgrounds.length;
  var shuffleNum = function() { 
    return Math.floor(Math.random()*max_num); // 0 - max_num 间的随机数
  };
  random_background_current_url = random_background_next_url;
  var index = shuffleNum();
  random_background_next_url = '{{ URL::to("/get-background") }}/' + random_backgrounds[index] + '?width=' + $(window).width() + '&height=' + $(window).height();

  if (1 >= random_backgrounds.length) {
    random_backgrounds = $.extend(true, [], random_backgrounds_bak);
  }
  else {
    random_backgrounds.splice(index,1);
  }
  // console.log('current=', random_background_current_url);
  // console.log('next=', random_background_next_url);
}
function doShuffleBackground() {
  shuffleBackground();

  var background_front = $('div.site-background-front');
  var background_back  = $('div.site-background-back');

  // 预加载下一张图片
  $('<img/>').attr('src', random_background_next_url).on('load', function() {
    $(this).remove(); // prevent memory leaks as @benweet suggested
  });
  // 在image下载完毕后再执行图片切换效果
  $('<img/>').attr('src', random_background_current_url).on('load', function() {
    // console.debug('image load completed.');
    // console.debug('index=' + index);

    $(this).remove(); // prevent memory leaks as @benweet suggested

    background_front.stop();
    background_back.stop();

    background_back.css("background-image", 'url( ' + random_background_current_url  + ' )');
    background_back.css({opacity: 0});

    // 缩小当前图像
    background_front.animate({textIndent: 1}, {
      queue: false,
      duration: 800, // 0.8 s
      step: function(scale_now,fx) {
        $(this).css(img_transform_scale(scale_now));
      },
      complete: function() {
        // console.debug('background_front zoom in completed.');
        // console.debug('index=' + index);
        var scale = 1.1;
        background_back
          .css(img_transform_scale(scale))
          .css('text-indent', scale);
        // 淡出当前图像 淡入下一张图像
        background_back.animate({opacity: 1}, {duration: 2000});
        background_front.animate({opacity: 0}, {
          duration: 2000, 
          complete: function() {
            // console.debug('background_front opacity 0 completed.');
            // console.debug('index=' + index);

            // 放大下一张图像
            background_front.removeClass('site-background-front').addClass('site-background-back');
            background_back.removeClass('site-background-back').addClass('site-background-front');
            background_back.animate({textIndent: 1.2}, {
              duration: 20*1000, 
              step: function(scale_now, fx) {
                $(this).css(img_transform_scale(scale_now));
              },
              complete: function() {
                // console.debug('background_front switch to background_back completed.');
                // console.debug('index=' + index);
              }
            });
          }
        });
      }
    });

  });
}
function img_transform_scale(scale_now) {
  var css = {
          // 'text-indent': scale_now,
    '-webkit-transform': "scale(" + scale_now + ", " + scale_now + ")",
       '-moz-transform': "scale(" + scale_now + ", " + scale_now + ")",
        '-ms-transform': "scale(" + scale_now + ", " + scale_now + ")",
         '-o-transform': "scale(" + scale_now + ", " + scale_now + ")",
            'transform': "scale(" + scale_now + ", " + scale_now + ")"
  };
  return css;
};

/**
 * @brief 加载图片的进度条
 */
function initProcessBar() {
    var image_index = 0;
    var images = [];

    images.push(random_background_current_url);
    images.push(random_background_next_url);
    for (var i = 0; i < document.images.length; i++) {
        images.push(document.images[i].src);
    }

    // just for test. TODO
    @foreach( $param['backgrounds'] as $image_bg)
      // images.push(
      //     "{{ URL::to('/get-background') }}/{{ $image_bg }}" 
      //     + '?width='  + $(window).width() 
      //     + '&height=' + $(window).height()
      //   );
    @endforeach

    // console.log(images);

    var page_progress_bar = $('#page_progress_bar');

    /*预加载图片*/
    $.imgpreload(images,
    {
        each: function () {
            /*this will be called after each image loaded*/
            // var status = $(this).data('loaded') ? 'success' : 'error';
            // if (status == "success") {
                var v = (parseFloat(++image_index) / images.length).toFixed(2);
                var percent = Math.round(v * 100);
                // console.log('percent:' + percent);
                page_progress_bar.width(percent+'%');
                page_progress_bar.text(percent+'%');
            // }
        },
        all: function () {
            /*this will be called after all images loaded*/
            // console.log('completed');
            var percent = 100;
            page_progress_bar.width(percent+'%');
            page_progress_bar.text(percent+'%');
            page_progress_bar.closest('div.progress-wrapper').fadeOut(1000);// 1秒内淡出
        }
    });
}


/**
 * @brief 实现点赞按钮
 */
function doLikeit(button, likeit) {
  if (likeit) {
    button.removeClass('btn-default').addClass('btn-danger');
  }
  else {
    button.removeClass('btn-danger').addClass('btn-default');
  }
}

function likeAblum (button, ablum_id) {
  // console.debug('likeAblum');

  button = $(button);
  var likeit = button.hasClass('btn-danger');
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
        var likeit_count = Object.keys(data.data.likes).length;
        button.find('span').html('赞(' + likeit_count + ')');
        button.attr('data-original-title', likeit_count + ' 人表示很赞');
        button.tooltip('show');
      }
    },     // callback
    "json" // data type
  );
  return false;
}

function toggleButtons(button) {
  // console.debug('toggleButtons');
  button = $(button);
  var comment = button.closest('li').find('.usercomment');
  var timeline_panel = button.closest('.timeline-panel');
  comment.toggle();
  button.toggleClass('active');
  timeline_panel.toggleClass('black-background');
  return false;
}

function toggleCommentsButtonInPicplayer(button) {
  // console.debug('toggleCommentsButtonInPicplayer');
  button = $(button);
  var comment = button.closest('.picplayer-control-comments');
  comment.toggleClass('picplayer-control-comments-show');
  button.toggleClass('active');

  return false;
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
        // $().alert('close'); // test TODO
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
  // console.debug('json  data_options=' +  data_options_string);
  commmentlist.attr('data-options', data_options_string);
  reloadComment(commmentlist);
}

function reloadComment(commmentlist) {
  // commmentlist.html('');
  var data_options = eval('({' + commmentlist.attr('data-options') + '})');
  var request_url = data_options.url;

  if ('' == request_url) {
    // console.log('request_url is empty!');
    return;
  }

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
          // console.debug('i.length=' + start + ', page_sum.length=' + end);
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

function addComments() {
  picture_wall.addComments();
}

/**
 * @brief 照片墙功能的事件响应函数
 */
function showOrHidePlayerControl() {
  // console.debug('showOrHidePlayerControl');
  if ( picture_wall.isShowPlayerControl()) {
    picture_wall.hidePlayerControl();
  }
  else {
    picture_wall.showPlayerControl();
  }
}

function hidePictureWall(event) {
  picture_wall.hide();
}

function showPictureWall(ablum_id, ablum_title) {
  picture_wall.show(ablum_id, ablum_title);
}

function showPictureWallNext(e) {
  // console.debug('showPictureWallNext');
  picture_wall.next(1);
  picture_wall.showPlayerControl();

  e.stopPropagation();
  e.preventDefault();
}
function showPictureWallLast(e) {
  // console.debug('showPictureWallLast');
  picture_wall.last(1);
  picture_wall.showPlayerControl();

  e.stopPropagation();
  e.preventDefault();

}


/**
 * @brief 常用 CSS ，写成 js 变量，方便复用
 */
function img_transform_x(x) {
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
function img_transform_y(x) {
  var css = {
    '-webkit-transform': 'translate3d(0px, ' + x + 'px, 0px)',
       '-moz-transform': 'translate3d(0px, ' + x + 'px, 0px)',
        '-ms-transform': 'translate3d(0px, ' + x + 'px, 0px)',
         '-o-transform': 'translate3d(0px, ' + x + 'px, 0px)',
            'transform': 'translate3d(0px, ' + x + 'px, 0px)'
  };
  return css;
};
function left_img_pos(offset) {
  return img_transform_x( -$(window).width() + parseInt(offset) );
};
function middle_img_pos(offset) {
  return img_transform_x( 0 + parseInt(offset) );
};
function right_img_pos(offset) {
  return img_transform_x( +$(window).width() + parseInt(offset) );
};

/**
 * @brief 常用 CSS ，写成 js 变量，方便复用
 */
function img_animations(time) {
  var css = {
          'transition': 'transform '+ time +'ms ease-out 0s',
       '-o-transition': 'transform '+ time +'ms ease-out 0s',
      '-ms-transition': 'transform '+ time +'ms ease-out 0s',
     '-moz-transition': 'transform '+ time +'ms ease-out 0s',
  '-webkit-transition': '-webkit-transform '+ time +'ms ease-out 0s'
  };
  return css;
};
function img_no_animations() {
  return img_animations(0);
};

/**
 * @brief 实现照片墙功能的 js 类
 */
function PictureWall() {
  var parent_div = $('#picture_player');
  var player_control_last = parent_div.find('.picplayer-content > .picplayer-control-last');
  var player_control_next = parent_div.find('.picplayer-content > .picplayer-control-next');
  var player_control_close = parent_div.find('.picplayer-content > .picplayer-control-close');
  var picture_list = parent_div.find('.picplayer-content > .picplayer-canvas > .item > ul');
  var picture_info = parent_div.find('.picplayer-content > .picplayer-canvas > .item > .info');
  var ablum_title = parent_div.find('.picplayer-content > .picplayer-control-header > .picplayer-control-title');
  var ablum_header = parent_div.find('.picplayer-content > .picplayer-control-header');
  var commmentlist = parent_div.find('.picplayer-content > .picplayer-control-comments .ourshow-commmentlist');
  var comment_input = parent_div.find('.picplayer-content > .picplayer-control-comments .input-group input');

  this._element_parent_div = parent_div;
  this._element_player_control_last = player_control_last;
  this._element_player_control_next = player_control_next;
  this._element_player_control_close = player_control_close;
  this._element_picture_list = picture_list;
  this._element_picture_info = picture_info;
  this._element_ablum_title = ablum_title;
  this._element_ablum_header = ablum_header;
  this._element_commmentlist = commmentlist;
  this._element_comment_input = comment_input;

  this._picture_array = [];
  this._picture_info_array = [];
  this._picture_index = 0;
  var this_ptr = this;

  var timer_ptr = $.timer(
    function() {
      // console.debug(new Date().toLocaleString() + ' ontimer timer_ptr');
      this_ptr.hidePlayerControl();
    },
    25 * 1000,
    false
  );
  this._timer_ptr = timer_ptr;
}

function getTranslateX(element) {
  if (element.size() == 0) {
    return 0;
  }
  // http://stackoverflow.com/questions/5968227/get-the-value-of-webkit-transform-of-an-element-with-jquery
  var style = window.getComputedStyle(element.get(0));  // Need the DOM object
  var matrix = new WebKitCSSMatrix(style.webkitTransform);
  return parseInt(matrix.m41);
}

/**
 * @brief 绑定 touch* 事件，仅执行一次即可
 */
PictureWall.prototype.bindTouchEvent = function() {
  var this_ptr = this;
  var pic_list = this._element_picture_list;

  var this_ptr = this;

  $(this._element_parent_div).keyup(function(e){
      // console.log('keycode=' + e.keyCode);
      if (this_ptr.isHide()) {
        return;
      }

      // 37 - left
      // 39 - right
      // 74 - j
      // 75 - k
      // 27 - esc
      if (37 == e.keyCode || 75 == e.keyCode) {
        this_ptr.last(0.2);
        return false;
      }
      else if (39 == e.keyCode || 74 == e.keyCode) {
        this_ptr.next(0.2);
        return false;
      }
      else if (27 == e.keyCode) {
        this_ptr.hide();
        return false;
      }
  });

  $.ontouchevent(this._element_parent_div, function(evt, dir, phase, swipetype, distance){
    var tag_name = ($(evt.target).prop("tagName")).toLowerCase();
    if (tag_name !== 'li' && tag_name !== 'img' ) {
      return;
    }

    var left_img = pic_list.find('li.left_img');
    var middle_img = pic_list.find('li.middle_img');
    var right_img = pic_list.find('li.right_img');

    if (phase == 'start'){ // on touchstart
      left_img.css(img_no_animations());
      middle_img.css(img_no_animations());
      right_img.css(img_no_animations());
      this._start_time = new Date();

      this._left_x = getTranslateX(left_img);
      this._middle_x = getTranslateX(middle_img);
      this._right_x = getTranslateX(right_img);
      // console.log('_left_x=' + this._left_x); 
      // console.log('_middle_x=' + this._middle_x); 
      // console.log('_right_x=' + this._right_x); 
    }
    else if (phase == 'move') {
      // console.log("move");
      if ((dir =='left') || (dir =='right')){ //  on touchmove and if moving left or right
        // console.debug('distance offset = ' + distance);
        // < < < 左右 > > > 移动

        // 以元素的 TranslateX 为起点，
        // 屏幕滑动的距离为 offset (distance) 
        // 设置新的 TranslateX， 使得可以连续滑动切换图片
        left_img.css(middle_img_pos(distance + this._left_x));
        middle_img.css(middle_img_pos(distance + this._middle_x));
        right_img.css(middle_img_pos(distance + this._right_x));
      }
    }
    else if (phase == 'end'){ // on touchend
      
      // 根据手指滑动速度改变照片切换速度，提高“跟手”感觉
      var end_time = new Date();
      var time = end_time.getTime()- this._start_time.getTime(); // ms
      var rate = 0;
      rate = time / Math.abs(distance); // 这里算的其实不是速度，只是时间与距离的比率。 此处数值越小，图片移动速度越快

      // console.log("end distance=" + distance);
      // console.log("end time=" + time + ',rate =' + rate);

      // TODO 多于1个手指在屏幕上滑动时，不执行照片切换操作
      if (swipetype == 'left' || swipetype == 'right'){ // if a successful left or right swipe is made
        if (dir == 'left') {
          // console.log('swipeleft ');
          this_ptr.next(rate);
        }
        else if (dir == 'right') {
          // console.log('swiperight ');
          this_ptr.last(rate);
        }
      }
      else if (dir == 'left' || dir == 'right'){
        var max_width = $(window).width() * 0.6; // 移动距离超过60%，即执行照片切换操作
        // console.log("50% window width = " + max_width);
        if (Math.abs(distance) > max_width) {
          if (dir == 'left') {
            // console.log('swipeleft because move over 50%, distance=' + distance);
            this_ptr.next(rate);
          }
          else if (dir == 'right') {
            // console.log('swiperight because move over 50%, distance=' + distance);
            this_ptr.last(rate);
          }
        }
        else {
          // console.info("showPictureWallReset() 1 ...");
          this_ptr.reset(rate);
        }
      }
      else {
        // console.info("showPictureWallReset() 2 ...");
        this_ptr.reset(rate);
      }
    }
  }); // end ontouch

}

/**
 * @brief 更新照片列表
 */
PictureWall.prototype.show = function (ablum_id, ablum_title) {
  var this_ptr = this;
  this._ablum_id = ablum_id;

  // 复原图片状态
  this._element_picture_list.find('img').attr('src', 'img/ajax-loader.gif');

  this.hidePlayerControl();
  this._element_parent_div.addClass('picplayer-animate-end');

  $.get(
    '{{ URL::to("/get-pictures") }}' + '/' + ablum_id,           // URL
    null, // data
    function(data) {
      if (data.status) {
        // console.debug("get-pictures ok! ablum_id=" + ablum_id);
        if (data.data.rows.length > 0) {
          this_ptr.reloadPictureList(data.data.rows, ablum_title);
          this_ptr._element_parent_div.focus(); // to handle keyup event for left/right/J/K/Esc
          this_ptr.reloadComments();
        }
        else {
          this_ptr.hide();
          this_ptr.hide(); // force hide()
        }

      }
      else {
        // console.debug("get-pictures fail!");
      }
    },     // callback
    "json" // data type
  );
}

/**
 * @brief 初始化照片墙
 */
PictureWall.prototype.reloadPictureList = function (picture_id_array, ablum_title) {
  // console.log("reloadPictureList() 1");
  this._picture_array = [];
  this._picture_info_array = [];
  this._picture_index = 0;

  var this_ptr = this;
  picture_id_array.forEach(function(entry) {
      this_ptr._picture_array.push('{{ URL::to("/get-picture") }}' + '/' + entry.picture_id);
      this_ptr._picture_info_array.push(entry);
      // console.log(this_ptr._picture_array[this_ptr._picture_array.length-1]);
  });

  this._element_ablum_title.text(ablum_title);

  var pic_list = this._element_picture_list;
  pic_list.empty();
 
  var src_param = '?width=' + $(window).width() + '&height=' + $(window).height();
  var index_left = null;
  var index_middle = null;
  var index_right = null;
  if (null !== (index_left = this.getLastPictureIndex())) {
    var image_url1 = this._picture_array[index_left]+src_param;
    // console.debug('append index_left=' + index_left + ', url=' + image_url1);
    var left_img = $('<li class="left_img"><img src="img/ajax-loader.gif"></li>')
        .css(left_img_pos(0));
    pic_list.append(left_img);
    $('<img/>').attr('src', image_url1).on('load', function() {
      left_img.find('img').attr('src', image_url1);
    });
  }
  if (null !== (index_middle = this.getCurrentPictureIndex())) {
    var pic_info = this._element_picture_info;
    pic_info.find('.name').html(this._picture_info_array[this._picture_index].name);
    pic_info.find('.caption').html(this._picture_info_array[this._picture_index].caption);
    pic_info.find('.page > .current').html(this._picture_index+1);
    pic_info.find('.page > .sum').html(this._picture_array.length);

    var image_url2 = this._picture_array[index_middle]+src_param;
    // console.debug('append index_middle=' + index_middle + ', url=' + image_url2);
    var middle_img = $('<li class="middle_img"><img src="img/ajax-loader.gif"></li>')
        .css(middle_img_pos(0));
    pic_list.append(middle_img);
    $('<img/>').attr('src', image_url2).on('load', function() {
      middle_img.find('img').attr('src', image_url2);
    });

  }
  if (null !== (index_right = this.getNextPictureIndex())) {
    var image_url3 = this._picture_array[index_right]+src_param;
    // console.debug('append index_right=' + index_right + ', url=' + image_url3);
    var right_img = $('<li class="right_img"><img src="img/ajax-loader.gif"></li>')
        .css(right_img_pos(0));
    pic_list.append(right_img);
    $('<img/>').attr('src', image_url3).on('load', function() {
      right_img.find('img').attr('src', image_url3);
    });
  }

  this.showOrHideLastNextButton();
  // console.log("reloadPictureList() 3");
}

/**
 * @brief 初始化评论列表
 */
 PictureWall.prototype.reloadComments = function () {
  var ablum_id = this._ablum_id;
  var commmentlist = this._element_commmentlist;
  var data_options = eval('({' + commmentlist.attr('data-options') + '})');
  data_options.url = '{{ URL::to("/get-comments") }}/' + ablum_id;

  var data_options_string = JSON.stringify(data_options);
  data_options_string = $.ltrim(data_options_string, '{');
  data_options_string = $.rtrim(data_options_string, '}');

  commmentlist.attr('data-options', data_options_string);
  reloadComment(commmentlist);
 }

 PictureWall.prototype.addComments = function () {
  var this_ptr = this;
  var ablum_id = this._ablum_id;
  var comment_input = this._element_comment_input;
  var comment = comment_input.val();
  comment_input.focus();

  $.post(
    '{{ URL::to("/add-comments") }}' + '/' + ablum_id,   // URL
    JSON.stringify({ comment : comment }), // data
    function(data) {
      if (1 != data.status) {
        // 失败
        // $().alert('close'); // test TODO
        return;
      }
      comment_input.val('');
      this_ptr.reloadComments();
    },     // callback
    "json" // data type
  );
  return true;
 }

/**
 * @brief 隐藏照片墙
 */
PictureWall.prototype.hide = function () {
  this._element_parent_div.removeClass('picplayer-animate-end');
}

PictureWall.prototype.isHide = function () {
  return !this._element_parent_div.hasClass('picplayer-animate-end');
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
 * rate 数值越小，图片移动速度越快,
 * 建议最大设置为0.7，以保证翻页不会太慢
 */
PictureWall.prototype.next = function (rate) {
  // console.debug("next()");
  rate = (rate>0.7) ? 0.7 : rate; // 切换图片时，图片移动速度稍快些
  var right_index = null;
  if (null === (right_index = this.getNextPictureIndex())) {
    this.reset(rate);
    return;
  }
  this._picture_index = right_index;

  var pic_info = this._element_picture_info;
  var pic_list = this._element_picture_list;
  var left_img = pic_list.find('li.left_img');
  var middle_img = pic_list.find('li.middle_img');
  var right_img = pic_list.find('li.right_img');

  // 下一个 middle 的 translateX 的值即为本次动画中需要移动的距离
  // 所以根据 距离(distance) 及 滑动屏幕的速率(rate) ，就能计算出本次动画的时间
  var distance = getTranslateX(right_img);
  var time = rate * Math.abs(distance);
  left_img.css(img_animations(time));
  middle_img.css(img_animations(time));
  right_img.css(img_animations(time));

  var src_param = '?width=' + $(window).width() + '&height=' + $(window).height();
  if (!right_img.length) {
    // 如果没找到 right_img 标签
    var image_url = this._picture_array[right_index]+src_param;
    right_img = $("<li class='right_img' ><img src='img/ajax-loader.gif' ></li>")
    middle_img.after(right_img.css($.extend(right_img_pos(0),img_no_animations())));

    $('<img/>').attr('src', image_url).on('load', function() {
      right_img.find('img').attr('src', image_url);
    });
  }

  // < < < 向左移动
  left_img.remove();
  middle_img.removeClass('middle_img').css($.extend(left_img_pos(0),img_animations(time)))
    .addClass('left_img');
  right_img.removeClass('right_img').css($.extend(middle_img_pos(0),img_animations(time)))
    .addClass('middle_img');

  if (null !== (right_index = this.getNextPictureIndex())) {
    var image_url = this._picture_array[right_index]+src_param;
    var new_right_img = $("<li class='right_img' ><img src='img/ajax-loader.gif' ></li>")
    right_img.after(new_right_img.css($.extend(right_img_pos(0),img_no_animations())));
    $('<img/>').attr('src', image_url).on('load', function() {
      new_right_img.find('img').attr('src', image_url);
    });
  }

  pic_info.find('.name').html(this._picture_info_array[this._picture_index].name);
  pic_info.find('.caption').html(this._picture_info_array[this._picture_index].caption);
  pic_info.find('.page > .current').html(this._picture_index+1);

  this.showOrHideLastNextButton();
}

PictureWall.prototype.last = function (rate) {
  // console.debug("last()");
  rate = (rate>0.7) ? 0.7 : rate; // 切换图片时，图片移动速度稍快些
  var left_index = null;
  if (null === (left_index = this.getLastPictureIndex())) {
    this.reset(rate);
    return;
  }
  this._picture_index = left_index;

  var pic_info = this._element_picture_info;
  var pic_list = this._element_picture_list;
  var left_img = pic_list.find('li.left_img');
  var middle_img = pic_list.find('li.middle_img');
  var right_img = pic_list.find('li.right_img');

  var distance = getTranslateX(left_img);
  var time = rate * Math.abs(distance);
  left_img.css(img_animations(time));
  middle_img.css(img_animations(time));
  right_img.css(img_animations(time));

  var src_param = '?width=' + $(window).width() + '&height=' + $(window).height();
  if (!left_img.length) {
    // 如果没找到 left_img 标签
    var image_url = this._picture_array[left_index]+src_param;
    left_img = $("<li class='left_img' ><img src='img/ajax-loader.gif' ></li>")
    middle_img.before(left_img.css($.extend(left_img_pos(0),img_no_animations())));
    $('<img/>').attr('src', image_url).on('load', function() {
      left_img.find('img').attr('src', image_url);
    });
  }

  // > > > 向右移动
  if (null !== (left_index = this.getLastPictureIndex())) {
    var image_url = this._picture_array[left_index]+src_param;
    var new_left_img = $("<li class='left_img' ><img src='img/ajax-loader.gif' ></li>")
    left_img.before(new_left_img.css($.extend(left_img_pos(0),img_no_animations())));
    $('<img/>').attr('src', image_url).on('load', function() {
      new_left_img.find('img').attr('src', image_url);
    });
  }
  left_img.removeClass('left_img').css($.extend(middle_img_pos(0),img_animations(time)))
    .addClass('middle_img');
  middle_img.removeClass('middle_img').css($.extend(right_img_pos(0),img_animations(time)))
    .addClass('right_img');
  right_img.remove();

  pic_info.find('.name').html(this._picture_info_array[this._picture_index].name);
  pic_info.find('.caption').html(this._picture_info_array[this._picture_index].caption);
  pic_info.find('.page > .current').html(this._picture_index+1);

  this.showOrHideLastNextButton();
}

PictureWall.prototype.reset = function (rate) {
  // console.debug("reset()");
  rate = (rate<0.2) ? 0.2 : rate; // 还原位置时，图片移动速度稍慢些
  var pic_list = this._element_picture_list;
  var left_img = pic_list.find('li.left_img');
  var middle_img = pic_list.find('li.middle_img');
  var right_img = pic_list.find('li.right_img');
  
  var distance = getTranslateX(middle_img);
  var time = rate * Math.abs(distance);

  // 提前设置一遍 transition ，才能在 windows phone 中的IE 看到动画效果
  left_img.css(img_animations(time));
  middle_img.css(img_animations(time));
  right_img.css(img_animations(time));

  // 图像归位
  var offset = 0;
  left_img.css($.extend(left_img_pos(offset),img_animations(time)));
  middle_img.css($.extend(middle_img_pos(offset),img_animations(time)));
  right_img.css($.extend(right_img_pos(offset),img_animations(time)));

}

PictureWall.prototype.isShowPlayerControl = function () {
  if ( this._element_player_control_last.hasClass('hidden_element')
    || this._element_player_control_next.hasClass('hidden_element')
    || this._element_player_control_close.hasClass('hidden_element')
    || this._element_picture_info.hasClass('hidden_element')
  ) {
    return false;
  }
  return true;
}

/**
 * @brief 显示、隐藏 控制按钮
 */
PictureWall.prototype.showPlayerControl = function () {
  // console.debug("showPlayerControl()");

  this._element_player_control_last
    .css('pointer-events', 'auto')
    .removeClass('hidden_element');
  this._element_player_control_next
    .css('pointer-events', 'auto')
    .removeClass('hidden_element');
  this._element_player_control_close
    .css('pointer-events', 'auto')
    .removeClass('hidden_element');
  this._element_picture_info
    .css('pointer-events', 'auto')
    .removeClass('hidden_element');
  this._element_ablum_header
    .css('pointer-events', 'auto')
    .removeClass('hidden_element');

  // 使用定时器，将左右按钮隐藏
  this._timer_ptr.stop();
  this._timer_ptr.play();
}
PictureWall.prototype.hidePlayerControl = function () {
  // console.debug("hidePlayerControl()");

  this._element_player_control_last.addClass('hidden_element').css('pointer-events', 'none');
  this._element_player_control_next.addClass('hidden_element').css('pointer-events', 'none');
  this._element_player_control_close.addClass('hidden_element').css('pointer-events', 'none');
  this._element_picture_info.addClass('hidden_element').css('pointer-events', 'none');
  this._element_ablum_header.addClass('hidden_element').css('pointer-events', 'none');
  this._timer_ptr.stop();
}

PictureWall.prototype.showOrHideLastNextButton = function () {
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

        // only trigger move left/right event , 因为目前不关注上下移动事件 
        dir = (distX < 0)? 'left' : 'right';
        handletouch(e, dir, 'move', swipeType, distX); // fire callback function with params dir="left|right", phase="move", swipetype="none" etc

        // if (Math.abs(distX) > Math.abs(distY)){ // if distance traveled horizontally is greater than vertically, consider this a horizontal movement
        //     dir = (distX < 0)? 'left' : 'right';
        //     handletouch(e, dir, 'move', swipeType, distX); // fire callback function with params dir="left|right", phase="move", swipetype="none" etc
        // }
        // else{ // else consider this a vertical movement
        //     dir = (distY < 0)? 'up' : 'down';
        //     handletouch(e, dir, 'move', swipeType, distY); // fire callback function with params dir="up|down", phase="move", swipetype="none" etc
        // }
        e.preventDefault(); // prevent scrolling when inside DIV
        // touchmove 事件中必须执行 preventDefault() , 否则ipad safari 中会导致图像失去 transition 中的动画效果。具体原因可能是此事件引发 去除动画效果的代码执行。
      })
      .on('touchend', function(e){
        e = e.originalEvent;
        var touchobj = e.changedTouches[0];
        elapsedTime = new Date().getTime() - startTime; // get time elapsed
        var left_right_speed = Math.abs(distX/elapsedTime);
        var up_down_speed = Math.abs(distY/elapsedTime);
        // console.log("left/right speed=" + left_right_speed);
        // console.log("up/down speed=" + up_down_speed);
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

<script src="{{ asset('js/jquery.appear.js') }}"></script>
@stop
