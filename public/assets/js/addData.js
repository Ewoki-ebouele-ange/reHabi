
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#dataForm").on("submit", function(e){
        // e.preventDefault();

        var uploadData = $(this).serialize();
        // console.log(uploadData)


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
        error: function(xhr, status, error) {
            console.log("Error response", xhr.responseText)
            console.log("Status", status)
            console.log("error", error)
            // showAlertModalError('An error occurred. Please try again.');
        }});
    });

    $("#extract").on("submit", function(e){
        // e.preventDefault();

        var uploadData2 = $(this).serialize();
        // console.log(uploadData)


        $.ajax({
        url: '/addData/app/extraction',
        type: 'POST',
        data: uploadData2,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
            } else {
                showAlertModalWarning('Something went wrong. Please try again.');
            }
        },
        error: function(xhr, status, error) {
            console.log("Error response", xhr.responseText)
            console.log("Status", status)
            console.log("error", error)
            // showAlertModalError('An error occurred. Please try again.');
        }});
    });    

    //Alerte de succès
    function showAlertModalSuccess(message) {
        $('#success-alert-modal-message').text(message);
        $('#success-alert-modal').modal('show');
    }

    //Alerte d'attention
    function showAlertModalWarning(message) {
        $('#warning-alert-modal-message').text(message);
        $('#warning-alert-modal').modal('show');
    }

    //Alerte d'erreur
    function showAlertModalError(message) {
        $('#danger-alert-modal-message').text(message);
        $('#danger-alert-modal').modal('show');
    }
    
})