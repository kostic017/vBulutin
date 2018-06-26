<table class="mt-3">
    <tr>
        <td>
            <a href="{{ route("admin.{$table['singular']}.edit", [$table['singular'] => $row->id]) }}" class="btn btn-xs btn-info">
                {{ __('admin.edit') }}
            </a>
        </td>
        <td>
            @if ($row->trashed())
                <form action="{{ route("admin.{$table['singular']}.restore", [$table['singular'] => $row->id]) }}" method="post">
                    @csrf
                    <button class="btn btn-xs btn-danger" type="submit">{{ __('admin.restore') }}</button>
                </form>
            @else
                <form action="{{ route("admin.{$table['singular']}.destroy", [$table['singular'] => $row->id]) }}" method="post">
                    @csrf
                    {{ method_field('DELETE') }}
                    <button class="btn btn-xs btn-danger" type="submit">{{ __('admin.delete') }}</button>
                </form>
            @endif
        </td>
    </tr>
</table>
