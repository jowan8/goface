<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="format-detection" content="telephone=no" /><!--不识别手机号-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><!--屏幕宽高限定 -->
    <meta name="apple-mobile-web-app-capable" content="yes" /><!--开启web app的支持-->
    <meta name="apple-mobile-web-app-status-bar-style" content="black" /><!--顶部栏颜色  default（白色）black（黑色）black-translucent（灰色半透明）-->
    <link rel="apple-touch-icon-precomposed" href="{{asset('images/ico.ico')}}" /><!--苹果 ico配置-->
    <link rel="apple-touch-startup-image" href="{{asset('images/ico.ico')}}" /><!--苹果 启动时的照片-->
    <link rel="icon" href={{asset('images/ico.ico')}} type="images/x-ico" />
    <link rel="stylesheet" href="{{asset('css/bootstrap-3.3.7.min.css')}}">
    <script src="{{asset('js/jquery-2.1.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-3.3.7.min.js')}}"></script>

    <title>{{$title}}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body{
            min-width: 320px;
            font-family: '微软雅黑', '宋体', sans-serif;
            height: 100%;
        }
        .none{
            text-align: center;
            margin-top: 30%;
        }
        .none p{
            text-align: center;
            color: #999999;
        }
    </style>

</head>
<body>
<div class="container">


        @foreach($chapter as $chap)
            <dl class="dl-horizontal">
                <h4><dt>{{$chap->chapter_title}}</dt></h4>
                <dd>{{$chap->chapter_desc}}</dd>
                @foreach($chap->sub_chapter as $cp)
                    <dt>&ensp;&ensp; {{$cp->chapter_title}}&ensp;<span class="text-muted small">({{$cp->video_time}})</span></dt>
                @endforeach
            </dl>
        @endforeach


</div>
</body>
<script>
    function see_detail(obj) {
        var url = $(obj).data('url');
        var aid = $(obj).data('id');
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{'aid':aid},
            url: "/add_views",
            dataType: "json",
            success: function (data) {
                console.log('success');
            }
        });
        window.location.href = url;
    }
</script>
</html>