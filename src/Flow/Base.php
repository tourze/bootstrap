<?php

namespace tourze\Bootstrap\Flow;

use tourze\Flow\HandlerInterface;
use tourze\Flow\Layer;
use tourze\Base\Helper\Url;
use tourze\Route\Route;

/**
 * SDK框架执行流
 *
 * @package tourze\Mvc\Flow
 */
class Base extends Layer implements HandlerInterface
{

    /**
     * 每个请求层，最终被调用的方法
     *
     * @return mixed
     */
    public function handle()
    {
        Route::$lowerUri = true;

        // 下面这样try catch，效率比较低，需要更改下
        if ( ! Route::exists('default'))
        {
            Route::set('default', '(<controller>(/<action>(/<id>)))')
                ->defaults([
                    'controller' => 'Site',
                    'action'     => 'index',
                ]);
        }

        $this->flow->contexts['uri'] = Url::detectUri();
    }
}
