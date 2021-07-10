<div class="modal fade" id="modalDiv" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <div id="modalContent">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="subsDiv" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="subsTitle"></h4>
            </div>
            <div id="subsContent">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="theme/assets/js/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="theme/assets/js/moment.min.js"></script>
<script type="text/javascript" src="theme/assets/js/bootstrap.min.js"></script>
<script src="theme/assets/js/sweetalert.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>
<link href="theme/assets/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="theme/assets/js/bootstrap-toggle.min.js"></script>
<script src="theme/assets/js/summernote.js"></script>
<script src="theme/lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="theme/assets/js/developerity.aside.js"></script>
<script src="theme/assets/js/jquery.dataTables.min.js"></script>
<script src="theme/assets/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="<?php echo STYLESHEETS_URL."/theme/admin/" ?>datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo STYLESHEETS_URL."/theme/admin/" ?>datepicker/locales/bootstrap-datepicker.tr.min.js"></script>
<script src="<?php echo STYLESHEETS_URL."/theme/admin/admin/" ?>toastDemo.js"></script>
<script src="<?php echo STYLESHEETS_URL."/theme/admin/admin/" ?>script.js"></script>
<script src="<?php echo STYLESHEETS_URL."/theme/admin/admin/" ?>script-2.js"></script>
<script src="theme/assets/js/jquery-ui.min.js"></script>
<script src="<?php echo STYLESHEETS_URL."/theme/admin/admin/" ?>jquery.tinytoggle.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var site_url = $('head base').attr('href');
        <?php if( route(2) == "new-service" || route(2) == "new-subscription" ): echo '$(document).ready(function(){
          getProviderServices($("#provider").val(),site_url);
        });'; endif; ?>
        <?php if( $error ): ?>
        $.toast({
            heading: 'Error',
            text: '<?php echo $errorText; ?>',
            icon: 'error',
            loader: true,
            loaderBg: '#9EC600'
        })
        <?php endif; ?>
        <?php if( $success ): ?>
        $.toast({
            heading: 'Success',
            text: '<?php echo $successText; ?>',
            icon: 'success',
            loader: true,
            loaderBg: '#9EC600'
        })
        <?php endif; ?>

        /*Summernote editor*/
        $('#summernoteExample').summernote({
            height: 300,
            tabsize: 2
        });

        $(".service-sortable").sortable({
            handle: '.handle',
            update: function(event, ui) {
                var array = [];
                $(this).find('tr').each(function(i) {
                    $(this).attr('data-line', i + 1);
                    var params = {};
                    params['id'] = $(this).attr('data-id');
                    params['line'] = $(this).attr('data-line');
                    array.push(params);
                });
                $.post(site_url + 'admin/ajax_data', {
                    action: 'service-sortable',
                    services: array
                });
            }
        });

        $(".methods-sortable").sortable({
            handle: '.handle',
            update: function(event, ui) {
                var array = [];
                $(this).find('tr').each(function(i) {
                    $(this).attr('data-line', i + 1);
                    var params = {};
                    params['id'] = $(this).attr('data-id');
                    params['line'] = $(this).attr('data-line');
                    array.push(params);
                });
                $.post(site_url + 'admin/ajax_data', {
                    action: 'paymentmethod-sortable',
                    methods: array
                });
            }
        });

        $(".category-sortable").sortable({
            handle: '.handle',
            update: function(event, ui) {
                var array = [];
                $(this).find('.categories').each(function(i) {
                    $(this).attr('data-line', i + 1);
                    var params = {};
                    params['id'] = $(this).attr('data-id');
                    params['line'] = $(this).attr('data-line');
                    array.push(params);
                });
                $.post(site_url + 'admin/ajax_data', {
                    action: 'category-sortable',
                    categories: array
                });
            }
        });

    });
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

</body>

</html>