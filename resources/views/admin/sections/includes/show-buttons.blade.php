<table class="mt-3">
    <tr>
        <td>
            <a href="{{ route("back.{$table['plural']}.edit", [$table['singular'] => $row->slug]) }}" class="btn btn-xs btn-info">
                {{ __('admin.edit') }}
            </a>
        </td>
        <td>
            @if ($row->trashed())
                <form action="{{ route("back.{$table['plural']}.restore", [$table['singular'] => $row->slug]) }}" method="post">
                    @csrf
                    <button class="btn btn-xs btn-danger" type="submit">{{ __('admin.restore') }}</button>
                </form>
            @else
                <form action="{{ route("back.{$table['plural']}.destroy", [$table['singular'] => $row->slug]) }}" method="post">
                    @csrf
                    {{ method_field('DELETE') }}
                    <button class="btn btn-xs btn-danger" type="submit">{{ __('admin.delete') }}</button>
                </form>
            @endif
        </td>
    </tr>
</table>
