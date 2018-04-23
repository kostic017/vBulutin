@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/public/general.css') }}">
    @yield('styles')
@overwrite

@section('scripts')
    @yield('scripts')
@overwrite

@section('navigation')
@stop

@section('content')
    <div class="main-container">
        <form name="search" action="search.php">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Pretraga..." aria-label="Pretraga">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary submitsearch" type="button"><i class="fas fa-search"></i></button>
                    <button class="btn btn-outline-secondary advancedsearch" type="button"><i class="fas fa-cog"></i></button>
                </div>
            </div>
        </form>

        <div class="content">
           @yield('content')
        </div>
    </div>
@overwrite

@section('footer')
    <footer>

        {{-- samo na pocetnoj --}}
        <section class="forum-stats">
            <ul>
                <li><strong>-</strong> poruka/e</li>
                <li><strong>-</strong> član(ova)</li>
                <li><strong>-</strong> najnoviji član</li>
                <li><strong>2</strong> najviše osoba na mreži</li>
            </ul>
        </section>


        <section class="online-users">

            <div class="info">
                <div>
                    <strong>2 korisnika su na mreži (ne ažurira se istog trena)</strong><br>
                    <small>55 član, 1 gost, 0 anonimnih korisnika <a href="">(Pogledaj celu listu)</a></small>
                </div>
                <ul>
                    <li><a href="">Moderatorski tim</a></li>
                </ul>
            </div>

            <ul class="list">
                <li><a href="">Kostić</a></li>
            </ul>

            <div class="chat">
                <strong>Članovi aktivni na četu</strong><br>
                <small>Trenutno niko nije aktivan.</small>
            </div>

        </section>

        <section class="footer-links">
            <div>
                <a href=""><img src="/public/images/icon-rss.png" alt="RSS" style="width:14px;height:14px;"></a>
                <ul>
                    <li><a href="">Označi sve kao pročitano</a></li>
                    <li><a href="">Pomoć</a></li>
                </ul>
            </div>
            <div>Copyright &copy; Nikola Kostić 2017.</div>
        </section>

    </footer>

    <div id="btn-back2top">
        <span>Povratak na vrh</span>
    </div>
@stop
