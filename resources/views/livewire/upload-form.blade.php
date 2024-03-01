<div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex items-center justify-center min-h-screen mx-auto max-w-7xl sm:px-6 lg:px-8">
        <form wire:submit.prevent="submit">
            @csrf
            <div class="mb-3">
                <label
                    for="formFile"
                    class="mb-2 inline-block text-slate-900 font-bold">Upload Your PDF</label>
                <input
                    class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-blue-500 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary form-control"
                    type="file" wire:model="file" id="file" name="file" />
                @error('file') <span class="error">{{ $message }}</span> @enderror

                @if ($uploadSuccess)
                    <p class="mt-3 text-green-500">Please wait. Results pending...</p>
                @endif

                @if ($uploadError)
                    <p class="mt-3 text-red-500">Error: {{ $message }}</p>
                @endif

                @if ($file)
                    <button type="submit" class="mt-4 btn text-white bg-emerald-600 rounded px-4 py-2">Upload File</button>
                @endif
            </div>
        </form>

        @section('scripts')
            <script type="module">
                document.addEventListener('livewire:init', function () {
                    console.log('Livewire loaded.')
                    Echo.channel('document-analysis')
                        .listen('.document.analysis.completed', (e) => {
                            console.log('Event received:', e);
                            const detail = { jobId: e.jobId, analysisResult: e.analysisResult, status: e.status };
                            window.location.href = '/results/' + e.jobId;
                        });
                });
            </script>
        @endsection
    </div>
</div>

