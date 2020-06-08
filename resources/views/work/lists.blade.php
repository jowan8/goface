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
    @if(isset($works[0]->id))
    <table class="table">
        <caption>
            <span>最新文章</span>
            <span>
                <a href="{{asset('/add_work')}}" style="float: right;display: block">我要添加</a>
            </span>
        </caption>

        <thead>
        <tr>
            <th> 文章名  </th>
            <th> 所属类别  </th>
            <th> 上架时间 </th>
        </tr>
        </thead>
        <tbody>
            @foreach($works as $work)
                <tr>
                    <td> <a class="detail" data-url="{{$work->data_url}}" data-id="{{$work->id}}" target="_blank" onclick="see_detail(this)" > {{$work->work_name}} </a> </td>
                    <td> {{$work->name}} </td>
                    <td> {{ date('Y-m-d',strtotime($work->created_at))}} </td>
                </tr>
            @endforeach


        </tbody>
    </table>
    @else
        <div class="none" >
            <img src="https://img.china-dfs.com/jowan/tishi/none.jpg">
            <p>旺旺旺</p>
            <p>暂时没有此类文章的数据哦!</p>
            <p>点此<a href="{{url('/add_work')}}">添加</a>吧</p>
        </div>

    @endif
        <dd><a href="{{url('/course_detail')}}">课程</a></dd>
        {{$works -> links()}}

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