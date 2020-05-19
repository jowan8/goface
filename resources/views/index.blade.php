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

    <title>{{$title}}</title>

    <style>
        body {
            min-width: 320px;
        }
    </style>

</head>
<body>

    学习任务:
    <table>
        <thead>
        <tr>
            <th> id</th>
            <th> name  </th>
            <th> adddress </th>
            <th> created_at </th>
        </tr>
        </thead>
        <tbody>
        @foreach($works as $work)
            <tr>
                <td> {{$work->id}} </td>
                <td> {{$work->work_name}} </td>
                <td> <a href="{{$work->data_url}}" > {{$work->work_name}} </a> </td>
                <td> {{$work->created_at}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a href="{{url('jumpTo')}}">查看特效</a>
</body>
</html>