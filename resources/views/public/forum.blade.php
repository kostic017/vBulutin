@extends('layouts.public')

@section('content')
    @include('public.includes.topbox')

    <div class="topbox-actions">
        <p><a href="#scform">Otvori temu</a></p>
        @if ($is_admin)
            <form action="{{ route('front.forum.lock', ['slug' => $self->slug]) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-{{ $self->is_locked ? 'success' : 'danger' }}">
                    {{ $self->is_locked ? 'Otključaj' : 'Zaključaj' }} forum
                </button>
            </form>
        @endif
    </div>

    @if (!$children->isEmpty())
        <table class="main-table table-hover">
            <caption>
                <div class="d-flex justify-content-space-beetween">
                    Potforumi
                    <a href="#top" class="back2top" title="Top">Top</a>
                </div>
            </caption>

            @foreach ($children as $child)
                <tr class="table-row">

                    <td class="icon">
                        <img class="iconpost" src="{{ asset("img/forum_{$child->readStatus()}.png") }}">
                    </td>

                    <td class="main-info">
                        <a href="{{ route('front.forums.show', ['forum' => $child->slug]) }}" class="name">{{ $child->title }}</a>
                    </td>

                    <td class="side-info count">
                        <strong>{{ $child->topics()->get()->count() }}</strong> tema/e<br>
                        <strong>{{ $child->postCount() }}</strong> poruka/e
                    </td>

                    <td class="side-info lastpost">
                        @if ($lastPost = $child->lastPost())
                            @php ($user = $lastPost->user()->first())
                            @php ($topic = $lastPost->topic()->first())

                            <div class="post-info">
                                <a href="{{ route('front.users.show', ['profile' => $user->username]) }}">@avatar(medium)</a>
                                <ul>
                                    <li><a href="{{ route('front.topics.show', ['topic' => $topic->slug]) }}">{{ limit_words($topic->title) }}</a></li>
                                    <li><a href="{{ route('front.users.show', ['profile' => $user->username]) }}">{{ $user->username }}</a></li>
                                    <li>{{ extractDate($lastPost->created_at) }}</li>
                                    <li>{{ extractTime($lastPost->created_at) }}</li>
                                </ul>
                            </div>
                        @endif
                    </td>

                </tr>
            @endforeach
        </table>
    @endif

    @if ($topics->isEmpty())
        <p>Nema tema u ovom forumu.</p>
    @else
        <table class="main-table">
            <caption>
                <div>
                    Teme
                    <a href="#top" class="back2top" title="Top">Top</a>
                </div>
            </caption>

            @foreach ($topics as $topic)

                <tr class="table-row">

                    <td class="icon">
                        <img class="iconpost" src="{{ asset("img/forum_{$topic->readStatus()}.png") }}">
                    </td>

                    <td class="main-info">
                        <a href="{{ route('front.topics.show', ['topic' => $topic->slug]) }}" class="name">{{ $topic->title }}</a>
                    </td>

                    <td class="side-info count">
                        <strong>{{ $topic->posts()->get()->count() - 1 }}</strong> odgovora<br>
                        <strong>1000</strong> pregleda
                    </td>

                    <td class="side-info lastpost">
                        @php ($lastPost = $topic->lastPost())
                        @php ($user = $lastPost->user()->first())

                        <div class="post-info">
                            <a href="{{ route('front.users.show', ['profile' => $user->username]) }}">@avatar(medium)</a>
                            <ul>
                                <li><a href="{{ route('front.topics.show', ['topic' => $topic->slug]) }}">{{ limit_words($topic->title) }}</a></li>
                                <li><a href="{{ route('front.users.show', ['profile' => $user->username]) }}">{{ $user->username }}</a></li>
                                <li>{{ extractDate($lastPost->created_at) }}</li>
                                <li>{{ extractTime($lastPost->created_at) }}</li>
                            </ul>
                        </div>
                    </td>

                </tr>

            @endforeach
        </table>
    @endif

    @if (!$self->is_locked)
        @auth
            <form action="{{ route('front.topics.store', ['forum' => $self->id]) }}" method="post" id="scform">
                @csrf
                <div class="form-group">
                    <label for="title">Naslov</label>
                    <input type="text" id="title" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') }}">
                    @if ($errors->has('title'))
                        <span class="invalid-feedback" style="display:block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

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
                    <button class="btn btn-success" type="submit">Otvori temu</button>
                </div>
            </form>

            @include('includes.sceditor')
        @endauth
    @endif
@stop
