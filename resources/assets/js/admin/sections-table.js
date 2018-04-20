$(function () {
    const table = $('table').data('name');

    const form = $('form#index');
    const sortLinks = $('.sort-link');
    const searchQuery = $('input[name=search_query]');
    const perPage = $('select[name=perPage]');
    const filters = $('input[name=filter]');

    sortLinks.on('click', function (event) {
        event.preventDefault();
        if ($(this).is('[data-order]')) {
            toggleOrder($(this));
        } else {
            $('.sort-link').removeAttr('data-order');
            $(this).attr('data-order', 'asc');
        }
    });

    bindSubmit([sortLinks], 'click');
    bindSubmit([perPage, filters], 'change');

    form.on('submit', function () {
        const sortLink = $('.sort-link[data-order]');

        appendData('perPage', perPage.val());
        appendData('search_query', searchQuery.val());
        appendData('sort_column', sortLink.data('column'));
        appendData('sort_order', sortLink.data('order'));
        appendData('filter', filters.filter(':checked').val());
    });

    function appendData(name, value) {
        if (isNotEmpty(value)) {
            form.append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', name)
                    .val(value)
            );
        }
    }

    function bindSubmit(elements, event) {
        elements.forEach(function (e) {
            e.on(event, () => { form.submit(); });
        });
    }

    function toggleOrder(link) {
        link.data('order', link.data('order') === 'asc' ? 'desc' : 'asc');
    }

});
