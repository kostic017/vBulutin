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

        @php ($isActive = if_uri(["admin/sections", "admin/forums"]))
        <li class="{{ active_class($isActive) }}">
            <a href="#tablesSubmenu" data-toggle="collapse" aria-expanded="false">
                <i class="fas fa-table fa-fw"></i>Tabele
                <i class="fas {{ $isActive ? "fa-caret-up" : "fa-caret-down" }}"></i>
            </a>
            <ul class="{{ $isActive ? "show" : "collapse" }} list-unstyled" id="tablesSubmenu">
                <li class="{{ active_class(if_uri(["admin/sections"])) }}">
                    <a href="{{ route("sections.index") }}">Sekcije</a>
                </li>
                <li class="{{ active_class(if_uri(["admin/forums"])) }}">
                    <a href="{{ route("forums.index") }}">Forumi</a>
                </li>
            </ul>
        </li>

        <li class="{{ active_class(if_uri(["admin/positioning"])) }}">
            <a href="/admin/positioning">
                <i class="fas fa-sort fa-fw"></i>Pozicioniranje
            </a>
        </li>
    </ul>

</nav>
