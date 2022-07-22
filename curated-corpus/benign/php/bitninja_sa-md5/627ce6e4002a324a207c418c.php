<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use App\Notifications\ReceivedLike;
use App\Post;

class LikeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle($event)
    {
        // get post owner
        $postOwner = Post::with('user.profile')->findOrFail($event->like->likeable_id);

        // build like event
        $likeEvent = ['user' => auth()->user()->profile->username, 'postId' => $event->like->likeable_id];

        // send notification
        $postOwner->user->notify(new ReceivedLike($likeEvent));

        // increase popularity of this user
        $postOwner->user->profile->increment('popularity');
    }
}
