@extends('layouts.public')

@section('content')
    @include('public.includes.topbox')

    @if ($topics->isEmpty())
        <p>Nema tema u ovom forumu.</p>
    @else
        @if (!$children->isEmpty())
            <table class="main-table">
                <caption>Potforumi</caption>

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
                                    <a href="{{ route('public.user', ['user' => $user->username]) }}">@avatar(medium)</a>
                                    <ul>
                                        <li><a href="{{ route('public.topic', ['topic' => $topic->slug]) }}">{{ limit_words($topic->title) }}</a></li>
                                        <li><a href="{{ route('public.user', ['user' => $user->username]) }}">{{ $user->username }}</a></li>
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

        <table class="main-table">
            <caption>Teme</caption>

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
                            <a href="{{ route('public.user', ['user' => $user->username]) }}">@avatar(medium)</a>
                            <ul>
                                <li><a href="{{ route('public.topic', ['topic' => $topic->slug]) }}">{{ limit_words($topic->title) }}</a></li>
                                <li><a href="{{ route('public.user', ['user' => $user->username]) }}">{{ $user->username }}</a></li>
                                <li>{{ extractDate($lastPost->created_at) }}</li>
                                <li>{{ extractTime($lastPost->created_at) }}</li>
                            </ul>
                        </div>
                    </td>

                </tr>

            @endforeach
        </table>
    @endif
@stop
