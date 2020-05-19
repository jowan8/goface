<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" href={{asset('images/ico.ico')}} type="images/x-ico" />
    <title>{{$title}}</title>

    <style>
        body{
            margin: 0;
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