<?php

namespace App\Listeners;

use App\Events\NewCommentEvent;
use App\Notifications\ReceivedComment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewCommentListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewCommentEvent  $event
     * @return void
     */
    public function handle(NewCommentEvent $event)
    {
        // get comment
        $comment = $event->comment;

        // get post owner
        $postOwner = $comment->commentable->user;

        // send notification
        $postOwner->notify(new ReceivedComment($comment));

        // increase popularity of this user
        $postOwner->profile->increment('popularity');
    }
}
