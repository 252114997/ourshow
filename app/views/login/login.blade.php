<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>登录</title>
  <link rel="stylesheet" href="{{ asset('css/default/login.css') }}">
  <!--[if lt IE 9]><script src="{{ asset('js/html5shiv.min.js') }}"></script><![endif]-->
  <script type="text/javascript">
    function autofocus() {
     document.getElementById("username").focus();
    }
  </script>  
</head>
<body onload="autofocus();">
  <section class="container">
    <div class="login">
      <h1>{{ $product }}</h1>
      <form method="post" action="{{ URL::to('/login') }}">
        @if (Session::has('login_error_info'))
        <p class="login-error">
          {{ Session::get('login_error_info') }}
        </p>
        @endif
        <p><input type="text" id="username" name="username" placeholder="账号" value="{{ Input::old('username') }}"></p>
        <p><input type="password" id="password" name="password" placeholder="密码"></p>
        <p class="remember_me">
          <label>
            <input type="checkbox" name="remember_me" id="remember_me">
            记住密码
          </label>
        </p>
        <p class="submit">
          <input type="submit" name="commit" value="登录">
        </p>
        <p class="submit">
          <a href="{{ URL::to('/password_remind') }}" target="_blank">忘记密码</a>
        </p>
      </form>
    </div>

    <div class="login-help">
      <p>了解更多惠尔顿安全解决方案？点击<a href="http://www.wholeton.com" target="_blank">这里</a>.</p>
    </div>
  </section>
</body>
</html>
