$(document).ready(function() {
    'use strict';
    $('#dt').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: 'Search ID, user, subject..',
            sSearch: '',
            lengthMenu: 'Show &nbsp; _MENU_ &nbsp; Tickets',
        }
    });
});