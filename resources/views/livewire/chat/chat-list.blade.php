<ul class="space-y-1.5">
    @isset($conversations)

        @foreach ($conversations as $conversation)
            <li wire:key="{{ $conversation->id }}"
                wire:click="chatUserSelected({{ $conversation }} ,  {{ $this->getUserInstance($conversation, 'id') }})"
                class="bg-gray-100 py-2 chat-selected">
                <a class="flex items-center justify-between gap-x-3.5 py-2 px-2.5  text-sm text-neutral-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-700 dark:text-white"
                    href="#">
                    <img src="https://ui-avatars.com/api/?background=random&rounded=true&name={{ $this->getUserInstance($conversation, 'name') }}"
                        alt="{{ $this->getUserInstance($conversation, 'name') }}" width="30px" height="30px">
                    <span>{{ $this->getUserInstance($conversation, 'name') }}</span>
                    @if (count($conversation->messages->where('read', 0)->where('receiver_id', auth()->id())) > 0)
                        <span
                            class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-medium bg-teal-500 text-white">
                            {{ count($conversation->messages->where('read', 0)->where('receiver_id', auth()->id())) }}
                        </span>
                    @endif
                </a>
                <div class="flex items-center justify-between text-sm px-3 text-gray-400">

                    <p>{{ Str::limit($conversation->messages->last()->body, 15, '...') }}</p>
                    <span class="text-sm">{{ $conversation->messages->last()?->created_at->diffForHumans() }}</span>
                </div>
            </li>
        @endforeach

    @endisset

</ul>
