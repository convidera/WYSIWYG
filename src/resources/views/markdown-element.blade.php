@if(!Auth::user() || !$editable)

    {!! $tag ? "<$tag>" : '' !!}
        {{ $data ? Illuminate\Mail\Markdown::parse($data) : '' }}
    {!! $tag ? "</$tag>" : '' !!}

@else

    <{{ $tag ? "$tag" : 'span' }} class="WYSIWYG__container WYSIWYG__container-markdown"
        data-value-origin="{{ $data }}"
        data-value-saved="{{ $data }}"
        data-value-current="{{ $data }}"
        data-placeholder="{{env('DISPLAY_TEXT_ELEMENT_KEYS', false) ? '>>' . $data . '<<' : ''}}"
    >
        {{ Illuminate\Mail\Markdown::parse($data) }}
    </{{ $tag ? "$tag" : 'span' }}>

@endif
