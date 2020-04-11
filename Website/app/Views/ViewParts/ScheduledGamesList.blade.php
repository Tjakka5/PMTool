@if(count($scheduledGames) > 0)
    @foreach ($scheduledGames as $scheduledGame)
        <li class="collection-item games" data-id="{{$scheduledGame["ID"]}}">
            <b>{{ $scheduledGame["StartTime"] }}</b>
        </li>
    @endforeach
@else
    <li class="collection-item"><b>Er zijn geen geplande games</b></li>
@endif
