function sectionsTable() {
    const form = $('form#index');
    const filters = $('input[name=filter]');
    const perPage = $('select[name=perPage]');
    const searchQuery = $('input[name=search_query]');
    const searchSubmit = $('button[name=search_submit]');

    bindSubmit('change', [perPage, filters]);

    $('.sort-link').on('click', function (event) {
        event.preventDefault();
        const th = $(this).parent();
        if (th.is('[data-order]')) {
            th.data('order', th.data('order') === 'asc' ? 'desc' : 'asc');
        } else {
            $('th').removeAttr('data-order');
            th.attr('data-order', 'asc');
        }
        form.submit();
    });

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
        const appendData = function (name, value) {
            if (isNotEmpty(value)) {
                form.append($('<input>').attr('type', 'hidden').attr('name', name).val(value));
            }
        }

        const thSort = $('th[data-order]');
        const thSearch = $('th[data-search]');

        appendData('perPage', perPage.val());
        appendData('sort_column', thSort.data('column'));
        appendData('sort_order', thSort.data('order'));
        appendData('search_column', thSearch.data('column'));
        appendData('search_query', searchQuery.val());
        appendData('filter', filters.filter(':checked').val());
    });

    function bindSubmit(event, elements) {
        elements.forEach(function (e) {
            e.on(event, () => { form.submit(); });
        });
    }
}
