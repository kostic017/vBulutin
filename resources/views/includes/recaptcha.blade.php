@if (is_captcha_set())
    <button type="submit" class="g-recaptcha btn btn-primary" data-callback="recaptcha_submit" data-sitekey="{{ config('custom.captcha.site_key') }}">
        {{ $recaptcha_action }}
    </button>

    <script>
        function recaptcha_submit() {
            $("#submit-me").submit();
        }
    </script>

    <script src="https://www.google.com/recaptcha/api.js"></script>
@else
    <button type="submit" class="btn btn-primary">
        {{ $recaptcha_action }}
    </button>
@endif
