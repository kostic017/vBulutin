<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="author" content="Nikola Kostić">
        <meta name="viewport" content="width=device-width,initial-scale=1">

        <title>Forum41</title>

        <link rel="icon" href="/shared/images/favicon.ico">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/evol-colorpicker@3.3.1/css/evol-colorpicker.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">

        @yield("styles")

    </head>

    <body>

        @yield("body")

        <script src="https://www.google.com/recaptcha/api.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

        <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/jquery-nestable@0.8.0/jquery.nestable.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/evol-colorpicker@3.3.1/js/evol-colorpicker.min.js"></script>

        {{--  <script src="/shared/scripts/js/functions.js"></script> --}}

        @yield("scripts")

    </body>

</html>
