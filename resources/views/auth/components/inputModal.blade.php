@once
    @push('head-scripts')
        @vite('resources/js/auth/components/inputModal')
    @endpush
@endonce

<div id="inputModal" class="hidden">
    <div id="inputModalBackground" class="fixed top-0 left-0 w-full min-h-screen bg-black/30 z-10"></div>

    <div class="fixed bottom-2/4 right-1/2 translate-y-1/2 translate-x-1/2 bg-white rounded-md p-8 z-20 max-w-md">
        <b class="text-xl">Escolha o nome da coluna</b>
        <p class="mt-3">
            Abra seu arquivo Excel e escolha o nome da coluna que você quer filtrar o número de telefone
        </p>

        <div class="w-full mt-8 flex flex-col gap-2">
            <label for="">Escolha o nome</label>
            <input type="text" id="columnInput" class="normal-input w-full" placeholder="Digite o nome da coluna...">
            <button id="inputModalFinalizar" class="normal-btn w-fit mt-3 self-end">Finalizar</button>
        </div>
    </div>
</div>
