@extends('layouts.public')

@section('content')
    @include('public.includes.topbox')

    @if (Auth::check() && (Auth::user()->id === $self->starter()->id))
        <a href="#" id="edittitle">Izmeni naslov</a>
        <form id="edittitle-form" action="{{ route('public.topic.title', ['topic' => $self->slug]) }}" method="post" class="m-2" style="display: none;">
            {{ csrf_field() }}
            <div class="form-group d-flex flex-wrap">
                <label for="title" class="sr-only">Novi naslov</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') ?? $self->title }}">
                <button type="submit" class="ml-1 btn btn-success">Izmeni</button>
            </div>
        </form>
    @endif

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

            <div class="actions">
                <ul>
                    @auth
                        @if (Auth::id() == $user->id)
                            <li><a href="#" class="editpost" data-postid="{{ $post->id }}">Izmeni</a></li>
                            <li><a href="#" class="deletepost" data-postid="{{ $post->id }}">Obriši</a></li>
                        @endif
                        <li><a href="#" class="quotepost" data-postid="{{ $post->id }}">Citiraj</a></li>
                    @endauth
                    <li><a href="#top" class="back2top" title="Top">Top</a></li>
                </ul>
            </div>

        </div>
    @endforeach

    @auth
        <form action="{{ route('public.post.create', ['topic' => $self->id]) }}" method="post">
            @csrf

            <div class="form-group">
                <label for="sceditor" class="sr-only">Poruka</label>
                <textarea id="sceditor" name="content">{{ old('content') }}</textarea>
            </div>

            <div class="form-group">
                <div class="text-center">
                    <button class="btn btn-success" type="submit">
                        Pošalji odgovor
                    </button>
                </div>
            </div>
        </form>

        @include('includes.overlay')
        @include('includes.sceditor')
    @endauth
@stop
