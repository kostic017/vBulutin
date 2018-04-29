@extends('layouts.public')

@section('content')
    @include('public.includes.topbox')

    <div class="post-container">

        @foreach ($posts as $post)
            @php ($user = $post->user()->first())

            <div class="post">

                <div class="d-flex">
                    <ul class="profile">
                        <li><a href="">@avatar(medium)</a></li>
                        <li><a href="" class="username-coloured">{{ $user->username }}</a></li>
                        <li>Site Admin</li>
                        <li><strong>Posts: </strong>{{ $user->posts()->get()->count() }}</li>
                        <li><strong>Joined: </strong>{{ extractDate($user->registered_at) }}</li>
                    </ul>
                    <div class="body">
                        <p class="author">
                            <a href="{{ route('public.topic', ['topic' => $self->slug]) }}&amp;post={{ $post->id }}"><img class="icon-post-target" src="{{ asset('img/icon_post_target.png') }}" alt="Post"></a>
                            napisao <strong><a href="" class="username-coloured">{{ $user->username }}</a></strong> » {{ $post->created_at }}
                        </p>
                        <div class="content">
                            {!! BBCode::parse($post->content) !!}
                        </div>
                        <div class="signature">
                            Some signature text
                        </div>
                    </div>
                </div>

                <div class="back2top">
                    <a href="#wrap" class="top" title="Top">Top</a>
                </div>

            </div>
        @endforeach

    </div>
@stop
