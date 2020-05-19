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
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>

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
    </style>

</head>
<body>
<div class="container">
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
    </table>
</div>


</body>
</html>