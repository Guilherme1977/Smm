$(document).ready(function() {
    'use strict';
    $('#dt').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Search User',
            sSearch: '',
            lengthMenu: 'Show &nbsp; _MENU_ &nbsp; Users',
        }
    });
});