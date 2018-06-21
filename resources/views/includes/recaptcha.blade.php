@if (is_captcha_set())
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <button type="submit" class="g-recaptcha btn btn-primary" data-callback="captchaOnSubmit" data-sitekey="{{ config('custom.captcha.site_key') }}">
        {{ $recaptcha_action }}
    </button>
@else
    <button type="submit" class="btn btn-primary">
        {{ $recaptcha_action }}
    </button>
@endif
