
var profilId = null;
var deleteProfId = null;

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Editer un employé
    $(document).on('click','.openModal', function (event) {
        event.preventDefault();
        profilId = $(this).data('id');

        $.get('/profil/' + profilId + '/edit', function(data) {
            $('#editForm').attr('action', '/profil/' + profilId);
            $('#code_profil').val(data.code_profil);
            $('#libelle_profil').val(data.libelle_profil)
        });
    });

    $('#editForm').on('submit', function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
        url: '/profil/' + profilId,
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updateProfList();
                $('#custom-modal').modal('hide');
            } else {
                showAlertModalWarning('Something went wrong. Please try again.');
            }
        },
        error: function(response) {
            showAlertModalError('An error occurred. Please try again.');
        }
        });
    });

    // Assigner un role
    $(document).on("click", ".openModal_two", function (event) {
        event.preventDefault();
        profilId = $(this).data("id");

        $.get("/profil/" + profilId + "/edit", function (data) {
            // $("#assignPostForm").attr("action", "/employe/" + employeId + "/assignPoste");
            $("#prfLib").text(data.libelle_profil);
            $("#prfCode").text(data.code_profil);
        });
    });
    $("#assignRoleForm").on('submit', function(event){
        event.preventDefault();
        var formData6 = $(this).serialize();
        // console.log(formData2)

        

        $.ajax({
            url: "/profil/" + profilId + "/assignRole",
            type: "POST",
            data: {
                formData6, 
                role_input: $('#role_input').val(),
            },
            success: function (response) {
                if (response.success) {
                    showAlertModalSuccess(response.message);
                    //updateEmployeList();
                    $("#custom-modal-five").modal("hide");
                } else {
                    showAlertModalWarning(
                        "Something went wrong. Please try again."
                    );
                }
            },
            error: function (error) {
                //showAlertModalError("An error occurred. Please try again.");
                //console.log($('#profil_input').val())
                 console.error(error)
            },
        })
    })

    $('#addForm').on('submit', function(event) {
        event.preventDefault();

        var uploadData = $(this).serialize();

        $.ajax({
        url: '/profil/add',
        type: 'POST',
        data: uploadData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updateProfList();
                $('#custom-modal-two').modal('hide');
            } else {
                showAlertModalWarning('Something went wrong. Please try again.');
            }
        },
        error: function(response) {
            showAlertModalError('An error occurred. Please try again.');
        }
        });
    });

    $('.delete-button').on('click', function() {

        deleteProfId = $(this).data('id');

        // Utiliser SweetAlert pour la confirmation
        Swal.fire({
        title: 'Êtes vous sûr de vouloir supprimer?',
        text: "Vous ne pourrez pas revenir en arrière !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
            url: '/profil/delete/' + deleteProfId, // Votre route de suppression
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content') // Token CSRF
            },
            success: function(response) {
                if (response.success) {
                Swal.fire(
                    'Supprimé!',
                    response.message,
                    'success'
                );
                updateProfList(); // Recharger la liste des employés
                } else {
                Swal.fire(
                    'Echoué!',
                    "Échec de la suppression du profil. Veuillez réessayer.",
                    'error'
                );
                }
            },
            error: function() {
                Swal.fire(
                'Erreur!',
                "Une erreur s'est produite. Veuillez réessayer.",
                'error'
                );
            }});
        }});
    });

    //Importer les profils
    document.getElementById('file-input1').addEventListener('change', function(){
        document.getElementById('upload-form').submit().addEventListener('click', function(e){
        e.preventDefault();

        var uploadData = $(this).serialize();

        $.ajax({
        url: '/profil/add/import',
        type: 'POST',
        data: uploadData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updateProfList();
                $('#custom-modal-two').modal('hide');
            } else {
                showAlertModalWarning('Something went wrong. Please try again.');
            }
        },
        error: function(response) {
            showAlertModalError('An error occurred. Please try again.');
        }
        });
        });
    });

    //Importer les profils et fonctions
    document.getElementById('file-input2').addEventListener('change', function(){
        document.getElementById('upload-form2').submit().addEventListener('click', function(e){
        e.preventDefault();

        var uploadData = $(this).serialize();

        $.ajax({
        url: '/profil/add/importFP',
        type: 'POST',
        data: uploadData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updateProfList();
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
    });

    //comparer un fichier et les données dans la base de données
    //Importer les profils et fonctions
    document.getElementById('file-input3').addEventListener('change', function(){
        document.getElementById('upload-form3').submit().addEventListener('click', function(e){
        e.preventDefault();

        var uploadData = $(this).serialize();

        $.ajax({
        url: '/profil/add/importIC',
        type: 'POST',
        data: uploadData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updateProfList();
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

    //Mise à jour du tableau
    function updateProfList() {
        $.ajax({
        url: '/profil/list',
        type: 'GET',
        success: function(response) {
            $('#datatable').html(response);
        },
        error: function() {
            showAlertModalError('Failed to reload profil list.');
        }
        });
    }
});