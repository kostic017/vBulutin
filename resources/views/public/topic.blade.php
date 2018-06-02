@extends('layouts.public')

@section('content')
    @include('public.includes.topbox')

    @if (Auth::check() && (Auth::user()->is_admin || Auth::id() === $topicStarter->id))
        <form id="solutionform" method="post" action="{{ route('public.topic.solution', ['topic' => $self->slug]) }}">
            @csrf
            <input type="hidden" name="solution_id" value="{{ $solution->id ?? '' }}">
        </form>

        <a href="#" id="edittitle">Izmeni naslov</a>
        <form id="edittitle-form" action="{{ route('public.topic.title', ['topic' => $self->slug]) }}" method="post" class="m-2" style="display: none;">
            @csrf
            <div class="form-group d-flex flex-wrap">
                <label for="title" class="sr-only">Novi naslov</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') ?? $self->title }}">
                <button type="submit" class="ml-1 btn btn-success">Izmeni</button>
            </div>
        </form>
    @endif

    <div class="topbox-actions">
        <p><a href="#scform">Pošalji odgovor</a></p>
        @if (Auth::user()->is_admin)
            <form action="{{ route('public.topic.togglelock', ['slug' => $self->slug]) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-{{ $self->is_locked ? 'success' : 'danger' }}">
                    {{ $self->is_locked ? 'Otključaj' : 'Zaključaj' }} temu
                </button>
            </form>
        @endif
    </div>

    @foreach ($posts as $post)
        @php ($user = $post->user()->first())

        <div class="post p-main" id="post-{{ $post->id }}">

            <div class="d-flex flex-wrap">
                <ul class="profile">
                    <li><a href="{{ route('public.profile.show', ['username' => $user->username]) }}">@avatar(medium)</a></li>
                    <li><a href="{{ route('public.profile.show', ['username' => $user->username]) }}">{{ $user->username }}</a></li>
                    <li><strong>Broj poruka: </strong>{{ $user->posts()->get()->count() }}</li>
                    <li><strong>Pridružio: </strong>{{ extractDate($user->registered_at) }}</li>
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
                        {{ $user->profile()->first()->signature }}
                    </div>
                </div>
            </div>

            <div class="actions">
                <ul>
                    @auth
                        {{-- @if (Auth::id() == $user->id)
                            <li><a href="#" class="editpost" data-postid="{{ $post->id }}">Izmeni</a></li>
                            @if ($lastPost->id === $post->id)
                                <li><a href="#" class="deletepost" data-postid="{{ $post->id }}">Obriši</a></li>
                            @endif
                        @endif --}}
                        @if (Auth::user()->is_admin || Auth::id() == $topicStarter->id)
                            @if ($solution && $solution->id === $post->id)
                                <li><a href="#" class="unmarksolution">Ipak ovo nije rešenje</a></li>
                            @else
                                <li><a href="#" class="marksolution" data-postid="{{ $post->id }}">Označi kao rešenje</a></li>
                            @endif
                        @endif
                        <li><a href="#" class="quotepost" data-postid="{{ $post->id }}">Citiraj</a></li>
                    @endauth
                    <li><a href="#top" class="back2top" title="Top">Top</a></li>
                </ul>
            </div>

        </div>
    @endforeach

    @if (!$self->is_locked)
        @auth
            <form action="{{ route('public.post.create', ['topic' => $self->id]) }}" method="post" id="scform">
                @csrf

                <div class="form-group">
                    <label for="sceditor" class="sr-only">Poruka</label>
                    <textarea id="sceditor" id="content" name="content" class="{{ $errors->has('content') ? ' is-invalid' : '' }}">{{ old('content') }}</textarea>

                    @if ($errors->has('content'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-success" type="submit">Pošalji odgovor</button>
                </div>
            </form>

            @include('includes.overlay')
            @include('includes.sceditor')
        @endauth
    @endif
@stop
