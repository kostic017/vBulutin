<table class="main-table table-hover">
    @if (if_route('boards.show'))
        <caption>
            <div class="flex-center-xy">
                <a href="{{ route_category_show($category) }}">{{ $category->title }}</a>
                <a href="#top" class="back2top" title="Top">Top</a>
            </div>
        </caption>
    @endif

    @if (($parent_forums = $category->parent_forums()->orderBy('position')->get())->isEmpty())
        <tr class="table-row">
            <td>Nema foruma u ovoj kategoriji.</td>
        </tr>
    @else
        @foreach ($parent_forums as $_parent_forum)
            @include('public.includes.table-row', ['row' => $_parent_forum, 'child_forums' => $_parent_forum->children()->orderBy('position')->get()])
        @endforeach
    @endif
</table>
