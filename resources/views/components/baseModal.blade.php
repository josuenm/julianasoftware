@once
    @push('head-scripts')
        @vite('resources/js/components/baseModal')
    @endpush
@endonce

<div id="baseModalContainer" class="hidden">
    <div id="baseModalBackground"></div>
    <div id="baseModalContent">
        <h4 id="baseModalTitle">
            Titulo do modal
        </h4>
        <p id="baseModalDescription">
            Essa é descrição do modal para explciar algo para o usuário
        </p>
        <div id="baseModalFooter">
            <button id="baseModalCloseBtn" class="soft-normal-btn-red">Fechar</button>
            <button id="baseModalNextBtn" class="normal-btn">Prosseguir</button>
        </div>
    </div>
</div>
