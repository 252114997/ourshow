@extends('tpl.layout')

@section('css')

  <link rel="stylesheet" type="text/css" href="{{ asset('css/cover.css') }}">

@stop


@section('body')

    <div class="site-wrapper" style="background-image: url({{ asset('img/hand.jpg') }})">

      <div class="site-wrapper-inner">

        <div class="cover-container" >

          <div class="inner cover" >
            <h1 class="cover-heading">见证我们爱情</h1>
            <p class="lead">欢迎xxxx参加新郎新娘的婚礼</p>
            <p class="lead">时间：2015-05-03 11:00</p>
            <p class="lead">地址：北京市长安街北京饭店 <a href="http://todo.com">查看地图</a></p>
            <p>
              <a href="#" class="btn btn-default">播放</a>
            </p>
          </div>

          <div class="mastfoot">
            <div class="inner">
              <p>by <a href="http://ws.ws">ws and tt</a>.</p>
            </div>
          </div>

        </div>

      </div>

    </div>

@stop


@section('js')
<script type="text/javascript">
</script>
@stop
