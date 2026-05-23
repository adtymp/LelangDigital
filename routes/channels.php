<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id1}.{id2}', function ($user, $id1, $id2) {
    return $user->id == $id1 || $user->id == $id2;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});