@once
    @push('head-scripts')
        @vite('resources/js/auth/components/excelItem')
    @endpush
@endonce

@if(count($files) > 0)
    @foreach($files as $file)
        @php
            $status = $file->status();
        @endphp

        <div class="excel-item-container">
            <div class="mb-2">
                <p class="excel-item-filename">
                    @if($file->filename)
                        {{ $file->filename }}
                    @else
                        Arquivo
                    @endif
                </p>
            </div>
            <p class="{{ 'process-' . $status->id }}">
                {{$status->title}}
            </p>
            @if(isset($status))
                @if($status->id === 3)
                    <div class="flex justify-between items-center">
                        <button data-id="{{$file->id}}" class="soft-normal-btn-red excelItemDeleteBtn" href="{{asset('storage/' . $file->filename)}}" download="{{$file->filename}}">
                            Deletar
                        </button>
                        <a class="soft-normal-btn" href="{{asset('storage/' . $file->filename)}}" download="{{$file->filename}}">
                            Baixar
                        </a>
                    </div>
                @endif
            @endif
        </div>

    @endforeach
@else
    <p class="text-primary text-center">Você não tem nenhuma importação</p>
@endif
