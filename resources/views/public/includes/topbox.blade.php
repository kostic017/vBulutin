@if (isset($topbox))
    <div class="top-box">

        <ul class="path">
            <li><a href="/">{{ config('app.name') }}</a></li>
            @if (isset($category) && $topbox !== 'category')
                <li><a href="{{ route('front.categories.show', ['category' => $category->slug]) }}">{{ $category->title }}</a></li>
            @endif
            @if (isset($parent))
                <li><a href="{{ route('front.forums.show', ['forum' => $parent->slug]) }}">{{ $parent->title }}</a></li>
            @endif
            @if (isset($forum))
                <li><a href="{{ route('front.forums.show', ['forum' => $forum->slug]) }}">{{ $forum->title }}</a></li>
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
                                <li><a href="{{ route('front.users.show', ['profile' => $mod->username]) }}">{{ $mod->username }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

        </div>

    </div>
@endif
