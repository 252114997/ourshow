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

  <link rel="stylesheet" type="text/css" href="{{ asset('css/ouershow.css') }}">

    <!-- Bootstrap core CSS -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->


    <title>Carousel Template for Bootstrap</title>
</head>
<body >

    <div class="picplayer boxline">
        <div class="picplayer-content boxline">
            <div class="picplayer-canvas">
                <div class="item" >
                  <img class="image" src="img/timeline/Hydrangeas.jpg" alt="Hydrangeas">
                  <div class="caption">
                    <h1>One more for good measure.</h1>
                    <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                    <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                  </div>
                </div>
            </div>
            <a class="picplayer-control-left boxline_red">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            
            <a class="picplayer-control-right boxline_red">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>

        </div>

        <div class="picplayer-process boxline">
          <!-- Indicators -->
          <ol class="boxline_blue">
            <li style="left:0%" position="0" data-slide-to="0" class="active">
              <a>
                <strong>2015-06-28</strong>
                <img src="logo.ico" alt="Hydrangeas">
              </a>
            </li>
            <li style="left:10%" position="1">
              <a>
                <strong>2015-07-28</strong>
                <img src="logo.ico" alt="Hydrangeas">
              </a>
            </li>
            <li style="left:30%" position="3">
              <a>
                <strong>2015-09-28</strong>
                <img src="logo.ico" alt="Hydrangeas">
              </a>
            </li>
            <li style="left:100%" position="50">
              <a>
                <strong>2015-09-29</strong>
                <img src="img/timeline/Hydrangeas.jpg" alt="Hydrangeas">
              </a>
            </li>
          </ol>
 
        </div>

    </div>

</body>
</html>
