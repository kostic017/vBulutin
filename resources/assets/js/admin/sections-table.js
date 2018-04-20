$(function () {
    const form = $('form#index');
    const table = $('table').data('name');

    const sortLinks = $('.sort-link');
    const filters = $('input[name=filter]');
    const perPage = $('select[name=perPage]');

    const searchTogglers = $('th .fa-search');
    const searchQuery = $('input[name=search_query]');
    const searchClear = $('button[name=search_clear]');
    const searchSubmit = $('button[name=search_submit]');

    searchTogglers.on('click', function () {
        const th = $(this).parent();
        $('th').removeAttr('data-search');
        th.attr('data-search', '');
    });

    searchClear.on('click', function () {
        searchQuery.val('');
        form.submit();
    });

    searchQuery.on('keypress', function (event) {
        if (event.which === VK_ENTER) {
            form.submit();
            return false;
        }
    });

    searchQuery.on('focus', function () {
        $(this).select();
    });

    sortLinks.on('click', function (event) {
        event.preventDefault();
        const th = $(this).parent();
        if (th.is('[data-order]')) {
            th.data('order', th.data('order') === 'asc' ? 'desc' : 'asc');
        } else {
            $('th').removeAttr('data-order');
            th.attr('data-order', 'asc');
        }
    });

    bindSubmit('change', [perPage, filters]);
    bindSubmit('click', [sortLinks, searchSubmit]);

    form.on('submit', function () {
        const thSort = $('th[data-order]');
        const thSearch = $('th[data-search]');

        appendData('perPage', perPage.val());
        appendData('sort_column', thSort.data('column'));
        appendData('sort_order', thSort.data('order'));
        appendData('search_column', thSearch.data('column'));
        appendData('search_query', searchQuery.val());
        appendData('filter', filters.filter(':checked').val());

        function appendData(name, value) {
            if (isNotEmpty(value)) {
                form.append($('<input>').attr('type', 'hidden').attr('name', name).val(value));
            }
        }
    });

    function bindSubmit(event, elements) {
        elements.forEach(function (e) {
            e.on(event, () => { form.submit(); });
        });
    }

});
