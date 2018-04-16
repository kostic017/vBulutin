@extends('layouts.admin')

@section('content')
    <div class="card p-3">

        <div class="card-header">
            <h3>{{ __('admin.admin-panel') }}</h3>
        </div>

        <div class="card-body">
            <p>{{ __('admin.info1') }}</p>
            <p>{{ __('admin.info2') }}</p>
            <p>{{ __('admin.info3') }}</p>
            <p>{{ __('admin.info4') }}</p>
        </div>

    </div>
@stop
