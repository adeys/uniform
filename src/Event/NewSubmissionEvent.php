<?php

namespace App\Event;

use App\Dto\FormSubmissionDto;

final readonly class NewSubmissionEvent
{
    public function __construct(public FormSubmissionDto $submission)
    {
    }
}
