<nav id="sidebar" class="hide">
    <div class="sidebar-header">
        <h3>Admin panel</h3>
        <strong>AP</strong>
    </div>

    <ul class="list-unstyled components">

        <li class="{{ active_class(if_uri(["admin"])) }}">
            <a href="/admin/">
                <i class="fas fa-home fa-fw"></i>Početna
            </a>
        </li>

        @php ($isActive = if_uri_pattern(["admin/table/*"]))
        <li class="{{ active_class($isActive) }}">
            <a href="#tablesSubmenu" data-toggle="collapse" aria-expanded="false">
                <i class="fas fa-table fa-fw"></i>Tabele
                <i class="fas {{ $isActive ? "fa-caret-up" : "fa-caret-down" }}"></i>
            </a>
            <ul class="{{ $isActive ? "show" : "collapse" }} list-unstyled" id="tablesSubmenu">
                <li class="{{ active_class(if_uri(["admin/table/sections"])) }}">
                    <a href="{{ route("table.index", ["name" => "sections"]) }}">Sekcije</a>
                </li>
                <li class="{{ active_class(if_uri(["admin/table/forums"])) }}">
                    <a href="{{ route("table.index", ["name" => "forums"]) }}">Forumi</a>
                </li>
            </ul>
        </li>

        <li class="{{ active_class(if_uri(["admin/table/positioning"])) }}">
            <a href="/admin/positioning">
                <i class="fas fa-sort fa-fw"></i>Pozicioniranje
            </a>
        </li>
    </ul>

    <ul class="list-unstyled CTAs">
        <li><a href="/admin/logout" class="white">Odjavi se</a></li>
    </ul>

</nav>
