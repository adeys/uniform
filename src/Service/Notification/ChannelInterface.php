<?php

namespace App\Service\Notification;

use App\Entity\FormSubmission;
use App\Entity\Settings\NotificationSettings;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(tags: ['app.notification.provider'])]
interface ChannelInterface
{
    public static function getName(): string;

    public static function getPriority(): int;

    public function triggerNotification(FormSubmission $formSubmission, NotificationSettings $notificationSettings): void;

    public function getConfigurationForm(): string;

    public function checkRequirements(): bool;

    public function getRequirementsMessage(): ?string;
}
