@extends('layouts.public')

@section('content')
    <div class="top-box">
        <ul class="path">
            <li><a href="{{ route('public.categories.show', ['category' => $category->slug]) }}">{{ $category->title }}</a></li>
            @if (isset($parent))
                <li><a href="{{ route('public.forums.show', ['forum' => $parent->slug]) }}">{{ $parent->title }}</a></li>
            @endif
            <li><a href="{{ route('public.forums.show', ['forum' => $forum->slug]) }}">{{ $forum->title }}</a></li>
        </ul>
        <div class="page-info">
            <h2>{{ $self->title }}</h2>
        </div>
    </div>

    @if ($is_admin || Auth::id() === $topicStarter->id)
        <form id="solutionform" method="post" action="{{ route('public.topics.solution', ['id' => $self->id]) }}">
            @csrf
            <input type="hidden" name="solution_id" value="{{ $solution->id ?? '' }}">
        </form>

        <a href="#" id="edittitle">Izmeni naslov</a>
        <form id="edittitle-form" action="{{ route('public.topics.title', ['id' => $self->id]) }}" method="post" class="m-2" style="display: none;">
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
        @if ($is_admin)
            <form action="{{ route('public.topics.lock', ['id' => $self->id]) }}" method="post">
                @csrf
                <button type="submit" class="btn btn-{{ $self->is_locked ? 'success' : 'danger' }}">
                    {{ $self->is_locked ? 'Otključaj' : 'Zaključaj' }} temu
                </button>
            </form>
        @endif
    </div>

    @foreach ($posts as $post)
        @php ($user = $post->user()->first())

        <div class="post p-main {{ $post->deleted_at ? 'deleted' : '' }} {{ $self->solution_id === $post->id ? 'solution' : ''}}" id="post-{{ $post->id }}">

            <div class="d-flex flex-wrap">
                <ul class="profile">
                    <li><a href="{{ route('website.users.show', ['username' => $user->username]) }}">@avatar(medium)</a></li>
                    <li><a href="{{ route('website.users.show', ['username' => $user->username]) }}">{{ $user->username }}</a></li>
                    <li><strong>Broj poruka: </strong>{{ $user->posts()->get()->count() }}</li>
                    <li><strong>Pridružio: </strong>{{ extract_date($user->registered_at) }}</li>
                </ul>
                <div class="body">
                    <p class="author">
                        <a href="{{ route('public.topics.show', ['slug' => $self->slug]) }}#post-{{ $post->id }}"><img class="icon-post-target" src="{{ asset('img/icon_post_target.png') }}" alt="Post"></a>
                        napisao <strong><a href="" class="username-coloured">{{ $user->username }}</a></strong> » {{ extract_date($post->created_at) }} {{ extract_time($post->created_at) }}
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
                        @if ($is_admin || Auth::id() == $user->id)
                            @if ($is_admin || $lastPost->id === $post->id)
                                <li>
                                    @if ($post->deleted_at)
                                        <form method="post" action="{{ route('public.posts.restore', ['id' => $post->id]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-link">Vrati</button>
                                        </form>
                                    @else
                                        <form method="post" action="{{ route('public.posts.destroy', ['id' => $post->id]) }}">
                                            @csrf
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-link">Obriši</button>
                                        </form>
                                    @endif
                                </li>
                            @endif
                        @endif
                        @if (!$post->deleted_at && ($is_admin || Auth::id() == $topicStarter->id))
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

    @if ($self->is_locked)
        <p>Tema je zaključana, te nije moguće odgovarati na nju.</p>
    @elseif ($forum->is_locked)
        <p>Tema se nalazi u zaključanom forumu, te nije moguće odgovorati na nju.</p>
    @elseif (Auth::check())
        <form action="{{ route('public.posts.store') }}" method="post" id="scform">
            @csrf

            <input type="hidden" name="topic_id" value="{{ $self->id }}">

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
