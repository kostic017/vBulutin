@extends('layouts.public')

@section('content')
    @include('public.includes.topbox')

    @foreach ($posts as $post)
        @php ($user = $post->user()->first())

        <div class="post p-main" id="post-{{ $post->id }}">

            <div class="d-flex flex-wrap">
                <ul class="profile">
                    <li><a href="">@avatar(medium)</a></li>
                    <li><a href="" class="username-coloured">{{ $user->username }}</a></li>
                    <li>Site Admin</li>
                    <li><strong>Posts: </strong>{{ $user->posts()->get()->count() }}</li>
                    <li><strong>Joined: </strong>{{ extractDate($user->registered_at) }}</li>
                </ul>
                <div class="body">
                    <p class="author">
                        <a href="{{ route('public.topic', ['topic' => $self->slug]) }}#post-{{ $post->id }}"><img class="icon-post-target" src="{{ asset('img/icon_post_target.png') }}" alt="Post"></a>
                        napisao <strong><a href="" class="username-coloured">{{ $user->username }}</a></strong> » {{ extractDate($post->created_at) }} {{ extractTime($post->created_at) }}
                    </p>
                    <div class="content">
                        {!! BBCode::parse($post->content) !!}
                    </div>
                    <div class="signature">
                        Some signature text
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="#top" class="back2top" title="Top">Top</a>
            </div>

        </div>
    @endforeach

    @auth
        <form action="{{ route('public.post.create', ['topic' => $self->id]) }}" method="post">
            @csrf

            <div class="form-group">
                <label for="content" class="sr-only">Poruka</label>
                <textarea class="sceditor" id="content" name="content">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <div class="text-center">
                    <button class="btn btn-success" type="submit">
                        Pošalji odgovor
                    </button>
                </div>
            </div>
        </form>

        @include('includes.sceditor')
    @endauth
@stop
