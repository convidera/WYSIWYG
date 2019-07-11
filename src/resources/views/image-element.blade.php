@if(!Auth::user() || !$options->changeable || empty($data))

    <{{ $options->tag }}
        @if(!empty($options->additionalClasses))
            class="{{ $options->additionalClasses }}"
        @endif
        @if(!empty($options->additionalAttributes))
            {{ $options->additionalAttributes }}
        @endif

        @if(!empty($data))
            @if(!empty($data->value))
                @if($options->asBackgroundImage)
                    style="background-image: url({{ $data->value }});"
                @else
                    src="{{ $data->value }}"
                @endif
            @endif

            @foreach($data->textElements as $textElement)
                @if(!empty($textElement->value))
                    {{ $textElement->key }}="{{ str_replace('"', '\"', $textElement->value) }}"
                @endif
            @endforeach
        @endif
    >

    @if($options->closeTag)
        </{{ $options->tag }}>
    @endif

@else

    <{{ $options->tag }}
        class="WYSIWYG__container WYSIWYG__container-media WYSIWYG__container-media-image {{ $options->additionalClasses ?: '' }}"
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

        @foreach($data->textElements as $textElement)
            @if(!empty($textElement->value))
                {{ $textElement->key }}="{{ str_replace('"', '\"', $textElement->value) }}"
            @endif
        @endforeach
    >

    @if($options->closeTag)
        </{{ $options->tag }}>
    @endif

@endif
