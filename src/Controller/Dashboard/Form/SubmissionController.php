<?php

namespace App\Controller\Dashboard\Form;

use App\Entity\FormSubmission;
use App\Message\Command\SendSubmissionNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;

use function Symfony\Component\Translation\t;

final class SubmissionController extends AbstractController
{
    #[Route('/submission/{id:submission}/notifications/send', name: 'app_submission_send_notification', methods: ['POST'])]
    #[IsCsrfTokenValid(id: new Expression('"submission-" ~ args["submission"].getId() ~ "-notification"'))]
    public function sendNotification(FormSubmission $submission, MessageBusInterface $bus): Response
    {
        $bus->dispatch(new SendSubmissionNotification($submission->getId()));

        $this->addFlash('success', t('flash.submission.notification_queued'));

        return $this->redirectToRoute('app_dashboard_form_endpoint_submission_list', [
            'id' => $submission->getForm()->getId(),
        ]);
    }
}
