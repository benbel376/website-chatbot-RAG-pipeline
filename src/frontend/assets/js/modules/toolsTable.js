'use strict';

export function initToolsTable() {
    // Suppress DataTables warnings
    $.fn.dataTable.ext.errMode = 'none';
    $.fn.dataTable.ext.internal._fnLog = function() {};

    $(document).ready(function() {
        // First, check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable('#toolsTable')) {
            // If it exists, just return to prevent reinitialization
            return;
        }

        var table = $('#toolsTable').DataTable({
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
            "drawCallback": function(settings) {
                var api = this.api();
                var pagination = $(api.table().container()).find('.dataTables_paginate');
                var totalPages = api.page.info().pages;
                var currentPage = api.page.info().page + 1;

                pagination.find('a').not('.first, .previous, .next, .last').remove();
                pagination.find('span.ellipsis').remove();

                function createPaginationItem(content, isCurrent, isEllipsis) {
                    if (isEllipsis) {
                        return $('<span class="ellipsis">...</span>');
                    } else if (isCurrent) {
                        return $('<span class="current">' + content + '</span>');
                    } else {
                        return $('<a href="#" class="page-link">' + content + '</a>').data('page', content - 1);
                    }
                }

                if (totalPages <= 4) {
                    for (var i = 1; i <= totalPages; i++) {
                        pagination.append(createPaginationItem(i, i === currentPage, false));
                    }
                } else {
                    if (currentPage <= 2) {
                        for (var i = 1; i <= 3; i++) {
                            pagination.append(createPaginationItem(i, i === currentPage, false));
                        }
                        pagination.append(createPaginationItem('...', false, true));
                        pagination.append(createPaginationItem(totalPages, false, false));
                    } else if (currentPage >= totalPages - 1) {
                        pagination.append(createPaginationItem(1, false, false));
                        pagination.append(createPaginationItem('...', false, true));
                        for (var i = totalPages - 2; i <= totalPages; i++) {
                            pagination.append(createPaginationItem(i, i === currentPage, false));
                        }
                    } else {
                        pagination.append(createPaginationItem(1, false, false));
                        pagination.append(createPaginationItem('...', false, true));
                        pagination.append(createPaginationItem(currentPage, true, false));
                        pagination.append(createPaginationItem('...', false, true));
                        pagination.append(createPaginationItem(totalPages, false, false));
                    }
                }

                pagination.find('a.page-link').off('click').on('click', function(e) {
                    e.preventDefault();
                    var page = $(this).data('page');
                    table.page(page).draw(false);
                });

                pagination.find('a.first').off('click').on('click', function(e) {
                    e.preventDefault();
                    table.page(0).draw(false);
                });
                pagination.find('a.previous').off('click').on('click', function(e) {
                    e.preventDefault();
                    table.page('previous').draw(false);
                });
                pagination.find('a.next').off('click').on('click', function(e) {
                    e.preventDefault();
                    table.page('next').draw(false);
                });
                pagination.find('a.last').off('click').on('click', function(e) {
                    e.preventDefault();
                    table.page(totalPages - 1).draw(false);
                });
            }
        });

        // Add search functionality
        $('#customSearchBox').on('keyup change clear', function() {
            table.search(this.value).draw();
        });

        // Add length select functionality
        $('#customLengthSelect').on('change', function() {
            table.page.len($(this).val()).draw();
        });

        // Add details toggle functionality
        $('#toolsTable').on('click', '.toggle-details', function(e) {
            e.preventDefault();
            var $this = $(this);
            var $detailsSnippet = $this.siblings('.details-snippet');
            var $detailsFull = $this.siblings('.details-full');

            if ($detailsFull.hasClass('hidden') || !$detailsFull.hasClass('show')) {
                $detailsSnippet.hide();
                $detailsFull.show().removeClass('hidden').addClass('show');
                $this.text('less');
                $this.attr('aria-expanded', 'true');
            } else {
                $detailsSnippet.show();
                $detailsFull.hide().removeClass('show').addClass('hidden');
                $this.text('more');
                $this.attr('aria-expanded', 'false');
            }
        });

        // Add category filter
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var category = $('#categoryFilter').val().toLowerCase();
                var rowCategory = data[1].toLowerCase();
                return category === "" || rowCategory === category;
            }
        );

        // Add category filter changes
        $('#categoryFilter').on('change', function() {
            table.draw();
        });
    });
}