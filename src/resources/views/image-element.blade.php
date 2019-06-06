@if(!Auth::user() || !$options->changeable)

    <{{ $options->tag }}
        @if(!empty($options->additionalClasses))
            class="{{ $options->additionalClasses }}"
        @endif
        @if(!empty($options->additionalAttributes))
            {{ $options->additionalAttributes }}
        @endif

        @if(!empty($data->value))
            @if($options->asBackgroundImage)
                style="background-image: url({{ $data->value }});"
            @else
                src="{{ $data->value }}"
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
        data-mime-type="media/image"
        data-value-origin="{{ $data->value }}"
        data-value-saved="{{ $data->value }}"
        data-value-current="{{ $data->value }}"
        data-placeholder="{{ env('DISPLAY_TEXT_ELEMENT_KEYS', false) ? '>>' . $data->key . '<<' : '' }}"

        @if(!empty($options->additionalAttributes))
            {{ $options->additionalAttributes }}
        @endif

        @if(!empty($data->value))
            @if($options->asBackgroundImage)
                style="background-image: url({{ $data->value }});"
            @else
                src="{{ $data->value }}"
            @endif
        @endif
    >

    @if($options->closeTag)
        </{{ $options->tag }}>
    @endif

@endif