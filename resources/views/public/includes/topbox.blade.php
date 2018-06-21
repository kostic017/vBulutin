@if (isset($topbox))
    <div class="top-box">

        <ul class="path">
            @if (isset($category) && $topbox !== 'category')
                <li><a href="{{ route('public.categories.show', ['category' => $category->slug]) }}">{{ $category->title }}</a></li>
            @endif
            @if (isset($parent))
                <li><a href="{{ route('public.forums.show', ['forum' => $parent->slug]) }}">{{ $parent->title }}</a></li>
            @endif
            @if (isset($forum))
                <li><a href="{{ route('public.forums.show', ['forum' => $forum->slug]) }}">{{ $forum->title }}</a></li>
            @endif
        </ul>

        <div class="page-info">
            <h2>{{ $self->title }}</h2>
            @if ($topbox !== 'topic')
                @if ($self->description)
                    <p class="desc">{{ $self->description }}</p>
                @endif
            @endif
        </div>

    </div>
@endif
