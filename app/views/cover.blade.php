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
          </div>
<!-- 
          <div class="mastfoot">
            <div class="inner">
              <p>by <a href="http://ws.ws">ws and tt</a>.</p>
            </div>
          </div>
 -->
        </div>

        <div class="footer_button">
          <a href="#timeline_continer" >
            <span class="glyphicon glyphicon-menu-down" ></span>
          </a>
        </div>
        <style type="text/css">
        .footer_button {
          position: absolute;
          bottom: 0px;
          display: block;
          margin-left: auto;
          margin-right: auto;
        }
        </style>
      </div>

    </div>

    <div id="timeline_continer" class="container">
        <div class="page-header">
            <h1 id="timeline">时光机</h1>
        </div>
        <ul class="timeline">
            <li>
              <div class="timeline-badge danger"><i class="glyphicon glyphicon-heart"></i></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">结婚</h4>
                  <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> 11 天后 在 天津</small></p>
                </div>
                <div class="timeline-body caption-body usercomment-sibling">
                  <img src="./img/timeline/Hydrangeas.jpg" class="img-rounded" style="width:100%;">
                  <p class="caption">这是一个幸福的时刻</p>
                </div>
                <div class="container usercomment">
                    <div class="text-center">
                      <div class="input-group">
                          <input type="text" class="form-control input-sm" placeholder="在这里填写赞美我们的文字..." />
                          <span class="input-group-btn" onclick="addComment()">     
                              <a href="#" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-comment"></span>评论</a>
                          </span>
                      </div>
                      <hr />
                      <ul class="list-unstyled text-left">
                          <strong class="pull-left">James</strong>
                          <small class="pull-right text-muted">
                             <span class="glyphicon glyphicon-time"></span>7 分钟 之前</small>
                          </br>
                          <li >帅呆了，帅呆了，帅呆了，帅呆了，帅呆了，帅呆了，帅呆了，</li>
                          </br>
                          
                          <strong class="pull-left">Taylor</strong>
                          <small class="pull-right text-muted">
                             <span class="glyphicon glyphicon-time"></span>14 分钟 之前</small>
                          </br>
                          <li >酷毕了帅呆了，帅呆了，帅呆了，帅呆了，帅呆了，帅呆了，</li>
                          </br>
                          
                          <strong class="pull-left">Taylor</strong>
                          <small class="pull-right text-muted">
                             <span class="glyphicon glyphicon-time"></span>14 分钟 之前</small>
                          </br>
                          <li >酷毕了帅呆了，帅呆了，帅呆了，帅呆了，帅呆了，帅呆了，</li>
                          </br>
                          
                      </ul>
                    </div>
                </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge danger"><i class="glyphicon glyphicon-heart"></i></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">有欢笑</h4>
                </div>
                <div class="timeline-body">
                  <img src="./img/timeline/Koala.jpg" class="img-circle" style="width:100%;">
                </div>
              </div>
            </li>
            <li>
              <div class="timeline-badge danger"><i class="glyphicon glyphicon-heart"></i></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">也有哭泣</h4>
                </div>
                <div class="timeline-body">
                  <img src="./img/timeline/Penguins.jpg" class="img-circle" style="width:100%;">
                </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge success"><i class="glyphicon glyphicon-thumbs-up"></i></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">但，幸福一直在</h4>
                </div>
                <div class="timeline-body">
                  <img src="./img/timeline/Lighthouse.jpg" class="img-rounded" style="width:100%;">
                </div>
              </div>
            </li>
            <li>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">有相聚</h4>
                </div>
                <div class="timeline-body">
                  <img src="./img/timeline/Hydrangeas.jpg" class="img-circle" style="width:100%;">
                </div>
              </div>
            </li>
            <li>
              <div class="timeline-badge info"><i class="glyphicon glyphicon-floppy-disk"></i></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">也有分离</h4>
                </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.</p>
                  <hr>
                  <div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                      <i class="glyphicon glyphicon-cog"></i> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Action</a></li>
                      <li><a href="#">Another action</a></li>
                      <li><a href="#">Something else here</a></li>
                      <li class="divider"></li>
                      <li><a href="#">Separated link</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </li>
            <li>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Mussum ipsum cacilds</h4>
                </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.</p>
                </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge success"><i class="glyphicon glyphicon-thumbs-up"></i></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Mussum ipsum cacilds</h4>
                </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.</p>
                </div>
              </div>
            </li>
        </ul>
    </div>

    <style type="text/css">

    .site-background {
      position: fixed;
      top: 0;
      left: 0;
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
      -webkit-filter: blur(1px); /* 模糊特效 */
    }

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
</script>
@stop
