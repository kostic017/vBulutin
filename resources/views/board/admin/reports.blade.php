@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Izveštaji</h2>

            <div id="accordion">
                <div class="card">
                    <div class="card-header p-1" id="headingCategories">
                        <h3 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseCategories" aria-expanded="true" aria-controls="collapseCategories">
                                Kategorije
                            </button>
                        </h3>
                    </div>
                    <div id="collapseCategories" class="collapse show" aria-labelledby="headingCategories" data-parent="#accordion">
                        <div class="card-body">
                            <form method="post" action="{{ route('back.reports.generate', ['table' => 'categories']) }}">
                                @php ($columns = $categories)
                                @include('board.admin.includes.report-form')
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header p-1" id="headingForums">
                        <h3 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseForums" aria-expanded="false" aria-controls="collapseForums">
                                Forumi
                            </button>
                        </h3>
                    </div>
                    <div id="collapseForums" class="collapse" aria-labelledby="headingForums" data-parent="#accordion">
                        <div class="card-body">
                            <form method="post" action="{{ route('back.reports.generate', ['table' => 'forums']) }}">
                                @php ($columns = $forums)
                                @include('board.admin.includes.report-form')
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header p-1" id="headingTopics">
                        <h3 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTopics" aria-expanded="false" aria-controls="collapseTopics">
                                Teme
                            </button>
                        </h3>
                    </div>
                    <div id="collapseTopics" class="collapse" aria-labelledby="headingTopics" data-parent="#accordion">
                        <div class="card-body">
                            <form method="post" action="{{ route('back.reports.generate', ['table' => 'topics']) }}">
                                @php ($columns = $topics)
                                @include('board.admin.includes.report-form')
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header p-1" id="headingUsers">
                        <h3 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="false" aria-controls="collapseTopics">
                                Korisnici
                            </button>
                        </h3>
                    </div>
                    <div id="collapseUsers" class="collapse" aria-labelledby="headingUsers" data-parent="#accordion">
                        <div class="card-body">
                            <form method="post" action="{{ route('back.reports.generate', ['table' => 'users']) }}">
                                @php ($columns = $users)
                                @include('board.admin.includes.report-form')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('input[type=checkbox]').change(function() {
                const select = $(this).parents('tr').find('select');
                if (this.checked) {
                    select.removeAttr('disabled');
                } else {
                    select.attr('disabled', '');
                }
            });
        });
    </script>
@stop
