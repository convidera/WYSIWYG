@if(!Auth::user() || !$editable)

    {!! $tag ? "<$tag>" : '' !!}
        {{ $data->value }}
    {!! $tag ? "</$tag>" : '' !!}

@else

    <span class="WYSIWYG__container"
        data-id="{{ $data->id }}"
        data-key="{{ $data->key }}"
        data-value-origin="{{ $data->value }}"
        data-value-saved="{{ $data->value }}"
        data-placeholder="{{env('DISPLAY_TEXT_ELEMENT_KEYS', false) ? '>>' . $data->key . '<<' : ''}}"
    >
        {{ $data->value }}
    </span>

@endif
