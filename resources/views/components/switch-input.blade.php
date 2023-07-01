@once
    @push('head-scripts')
        @vite('resources/js/components/switch-input.js')
    @endpush
@endonce

<input type="hidden" name="{{$inputName}}" id="{{$inputId}}" value="{{ var_export($active) }}">
<div
    id="{{$switchId}}"
    data-input="{{$inputId}}"
    class="switch-input @if($disabled === true) disabled @endif @if($active === true) active @endif">
    <span class="switch-input-focus"></span>
</div>
