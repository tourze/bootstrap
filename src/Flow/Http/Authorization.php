<?php

namespace tourze\Bootstrap\Flow\Http;

use tourze\Flow\HandlerInterface;
use tourze\Flow\Layer;

/**
 * HTTP授权
 *
 * @package tourze\Bootstrap\Flow\Http
 */
class Authorization extends Layer implements HandlerInterface
{

    /**
     * 每个请求层，最终被调用的方法
     *
     * @return mixed
     */
    public function handle()
    {
        // empty
    }
}
