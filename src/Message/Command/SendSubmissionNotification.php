<?php

namespace App\Message\Command;

final readonly class SendSubmissionNotification
{
    public function __construct(private int $submissionId)
    {
    }

    public function getSubmissionId(): int
    {
        return $this->submissionId;
    }
}
