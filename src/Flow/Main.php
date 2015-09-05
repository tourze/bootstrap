<?php

namespace tourze\Bootstrap\Flow;

use tourze\Base\Base;
use tourze\Flow\HandlerInterface;
use tourze\Flow\Layer;
use tourze\Base\Helper\Url;
use tourze\Route\Route;

/**
 * 主体执行流
 *
 * @package tourze\Bootstrap\Flow
 */
class Main extends Layer implements HandlerInterface
{

    /**
     * 每个请求层，最终被调用的方法
     *
     * @return mixed
     */
    public function handle()
    {
        Base::getLog()->debug(__METHOD__ . ' handle main flow - start');

        Route::$lowerUri = true;

        if ( ! Route::exists('default'))
        {
            Base::getLog()->debug(__METHOD__ . ' set default route');
            Route::set('default', '(<controller>(/<action>(/<id>)))')
                ->defaults([
                    'controller' => 'Site',
                    'action'     => 'index',
                ]);
        }

        $this->flow->contexts['uri'] = Url::detectUri();

        Base::getLog()->debug(__METHOD__ . ' handle main flow - end');
    }
}
