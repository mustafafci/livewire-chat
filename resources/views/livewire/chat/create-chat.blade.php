<div>
    <x:slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('users') }}
        </h2>
    </x:slot>
    <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap " data-hs-accordion-always-open>

        <ul class="space-y-1.5 bg-white px-2 py-4">
            @foreach ($users as $user)
                <li wire:click="checkConversation('{{ $user->id }}')">
                    <a class="flex items-center gap-x-3.5 py-2 px-2.5 bg-gray-100 text-sm text-neutral-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-700 dark:text-white"
                        href="#">
                        <img src="https://ui-avatars.com/api/?background=random&rounded=true&name={{ $user->name }}"
                            alt="{{ $user->name }}" width="30px" height="30px">
                        {{ $user->name }}
                    </a>
                </li>
            @endforeach

        </ul>
        <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
</div>
