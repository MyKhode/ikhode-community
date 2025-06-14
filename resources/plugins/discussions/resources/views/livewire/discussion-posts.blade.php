<div class="w-full">
    <div class="mb-4 space-y-4">
        @foreach ($posts as $post)
            <div class="p-5 border bg-white dark:bg-white/5 dark:border-gray-700 border-neutral-200 @if(config("discussions.styles.rounded") == 'rounded-full') rounded-3xl @else {{ config("discussions.styles.rounded") }} @endif">
                <div class="flex items-center mb-5 space-x-2">
                    <a href="{{ $post->user->profile_url }}" class="flex items-center space-x-2 text-sm font-bold group">
                        @include('discussions::partials.discussion-avatar', ['user' => $post->user, 'size' => 'sm'])
                        <span class="text-gray-900 group dark:text-gray-100 group-hover:underline">{{ $post->user->name }}</span>
                    </a>
                    <p class="text-xs text-gray-500">on {{ $post->created_at->format('F jS, Y') }}</p>
                </div>
                @if(auth()->guest() || auth()->user() && $editingPostId !== $post->id )
                <discussion-post class="mb-2 prose-sm prose dark:prose-invert">
                    {!! Str::markdown($post->content) !!}
                </discussion-post>
                @endif

                @auth
                    @if ($editingPostId === $post->id)
                        {{ $this->form }}
                        <div class="flex items-end justify-end mt-2 space-x-2 text-sm">
                            <x-button color="gray" wire:click="cancelEdit()" class="flex-shrink-0 !{{ config('discussions.styles.rounded') }}">
                                @lang('discussions::messages.words.cancel')
                            </x-button>
                            <x-button wire:click="update({{ $post->id }})" class="flex-shrink-0 !{{ config('discussions.styles.rounded') }}">
                                @lang('discussions::messages.words.save')
                            </x-button>
                        </div>
                    @else
                        <div class="flex justify-end mr-auto space-x-2 text-sm">
                            @if (auth()->user()->id == $post->user_id)
                                <button wire:click="edit({{ $post->id }})" class="font-medium text-neutral-500 hover:text-blue-500 hover:underline">
                                    @lang('discussions::messages.words.edit')
                                </button>
                                <button wire:click="delete({{ $post->id }})" class="font-medium text-neutral-500 hover:text-red-500 hover:underline">
                                    @lang('discussions::messages.words.delete')
                                </button>
                            @endif
                        </div>
                    @endif
                @endauth
            </div>
        @endforeach

        @if ($posts->hasMorePages())
            <div class="flex justify-center">
                <button wire:click="loadMore" wire:loading.attr="disabled" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                    @lang('discussions::messages.discussion.load_more')
                </button>
            </div>
        @endif
    </div>
</div>
