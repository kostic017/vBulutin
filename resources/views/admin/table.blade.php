@extends("admin.base")

@section("title")
    {{ $table }}
@stop

@section("real-content")
    @if (empty($rows))
        <p>
            Tabela je prazna.<br>
            <a href="{{ route("{$table}.create") }}">Ubaci neki podatak.</a>
        </p>
    @else
        <section class="horizontal">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>&nbsp;</th>
                        @foreach ($rows[0] as $key => $value)
                            <th class="nowrap">
                                <div>
                                    <a href="javascript:void(0)" class="sort-link">{{ $key }}</a>
                                    <span class="icon sort-icon {{ active_class($sortColumn == $key, "ic_s_{$sortOrder}") }}"></span>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                @foreach ($rows as $row)
                    <tr id="row-{{ $row->id }}">
                        <td class="nowrap">
                            <a href="{{ route("{$table}.edit", [0 => $row->id]) }}">
                                <span class="icon ic_b_edit" title="Izmeni"></span>
                            </a>
                            <a href="{{ route("{$table}.destroy", [0 => $row->id]) }}">
                                <span class="icon ic_b_drop" title="Obriši"></span>
                            </a>
                        </td>
                        @foreach ($row as $value)
                            <td><div>{{ $value }}</div></td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        </section>

        <section class="vertical">
            @foreach ($rows as $row)
                <a href="{{ route("{$table}.edit", [0 => $row->id]) }}">
                    <span class="icon ic_b_edit"></span>Izmeni
                </a>
                <a href="{{ route("{$table}.destroy", [0 => $row->id]) }}">
                    <span class="icon ic_b_drop"></span>Obriši
                </a>
                <table class="table table-striped">
                    @foreach ($row as $key => $value)
                        <tr>
                            <td><b>{{ $key }}</b></td>
                            <td>{{ $value }}</td>
                        </tr>
                    @endforeach
                </table>
                <div class="line"></div>
            @endforeach
        </section>
    @endif
@stop
