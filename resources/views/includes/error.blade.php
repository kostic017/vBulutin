@if ($errors->has($error_key))
    <span class="invalid-feedback" style="display:block">
        <strong>{{ $errors->first($error_key) }}</strong>
    </span>
@endif
