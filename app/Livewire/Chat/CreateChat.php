<?php

namespace App\Livewire\Chat;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use App\Models\Conversation;

class CreateChat extends Component
{
    public $users, $authUserId;
    public $message = 'How Are You ?';
    public function mount()
    {
        $this->authUserId = auth()->id();
        $this->users = User::wherenot('id', auth()->user()->id)->get();
    }
    public function render()
    {
        return view('livewire.chat.create-chat');
    }

    public function checkConversation($receiverId)
    {
        $checkedConversation = Conversation::where('receiver_id', $receiverId)->where('sender_id', $this->authUserId)
            ->orWhere('receiver_id', $this->authUserId)->where('sender_id', $receiverId)->get();

        if (count($checkedConversation) < 1) {
            $createdConversation = Conversation::create([
                'sender_id' => $this->authUserId,
                'receiver_id' => $receiverId
            ]);

            $createdMessage = Message::create([
                'conversation_id' => $createdConversation->id,
                'sender_id' => $this->authUserId,
                'receiver_id' => $receiverId,
                'body' => $this->message
            ]);

            $createdConversation->last_time_message = $createdMessage->created_at;
            $createdConversation->save();

            dd('saved');

        } else {
            dd('false');
        }
    }
}
