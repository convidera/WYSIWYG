@if(!Auth::user() || !$options->changeable || empty($data))

    {!! empty($options->tag) ? '' : "<$options->tag>" !!}
        {{ empty($data) ? '' : $data->value }}
    {!! empty($options->tag) ? '' : "</$options->tag>" !!}

@else

    <{{ empty($options->tag) ? 'span' : "$options->tag" }}
        class="WYSIWYG__container WYSIWYG__container-text WYSIWYG__container-text-plain"
        data-id="{{ $data->id }}"
        data-key="{{ $data->key }}"
        data-element-type="text"
        data-mime-type="text/plain"
        data-value-origin="{{ $data->value }}"
        data-value-saved="{{ $data->value }}"
        data-placeholder="{{env('DISPLAY_TEXT_ELEMENT_KEYS', false) ? '>>' . $data->key . '<<' : ''}}"
    >
        {{ $data->value }}
    </{{ empty($options->tag) ? 'span' : "$options->tag" }}>

@endif
