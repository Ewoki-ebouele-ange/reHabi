
var posteId = null;
var deletePostId = null;

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Editer un employé
    $(document).on('click','.openModal', function (event) {
        event.preventDefault();
        posteId = $(this).data('id');

        $.get('/poste/' + posteId + '/edit', function(data) {
            $('#editForm').attr('action', '/poste/' + posteId);
            $('#code_poste').val(data.code_poste);
            $('#libelle_poste').val(data.libelle_poste)
        });
    });

    $('#editForm').on('submit', function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
        url: '/poste/' + posteId,
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updatePostList();
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

    $('#addForm').on('submit', function(event) {
        event.preventDefault();

        var uploadData = $(this).serialize();

        $.ajax({
        url: '/poste/add',
        type: 'POST',
        data: uploadData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updatePostList();
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

        deletePostId = $(this).data('id');

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
            url: '/poste/delete/' + deletePostId, // Votre route de suppression
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
                updatePostList(); // Recharger la liste des employés
                } else {
                Swal.fire(
                    'Echoué!',
                    "Échec de la suppression du poste. Veuillez réessayer.",
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

    //Importer les poste
    document.getElementById('file-input1').addEventListener('change', function(){
        document.getElementById('upload-form').submit().addEventListener('click', function(e){
        e.preventDefault();

        var uploadData = $(this).serialize();

        $.ajax({
        url: '/poste/add/import',
        type: 'POST',
        data: uploadData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updateEmployeList();
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
        url: '/poste/add/importPP',
        type: 'POST',
        data: uploadData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updatePostList();
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
    function updatePostList() {
        $.ajax({
        url: '/poste/list',
        type: 'GET',
        success: function(response) {
            $('#datatable').html(response);
        },
        error: function() {
            showAlertModalError('Failed to reload poste list.');
        }
        });
    }
});
