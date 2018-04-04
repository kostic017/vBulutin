@extends("layout")

@section("styles")
    <link rel="stylesheet" href="{{ asset("css/admin.css") }}">
@stop

@section("body")
    <div class="wrapper">

        @include("admin.includes.sidebar")

        <div id="content">

            <div id="title">
                <h2>@yield("title")</h2>
                <button type="button" id="toggle-sidebar" class="navbar-btn show">
                    <span></span><span></span><span></span>
                </button>
            </div>

            @yield("content")

        </div>

   </div>
@stop

@section("scripts")
    <script>
        $(function() {
            $("a[data-toggle='collapse']").on("click", function() {
                $("svg.fa-caret-up, svg.fa-caret-down").toggleClass("fa-caret-up").toggleClass("fa-caret-down");
            })

            $("#toggle-sidebar").on("click", function() {
                $(this).toggleClass("show").toggleClass("hide");
                $("#sidebar").toggleClass("show").toggleClass("hide");
            });
        });
    </script>
@stop
