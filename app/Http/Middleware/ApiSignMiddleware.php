<?php
/**
 * API 输入检查签名
 */

namespace App\Http\Middleware;

use Closure;

class ApiSignMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $pass = false;
        if ($request->method() == "GET") {
            return $next($request);
        }
        $content = $request->getContent();
        if (empty($content)) {
            return response()->json(["status" => false, 'errors' => 'json ddd']);
        }
        if (!isset($data["sign"]) || !isset($data["nonce"])) {
            return response()->json(["status" => false, 'errors' => 'sign or nonce is empty']);
        }
        $data = json_decode($content, true);
        $sign = $this->getSign($data, $request);
        if ($sign == $data['sign']) {
            $pass = true;
        }
        if ($pass) {
            return $next($request);
        } else {
            return response()->json(["status" => false, 'errors' => 'sign errors']);
        }

    }

    protected function formatParams($paraMap)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    protected function getSign($data, $request)
    {
        unset($data['sign']);
        $str = $this->formatParams($data);
        $str .= "&key=" . config('app.api_key');
        return md5($str);
    }
}
