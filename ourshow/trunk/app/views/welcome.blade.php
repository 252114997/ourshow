@extends('tpl.layout')

@section('css')

  <link rel="stylesheet" type="text/css" href="{{ asset('css/cover.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/timeline.css') }}">

@stop


@section('body')
    <div class="site-background">
    </div>

    <div class="site-wrapper" >

      <div class="site-wrapper-inner">

        <div class="cover-container" >

          <div class="inner cover" >
            <h1 class="cover-heading">见证我们爱情</h1>
            <p class="lead">欢迎xxxx参加新郎新娘的婚礼</p>
            <p class="lead">时间：2015-05-03 11:00</p>
            <p class="lead">地址：北京市长安街北京饭店 <a href="http://todo.com">查看地图</a></p>
            <div >
              <a href="#timeline_continer" >
                <span class="glyphicon glyphicon-menu-down" ></span>
              </a>
            </div>
          </div>

        </div>

      </div>

    </div>

    <div id="timeline_continer" class="container">
        <div class="page-header">
            <h1 id="timeline">时光机</h1>
        </div>
        <ul class="timeline">

            @foreach ($param as $index => $ablum) 
              <li id="ablum_{{ $ablum['id'] }}" class="{{ ($index%2) ? 'timeline-inverted' : '' }}">
                
                <div class="ourshow-likebuttion timeline-badge {{ $ablum['likeit'] ? 'danger' : 'primary' }}" 
                    data-toggle="tooltip" data-placement="top" 
                    title="{{ count($ablum['likes']) }} 人表示很赞"
                    onclick='likeAblum(this, {{ $ablum["id"] }});'
                >
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
                            <input type="text" class="form-control input-sm" placeholder="赞..." name='comment' />
                            <span class="input-group-btn" onclick="addComment(this, {{ $ablum['id'] }} );">     
                                <a class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-comment"></span>评论</a>
                            </span>
                        </div>

                        <hr/>
                        <ul class="ourshow-commmentlist"
                          data-options="url:'{{ URL::to('/get-comments').'/'.$ablum['id'] }}'" 
                        />

                      </div>
                  </div>
                </div>
              </li>
            @endforeach

        </ul>
    </div>

    <style type="text/css">

      div.usercomment-sibling {
        position: relative;
      }

      div.usercomment {
        position: relative;
        top: 20px;
        left: 0px;
        width: 100%;
      }

      div.caption-body {
        position: relative;
      }
      div.caption-body p.caption {
        position: absolute;
        bottom: 0;
        left: 0;
        margin: 0;
        color: #fff;
        font-size: 13px;
        line-height: 16px;
        font-style: italic;
        padding: 5px;
        background: rgba(0,0,0,0.5);
        width: 100%;
      }

    </style>
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

function likeAblum (button, ablum_id) {
  button = $(button);
  var likeit = button.hasClass('danger');
  $.post(
    '{{ URL::to("/switch-like") }}' + '/' + ablum_id + '/' + (likeit ? '0' : '1'),   // URL
    JSON.stringify({}), // data
    function(data) {
      if (1 != data.status) {
        // 失败
        $().alert('close'); // test TODO
        return;
      }
      if (likeit != data.data.likeit) {
        if (likeit) {
          button.removeClass('danger');
          button.addClass('primary');
        }
        else {
          button.removeClass('primary');
          button.addClass('danger');
        }
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
  $.post(
    '{{ URL::to("/add-comments") }}' + '/' + ablum_id,   // URL
    JSON.stringify({ comment : comment }), // data
    function(data) {
      if (1 != data.status) {
        // 失败
        $().alert('close'); // test TODO
        return;
      }
      var commmentlist = $(button).closest('div.text-center').children('ul.ourshow-commmentlist');
      reloadComment($(commmentlist));
    },     // callback
    "json" // data type
  );
  return true;
}

function reloadComment(commmentlist) {
  commmentlist.html('');
  var data_options = eval('({' + commmentlist.attr('data-options') + '})');
  $.get(
    data_options.url,   // URL
    function(data) {
      if (1 != data.status) {
        // fail
        return;
      }
      // success
      // elem.append('<hr/>');
      for (var index in data.data) {
        var comment = data.data[index];
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
    }// callback
  );
}
</script>

<script type="text/javascript">
</script>
@stop
