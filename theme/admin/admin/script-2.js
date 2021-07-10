$(document).ready(function () {
    'use strict';
    var site_url = $('head base').attr('href');

    $(document).on('submit', 'form[data-xhr]', function (event) {
        event.preventDefault();
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: action,
            type: method,
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
            .done(function (result) {

                if (result.s == "error") {
                    var heading = "Error";
                } else {
                    var heading = "Successful";
                }
                $.toast({
                    heading: heading,
                    text: result.m,
                    icon: result.s,
                    loader: true,
                    loaderBg: "#9EC600"
                });
                if (result.r != null) {
                    if (result.time == null) { result.time = 3; }

                    setTimeout(function () {
                        window.location.href = result.r;
                    }, result.time * 1000);
                }
                if (result.m != null) {
                    swal(options);
                }
            })
            .fail(function () {

                $.toast({
                    heading: 'An error occurred!',
                    text: 'Request failed',
                    icon: 'error',
                    loader: true,
                    loaderBg: "#9EC600"
                });
            })
    });

    $("#delete-row").on('click', function() {
        var action = $(this).attr("data-url");
        swal({
            title: "Are you sure to delete?",
            text: "If you confirm this content will be deleted, it may not be possible to bring it back.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            buttons: ["Cancel", "Yes, I'm sure!"],
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: action,
                        type: "GET",
                        dataType: "json",
                        cache: false,
                        contentType: false,
                        processData: false
                    })
                        .done(function (result) {
                            if (result.s == "error") {
                                var heading = "Error";
                            } else {
                                var heading = "Successful";
                            }
                            $.toast({
                                heading: heading,
                                text: result.m,
                                icon: result.s,
                                loader: true,
                                loaderBg: "#9EC600"
                            });
                            if (result.r != null) {
                                if (result.time == null) { result.time = 3; }
                                setTimeout(function () {
                                    window.location.href = result.r;
                                }, result.time * 1000);
                            }
                        })
                        .fail(function () {
                            $.toast({
                                heading: "Error",
                                text: "Request failed",
                                icon: "error",
                                loader: true,
                                loaderBg: "#9EC600"
                            });
                        });

                } else {
                    $.toast({
                        heading: "Error",
                        text: "Request for deletion denied",
                        icon: "error",
                        loader: true,
                        loaderBg: "#9EC600"
                    });
                }
            });
    });
});