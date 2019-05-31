@if(!Auth::user() || !$editable)

    {!! $tag ? "<$tag>" : '' !!}
        {{ $data ? Illuminate\Mail\Markdown::parse($data->value) : '' }}
    {!! $tag ? "</$tag>" : '' !!}

@else

    <{{ $tag ? "$tag" : 'span' }} class="WYSIWYG__container WYSIWYG__container-markdown"
        data-id="{{ $data->id }}"
        data-key="{{ $data->key }}"
        data-value-origin="{{ $data->value }}"
        data-value-saved="{{ $data->value }}"
        data-value-current="{{ $data->value }}"
        data-placeholder="{{env('DISPLAY_TEXT_ELEMENT_KEYS', false) ? '>>' . $data->key . '<<' : ''}}"
    >
        {{ Illuminate\Mail\Markdown::parse($data->value) }}
    </{{ $tag ? "$tag" : 'span' }}>

    {{  }}
@endif
