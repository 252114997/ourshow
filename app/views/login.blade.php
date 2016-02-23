@extends('tpl.layout')

@section('css')

  <link rel="stylesheet" type="text/css" href="{{ asset('css/cover.css') }}">

@stop


@section('body')

    <div class="cover-continer" >

          <div class="cover-inner" >

          @if (isset($deny_info))

            <form class="form-horizontal" >
              <div class="form-group">
                <div class="col-sm-12">
                    <h2 class="text-primary">
                      {{ $deny_info }}
                    </h2>
                    <p class="text-muted">{{ $deny_user_id }}</p>
                </div>
              </div>
            </form>

          @else

            <form class="form-horizontal" method="post" action='{{ URL::to("/login") }}'>

              <div class="form-group">
                <div class="col-sm-12">
                  @if (Session::has('error_info'))
                    <p class="lead text-danger">
                      {{ Session::get('error_info') }}
                    </p>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label for="inputToken" class="col-xs-12 col-sm-2 control-label text-left">邀请码：</label>
                <div class="col-xs-12 col-sm-10">
                  <input type="text" class="form-control" id="inputToken" name="token" placeholder="邀请码" 
                    value="">
                </div>
              </div>

              <div class="form-group">
                <div class="col-xs-12 col-sm-offset-2 col-sm-2"> 
                  <button type="submit" class="pull-left btn btn-primary">登录</button>
                </div>
              </div>

            </form>

          @endif
          </div>

          <div class="footer-info">
            <p>由<a > WS & TT </a>提供强劲技术支持</p>
          </div>
    </div>

    <style type="text/css">
      .filter-blur {
        -webkit-filter: blur(10px); /* 模糊特效 */
      }
    </style>

@stop


@section('js')

<script type="text/javascript">
  $('#inputToken').focus();
</script>

@stop
