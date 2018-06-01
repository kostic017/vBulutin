@extends('layouts.public')

@section('content')
    @include('public.includes.topbox')

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
                        <a href="{{ route('public.forum', ['forum' => $child->slug]) }}" class="name">{{ $child->title }}</a>
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
                                <a href="{{ route('public.profile.show', ['profile' => $user->username]) }}">@avatar(medium)</a>
                                <ul>
                                    <li><a href="{{ route('public.topic', ['topic' => $topic->slug]) }}">{{ limit_words($topic->title) }}</a></li>
                                    <li><a href="{{ route('public.profile.show', ['profile' => $user->username]) }}">{{ $user->username }}</a></li>
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
                        <a href="{{ route('public.topic', ['topic' => $topic->slug]) }}" class="name">{{ $topic->title }}</a>
                    </td>

                    <td class="side-info count">
                        <strong>{{ $topic->posts()->get()->count() - 1 }}</strong> odgovora<br>
                        <strong>1000</strong> pregleda
                    </td>

                    <td class="side-info lastpost">
                        @php ($lastPost = $topic->lastPost())
                        @php ($user = $lastPost->user()->first())

                        <div class="post-info">
                            <a href="{{ route('public.profile.show', ['profile' => $user->username]) }}">@avatar(medium)</a>
                            <ul>
                                <li><a href="{{ route('public.topic', ['topic' => $topic->slug]) }}">{{ limit_words($topic->title) }}</a></li>
                                <li><a href="{{ route('public.profile.show', ['profile' => $user->username]) }}">{{ $user->username }}</a></li>
                                <li>{{ extractDate($lastPost->created_at) }}</li>
                                <li>{{ extractTime($lastPost->created_at) }}</li>
                            </ul>
                        </div>
                    </td>

                </tr>

            @endforeach
        </table>
    @endif

    @auth
        <form action="{{ route('public.topic.create', ['forum' => $self->id]) }}" method="post">
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
                <textarea id="sceditor" id="content" name="content">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <div class="text-center">
                    <button class="btn btn-success" type="submit">
                        Otvori temu
                    </button>
                </div>
            </div>
        </form>

        @include('includes.sceditor')
    @endauth
@stop
