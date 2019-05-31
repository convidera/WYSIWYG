@if(!Auth::user() || !$editable || !$data)

    {!! $tag ? "<$tag src=\"$data->url\">" : '' !!}

@else

    {!! $tag ? "<$tag src=\"$data->url\">" : '' !!}

@endif
