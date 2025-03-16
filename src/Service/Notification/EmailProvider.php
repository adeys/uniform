<?php

namespace App\Service\Notification;

use App\Entity\FormSubmission;
use App\Form\Settings\Notification\EmailNotificationType;

class EmailProvider implements ProviderInterface
{
    public function triggerNotification(FormSubmission $formSubmission): void
    {
    }

    public function getConfigurationForm(): string
    {
        return EmailNotificationType::class;
    }

    public static function getName(): string
    {
        return 'email';
    }

    public static function getPriority(): int
    {
        return 0;
    }
}
