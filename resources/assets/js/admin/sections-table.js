function sectionsTable() {
    const form = $('form#index');
    const filters = $('input[name=filter]');
    const perPage = $('select[name=perPage]');
    const searchQuery = $('input[name=search_query]');
    const searchSubmit = $('button[name=search_submit]');

    bindSubmit('change', [perPage, filters]);

    $('th .fa-search').on('click', function () {
        const th = $(this).parent();
        $('th').removeAttr('data-search');
        th.attr('data-search', '');
    });

    $('button[name=search_clear]').on('click', function () {
        const searchParams = new URLSearchParams(location.search);
        if (searchParams.has('search_query')) {
            searchQuery.val('');
            form.submit();
        }
    });

    searchQuery.on({
        focus() {
            $(this).select();
        },
        keypress(event) {
            if (event.which === VK_ENTER) {
                searchSubmit.click();
                return false;
            }
        }
    });

    searchSubmit.on('click', function () {
        if (isNotEmpty(searchQuery.val())) {
            form.submit();
        }
    });

    form.on('submit', function () {
        const thSort = $('th[data-order]');
        const thSearch = $('th[data-search]');

        appendDataToForm(form, 'perPage', perPage.val());
        appendDataToForm(form, 'search_column', thSearch.data('column'));
        appendDataToForm(form, 'search_query', searchQuery.val());
        appendDataToForm(form, 'filter', filters.filter(':checked').val());
    });

    function bindSubmit(event, elements) {
        elements.forEach(function (e) {
            e.on(event, () => { form.submit(); });
        });
    }
}
