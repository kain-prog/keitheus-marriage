<?php

namespace App\Bundles\GuestBundle\Message;

readonly class SendEmailMessage
{
    public function __construct(
        public array $to,
        public string $subject,
        public string $htmlContent
    ) {}
}
