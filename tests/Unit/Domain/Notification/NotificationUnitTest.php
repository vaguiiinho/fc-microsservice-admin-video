<?php

namespace Tests\Unit\Domain\Notification;

use Core\Domain\Notification\Notification;
use PHPUnit\Framework\TestCase;

class NotificationUnitTest extends TestCase
{
    public function testGetErrors()
    {
        $notification = new Notification();
        $errors = $notification->getErrors();

        $this->assertIsArray($errors);
    }

    public function testAddErrors()
    {
        $notification = new Notification();

        $notification->addErrors([
            'context' => 'video',
            'message' => 'video title is required'
        ]);

        $errors = $notification->getErrors();
        $this->assertCount(1, $errors);
    }

    public function testHasError()
    {
        $notification = new Notification();
        $hasErrors = $notification->hasErros();
        $this->assertFalse($hasErrors);

        $notification->addErrors([
            'context' => 'video',
            'message' => 'video title is required'
        ]);

        $this->assertTrue($notification->hasErros());
    }
}
