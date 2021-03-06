<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" href={{asset('images/ico.ico')}} type="images/x-ico" />
    <title>{{$title}}</title>
</head>
<body>
<canvas></canvas>
<script>
    var c = document.getElementsByTagName('canvas')[0];
    var b = document.body;
    var a = c.getContext('2d');
</script>
<script>
    /* http://github.com/cowboy/js1k-organ1k */

    with(Math) with(a) ! function(e, t, m) {
        var A, v, E, C, o, l, F = b.style,
            n = 32,
            H = 360,
            k = random,
            z = PI * 2,
            p = z / H,
            s = 0,
            d = 0,
            B = 0,
            j = 0,
            g = F.margin = 0,
            G = 2,
            r = 2,
            q = 3,
            D = 6,
            f = n,
            u = [],
            h = [],
            i = k(F.overflow = "hidden") * H,
            w = k(onmousemove = function(x) {
                f = 0;
                o = x.clientX - E;
                l = x.clientY - C
            }) < .5 ? 1 : -1;
        setInterval(function(J, x, I, y, K) {
            if (!(++s % n)) {
                while (j == ~~(y = k(K = k()) * 5));
                j = ~~y;
                y < .4 ? w = -w : y < 2 ? g++ : y < 3 ? B = K * 7 : y < 4 ? G = K * 8 + 1 : r = K * 3 + 2
            }
            A = c.width = innerWidth;
            v = c.height = innerHeight;
            J = min(E = A / 2, C = v / 2);
            x = J / H * 4;
            J -= 5 * x;
            if (++f > n) {
                if (B < 1) {
                    i -= G * w * 4;
                    o = sin(i * p) * J;
                    l = cos(i * p) * J
                } else {
                    i -= G * w * 2;
                    y = abs(o = sin(i * p) * J);
                    o = y * cos(K = atan2(0, o) + i * p / B);
                    l = y * sin(K)
                }
            }
            for (I = 0; I < n;) {
                y = u[I] = u[I] || {
                    x: 0,
                    y: 0
                };
                K = u[I - 1];
                y.x = I ? y.x + (K.x - y.x) / r : o;
                y.y = I++ ? y.y + (K.y - y.y) / r : l
            }
            for (I = 0; y = u[I * 4];) {
                h[d++ % H] = {
                    s: 1,
                    d: 1,
                    c: m[(g + I++) % 8],
                    x: y.x,
                    y: y.y
                }
            }
            fillRect(I = 0, 0, A, v);
            while (y = h[I++]) {
                K = y.s += y.d;
                y.d = K > D ? -1 : K < q ? 1 : y.d;
                fillStyle = "#" + y.c;
                font = K * x * e + "px Arial";
                textAlign = "center";
                textBaseline = "middle";
                fillText(t[I % t.length], E + y.x, C + y.y)
            }
        }, n)
    }(1.5, "\u2605\u2736\u2606\u2739\u2735\u2727\u2738", "f001a001700140010f010a010701040".split(1))
</script>
</body>
</html>