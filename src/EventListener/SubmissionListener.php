<?php

namespace App\EventListener;

use App\Event\NewSubmissionEvent;
use App\Message\Command\SendSubmissionNotification;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEventListener(event: NewSubmissionEvent::class)]
readonly class SubmissionListener
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(NewSubmissionEvent $event): void
    {
        $this->messageBus->dispatch(new SendSubmissionNotification($event->submission->id));
    }
}
