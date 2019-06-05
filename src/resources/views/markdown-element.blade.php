@if(!Auth::user() || !$editable)

    {!! $tag ? "<$tag>" : '' !!}
        {{ $data ? Illuminate\Mail\Markdown::parse($data) : '' }}
    {!! $tag ? "</$tag>" : '' !!}

@else

    <{{ $tag ? "$tag" : 'span' }}
        class="WYSIWYG__container WYSIWYG__container-text WYSIWYG__container-text-markdown"
        data-id="{{ $data->id }}"
        data-key="{{ $data->key }}"
        data-element-type="text"
        data-mime-type="text/markdown"
        data-value-origin="{{ $data->value }}"
        data-value-saved="{{ $data->value }}"
        data-value-current="{{ $data->value }}"
        data-placeholder="{{env('DISPLAY_TEXT_ELEMENT_KEYS', false) ? '>>' . $data->key . '<<' : ''}}"
    >
        {{ Illuminate\Mail\Markdown::parse($data->value) }}
    </{{ $tag ? "$tag" : 'span' }}>

@endif
