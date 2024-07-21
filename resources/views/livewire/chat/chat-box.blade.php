<ul class="space-y-5 bg-white px-4 py-4 h-full overflow-y-auto chat-body" style="padding-bottom:30px">
    @if ($selectedConversation)
        <h2>
            <img src="https://ui-avatars.com/api/?background=random&rounded=true&name= {{ $receiverUser->name }}"
                alt="{{ $receiverUser->name }}" width="30px" height="30px" class="inline-block">
            {{ $receiverUser->name }}
        </h2>
        <hr>
        <!-- Chat -->
        @foreach ($messages as $message)
            <li wire:key="{{ $message->id }}"
                class="max-w-lg flex gap-x-2 sm:gap-x-4 me-11 {{ auth()->id() == $message->sender_id ? 'sender-message' : '' }}">
                <img class="inline-block size-9 rounded-full"
                    src="https://ui-avatars.com/api/?background=random&rounded=true&name= {{ auth()->id() == $message->sender_id ? auth()->user()->name : $receiverUser->name }}"
                    alt="{{ $receiverUser->name }}" width="30px" height="30px">

                <div>
                    <!-- Card -->
                    <div
                        class="body bg-white border border-gray-200 rounded-2xl p-4 space-y-3 dark:bg-neutral-900 dark:border-neutral-700">
                        {{ $message->body }}
                    </div>
                    <!-- End Card -->
                    <div class="flex justify-between items-center">
                        <span class="mt-1.5 flex items-center gap-x-1 text-xs text-gray-500 dark:text-neutral-500">
                            @if ($message->read)
                                <svg class="{{ auth()->id() == $message->user->id ? 'text-blue-600' : '' }} flex-shrink-0 size-3"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M18 6 7 17l-5-5"></path>
                                    <path d="m22 10-7.5 7.5L13 16"></path>
                                </svg>
                            @else
                                <svg class=" flex-shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 7 17l-5-5"></path>
                                    <path d="m22 10-7.5 7.5L13 16"></path>
                                </svg>
                            @endif
                        </span>
                        <span class="mt-1.5 text-xs text-gray-500 dark:text-neutral-500">
                            {{ $message->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </li>
        @endforeach

        <!-- End Chat -->

        <!-- Chat -->

        <!-- End Chat -->
    @else
        <h3 class="my-3 text-blue-600 text-2xl text-center">There is no conversation yet</h3>
    @endif

    <script>
        document.addEventListener('scrollChatBodyToBottom', event => {
            $('.chat-body').scrollTop($('.chat-body')[0].scrollHeight);
        });


        $('.chat-body').on('scroll', function() {
            let top = $(this).scrollTop;
            console.log(top);
            if (top == 0) {
                window.livewire.dispatch('loadMore');
            }
        });
    </script>

</ul>
