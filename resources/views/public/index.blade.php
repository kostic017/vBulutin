@extends('layouts.public')

@section('content')

    @foreach ($categories as $category)
        @php ($parents = $category->forums()->get())

        <table class="main-table">

            <caption class="captionbar">
                <a href="category.php?id={{ $category->id }}">{{ $category->title }}</a>
            </caption>

            @if ($parents->isEmpty())

                <tr class="table-row">
                    <td>Nema foruma u ovoj sekciji.</td>
                </tr>

            @else

                @foreach ($parents as $parent)
                    @php ($children = $parent->children()->get())

                    <tr class="table-row">

                        <td class="icon">
                            <img class="iconpost" src="{{ asset("img/forum_{$parent->readStatus()}.png") }}">
                        </td>

                        <td class="main-info">
                            <a href="forum.php?id={{ $parent->id }}" class="name">{{ $parent->title }}</a>
                            @if ($children->count())
                                <div class="subforums">
                                    Potforumi:
                                    <ul class="subforum-list post-list">
                                        @foreach ($children as $child)
                                            <li>
                                                <img class="iconpost" src="{{ asset("img/subforum_{$child->readStatus()}.png") }}">
                                                <a href="forum.php?id={{ $child->id }}">{{ $child->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </td>

                        <td class="side-info">
                            <strong>{{ $parent->topics()->get()->count() }}</strong> tema/e<br>
                            <strong>{{ $parent->postCount() }}</strong> poruka/e
                        </td>

                        <td class="side-info">
                            @if ($lastPost = $parent->lastPost()):
                                <div class="post-info">
                                    <a href="profile.php?id={{ $lastPost->user()->id }}">
                                        {{-- displayAvatar($lastPost["user"], "avatar-small") --}}
                                    </a>
                                    <ul>
                                        <li><a href="">{{ $lastPost->user()->username() }}</a></li>
                                        <li>{{ extractTime($lastPost->created_at) }}</li>
                                        <li>{{ extractDate($lastPost->created_at) }}</li>
                                    </ul>
                                </div>
                            @endif
                        </td>

                    </tr>

                @endforeach

            @endif

        </table>

        {{-- <tr class="table-row">
            <td>
                <span class="icon icon-forum-redirect"></span>
                <a href="" class="name">Preusmeravanje, reklame?</a>
            </td>
            <td colspan="2"><strong>0</strong> klika</td>
        </tr> --}}
    @endforeach

@stop
