@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @include('layouts.partials.search', ['route' => '?', 'sort' => $sort])

    <div class="row">
        <div class="col-md-12">
            {!! $logs->appends(['sort' => $sort, 'type' => $type])->render() !!}
            <div class="logs-list">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Message</th>
                    </tr>
                    </thead>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->dt }}</td>
                        <td>{{ $log->type }}</td>
                        <td>{{ $log->message }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection