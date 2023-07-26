@extends('layouts.base')

@push('head-scripts')
    @vite('resources/js/auth/home.js')
@endpush
@push('css')
    @vite('resources/css/pages/home.css')
@endpush

@section('title', 'Início')

@section('content')
    <main class="safe-area py-12 w-full min-h-screen flex justify-center items-center">
        <div class="w-full flex flex-col gap-8 min-h-[350px]">
           @include('components.logo')

            <div class="col-span-12 bg-primary/10 p-8 rounded-md min-w-md w-full">
                <h2 class="font-bold text-xl text-center">Importe um arquivo Excel</h2>

                <div class="mt-8 w-full flex flex-col justify-center items-center gap-6 py-12 bg-primary/20 rounded-md min-h-[250px]">
                    <p id="nomeArquivoImportado" class="text-primary hidden"></p>
                    <label for="importarArquivo" id="labelImportarArquivo" class="normal-rounded-btn cursor-pointer">
                        Escolher arquivo
                    </label>
                    <input type="file" name="file" id="importarArquivo" class="hidden" accept=".xlsx" multiple="false">
                </div>

                <div id="arquivoImportado" class="hidden mt-12 p-4 bg-white rounded-md">
                    <h3 class="text-lg text-center">
                        Antes de fazer a importação preciso que você digite o nome da coluna que quer filtrar o número de telefone
                    </h3>

                    <button id="definirInputBtn" class="normal-rounded-btn mx-auto mt-8">Definir nome</button>
                </div>
            </div>
            <div class="col-span-12 bg-primary/10 p-8 rounded-md w-full max-h-[90vh] overflow-x-hidden overflow-y-auto">
                <div class="flex items-center justify-between mb-8">
                    <button id="updateListBtn" class="soft-normal-btn">Atualizar lista</button>
                    <p id="updateList"></p>
                </div>

                {{-- Lista gerada via AJAX --}}
                <div id="excelList" class="grid grid-cols-12 gap-6"></div>
            </div>
        </div>
    </main>
    @include('components.auth.inputModal')
    @include('components.baseModal')
@endsection
