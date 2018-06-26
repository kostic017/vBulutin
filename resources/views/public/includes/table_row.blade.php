<tr class="table-row">
    <td class="icon">
        <img class="iconpost" src="{{ asset("img/forum_{$row->readStatus()}.png") }}">
    </td>
    <td class="main-info">
        <a href="{{ isset($is_topic) ? route_topic_show($row) : route_forum_show($row) }}" class="name">{{ $row->title }}</a>
        @if (!if_route('public.forum.show') && $child_forums->count())
            <ul class="subforum-list post-list">
                @foreach ($child_forums as $child_forum)
                    <li>
                        <img class="iconpost" src="{{ asset("img/subforum_{$child_forum->readStatus()}.png") }}">
                        <a href="{{ route_forum_show($child_forum) }}">{{ $child_forum->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </td>
    <td class="side-info count">
        @if (if_route('public.forum.show'))
            <strong>{{ $row->postCount() - 1 }}</strong> odgovora
        @else
            <strong>{{ $row->topics()->get()->count() }}</strong> tema/e<br>
            <strong>{{ $row->postCount() }}</strong> poruka/e
        @endif
    </td>
    <td class="side-info lastpost">
        @if ($lastPost = $row->lastPost())
            @php ($user = $lastPost->user()->first())
            @php ($topic = $lastPost->topic()->first())
            <div class="post-info">
                <a href="{{ route_user_show($user) }}">@avatar(medium)</a>
                <ul>
                    <li><a href="{{ route_topic_show($topic) }}">{{ limit_words($topic->title) }}</a></li>
                    <li><a href="{{ route_user_show($user) }}">{{ $user->username }}</a></li>
                    <li>{{ extract_date($lastPost->created_at) }}</li>
                    <li>{{ extract_time($lastPost->created_at) }}</li>
                </ul>
            </div>
        @endif
    </td>
</tr>
