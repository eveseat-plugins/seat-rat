@extends('web::layouts.grids.12')

@section('title', "Ratting Monitor")
@section('page_header', "Ratting Monitor")


@section('full')
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
                    <label for="days">Days</label>
                    <input class="form-control" type="number" name="days" id="days" min="1" value="{{ $days }}">
                    <small class="form-text text-muted">How many days back should calculations be made?</small>
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
                    <form method="POST" class="float-left">
                        @csrf
                        <button class="btn btn-primary">Add {{ $system_name }}</button>
                        <input type="hidden" name="add_favorite" value="{{ $system }}">
                    </form>
                @else
                    <form method="POST" class="float-left">
                        @csrf
                        <button class="btn btn-danger">Remove {{ $system_name }}</button>
                        <input type="hidden" name="remove_favorite" value="{{ $system }}">
                    </form>
                @endif
            </div>

            <ul class="list-group">
            @foreach($favorites as $favorite)
                @if($favorite->system_id!=$system)
                    <a href="{{ route(Route::current()->getName(),["days"=>$days,"system"=>$favorite->system_id,"system_text"=>$favorite->system->name]) }}"
                       class="list-group-item list-group-item-action">
                        {{ $favorite->system->name }}
                    </a>
                @endif
            @endforeach
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
