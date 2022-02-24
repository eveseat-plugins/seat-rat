<a href="{{ route('character.view.default', ['character' => $character_id]) }}">
    {!! img('characters', 'portrait', $character_id, 32, ['class' => 'img-circle eve-icon'], false) !!}
    {{ $name }}
</a>