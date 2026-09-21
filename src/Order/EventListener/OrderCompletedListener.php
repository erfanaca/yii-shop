<?php

declare(strict_types=1);

namespace App\Order\EventListener;

use App\Order\Event\OrderCompleted;
use App\Order\OrderRepository;
use App\User\UserRepository;
use Yiisoft\Mailer\MailerInterface;
use Yiisoft\Mailer\Message;

final readonly class OrderCompletedListener
{
    public function __construct(
        private MailerInterface $mailer,
        private OrderRepository $orderRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function handle(OrderCompleted $event): void
    {
        $order = $this->orderRepository->findById($event->orderId);
        $user = $this->userRepository->findIdentity((string) $event->userId);

        if ($order === null || $user === null) {
            return;
        }

        $message = new Message(
            from: 'support@shop.test',
            to: $user->getEmail(),
            subject: 'Your order has been completed',
            textBody: "Your order #{$order->getInvoiceNumber()} has been successfully completed.",
        );

        $this->mailer->send($message);
    }
}
