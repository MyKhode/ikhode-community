<div x-data
     x-init="() => {
        ClassicEditor
            .create($refs.ckeditor)
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    $dispatch('input', editor.getData())
                });
            })
            .catch(error => {
                console.error(error);
            });
     }"
>
    <textarea
        x-ref="ckeditor"
        wire:model.defer="data.content"
        class="w-full h-64 border rounded p-2"
    ></textarea>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
