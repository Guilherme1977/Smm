$(document).ready(function() {
    'use strict';
    $('#dt').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Search Payment',
            sSearch: '',
            lengthMenu: 'Show &nbsp; _MENU_ &nbsp; Payments',
        }
    });
});