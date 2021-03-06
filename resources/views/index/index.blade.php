<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="title" content="一堆BUG网" />
    <meta name="keywords" content="一堆bug网 程序员交流学习进步的平台  " />
    <meta name="description" content="ydbug(一堆bug网)是由个人开发,记录学习PHP、NGINX、HTML5、JS、LINUX、GOLANG等当前主流语言的交流平台,主要文章来自于三方技术网站" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="format-detection" content="telephone=no" /><!--不识别手机号-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /><!--屏幕宽高限定 -->
    <meta name="apple-mobile-web-app-capable" content="yes" /><!--开启web app的支持-->
    <meta name="apple-mobile-web-app-status-bar-style" content="black" /><!--顶部栏颜色  default（白色）black（黑色）black-translucent（灰色半透明）-->
    <link rel="apple-touch-icon-precomposed" href="{{asset('images/ico.ico')}}" /><!--苹果 ico配置-->
    <link rel="apple-touch-startup-image" href="{{asset('images/ico.ico')}}" /><!--苹果 启动时的照片-->
    <link rel="icon" href={{asset('images/ico.ico')}} type="images/x-ico" />
    <link rel="stylesheet" href="{{asset('css/swiper-5.4.1.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-3.3.7.min.css')}}">
    <script src="{{asset('js/swiper-5.4.1.min.js')}}"> </script>
    <script src="{{asset('js/jquery-2.1.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-3.3.7.min.js')}}"></script>
    <script src="{{asset('js/echarts.js')}}"></script>

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
            height: 40rem;
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
        .analyze{
            margin: 2rem 0;
            clear: both;
        }
        .tab-pane{
            height: 40rem;
            width: 33.5rem;
        }
    </style>

    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?86f264319519d6bf86b0c1199994db2c";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head>
<body>
<div class="container">

    <div id="banner">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach($banners as $banner)
                <div class="swiper-slide"> <a href="{{$banner->jump_url}}"><img src="{{$banner->img_url}}" > </a></div>
                @endforeach
            </div>
            <!-- 如果需要分页器 -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <div class="icon-cl">
        <ul class="icon">
        @foreach($work_types as $type)
                <li> <a href="{{url('/lists?type_id='.$type->id)}}"><img src="{{$type->icon}}"> {{$type->name}}</a></li>
        @endforeach
        </ul>
    </div>

    <div class="learning">
        <table class="table">
            <caption> 热门文章</caption>
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
                    <td> <a class="detail" data-url="{{$work->data_url}}" data-id="{{$work->id}}" target="_blank" onclick="see_detail(this)" > {{$work->work_name}} </a> </td>
                    <td> @if($work->view_times>1000) 999+ @else{{$work->view_times}}@endif </td>
                    <td> {{date('Y-m-d',strtotime($work->created_at))}} </td>
                </tr>
            @endforeach
            </tbody>
            <tr>
                <td></td>
                <td><a class="more" href="{{url('/lists')}}" >查看更多</a></td>
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

    <div class='analyze'>
        <caption> 近期访客</caption>

        <div class="recent-visit" >
        </div>

        <div class='tabbable tabs-left'>
            <ul class='nav nav-tabs'>
                <li class="active" data-type_id="1" onclick="analyze(1)"><a href='#tab-pane-1'data-toggle='tab'>本日数据</a></li>
                <li class="" data-type_id="2"  onclick="analyze(2)"><a href='#tab-pane-2' data-toggle='tab'>本周数据</a></li>
                <li class="" data-type_id="3" onclick="analyze(3)"><a href='#tab-pane-3' data-toggle='tab'>上月数据</a></li>
            </ul>
            <div class='tab-content'>
                <div  class='tab-pane active' id='tab-pane-1'></div>
                <div  class='tab-pane' id='tab-pane-2'></div>
                <div  class='tab-pane' id='tab-pane-3'></div>
            </div>
        </div>

    </div>
</div>


</body>
<script>
    //加载banner
    var mySwiper = new Swiper ('.swiper-container', {
        autoplay: {
            delay: 3000,
            stopOnLastSlide: false,
            disableOnInteraction: true,
        },
        pagination: {
            el: '.swiper-pagination',
        },
    });

    function visit(){
        $.ajax({
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/recent_visit",
            dataType: "json",
            success: function (return_obj) {
                if(return_obj.code == 200){
                    var html = '';
                    $.each(return_obj.data.visit,function(index,value){
                        html+= '<marquee direction="up" scrolldelay="1500" >来自'+value.client_address +'的网友['+ value.client_ip +']'+ value.created_at+'到访</marquee>';
                    });
                    $('.recent-visit').append(html);
                }
            }
        });
    }


    function analyze(obj){
        var type_id = obj;
        var myChart = echarts.init(document.getElementById('tab-pane-'+type_id));
        console.log(document.getElementById('tab-pane-'+type_id));
        myChart.showLoading();
        $.ajax({
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{'type':type_id},
            url: "/analyze",
            dataType: "json",
            success: function (return_obj) {
                myChart.hideLoading();
                option = {
                    title: {
                        text: ''
                    },
                    color:  [return_obj.data.analyze.color],
                    tooltip: {},
                    legend: {
                        //data:['销量']
                    },
                    xAxis: {
                        data: return_obj.data.analyze.time
                    },
                    yAxis: {},
                    series: [{
                        name: '访问量(次)',
                        type: return_obj.data.analyze.type,
                        data: return_obj.data.analyze.data
                    }]
                };
                myChart.setOption(option);
            }
        });
    }



    setTimeout("visit()",5000);
    //visit();


    analyze(1);

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