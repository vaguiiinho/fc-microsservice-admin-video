<?php

namespace Core\Domain\Notification;

class Notification
{
    private $errors = [];
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param $error array[context, message]
     */
    public function addErrors(array $errors): void
    {
        array_push($this->errors, $errors);
    }

    public function hasErros(): bool
    {
        return count($this->errors) > 0;
    }

    public function messages(): string
    {
        $messages = '';

        foreach ($this->errors as $error) {
            $messages .= "{$error['context']}: {$error['message']},";
        }

        return $messages;
    }
}
