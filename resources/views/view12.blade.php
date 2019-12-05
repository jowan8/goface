<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" href={{asset('images/ico.ico')}} type="images/x-ico" />
    <title>{{$title}}</title>
    <style>
        body,
        .tracking-section {
            background-color: #263238;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100vw;
            margin: 0;
        }
        body:hover,
        .tracking-section:hover {
            cursor: url({{asset('images/20191205/meat.cur')}})/*tpa=http://demo.jb51.net/js/2019/html5-trex-teased-by-meat-codes/images/meat.cur*/, auto;
        }
        .trex {
            position: relative;
        }
        .trex .trex-arm-front,
        .trex .trex-arm-back {
            position: absolute;
            transform-origin: 80% 20%;
        }
        .trex .trex-arm-back {
            top: 110px;
            left: 70px;
        }
        .trex .trex-arm-front {
            top: 120px;
            left: 80px;
        }
        .trex .trex-body .trex-eye {
            position: relative;
            top: 39px;
            left: 60px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e6e6e6;
        }
        .trex .trex-body .trex-eye:after {
            position: absolute;
            content: "";
            width: 15px;
            height: 15px;
            top: 5px;
            left: 1px;
            border-radius: 50%;
            background-color: #4d3019;
        }
    </style>
</head>
<body>
<section class="tracking-section">
    <div class="trex">
        <div class="trex-arm-back">
            <img width="60" src="{{asset('images/20191205/trex-arm.png')}}" />
        </div>
        <div class="trex-body">
            <div class="trex-eye"></div>
            <img width="300" src="{{asset('images/20191205/trex-body.png')}}" />
        </div>
        <div class="trex-arm-front">
            <img width="60" src="{{asset('images/20191205/trex-arm3.png')}}" />
        </div>
    </div>
</section>
<script src="{{asset('js/jquery-1.11.1.min.js')}}"></script>
<script>
    function calculateRotation(element, offset) {
        var x = element.offset().left + element.width() / 2;
        var y = element.offset().top + element.height() / 2;
        var rad = Math.atan2(event.pageX - x, event.pageY - y);
        var rot = rad * (180 / Math.PI) * -1 + (230 + offset);
        element.css({
            "-webkit-transform": "rotate(" + rot + "deg)",
            "-moz-transform": "rotate(" + rot + "deg)",
            "-ms-transform": "rotate(" + rot + "deg)",
            transform: "rotate(" + rot + "deg)"
        });
    }

    $(".tracking-section").mousemove(function(event) {
        var eye = $(".trex-eye");
        var armBack = $(".trex-arm-back");
        var armFront = $(".trex-arm-front");

        calculateRotation(eye, 0);
        calculateRotation(armBack, 0);
        calculateRotation(armFront, 15);
    });
</script>
</html>