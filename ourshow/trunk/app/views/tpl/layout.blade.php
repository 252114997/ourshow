<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="shortcut icon" href="{{ asset('logo.ico') }}">

    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->

	@yield('css')

    <title>Carousel Template for Bootstrap</title>
</head>
<body >

	@yield('body')

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.js') }}"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
	<script src="{{ asset('js/vendor/holder.js') }}"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="{{ asset('js/ie10-viewport-bug-workaround.js') }}"></script>

	<script src="{{ asset('js/jquery.timer.js') }}"></script>

	@yield('js')

</body>
</html>
