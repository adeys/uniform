<?php

namespace App\Dto;

use App\Entity\FormSubmission;

readonly class FormSubmissionDto
{
    public function __construct(
        public int $id,
        public array $payload,
        public int $formId,
    ) {
    }

    public static function fromSubmission(FormSubmission $submission): self
    {
        return new self(
            id: $submission->getId(),
            payload: $submission->getPayload(),
            formId: $submission->getForm()->getId(),
        );
    }
}
