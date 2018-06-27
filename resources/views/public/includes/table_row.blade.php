<tr class="table-row">
    <td class="icon">
        <img class="iconpost" src="{{ asset('img/forum_' . ($row->is_read() ? 'old' : 'new') . '.png') }}">
    </td>
    <td class="main-info">
        <a href="{{ isset($is_topic) ? route_topic_show($row) : route_forum_show($row) }}" class="name">{{ $row->title }}</a>
        @if (!if_route('public.forum.show') && $child_forums->count())
            <ul class="subforum-list post-list">
                @foreach ($child_forums as $child_forum)
                    <li>
                        <img class="iconpost" src="{{ asset('img/subforum_' . ($row->is_read() ? 'old' : 'new') . '.png') }}">
                        <a href="{{ route_forum_show($child_forum) }}">{{ $child_forum->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </td>
    <td class="side-info count">
        @if (if_route('public.forum.show'))
            {{-- row = topic --}}
            <strong>{{ $row->posts()->count() - 1 }}</strong> odgovora
        @else
            {{-- row = forum --}}
            <strong>{{ $row->topics()->get()->count() }}</strong> tema/e<br>
            <strong>{{ $row->posts()->count() }}</strong> poruka/e
        @endif
    </td>
    <td class="side-info lastpost">
        @if ($last_post = $row->last_post())
            @php ($user = $last_post->user)
            @php ($topic = $last_post->topic)
            <div class="post-info">
                <a href="{{ route_user_show($user) }}">@avatar(medium)</a>
                <ul>
                    <li><a href="{{ route_topic_show($topic) }}">{{ limit_words($topic->title) }}</a></li>
                    <li><a href="{{ route_user_show($user) }}">{{ $user->username }}</a></li>
                    <li>{{ extract_date($last_post->created_at) }}</li>
                    <li>{{ extract_time($last_post->created_at) }}</li>
                </ul>
            </div>
        @endif
    </td>
</tr>
