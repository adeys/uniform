<?php

namespace App\Controller;

use App\Dto\FormSubmissionDto;
use App\Entity\FormDefinition;
use App\Event\NewSubmissionEvent;
use App\Service\FormEndpoint\SubmissionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EndpointController extends AbstractController
{
    #[Route('/e/{uid:endpoint}', name: 'app_form_endpoint_submit', methods: ['POST'])]
    public function index(
        Request $request,
        FormDefinition $endpoint,
        SubmissionService $submissionService,
        EventDispatcherInterface $eventDispatcher,
    ): Response {
        if (!$endpoint->isEnabled()) {
            throw $this->createNotFoundException();
        }

        $submission = $submissionService->saveSubmission($endpoint, $request);

        $eventDispatcher->dispatch(
            new NewSubmissionEvent(
                FormSubmissionDto::fromSubmission($submission)
            )
        );

        return $endpoint->getRedirectUrl()
            ? $this->redirect($endpoint->getRedirectUrl())
            : $this->redirectToRoute('app_form_submit_success')
        ;
    }
}
