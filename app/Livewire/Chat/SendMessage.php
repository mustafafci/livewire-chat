<?php

namespace App\Livewire\Chat;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageSent;
use Livewire\Attributes\On;
use App\Models\Conversation;

class SendMessage extends Component
{
    public $selectedConversation, $receiverUser,$createdMessage;
    public $body;
    public function render()
    {
        return view('livewire.chat.send-message');
    }

    #[On('updateSendMessage')]
    public function updateSendMessage(Conversation $conversation, User $receiver)
    {
        //dd($conversation, $receiver);
        $this->selectedConversation = $conversation;
        $this->receiverUser = $receiver;
    }

    public function sendMessage()
    {
        if ($this->body == '') {
            return null;
        }

        $this->createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->receiverUser->id,
            'body' => $this->body
        ]);

        $this->selectedConversation->last_time_message = $this->createdMessage->created_at;
        $this->selectedConversation->save();

        $this->dispatch('pushMessage', $this->createdMessage->id);

        $this->dispatch('chatSelected');
        
        $this->dispatch('dispatchMessageSent')->self();
        $this->dispatch('refresh');

        $this->reset('body');
    }

    #[On('dispatchMessageSent')]
    public function dispatchMessageSent(){
        $user = User::find(auth()->user()->id);
        broadcast(new MessageSent($user, $this->createdMessage,$this->selectedConversation , $this->receiverUser));
    }
}
