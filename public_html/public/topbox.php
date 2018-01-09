<div class="top-box">

    <nav class="path">
        <ul>
            <li><a href="">Kostic41</a></li>
            <li><a href="">Forum</a></li>
            <li><a href="">Ime foruma</a></li>
        </ul>
    </nav>

    <div class="page-info">

        <div data-shclass="page-title" class="title">
            <?php if (FILENAME == "forum"): ?>
                <h1>Ime foruma</h1>
                <p class="desc">Opis foruma.</p>
                <div class="mods">
                    Moderatori:
                    <ul>
                        <li><a href="">Pera</a></li>
                        <li><a href="">Zika</a></li>
                    </ul>
                </div>
            <?php elseif (FILENAME === "topic"): ?>
                <h1>Ime teme</h1>
            <?php endif; ?>
        </div>

        <div id="btn-follow">
            <button>Prati <?=FILENAME === "forum" ? "ovaj forum" : "ovu temu"?></button>
            <span>28</span>
        </div>

    </div>

    <div class="page-buttons">

        <div class="pages">
            Stranica 1 od 107
            <ul>
                <li data-shclass="active-page"><a href="">1</a></li>
                <li><a href="">2</a></li>
                <li><a href="">3</a></li>
                <li><a href="">Next</a></li>
                <li><a href="">&rsaquo;&rsaquo;</a></li>
            </ul>
        </div>

        <div>
            <?php if (FILENAME === "forum"): ?>
                <a href="" id="btn-mark-read">Označi ovaj forum kao pročitan</a>
            <?php endif; ?>
            <button data-shclass="btn-reply"
                    id="btn-reply"><?=FILENAME === "forum" ? "Započni novu temu" : "Napiši odgovor"?></button>
        </div>

    </div>

</div>