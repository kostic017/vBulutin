@extends('layouts.public')

@section('content')
    <div class="top-box">
        <ul class="path">
            <li><a href="{{ route_category_show($category) }}">{{ $category->title }}</a></li>
            @if (isset($parent_forum)) <li><a href="{{ route_forum_show($parent_forum) }}">{{ $parent_forum->title }}</a></li> @endif
            <li><a href="{{ route_forum_show($forum) }}">{{ $forum->title }}</a></li>
        </ul>
        <div class="page-info">
            <h2>{{ $topic->title }}</h2>
        </div>
    </div>

    @if ($is_admin || Auth::id() === $topic_starter->id)
        <form id="solutionform" method="post" action="{{ route('topics.solution', [$topic->id]) }}">
            @csrf
            <input type="hidden" name="solution_id" value="{{ $solution->id ?? '' }}">
        </form>
        <a href="#" id="edittitle">Izmeni naslov</a>
        <form id="edittitle-form" action="{{ route('topics.title', [$topic->id]) }}" method="post" class="m-2" style="display: none;">
            @csrf
            <div class="form-group d-flex flex-wrap">
                <label for="title" class="sr-only">Novi naslov</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') ?? $topic->title }}">
                <button type="submit" class="ml-1 btn btn-success">Izmeni</button>
            </div>
        </form>
    @endif

    @if ($is_admin || !$topic->is_locked)
        <div class="topbox-actions">
            @if (!$topic->is_locked) <p><a href="#scform">Pošalji odgovor</a></p> @endif
            @if ($is_admin)
                <form action="{{ route('topics.lock', [request()->route('board_address'), $topic->id]) }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-{{ $topic->is_locked ? 'success' : 'danger' }}">
                        {{ $topic->is_locked ? 'Otključaj' : 'Zaključaj' }} temu
                    </button>
                </form>
            @endif
        </div>
    @endif

    @foreach ($posts as $post)
        @php ($user = $post->user)
        <div class="post p-main {{ $post->deleted_at ? 'deleted' : '' }} {{ $topic->solution_id === $post->id ? 'solution' : ''}}" id="post-{{ $post->id }}">
            <div class="d-flex flex-wrap">
                <ul class="profile">
                    <li><a href="{{ route_user_show($user) }}">@avatar(medium)</a></li>
                    <li><a href="{{ route_user_show($user) }}">{{ $user->username }}</a></li>
                    <li><strong>Broj poruka: </strong>{{ $user->posts()->get()->count() }}</li>
                    <li><strong>Pridružio: </strong>{{ extract_date($user->registered_at) }}</li>
                </ul>
                <div class="body">
                    <p class="author">
                        <a href="{{ route_topic_show($topic) }}#post-{{ $post->id }}"><img class="icon-post-target" src="{{ asset('images/icon_post_target.png') }}" alt="Post"></a>
                        napisao <strong><a href="{{ route_user_show($user) }}">{{ $user->username }}</a></strong> » {{ extract_date($post->created_at) }} {{ extract_time($post->created_at) }}
                    </p>
                    <div class="content">
                        {!! BBCode::parse($post->content) !!}
                    </div>
                    <div class="signature">
                        {{ $user->profile->signature }}
                    </div>
                </div>
            </div>
            <div class="actions">
                <ul>
                    @auth
                        @if ($is_admin || (Auth::id() == $user->id && $last_post->id === $post->id))
                            <li>
                                @if ($post->deleted_at)
                                    <form method="post" action="{{ route('posts.restore', [$post->id]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-link">Vrati</button>
                                    </form>
                                @else
                                    <form method="post" action="{{ route('posts.destroy', [$post->id]) }}">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-link">Obriši</button>
                                    </form>
                                @endif
                            </li>
                        @endif
                        @if (!$post->deleted_at && ($is_admin || Auth::id() == $topic_starter->id))
                            @if ($solution && $solution->id === $post->id)
                                <li><a href="#" class="unmarksolution">Ipak nije ovo rešenje</a></li>
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

    @if ($topic->is_locked)
        <p>Tema je zaključana, te nije moguće odgovarati na nju.</p>
    @elseif ($forum->is_locked)
        <p>Tema se nalazi u zaključanom forumu, te nije moguće odgovorati na nju.</p>
    @elseif (Auth::check())
        <form action="{{ route('posts.store') }}" method="post" id="scform">
            @csrf
            <input type="hidden" name="topic_id" value="{{ $topic->id }}">
            <div class="form-group">
                <label for="sceditor" class="sr-only">Poruka</label>
                <textarea id="sceditor" id="content" name="content" class="{{ $errors->has('content') ? ' is-invalid' : '' }}">{{ old('content') }}</textarea>
                @include('includes.error', ['error_key' => 'content'])
            </div>

            <div class="form-group text-center">
                <button class="btn btn-success" type="submit">Pošalji odgovor</button>
            </div>
        </form>
        @include('includes.overlay')
        @include('includes.sceditor')
    @else
        <p>Samo prijavljeni korisnici mogu slati odgovore.</p>
    @endif
@stop

@section('scripts')
    <script>
        $(function() {
            $("#edittitle").on("click", function(event) {
                event.preventDefault();
                $("#edittitle-form").toggle();
            });

            $(".quotepost").on("click", function(event) {
                event.preventDefault();
                const postId = $(this).attr("data-postid");
                const editor = sceditor.instance(document.querySelector("#sceditor"));

                const overlay = $("#overlay");
                overlay.show();
                overlay.fitText();

                $.post('/ajax/quote', { postId }, function (code) {
                    editor.insert(code);
                    overlay.hide();
                }).fail(function() {
                    toastr.error($("span[data-key='generic.error']").text());
                    overlay.hide();
                });
            });

            $(".marksolution").on("click", function (event) {
                event.preventDefault();
                $("input[name=solution_id]").val($(this).attr("data-postId"));
                $("#solutionform").submit();
            });

            $(".unmarksolution").on("click", function(event) {
                event.preventDefault();
                $("input[name=solution_id]").val("");
                $("#solutionform").submit();
            });
        });
    </script>
@stop
