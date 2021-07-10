$(document).ready(function() {
    'use strict';
    $('#dt').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Search Order',
            sSearch: '',
            lengthMenu: 'Show &nbsp; _MENU_ &nbsp; Orders',
        }
    });
});