<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-xl p-10 space-y-10">

        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">📝 Submit Your Research Paper</h2>
            <p class="mt-2 text-sm text-gray-500">Craft, document, and share your latest discoveries with the world.</p>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="submit" class="space-y-6">
            {{-- Title --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700">Title</label>
                <input wire:model="title" type="text" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
            </div>

            {{-- Abstract --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700">Abstract</label>
                <textarea wire:model="abstract" rows="4"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out"
                    placeholder="Summarize your paper..."></textarea>
            </div>

            {{-- Markdown Editor --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Content (Markdown)</label>
                <div class="rounded-lg border border-gray-300 shadow-sm overflow-hidden">
                <livewire:markdown-x
                wire:model="markdown_content"
                :content="$markdown_content ?? ''"
                name="markdown_content"
                key="paper-submission"
            />

                </div>

            </div>

            {{-- Researchers --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Researchers</label>
                <div class="space-y-2">
                    @foreach ($researchers as $index => $researcher)
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <input wire:model="researchers.{{ $index }}.name" type="text" required
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Name">
                            <input wire:model="researchers.{{ $index }}.affiliation" type="text"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Affiliation">
                            <button type="button" wire:click="removeResearcher({{ $index }})"
                                class="text-red-600 hover:text-red-800 transition">✕</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" wire:click="addResearcher"
                    class="mt-3 text-indigo-600 hover:text-indigo-800 text-sm font-medium transition">+ Add Researcher</button>
            </div>

            {{-- Submit Button --}}
            <div class="text-center">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    🚀 Submit Paper
                </button>
            </div>
        </form>

    </div>
</div>
