<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no" /><!--不识别手机号-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><!--屏幕宽高限定 -->
    <meta name="apple-mobile-web-app-capable" content="yes" /><!--开启web app的支持-->
    <meta name="apple-mobile-web-app-status-bar-style" content="black" /><!--顶部栏颜色  default（白色）black（黑色）black-translucent（灰色半透明）-->
    <link rel="apple-touch-icon-precomposed" href="{{asset('images/ico.ico')}}" /><!--苹果 ico配置-->
    <link rel="apple-touch-startup-image" href="{{asset('images/ico.ico')}}" /><!--苹果 启动时的照片-->
    <link rel="icon" href={{asset('images/ico.ico')}} type="images/x-ico" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.min.css">
    <script src="https://unpkg.com/swiper/js/swiper.min.js"> </script>
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

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

        #banner{
            margin-top: 1rem;
            height: 10%;
        }
        .swiper-slide img{
            width: 100%;
        }

        .icon-cl{
            height: 15%;
        }
        .icon{
            list-style: none;
            height: 15rem;
            background: #f9f9f9;
            padding: 0;
            margin: 0;
        }
        .icon li{
            float: left;
            width: 24%;
            padding: 0.5rem 1rem;
            text-align: center;
        }
        .icon li img{
            width: 100%;
        }


        .learning{
            background: #f5f8fa;
            height: 30%;
        }

        .shows-cl{
            height: 30%;
        }
        .shows-cl span{
            color: #777777;
        }

        .shows{
            list-style: none;
            background: #f9f9f9;
            padding: 0;
            margin: 0;
            height: 39rem;
        }
        .shows li{
            padding: 0.5rem;
            float: left;
            width: 50%;
            text-align: center;
        }
        .shows li img{
            width: 100%;
        }
    </style>

</head>
<body>
<div class="container">

    <div id="banner">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide"> <img src="http://bpic.588ku.com/back_pic/02/51/25/72578107a0e8f67.jpg" > </div>
                <div class="swiper-slide"> <img src="http://bpic.588ku.com/back_pic/02/51/25/72578107a0e8f67.jpg" > </div>
                <div class="swiper-slide"> <img src="http://bpic.588ku.com/back_pic/02/51/25/72578107a0e8f67.jpg" > </div>
            </div>
            <!-- 如果需要分页器 -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <div class="icon-cl">
        <ul class="icon">
        @foreach($work_types as $type)
                <li> <img src="{{$type->icon}}"> {{$type->name}}</li>
        @endforeach
        </ul>
    </div>

    <div class="learning">
        <table class="table">
            <caption> 学习指南</caption>
            <thead>
            <tr>
                <th> 文章名  </th>
                <th> 浏览数  </th>
                <th> 添加时间 </th>
            </tr>
            </thead>
            <tbody>
            @foreach($works as $work)
                <tr>
                    <td> <a href="{{$work->data_url}}" target="_blank" > {{$work->work_name}} </a> </td>
                    <td> @if($work->view_times>100) 99+ @else{{$work->view_times}}@endif </td>
                    <td> {{$work->created_at}} </td>
                </tr>
            @endforeach
            </tbody>
            <tr>
                <td></td>
                <td><a class="more">查看更多</a></td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="shows-cl">
        <span>炫彩动效</span>
        <ul class="shows">
           @foreach($shows as $show)
                <li>
                    <a href="{{url('/jumpTo?style='.$show->id)}}">
                        <img src="{{$show->img}}">
                        <p>{{$show->name}}</p>
                    </a>
                </li>
            @endforeach

        </ul>
    </div>

</div>


</body>
<script>
    var mySwiper = new Swiper ('.swiper-container', {
        autoplay: {
            delay: 3000,
            stopOnLastSlide: false,
            disableOnInteraction: true,
        },
        pagination: {
            el: '.swiper-pagination',
        },
    })
</script>
</html>