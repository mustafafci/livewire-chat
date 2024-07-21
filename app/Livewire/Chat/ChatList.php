<?php

namespace App\Livewire\Chat;

use App\Models\User;
use Livewire\Component;
use App\Models\Conversation;

class ChatList extends Component
{
    public $conversations, $authUserId, $receiverUser;
    public $selectedConversation;

    protected $listeners = ['chatUserSelected' , 'refresh' => '$refresh'];

    public function mount()
    {
        $this->authUserId = auth()->id();
        $this->conversations = Conversation::where('sender_id', $this->authUserId)
            ->orWhere('receiver_id', $this->authUserId)
            ->orderBy('last_time_message', 'desc')->get();
    }
    public function render()
    {
        return view('livewire.chat.chat-list');
    }

    public function getUserInstance(Conversation $conversation, $request)
    {
        if ($conversation->sender_id == $this->authUserId) {
            $this->receiverUser = User::firstWhere('id', $conversation->receiver_id);
        } else {
            $this->receiverUser = User::firstWhere('id', $conversation->sender_id);
        }

        if (isset($request)) {
            return $this->receiverUser->$request;
        }
    }

    public function chatUserSelected(Conversation $conversation, $receiverId)
    {
        //dd($conversation, $receiverId);
        $this->selectedConversation = $conversation;
        $this->receiverUser = User::findOrFail($receiverId);

        $this->dispatch( 'loadConversation' , $this->selectedConversation , $this->receiverUser);
        $this->dispatch('updateSendMessage' , $this->selectedConversation , $this->receiverUser);
        $this->dispatch('chatSelected');
    }
}
