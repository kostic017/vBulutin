$(function () {
    const table = $('table').data('name');

    const form = $('form#index');
    const cPerPage = $('select[name=perPage]');
    const cFilters = $('input[name=filter]');
    const cSortLinks = $('.sort-link');

    cSortLinks.on('click', function (event) {
        event.preventDefault();
        if ($(this).is('[data-order]')) {
            toggleOrder($(this));
        } else {
            $('.sort-link').removeAttr('data-order');
            $(this).attr('data-order', 'asc');
        }
    });

    bindSubmit([cSortLinks], 'click');
    bindSubmit([cPerPage, cFilters], 'change');

    form.on('submit', function () {
        const sortLink = $('.sort-link[data-order]');
        $(this).append(input('perPage', cPerPage.val()));
        $(this).append(input('filter', cFilters.filter(':checked').val()));
        $(this).append(input('sort_column', sortLink.data('column')));
        $(this).append(input('sort_order', sortLink.data('order')));
    });

    function input(name, value) {
        return $('<input>')
            .attr('type', 'hidden')
            .attr('name', name)
            .val(value);
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
