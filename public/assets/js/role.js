var profilId = null;
var fonctId = null;

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // suspendre un role
    $(document).on("click", ".openModal_tree", function (event) {
        event.preventDefault();
        profilId = $(this).data("id");
        fonctId = $(this).data("fonct");

        $.get("/profil/" + profilId + "/edit", function (data) {
            // $("#susRole").attr("action", "/profil/" + profilId + "/susRole");
            $("#prfLib").text(data.libelle_profil);
            $("#prfCode").text(data.code_profil);
            // $('#fonct').val(fonctId);
        });
    });
    $("#susRole").on('submit', function(event){
        event.preventDefault();
        var formData7 = $(this).serialize();
        // console.log(fonctId)

        $.ajax({
            url: "/profil/" + profilId + "/susRole/"+ fonctId,
            type: "POST",
            data: {
                formData7, 
                dt_sus: $('#dt_sus').val(),
                // fonct: $('#fonct').val(),
            },
            success: function (response) {
                if (response.success) {
                    showAlertModalSuccess(response.message);
                    // updateRoleList();
                    $("#custom-modal-six").modal("hide");
                } else {
                    showAlertModalWarning(
                        "Something went wrong. Please try again."
                    );
                }
            },
            error: function (error) {
                //showAlertModalError("An error occurred. Please try again.");
                //console.log($('#profil_input').val())
                console.error(error);
            },
        });
    });



    //Alerte de succès
    function showAlertModalSuccess(message) {
        $("#success-alert-modal-message").text(message);
        $("#success-alert-modal").modal("show");
    }

    //Alerte d'attention
    function showAlertModalWarning(message) {
        $("#warning-alert-modal-message").text(message);
        $("#warning-alert-modal").modal("show");
    }

    //Alerte d'erreur
    function showAlertModalError(message) {
        $("#danger-alert-modal-message").text(message);
        $("#danger-alert-modal").modal("show");
    }

    // //Mise à jour du tableau
    // function updateRoleList() {
    //     $.ajax({
    //         url: "/profil/listRole",
    //         type: "GET",
    //         success: function (response) {
    //             $("#table_role").html(response);
    //         },
    //         error: function (error) {
    //             // showAlertModalError("Failed to reload employe list.");
    //             console.error(error);
    //         },
    //     });
    // }
});