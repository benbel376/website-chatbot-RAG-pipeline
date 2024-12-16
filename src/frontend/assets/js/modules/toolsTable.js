'use strict';

export function initToolsTable() {
    // Suppress DataTables warnings
    $.fn.dataTable.ext.errMode = 'none';

    const table = $('#toolsTable');
    if (!table.length) return;

    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable(table)) {
        table.DataTable().destroy();
    }

    // Initialize DataTable
    const dataTable = table.DataTable({
        "dom": 'rt<"info-pagination"ip>',
        "pagingType": "full_numbers",
        "lengthMenu": [5, 10, 25, 50, 100],
        "pageLength": 5,
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ tools",
            "paginate": {
                "first": '<<',
                "previous": '<',
                "next": '>',
                "last": '>>'
            }
        },
        "responsive": true,
        "columnDefs": [
            { "orderable": true, "targets": [0, 1] },
            { "orderable": false, "targets": 2 }
        ],
        "drawCallback": function() {
            // Reinitialize toggle details functionality
            initializeToggleDetails();
        }
    });

    // Bind custom search box
    $('#customSearchBox').on('keyup', function() {
        dataTable.search(this.value).draw();
    });

    // Bind custom length menu
    $('#customLengthSelect').on('change', function() {
        dataTable.page.len($(this).val()).draw();
    });

    // Bind category filter
    $('#categoryFilter').on('change', function() {
        const category = $(this).val().toLowerCase();
        dataTable.column(1).search(category).draw();
    });

    // Initialize toggle details functionality
    function initializeToggleDetails() {
        $('.toggle-details').off('click').on('click', function(e) {
            e.preventDefault();
            const $this = $(this);
            const $snippet = $this.siblings('.details-snippet');
            const $full = $this.siblings('.details-full');

            if ($full.hasClass('hidden')) {
                $snippet.hide();
                $full.removeClass('hidden').show();
                $this.text('less');
            } else {
                $full.hide().addClass('hidden');
                $snippet.show();
                $this.text('more');
            }
        });
    }
}