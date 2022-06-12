<?php

namespace Illuminate\Tests\Integration\Mail;

use Illuminate\Mail\Attachment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase;

class AttachingFromStorageTest extends TestCase
{
    public function testItCanAttachFromStorage()
    {
        Storage::disk('local')->put('/dir/foo.png', 'expected body contents');
        $mail = new MailMessage();
        $attachment = Attachment::fromStorageDisk('local', '/dir/foo.png')
            ->as('bar')
            ->withMime('text/css');

        $attachment->attachTo($mail);

        $this->assertSame([
            'data' => 'expected body contents',
            'name' => 'bar',
            'options' => [
                'mime' => 'text/css',
            ],
        ], $mail->rawAttachments[0]);

        Storage::disk('local')->delete('/dir/foo.png');
    }

    public function testItCanAttachFromStorageAndFallbackToStorageNameAndMime()
    {
        Storage::disk()->put('/dir/foo.png', 'expected body contents');
        $mail = new MailMessage();
        $attachment = Attachment::fromStorageDisk('local', '/dir/foo.png');

        $attachment->attachTo($mail);

        $this->assertSame([
            'data' => 'expected body contents',
            'name' => 'foo.png',
            'options' => [
                // when using "prefer-lowest" the local filesystem driver will
                // not detect the mime type based on the extension and will
                // instead fallback to "text/plain".
                'mime' => class_exists(\League\Flysystem\Local\FallbackMimeTypeDetector::class)
                    ? 'image/png'
                    : 'text/plain',
            ],
        ], $mail->rawAttachments[0]);

        Storage::disk('local')->delete('/dir/foo.png');
    }
}
