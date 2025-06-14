<div class="max-w-4xl mx-auto py-10">
    <h2 class="text-2xl font-bold mb-6">Submit a Research Paper</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="bg-white p-6 rounded shadow space-y-6">
        <div>
            <label class="block font-medium text-sm">Title</label>
            <input wire:model="title" type="text" class="w-full mt-1 border border-gray-300 rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block font-medium text-sm">Abstract</label>
            <textarea wire:model="abstract" class="w-full mt-1 border border-gray-300 rounded px-3 py-2" rows="4"></textarea>
        </div>

        <div>
            <label class="block font-medium text-sm">Markdown Content</label>
            <textarea wire:model="markdown_content" class="w-full mt-1 border border-gray-300 rounded px-3 py-2" rows="8" placeholder="Write your paper in Markdown..."></textarea>
        </div>

        <div>
            <label class="block font-medium text-sm mb-2">Researchers</label>
            @foreach ($researchers as $index => $researcher)
                <div class="flex items-center space-x-2 mb-2">
                    <input wire:model="researchers.{{ $index }}.name" type="text" class="w-1/2 border rounded px-2 py-1" placeholder="Name" required>
                    <input wire:model="researchers.{{ $index }}.affiliation" type="text" class="w-1/2 border rounded px-2 py-1" placeholder="Affiliation">
                    <button type="button" wire:click="removeResearcher({{ $index }})" class="text-red-500 hover:text-red-700 font-bold">✕</button>
                </div>
            @endforeach
            <button type="button" wire:click="addResearcher" class="text-blue-500 hover:underline">+ Add Researcher</button>
        </div>

        <div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Submit Paper</button>
        </div>
    </form>
</div>
