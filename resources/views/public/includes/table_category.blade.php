@php ($parents = $category->forums()->get())

<table class="main-table table-hover">

    <caption class="captionbar">
        @if (isset($topbox))
            Forumi
        @else
            <a href="{{ route('public.category', ['category' => $category->slug]) }}">{{ $category->title }}</a>
        @endif
    </caption>

    @if ($parents->isEmpty())
        <tr class="table-row">
            <td>Nema foruma u ovoj kategoriji.</td>
        </tr>
    @else
        @foreach ($parents as $parent)
            @php ($children = $parent->children()->get())

            <tr class="table-row">

                <td class="icon">
                    <img class="iconpost" src="{{ asset("img/forum_{$parent->readStatus()}.png") }}">
                </td>

                <td class="main-info">
                    <a href="{{ route('public.forum', ['forum' => $parent->slug]) }}" class="name">{{ $parent->title }}</a>
                    @if ($children->count())
                        <ul class="subforum-list post-list">
                            @foreach ($children as $child)
                                <li>
                                    <img class="iconpost" src="{{ asset("img/subforum_{$child->readStatus()}.png") }}">
                                    <a href="{{ route('public.forum', ['forum' => $child->slug]) }}">{{ $child->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </td>

                <td class="side-info count">
                    <strong>{{ $parent->topics()->get()->count() }}</strong> tema/e<br>
                    <strong>{{ $parent->postCount() }}</strong> poruka/e
                </td>

                <td class="side-info lastpost">
                    @if ($lastPost = $parent->lastPost())
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
    @endif

    <tr class="bgc-main">
        <td colspan="4">
            <div class="back2top">
                <a href="#top" class="top" title="Top">Top</a>
            </div>
        </td>
    </tr>

</table>

{{-- <tr class="table-row">
    <td>
        <span class="icon icon-forum-redirect"></span>
        <a href="" class="name">Preusmeravanje, reklame?</a>
    </td>
    <td colspan="2"><strong>0</strong> klika</td>
</tr> --}}
