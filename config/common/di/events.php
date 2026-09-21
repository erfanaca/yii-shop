<?php

declare(strict_types=1);

use App\Order\EventListener\OrderCompletedListener;
use Psr\EventDispatcher\ListenerProviderInterface;
use Yiisoft\EventDispatcher\Provider\ListenerCollection;
use Yiisoft\EventDispatcher\Provider\Provider;

return [
    ListenerProviderInterface::class => static function (
        OrderCompletedListener $orderCompletedListener,
    ): ListenerProviderInterface {
        $listeners = new ListenerCollection();

        $listeners = $listeners->add(
            $orderCompletedListener->handle(...),
        );

        return new Provider($listeners);
    }
];
