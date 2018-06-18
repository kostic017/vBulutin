@extends('layouts.website')

@section('content')
    <div class="card-body">
        <div class="directories">
            @foreach ($directories as $directory)
                <div class="directory">
                    <a href="{{ route('website.directory', ['slug' => $directory->slug]) }}">
                        <h2>{{ $directory->title }}</h2>
                        <p>{{ limit_words($directory->description, 8) }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@stop
