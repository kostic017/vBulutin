@extends("layout")

@section("styles")
    <link rel="stylesheet" href="{{ asset("css/admin.css") }}">
@stop

@section("body")
    <div class="wrapper">

        @include("admin.navigation")

        <div id="content">

            @yield("content")

        </div>

   </div>
@stop

@section("scripts")
    <script>
        $(function() {
            $("li.active .collapse").addClass("show");
            $("a[data-toggle='collapse']").on("click", function() {
                $(".fa-caret-down, .fa-caret-up", $(this)).toggleClass("fa-caret-down").toggleClass("fa-caret-up");
            });
        });
    </script>
@stop
