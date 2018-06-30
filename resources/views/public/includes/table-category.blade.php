<table class="main-table table-hover">
    @if (if_route('boards.show'))
        <caption>
            <div class="flex-center-xy">
                <a href="{{ route_category_show($category) }}">{{ $category->title }}</a>
                <a href="#top" class="back2top" title="Top">Top</a>
            </div>
        </caption>
    @endif

    @if (($parent_forums = $category->parent_forums()->get())->isEmpty())
        <tr class="table-row">
            <td>Nema foruma u ovoj kategoriji.</td>
        </tr>
    @else
        @foreach ($parent_forums as $parent_forum)
            @include('public.includes.table-row', ['row' => $parent_forum, 'child_forums' => $parent_forum->children()->get()])
        @endforeach
    @endif
</table>
