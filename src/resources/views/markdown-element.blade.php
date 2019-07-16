@if(!Auth::user() || !$options->changeable || empty($data))

    @if(!empty($options->tag))
        <{{ $options->tag }}
            @if(!empty($options->additionalClasses))
                class="{{ $options->additionalClasses }}"
            @endif
            @if(!empty($options->additionalAttributes))
                {!! $options->additionalAttributes !!}
            @endif
        >
    @endif

        {{ empty($data) ? '' : Illuminate\Mail\Markdown::parse($data->value) }}

    @if(!empty($options->tag) && $options->closeTag)
        </{{ $options->tag }}>
    @endif

@else

    <{{ empty($options->tag) ? 'span' : "$options->tag" }}
        class="WYSIWYG__container WYSIWYG__container-text WYSIWYG__container-text-markdown {{ $options->additionalClasses }}"
        data-id="{{ $data->id }}"
        data-key="{{ $data->key }}"
        data-element-type="text"
        data-mime-type="text/markdown"
        data-value-origin="{{ $data->value }}"
        data-value-saved="{{ $data->value }}"
        data-value-current="{{ $data->value }}"
        data-placeholder="{{env('DISPLAY_TEXT_ELEMENT_KEYS', false) ? '>>' . $data->key . '<<' : ''}}"

        @if(!empty($options->additionalAttributes))
            {!! $options->additionalAttributes !!}
        @endif
    >

        {{ Illuminate\Mail\Markdown::parse($data->value) }}
    
    @if(empty($options->tag) || $options->closeTag)
        </{{ empty($options->tag) ? 'span' : "$options->tag" }}>
    @endif

@endif
