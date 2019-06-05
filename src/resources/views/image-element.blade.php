{{dd($data)}}
{{dd($options)}}
@if(!Auth::user() || !$options->$changeable)

    <{{ $options->tag }}
        @if(!empty($options->additionalClasses))
            class="{{ $options->additionalClasses }}"
        @endif
        @if(!empty($options->additionalAttributes))
            {{ $options->additionalAttributes }}
        @endif

        @if(!empty($data->url))
            @if($options->asBackgoundImage)
                style="background-image: url({{ $data->url }});"
            @else
                src="{{ $data->url }}"
            @endif
        @endif
    >

    @if($options->closeTag)
        </{{ $options->tag }}>
    @endif

@else

    <{{ $options->tag }}
        class="WYSIWYG__container WYSIWYG__container-media {{ $options->additionalClasses ?: '' }}"
        data-id="{{ $data->id }}"
        data-key="{{ $data->key }}"
        data-element-type="media"
        data-mime-type="media"
        data-value-origin="{{ $data->url }}"
        data-value-saved="{{ $data->url }}"
        data-value-current="{{ $data->url }}"
        data-placeholder="{{ env('DISPLAY_TEXT_ELEMENT_KEYS', false) ? '>>' . $data->key . '<<' : '' }}"

        @if(!empty($options->additionalAttributes))
            {{ $options->additionalAttributes }}
        @endif

        @if(!empty($data->url))
            @if($options->asBackgoundImage)
                style="background-image: url({{ $data->url }});"
            @else
                src="{{ $data->url }}"
            @endif
        @endif
    >

    @if($options->closeTag)
        </{{ $options->tag }}>
    @endif

@endif
