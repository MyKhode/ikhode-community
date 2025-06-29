<div class="flex-shrink-0 mr-6 space-y-1 {{ config('discussions.styles.sidebar_width') }}">
    <h3 class="mb-2 font-bold text-gray-900 dark:text-gray-100">Categories</h3>

    <a href="/{{ config('discussions.route_prefix') }}"
        wire:navigate
        class="flex items-center px-3 py-2 font-medium space-x-2 text-sm @if (Request::is(config('discussions.home_route'))) {{ 'bg-neutral-100 dark:bg-neutral-700 dark:text-gray-200 text-gray-800' }}@else{{ 'hover:bg-gray-100 text-gray-700 dark:hover:bg-gray-700 dark:text-gray-500 dark:hover:text-gray-400 hover:text-gray-800' }} @endif {{ config('discussions.styles.rounded') }}">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"></path></svg>
        <span>All Discussions</span>
    </a>
    <ul class="space-y-1">
        @forelse (config('discussions.categories') as $slug => $category)
            <li>
                <a href="/{{ config('discussions.route_prefix') }}/category/{{ $slug }}"
                    @class([
                        'flex items-center px-3 py-2 font-medium space-x-2 text-sm ' . config('discussions.styles.rounded'),
                        'bg-neutral-100 dark:bg-neutral-700 dark:text-gray-200 text-gray-800' => Request::is(config('discussions.route_prefix') . '/category/' . $slug ),
                        'hover:bg-gray-100 text-gray-700 dark:hover:bg-gray-700 dark:text-gray-500 dark:hover:text-gray-400 hover:text-gray-800' => !Request::is(config('discussions.route_prefix') . '/category/' . $slug )
                        ])>
                    <span>{{ $category['icon'] }}</span>
                    <span>{{ $category['title'] }}</span>
                </a>
            </li>
        @empty
        @endforelse
    </ul>
</div>
