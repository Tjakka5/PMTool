<div class="row">
    <div class="col l12">
        <label for="player-list">Spelers</label>
        <ul id="player-list" class="collection">
            @if(count($playerList) > 0)
                @foreach($playerList as $player)
                    <li class="collection-item">{{$player["UserName"]}}</li>
                @endforeach
            @else
                <li class="collection-item">Er zijn nog geen spelers ingeschreven voor dit spel</li>
            @endif
        </ul>
    </div>
</div>

<div class="row">
    <div class="col l12">
        <input id="tournament-id" type="text" value="{{$tournamentID}}" hidden>

        @if($isJoined == 0)
            <button class="btn waves-effect waves-light" style="width: 100%" id="join-game">Inschrijven</button>
        @else
            <button class="btn waves-effect waves-light" style="width: 100%" id="leave-game" invisible>Uitschrijven</button>
        @endif
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#join-game").on("click", joinGame);
        $("#leave-game").on("click", leaveGame)
    })

    function joinGame() {
        $.ajax({
            method: "POST",
            url: "@asset('Tournament/JoinGame')",
            dataType: "json",
            data: {
                "TournamentID": $("#tournament-id").val(),
            }
        })
        .done(serverSuccess(function(response) {
            wsc.send(JSON.stringify({
                "command": "userSignup",
            }));
        }))
        .fail(serverError);
    }

    function leaveGame(){
        $.ajax({
            method: "POST",
            url: "@asset('Tournament/LeaveGame')",
            dataType: "json",
            data: {
                "TournamentID": $("#tournament-id").val(),
            }
        })
        .done(serverSuccess(function(response) {
            wsc.send(JSON.stringify({
                "command": "userSignout",
            }));
        }))
        .fail(serverError);
    }
</script>