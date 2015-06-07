@extends('tpl.layout')

@section('css')

  <link rel="stylesheet" type="text/css" href="{{ asset('css/cover.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/timeline.css') }}">

@stop


@section('body')

    <div class="site-wrapper site-background" >

      <div class="site-wrapper-inner">

        <div class="cover-container" >

          <div class="inner cover" >

            <form id="input_form" class="form-horizontal" 
              method="post" action='{{ URL::to("/login") }}'>
<!-- 
              <div class="form-group">
                <div class="col-sm-12">
                  <h4 class="text-center">输入邀请码才能浏览</h4>
                </div>
              </div>
 -->

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
                <label for="inputToken" class="col-sm-2 control-label">邀请码：</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="inputToken" name="token" placeholder="邀请码" 
                    value="">
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-2">
                  <input type="hidden" name="user_id" value="{{ Input::get('id', '') }}" />  
                  <button type="submit" class="btn btn-primary">登录</button>
                </div>
              </div>

            </form>

          </div>

        </div>

      </div>

    </div>

    <style type="text/css">

      .site-background {
        z-index: -1;
        background-image: url(../img/hand.jpg);
        background-repeat:no-repeat;
        width: 100%;
        height: 100%; /* For at least Firefox */
        min-height: 100%;
        -webkit-box-shadow: inset 0 0 100px rgba(0,0,0,.7);
                box-shadow: inset 0 0 100px rgba(0,0,0,.7);
        ;
        background-color: #333; /* 黑灰色的背景 */
        -webkit-filter: blur(0px); /* 模糊特效 */
      }
    </style>

@stop


@section('js')

<script type="text/javascript">
</script>

@stop
