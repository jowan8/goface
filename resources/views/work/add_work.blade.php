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
    <link rel="stylesheet" href={{asset('layer/theme/default/layer.css')}}>
    <script src="{{asset('js/jquery-2.1.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-3.3.7.min.js')}}"></script>
    <script src={{asset('layer/layer.js')}}></script>

    <title>添加文章</title>

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
        #add_work{
            margin-top: 10%;
        }
    </style>

</head>
<body>
<div class="container">
    <form class="form-horizontal" id="add_work" role="form" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="work_name" class="col-sm-2 control-label">文章名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="work_name" name="work_name" placeholder="请输入文章名称">
            </div>
        </div>
        <div class="form-group">
            <label for="data_url" class="col-sm-2 control-label">文章地址</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="data_url" name="data_url" placeholder="请输入文章地址">
            </div>
        </div>
        <div class="form-group">
            <label for="type_id" class="col-sm-2 control-label">所属类型</label>
            <div class="col-sm-10">

            <select class="form-control" name="type_id" id="type_id">
                @foreach($work_types as $type)
                    <option value="{{$type->id}}">{{$type->name}}</option>
                @endforeach
            </select>
            </div>
        </div>
        <button type="button" class="btn btn-block btn-info" onclick="check_data()" >提交</button>
    </form>
</div>
</body>
<script>
    function check_data() {
        var work_name = $('#work_name').val();
        var data_url = $('#data_url').val();
        var type_id = $("#type_id").val();
        if(work_name==''||work_name==null){
            layer.alert('文章名称必须填写',{icon: 2});
            //$('#work_name').addClass('alert alert-danger');
            return false;
        }

        if(data_url==''||data_url==null){
            layer.alert('文章地址必须填写',{icon: 2});

            //$('#data_url').addClass('alert alert-danger');
            return false;
        }
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{'work_name':work_name,'data_url':data_url,'type_id':type_id},
            url: "/add_work",
            dataType: "json",
            success: function () {
                layer.msg('添加成功',{icon: 1});
                window.location.href = '/lists'
            }
        });
       // window.location.href = url;

    }
</script>
</html>