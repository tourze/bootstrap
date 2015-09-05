<?php

namespace tourze\Bootstrap\Flow;

use tourze\Base\Base;
use tourze\Flow\Flow;
use tourze\Flow\HandlerInterface;
use tourze\Flow\Layer;
use tourze\Http\Request;

/**
 * HTTP请求和处理流
 *
 * @package tourze\Bootstrap\Flow
 */
class Http extends Layer implements HandlerInterface
{

    /**
     * 每个请求层，最终被调用的方法
     *
     * @return mixed
     */
    public function handle()
    {
        Base::getLog()->debug(__METHOD__ . ' handle request flow - start');

        $request = new Request($this->flow->contexts['uri']);

        // 上下文
        $this->flow->contexts['request'] = $request;

        // 处理HTTP相关，例如过滤变量，初始化相关设置
        $flow = Flow::instance('tourze-http');
        $flow->contexts =& $this->flow->contexts;
        $flow->layers = [
            'tourze\Bootstrap\Flow\Http\Initialization', // HTTP初始化
            'tourze\Bootstrap\Flow\Http\Authentication', // HTTP认证
            'tourze\Bootstrap\Flow\Http\Authorization',  // HTTP授权
        ];
        $flow->start();

        // 执行请求
        $response = $request->execute();
        echo $response
            ->sendHeaders(true)
            ->body;

        Base::getLog()->debug(__METHOD__ . ' handle request flow - end');
    }
}
