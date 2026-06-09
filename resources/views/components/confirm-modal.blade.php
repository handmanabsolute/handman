@props([ 'id', 'title', 'message', 'action', 'method' => 'POST', 'type' => 'danger' ])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">

        <div class="fixed inset-0 bg-gray-500/50 bg-opacity-75 transition-opacity" onclick="closeModal('{{ $id }}')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 relative z-50">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10 {{ $type === 'danger' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600' }}">
                        @if($type === 'danger')
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        @else
                            <i class="fa-solid fa-circle-question text-sm"></i>
                        @endif
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title">
                            {{ $title }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                {{ $message }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                @if(str_contains($action, 'executeGlobalAjaxSubmit'))
                    <button type="button" onclick="{{ $action }}" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 text-sm font-medium text-white sm:w-auto transition-colors cursor-pointer {{ $type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-[#3B28CC] hover:bg-opacity-90' }} disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Iya
                    </button>
                @else
                    <form action="{{ $action }}" method="POST" class="inline-block w-full sm:w-auto m-0">
                        @csrf
                        @method($method)
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 text-sm font-medium text-white sm:w-auto transition-colors cursor-pointer {{ $type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-[#3B28CC] hover:bg-opacity-90' }} disabled:bg-gray-400 disabled:cursor-not-allowed">
                            Iya
                        </button>
                    </form>
                @endif
                <button type="button" onclick="closeModal('{{ $id }}')" class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-xl border border-gray-200 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto transition-colors cursor-pointer disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof openModal !== 'function') {
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
    }

    if (typeof closeModal !== 'function') {
        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
    }
</script>
