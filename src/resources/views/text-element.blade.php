@if(!Auth::user() || !$editable || !$data)

    {!! $tag ? "<$tag>" : '' !!}
        {{ $data ? $data->value : '' }}
    {!! $tag ? "</$tag>" : '' !!}

@else

    <{{ $tag ? "$tag" : 'span' }} class="WYSIWYG__container"
        data-id="{{ $data->id }}"
        data-key="{{ $data->key }}"
        data-mime-type="text/plain"
        data-value-origin="{{ $data->value }}"
        data-value-saved="{{ $data->value }}"
        data-placeholder="{{env('DISPLAY_TEXT_ELEMENT_KEYS', false) ? '>>' . $data->key . '<<' : ''}}"
    >
        {{ $data->value }}
    </{{ $tag ? "$tag" : 'span' }}>

@endif
