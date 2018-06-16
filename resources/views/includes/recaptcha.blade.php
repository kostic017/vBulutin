<div class="form-group row mb-0">
    <div class="col-md-6 offset-md-4">
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <button type="submit" class="g-recaptcha btn btn-primary" data-callback="captchaOnSubmit" data-sitekey="{{ config('custom.captcha.site_key') }}">
            {{ $recaptcha_action }}
        </button>
    </div>
</div>
