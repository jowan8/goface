<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class WebRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data['client_ip'] = $request->ip();
        if($data['client_ip']!='127.0.0.1') {
            $data['request_data'] = json_encode($request->all());
            $data['request_url'] = $request->path();
            $url = 'http://ip-api.com/json/' . $data['client_ip'] . '?lang=zh-CN';
            $return_json = curl_request($url, 'GET');
            $return = json_decode($return_json, true);
            $data['client_info'] = $return_json;
            if ($return && $return['status'] == 'success') {
                $data['clitent_address'] = $return['country'] . ' ' . $return['regionName'];
            } else {
                $data['clitent_address'] = '';
            }
            DB::table('request')->insert($data);
        }
        return $next($request);
    }
}
