<?php

declare(strict_types=1);

namespace App\Http;

use App\Contact\ContactForm;
use App\Contact\Validator;
use Monolog\Logger;

class ContactHandler implements RequestHandler
{
    public function __construct(
        private Validator $validator,
        private Logger $logger,
    ) {}

    public function handle(array $input): array
    {
        $form = ContactForm::fromArray($input);
        $errors = $this->validator->validate($form);
        $sent = $errors === [];

        if ($sent) {
            $this->logger->info('Новое сообщение', [
                'name' => $form->name,
                'email' => $form->email,
                'message' => $form->message,
            ]);
        }

        return ['form' => $form, 'errors' => $errors, 'sent' => $sent];
    }
}
