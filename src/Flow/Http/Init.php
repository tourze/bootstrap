<?php

namespace tourze\Bootstrap\Flow\Http;

use tourze\Base\Base;
use tourze\Flow\HandlerInterface;
use tourze\Flow\Layer;
use tourze\Base\Helper\Cookie;
use tourze\Http\Http;
use tourze\Http\Request;

/**
 * HTTP初始化
 *
 * @package tourze\Mvc\Flow
 */
class Init extends Layer implements HandlerInterface
{

    /**
     * 每个请求层，最终被调用的方法
     *
     * @return mixed
     */
    public function handle()
    {
        // 决定当前使用的协议版本
        if (isset($_SERVER['SERVER_PROTOCOL']))
        {
            Base::getHttp()->protocol = $_SERVER['SERVER_PROTOCOL'];
        }

        /** @var Request $request */
        $request =& $this->flow->contexts['request'];
        //var_dump($request->isInitial());
        //echo "httpInit1:" . spl_object_hash($request) . "<br>\n";

        // 如果当前请求是初始请求，那么对其进行额外处理
        if ($request->isInitial())
        {
            if (
                ( ! empty($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN))
                || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'])
                && in_array($_SERVER['REMOTE_ADDR'], Request::$trustedProxies)
            )
            {
                $request->secure = true;
            }

            $protocol = Base::getHttp()->protocol;

            if (isset($_SERVER['REQUEST_METHOD']))
            {
                $method = $_SERVER['REQUEST_METHOD'];
            }
            else
            {
                $method = Http::GET;
            }

            if (isset($_SERVER['HTTP_REFERER']))
            {
                // There is a referrer for this request
                $referrer = $_SERVER['HTTP_REFERER'];
            }

            if (isset($_SERVER['HTTP_USER_AGENT']))
            {
                // Browser type
                Request::$userAgent = $_SERVER['HTTP_USER_AGENT'];
            }

            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']))
            {
                // Typically used to denote AJAX requests
                $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'];
            }

            Request::$clientIp = Request::getClientIP();

            if ($method !== Http::GET)
            {
                // Ensure the raw body is saved for future use
                $body = file_get_contents('php://input');
            }

            $cookies = [];
            if (($cookieKeys = array_keys($_COOKIE)))
            {
                foreach ($cookieKeys as $key)
                {
                    $cookies[$key] = Cookie::get($key);
                }
            }

            // Store global GET and POST data in the initial request only
            $request->protocol = $protocol;
            $request
                ->query($_GET)
                ->post($_POST);

            if (isset($method))
            {
                // Set the request method
                $request->method = $method;
            }
            if (isset($referrer))
            {
                // Set the referrer
                $request->referrer = $referrer;
            }
            if (isset($requestedWith))
            {
                // Apply the requested with variable
                $request->requestedWith = $requestedWith;
            }
            if (isset($body))
            {
                // Set the request body (probably a PUT type)
                $request->body = $body;
            }
            if (isset($cookies))
            {
                $request->cookie($cookies);
            }

            //var_dump($request->query());
            //var_dump($request->isInitial());
            //echo "httpInit2:" . spl_object_hash($request) . "<br>\n";
            //$this->flow->contexts['request'] = $request;
        }
    }
}
