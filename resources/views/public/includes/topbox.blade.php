@if (isset($topbox))
    <div class="top-box">

        <ul class="path">
            <li><a href="/">{{ config('app.name') }}</a></li>
            <li><a href="{{ route('public.category', ['category' => $category->slug]) }}">{{ $category->title }}</a></li>
            @if (isset($parent))
                <li><a href="{{ route('public.forum', ['forum' => $parent->slug]) }}">{{ $parent->title }}</a></li>
            @endif
            @if (isset($forum))
                <li><a href="{{ route('public.forum', ['forum' => $forum->slug]) }}">{{ $forum->title }}</a></li>
            @endif
            @if (isset($topic))
                <li><a href="{{ route('public.topic', ['topic' => $topic->slug]) }}">{{ $topic->title }}</a></li>
            @endif
        </ul>

        <div class="page-info">

            <h2>{{ $self->title }}</h2>

            @if ($topbox !== 'topic')
                @if ($self->description)
                    <p class="desc">{{ $self->description }}</p>
                @endif

                <div class="mods">
                    Moderatori:
                    <ul>
                        <li><a href="">Pera</a></li>
                        <li><a href="">Zika</a></li>
                    </ul>
                </div>
            @endif

        </div>

    </div>
@endif
