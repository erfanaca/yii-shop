<?php

declare(strict_types=1);

use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\Symfony\Mailer;
use Symfony\Component\Mailer\Transport;

return [
    MailerInterface::class => static function (): MailerInterface {
        $transport = Transport::fromDsn('smtp://127.0.0.1:1025');

        return new Mailer($transport);
    },
];
