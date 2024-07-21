<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat.{receiver}', function (User $user, $receiverId) {
    //check if the user is the same as receiver
    return (int) $user->id === (int) $receiverId;
});