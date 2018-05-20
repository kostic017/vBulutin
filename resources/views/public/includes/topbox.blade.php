@if (isset($topbox))
    <div class="top-box">

        <ul class="path">
            <li><a href="/">{{ config('app.name') }}</a></li>
            @if (isset($category) && $topbox !== 'category')
                <li><a href="{{ route('public.category', ['category' => $category->slug]) }}">{{ $category->title }}</a></li>
            @endif
            @if (isset($parent))
                <li><a href="{{ route('public.forum', ['forum' => $parent->slug]) }}">{{ $parent->title }}</a></li>
            @endif
            @if (isset($forum))
                <li><a href="{{ route('public.forum', ['forum' => $forum->slug]) }}">{{ $forum->title }}</a></li>
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
                    @if ($mods->isEmpty())
                        nema
                    @else
                        <ul>
                            @foreach ($mods as $mod)
                                <li><a href="{{ route('public.profile.show', ['profile' => $mod->username]) }}">{{ $mod->username }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

        </div>

    </div>
@endif
