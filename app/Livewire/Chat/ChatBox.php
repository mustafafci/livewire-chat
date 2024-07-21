<?php

namespace App\Livewire\Chat;

use App\Events\MessageRead;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Conversation;

class ChatBox extends Component
{
    public $messageCount, $messages, $selectedConversation, $receiverUser;
    protected $paginate = 10;


    public function render()
    {
        return view('livewire.chat.chat-box');
    }

    #[On('loadConversation')]
    public function loadConversation(Conversation $conversation, User $receiver)
    {

        // dd($conversation , $receiver);
        $this->selectedConversation = $conversation;
        $this->receiverUser = $receiver;

        $this->messageCount = Message::where('conversation_id', $this->selectedConversation->id)->count();

        $this->messages = Message::where('conversation_id', $this->selectedConversation->id)
            ->skip($this->messageCount - $this->paginate)->take($this->paginate)->get();

        Message::where('conversation_id', $this->selectedConversation->id)
            ->where('receiver_id', auth()->user()->id)
            ->update(['read' => 1]);

            $this->dispatch('broadcastMessageRead')->self();
    }

    #[On('pushMessage')]
    public function pushMessage($messageId)
    {
        $newMessage = Message::find($messageId);
        $this->messages->push($newMessage);

        $this->dispatch('scrollChatBodyToBottom');
    }

    #[On('loadMore')]
    public function loadMore()
    {
        dd('more');
    }



    public function getListeners()
    {
        $auth_id = auth()->user()->id;

        return [
            // Private Channel
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastMessageReceived',
            "echo-private:chat.{$auth_id},MessageRead" => 'broadcastMessageRead',


        ];
    }

    #[On('broadcastMessageReceived')]
    public function broadcastMessageReceived($event)
    {

        $broadcastedMessage = Message::find($event['message_id']);
        if ($this->selectedConversation) {
            if ((int) $this->selectedConversation->id === (int) $event['conversation_id']) {

                $broadcastedMessage->read = 1;
                $broadcastedMessage->save();

                $this->pushMessage($broadcastedMessage->id);
                $this->dispatch('refresh');
                $this->dispatch('broadcastMessageRead')->self();
            }
        }

    }

    #[On('broadcastedMessageRead')]
    public function broadcastedMessageRead($event)
    {
        dd($event);
    }
    #[On('broadcastMessageRead')]
    public function broadcastMessageRead()
    {
        broadcast(new MessageRead($this->selectedConversation->id, $this->receiverUser->id));
    }
}
