<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function destroy(User $user, Reply $reply)
    {
        return $user->isAuthorOf($reply->topic) || $user->isAuthorOf($reply);
    }
}
