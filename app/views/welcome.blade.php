@extends('tpl.layout')

@section('css')

  <link rel="stylesheet" type="text/css" href="{{ asset('css/cover.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/timeline.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/comment.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bounce.css') }}">

@stop


@section('body')

    <div class="site-background">
    </div>

    <div class="cover-continer-my" >

          <div class="cover-inner-my" >
            <h1 class="cover-heading">见证我们爱情</h1>
            <p class="lead">欢迎xxxx参加新郎新娘的婚礼</p>
            <p class="lead">时间：2015-05-03 11:00</p>
            <p class="lead">地址：北京市长安街北京饭店 <a href="http://todo.com">查看地图</a></p>

            <a href="#timeline_continer" class="icon-button bounce" title="下拉显示更多">
              <span class="glyphicon glyphicon-triangle-bottom" ></span>
            </a>
          </div>

    </div>

    <div id="timeline_continer" class="container">
        <div class="page-header">
            <h1 id="timeline">时光机</h1>
        </div>
        <ul class="timeline">

            @foreach ($param as $index => $ablum) 
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

                  <div class="timeline-body">
                    <img src="{{ $ablum['picture_id']['path'] }}" style="width:100%;"
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

    <div class="mastfoot mastfoot-ext">
        <p>由<a > WS & TT </a>提供强劲技术支持</p>
    </div>

@stop


@section('js')

<script type="text/javascript">

$(function(){
  // 相册评论列表
  $('ul.ourshow-commmentlist').each(function(index, elem){
    reloadComment($(elem));
  });

  // 工具栏提示
  $('[data-toggle="tooltip"]').tooltip();
});

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

      console.debug('page_number=' + page_number);
      console.debug('page_size=' + page_size);
      console.debug('page_sum=' + page_sum);
      console.debug('data.count=' + data.data.count);
      console.debug(' ');

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
</script>

<script type="text/javascript">

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
