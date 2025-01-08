<?php

namespace Tests\Unit\Domain\Notification;

use Core\Domain\Notification\Notification;
use PHPUnit\Framework\TestCase;

class NotificationUnitTest extends TestCase
{
    public function test_get_errors()
    {
        $notification = new Notification;
        $errors = $notification->getErrors();

        $this->assertIsArray($errors);
    }

    public function test_add_errors()
    {
        $notification = new Notification;

        $notification->addErrors([
            'context' => 'video',
            'message' => 'video title is required',
        ]);

        $errors = $notification->getErrors();
        $this->assertCount(1, $errors);
    }

    public function test_has_error()
    {
        $notification = new Notification;
        $hasErrors = $notification->hasErrors();
        $this->assertFalse($hasErrors);

        $notification->addErrors([
            'context' => 'video',
            'message' => 'video title is required',
        ]);

        $this->assertTrue($notification->hasErrors());
    }

    public function test_message()
    {
        $notification = new Notification;
        $notification->addErrors([
            'context' => 'video',
            'message' => 'title is required',
        ]);

        $notification->addErrors([
            'context' => 'video',
            'message' => 'description is required',
        ]);

        $message = $notification->messages();

        $this->assertIsString($message);
        $this->assertEquals(
            expected: 'video: title is required,video: description is required,',
            actual: $message
        );
    }

    public function test_message_filter_context()
    {
        $notification = new Notification;
        $notification->addErrors([
            'context' => 'video',
            'message' => 'title is required',
        ]);

        $notification->addErrors([
            'context' => 'category',
            'message' => 'name is required',
        ]);

        $message = $notification->messages(
            context: 'video'
        );

        $this->assertIsString($message);
        $this->assertEquals(
            expected: 'video: title is required,',
            actual: $message
        );
    }
}
