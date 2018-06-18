@if ($errors->any())
    <span class="invalid-feedback" style="display:block">
        <ul class="m-0 list-unstyled">
            @foreach ($errors->all() as $error)
                <li><strong>{{ $error }}</strong></li>
            @endforeach
        </ul>
    </span>
@endif
