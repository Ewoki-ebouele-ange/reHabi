
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

        document.getElementById('dataForm').submit().addEventListener('click', function(e){
        e.preventDefault();

        var uploadData = $(this).serialize();

        $.ajax({
        url: '/addData/add',
        type: 'POST',
        data: uploadData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                //updatePostList();
                //$('#custom-modal-two').modal('hide');
            } else {
                showAlertModalWarning('Something went wrong. Please try again.');
            }
        },
        error: function(response) {
            showAlertModalError('An error occurred. Please try again.');
        }
        });
        });

})