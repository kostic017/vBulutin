@extends("admin.base")

@section("title")
    {{ $tableName }}
@stop

@section("content")
    @if (empty($rows))
        <p>
            Tabela je prazna.<br>
            <a href="{{ route("{$tableName}.create") }}">Ubaci neki podatak.</a>
        </p>
    @else
        <table class="table table-striped horizontal">
            <thead class="thead-dark">
                <tr>
                    @foreach ($rows[0] as $key => $value)
                        <th>{{ $key }}</th>
                    @endforeach
                </tr>
            </thead>
            @foreach ($rows as $row)
                <tr>
                    @foreach ($row as $value)
                        <td><div>{{ $value }}</div></td>
                    @endforeach
                </tr>
            @endforeach
        </table>

        <section class="vertical">
            @foreach ($rows as $row)
                {{-- <ul class="list-unstyled">
                    @foreach ($row as $key => $value)
                        <li><b>{{ $key }}:</b> {{ $value }}</li>
                    @endforeach
                </ul> --}}
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
