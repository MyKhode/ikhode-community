<h3 class="font-semibold text-neutral-500 dark:text-gray-400">Category</h3>
@if(is_null($this->discussion->category_slug))
    <p class="w-full my-4 text-xs text-gray-500 rounded-md dark:text-gray-400">{{ trans('discussions::messages.discussion.no_category') }}</p>
@else
    <p class="my-2 text-xs text-gray-500 dark:text-gray-400">
        {{ Wave\Plugins\Discussions\Helpers\Category::name($this->discussion->category_slug) }}
    </p>
@endif

<hr class="border-gray-200 dark:border-gray-700" />

<h3 class="mt-5 font-semibold text-neutral-500 dark:text-gray-400">Participants</h3>
<div class="my-2 space-y-1.5">
    @forelse ($this->discussion->users()->get() as $user)
        <a href="{{ $user->link() }}" class="flex items-center space-x-2 font-medium text-gray-700 dark:text-gray-200 hover:underline">
            @include('discussions::partials.discussion-avatar', ['user' => $user, 'size' => 'xs'])
            <span>{{ $user->name }}</span>
        </a>
    @empty
        <p class="w-full my-4 text-xs text-gray-400 rounded-md">{{ trans('discussions::messages.discussion.no_participants') }}</p>
    @endforelse
</div>

<hr class="border-gray-200 dark:border-gray-700" />

@auth
    <h3 class="mt-5 font-semibold text-neutral-500 dark:text-gray-400">Notifications</h3>
    <div class="relative w-auto h-full my-2">
        @if ($user_subscribed)
            <x-button color="success" icon="phosphor-bell-ringing" x-on:click="$dispatch('toggleNotification')" class="w-full flex items-center justify-center">
                <span>Subscribed</span>
            </x-button>
            <p class="mt-1 text-xs text-gray-600">You're receiving notifications because you're subscribed to this thread.</p>
        @else
            <x-button color="gray" icon="phosphor-bell" x-on:click="$dispatch('toggleNotification')" class="w-full flex items-center justify-center">
                <span>Subscribe</span>
            </x-button>
            <p class="mt-1 text-xs text-gray-600">You are not receiving notifications about this discussion.</p>
        @endif
    </div>
@endauth
