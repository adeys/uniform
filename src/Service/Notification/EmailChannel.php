<?php

namespace App\Service\Notification;

use App\Entity\FormSubmission;
use App\Entity\Settings\NotificationSettings;
use App\Form\Settings\Notification\EmailNotificationType;
use App\Service\AccountSettingsService;
use App\Service\Notification\Email\EmailBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\DependencyInjection\Attribute\AutowireCallable;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;

class EmailChannel implements ChannelInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ?ArrayCollection $settings = null;

    public function __construct(
        private readonly AccountSettingsService $settingsManager,
        #[AutowireCallable(service: 'mailer.transport_factory.smtp', method: 'create')]
        private readonly \Closure $createTransport,
        private readonly EmailBuilder $emailBuilder,
    ) {
    }

    public function triggerNotification(FormSubmission $formSubmission, NotificationSettings $notificationSettings): void
    {
        if (!$this->checkRequirements()) {
            $this->logger->error('Email notification channel is not configured properly. Please check the settings.');

            return;
        }

        $mailer = $this->createMailer();
        $config = $this->settingsManager->getSettings();
        $sender = new Address(
            address: $config->getEmailFromAddress() ?? 'no-reply@uniform.io', // Fallback to a default address, TODO: Get default address from env vars
            name: $config->getEmailFromName() ?? 'UniForm' // Fallback to a default name, TODO: Get default name from env vars
        );
        $email = $this->emailBuilder->buildNotificationEmail($formSubmission);
        $email
            ->to($notificationSettings->getTarget())
            ->from($sender)
        ;

        $this->logger->info('Sending email notification...');

        try {
            $mailer->send($email);
            $this->logger->info('Email notification sent successfully.');
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('An error occurred while sending email notification.', ['exception' => $e]);
            throw $e;
        }
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

    public function checkRequirements(): bool
    {
        return !$this->getSettings()
            ->filter(fn ($_, string $key) => in_array($key, ['host', 'port'])) // Check only host and port
            ->exists(fn ($key, $value) => !$value);
    }

    public function getRequirementsMessage(): ?string
    {
        return "You need to configure the email server settings in order to use email notification channel.\nRequired settings: host, port";
    }

    private function createMailer(): MailerInterface
    {
        $settings = $this->getEmailServerSettings();
        $dsn = new Transport\Dsn(
            scheme: 'smtp',
            host: $settings['host'],
            user: $settings['username'],
            password: $settings['password'],
            port: $settings['port']
        );

        $transport = ($this->createTransport)($dsn);

        return new Mailer($transport);
    }

    private function getSettings(): ArrayCollection
    {
        if (null === $this->settings) {
            $this->settings = new ArrayCollection($this->getEmailServerSettings());
        }

        return $this->settings;
    }

    private function getEmailServerSettings(): array
    {
        $settings = $this->settingsManager->getSettings();

        return [
            'host' => $settings?->getSmtpHost(),
            'port' => $settings?->getSmtpPort(),
            'username' => $settings?->getSmtpUser(),
            'password' => $settings?->getSmtpPassword(),
        ];
    }
}
