<div x-data="{ sidebarOpen: false }" class="{{ config('discussions.styles.container_classes') }}">
    @include('discussions::partials.custom-styles')
    
    <discussion-content-top>
        <div class="relative mb-5 space-y-2">
            <h1 class="{{ config('discussions.styles.header_classes') }}">{{ $this->discussion->title }}</h1>
            <div class="flex items-center justify-between w-full h-auto pb-3">
                <a href="{{ route('discussions') }}" class="relative inline-block w-auto text-sm font-medium text-black opacity-50 cursor-pointer dark:text-white hover:opacity-100 group">
                    <span>&larr; back to all {{ strtolower(trans('discussions::text.titles.discussions')) }}</span>
                    <span class="absolute bottom-0 left-0 w-0 h-px duration-200 ease-out bg-gray-900 group-hover:w-full"></span>
                </a>
            </div>
            @include('discussions::partials.guest-auth-message')
            @if (session()->has('message'))
            <div class="p-4 mb-4 text-white bg-green-500 rounded">
                {{ session('message') }}
            </div>
            @endif
        </div>
    </discussion-content-top>

    <!-- Mobile Sidebar Toggle Button -->
    <div class="md:hidden mb-4">
        <button @click="sidebarOpen = true" class="px-4 py-2 text-sm font-medium text-white bg-gray-600 rounded">
            Discussion Post Sidebar 
             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <div class="flex flex-col md:flex-row items-start w-full space-x-0 md:space-x-5">

        <!-- Left: Main Content -->
        <discussion-content-left class="relative w-full md:w-2/3">
            <div class="mb-4 space-y-4">
                @if ($editing)
                    <x-filament::input.wrapper class="w-full !{{ config('discussions.styles.rounded') }}">
                        <x-filament::input
                            type="text"
                            wire:model="editingTitle"
                            class="w-full"
                        />
                    </x-filament::input.wrapper>
                    {{ $this->form }}
                    <div class="flex justify-end mt-2 space-x-3">
                        <x-button wire:click="cancelEditing" color="gray" class="flex-shrink-0 !{{ config('discussions.styles.rounded') }}">@lang('discussions::messages.words.cancel')</x-button>
                        <x-button wire:click="updateDiscussion" class="flex-shrink-0 !{{ config('discussions.styles.rounded') }}">@lang('discussions::messages.words.save')</x-button>
                    </div>
                @else
                <div class="p-5 bg-white dark:bg-white/5 border border-neutral-200 dark:border-gray-700 @if (config('discussions.styles.rounded') == 'rounded-full') {{ 'rounded-xl' }}@else{{ config('discussions.styles.rounded') }} @endif">
                    <div class="flex items-center mb-5 space-x-2">
                        <a href="{{ $this->discussion->user->profile_url }}" class="flex items-center space-x-2 text-sm font-bold group">
                            @include('discussions::partials.discussion-avatar', ['user' => $this->discussion->user, 'size' => 'sm'])
                            <span class="text-gray-900 group-hover:underline dark:text-gray-100">{{ $this->discussion->user->name }}</span>
                        </a>
                        <p class="text-xs text-gray-500">on {{ $this->discussion->created_at->format('F jS, Y') }}</p>
                    </div>
                    <discussion-post class="mb-2 prose-sm prose dark:prose-invert">
                        {!! Str::markdown($this->discussion->content) !!}
                    </discussion-post>
                    @auth
                        <div class="flex justify-end mr-auto space-x-2 text-sm">
                            @if (auth()->user()->id == $this->discussion->user_id)
                                <button wire:click="editDiscussion" class="font-medium text-neutral-500 hover:text-blue-500 hover:underline">@lang('discussions::messages.words.edit')</button>
                                <x-filament::modal id="delete-modal">
                                    <x-slot name="trigger">
                                        <button class="font-medium text-neutral-500 hover:text-red-500 hover:underline">@lang('discussions::messages.words.delete')</button>
                                    </x-slot>
                                    @include('discussions::partials.delete-modal-content', ['type' => 'discussion'])
                                </x-filament::modal>
                            @endif
                        </div>
                    @endauth
                </div>
                @endif
            </div>

            @livewire('discussion-posts', ['discussion' => $this->discussion], key($this->discussion->id))

            <div class="flex flex-col items-end mt-4 mb-4 space-y-4">
                <div class="w-full mb-1">
                    {{ $this->replyForm }}
                </div>
                <x-button wire:click="answer" class="flex-shrink-0 !{{ config('discussions.styles.rounded') }}">@lang('discussions::messages.words.comment')</x-button>
            </div>
        </discussion-content-left>

        <!-- Right: Sidebar -->
        <discussion-content-right class="{{ config('discussions.styles.sidebar_width') }} hidden md:block flex-shrink-0 text-sm ml-0 md:ml-8">
            @include('discussions::partials.sidebar')
        </discussion-content-right>
    </div>

    <!-- Mobile Sidebar Overlay with click-away behavior -->
    <div
        x-show="sidebarOpen"
        class="fixed inset-0 z-50 flex justify-end md:hidden bg-black bg-opacity-50"
        @click="sidebarOpen = false"
        style="display: none;"
    >
        <div
            class="w-3/4 h-full bg-white dark:bg-gray-900 p-4 overflow-y-auto shadow-lg"
            @click.stop
        >
            <button @click="sidebarOpen = false" type="button" class="inline-flex bg-gray-100 justify-center items-center p-1 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span class="sr-only">Close</span>
                <!-- Heroicon name: outline/x -->
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            @include('discussions::partials.sidebar')
        </div>
    </div>

</div>
