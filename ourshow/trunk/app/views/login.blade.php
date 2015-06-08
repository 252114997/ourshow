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
                  @if (isset($error_info))
                    <p class="lead text-danger">
                      {{ $error_info }}
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
                  <button type="submit" class="btn btn-primary">登录</button>
                </div>
              </div>

            </form>

          </div>

        </div>

      </div>

    </div>

    <style type="text/css">

    </style>

@stop


@section('js')

<script type="text/javascript">
</script>

@stop
