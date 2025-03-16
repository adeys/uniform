<?php

namespace App\MessageHandler\Command;

use App\Message\Command\SendSubmissionNotification;
use App\Repository\FormSubmissionRepository;
use App\Service\Notification\ChannelInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SendSubmissionNotificationHandler implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private array $channels;

    public function __construct(
        private readonly FormSubmissionRepository $submissionRepository,
        #[AutowireIterator(tag: 'app.notification.provider', defaultIndexMethod: 'getName', defaultPriorityMethod: 'getPriority')]
        iterable $notificationProviders,
    ) {
        $this->channels = iterator_to_array($notificationProviders);
    }

    public function __invoke(SendSubmissionNotification $message): void
    {
        $submission = $this->submissionRepository->find($message->getSubmissionId());
        $form = $submission->getForm();

        $enabledNotifications = $form->getNotificationSettings()->filter(fn ($notification) => $notification->isEnabled());
        foreach ($enabledNotifications as $enabledNotification) {
            if (!isset($this->channels[$enabledNotification->getType()])) {
                $this->logger->error(sprintf('Notification channel "%s" is not registered', $enabledNotification->getType()));
                continue;
            }
            /** @var ChannelInterface $channel */
            $channel = $this->channels[$enabledNotification->getType()];

            // Only trigger the notification if the requirements are met
            if ($channel->checkRequirements()) {
                $channel->triggerNotification($submission, $enabledNotification);
            }
        }
    }
}
