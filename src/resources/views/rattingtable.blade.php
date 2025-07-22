@extends('web::layouts.grids.12')

@section('title', "Ratting Monitor | $system_name")
@section('page_header', "Ratting Monitor")


@section('full')
    @include("treelib::giveaway")

    <div class="card">
        <div class="card-body">
            <h2>Ratting Monitor</h2>
            <form action="" method="GET">
                <div class="form-group">
                    <label for="location">System</label>
                    <select
                            placeholder="enter the name of a system"
                            class="form-control basicAutoComplete"
                            autocomplete="off"
                            id="location"
                            data-url="{{ route("rattingmonitor.systems") }}"
                            name="system">
                    </select>
                    <small class="form-text text-muted">From which system should the data be fetched?</small>
                </div>

                <div class="form-group">
                    <label>Time</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="timeType" id="timeDays" value="days" @checked($timeType==="days")>
                        <label class="form-check-label" for="timeDays">
                            Last n days
                        </label>
                        <input class="form-control" type="number" name="days" id="days" min="1" value="{{ $days }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="timeType" id="timeMonth" value="month" @checked($timeType==="month")>
                        <label class="form-check-label" for="timeMonth">
                            Select a month
                        </label>
                        <input type="month" class="form-control" name="month" value="{{ $month }}">
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row justify-content-between">
                <h2>Favorites</h2>
                @if($favorites->where("system_id",$system)->isEmpty())
                    <form method="POST" class="float-left" action="{{ route("rattingmonitor.favorites") }}">
                        @csrf
                        <button class="btn btn-primary">Add {{ $system_name }}</button>
                        <input type="hidden" name="add_favorite" value="{{ $system }}">
                    </form>
                @else
                    <form method="POST" class="float-left" action="{{ route("rattingmonitor.favorites") }}">
                        @csrf
                        <button class="btn btn-danger">Remove {{ $system_name }}</button>
                        <input type="hidden" name="remove_favorite" value="{{ $system }}">
                    </form>
                @endif
            </div>

            <ul class="list-group">
                @foreach($favorites as $favorite)
                    <a href="{{ route(Route::current()->getName(),["timeType" => "days", "days"=>$days,"system"=>$favorite->system_id,"system_text"=>$favorite->system->name]) }}"
                       class="list-group-item list-group-item-action">
                        {{ $favorite->system->name }}
                    </a>
                @endforeach
                @if($favorites->isEmpty())
                    <li class="list-group-item">
                        You haven't added any favorites.
                    </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h2>{{ $system_name }}</h2>

            {!! $dataTable->table() !!}
    </div>
@stop

@push('javascript')
    <script src="{{ asset('web/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('web/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="{{ asset("rattingmonitor/js/bootstrap-autocomplete.js") }}"></script>

    <script>
        $('.basicAutoComplete').autoComplete({
            resolverSettings: {
                requestThrottling: 50
            },
            minLength: 0,
        });

        $('#location').autoComplete('set', {
            value: "{{ $system }}",
            text: "{{ $system_name }}"
        });
    </script>

    {{$dataTable->scripts()}}
@endpush
