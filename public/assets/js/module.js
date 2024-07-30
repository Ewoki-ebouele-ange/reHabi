
var moduleId = null;
var deleteModId = null;

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Editer un employé
    $(document).on('click','.openModal', function (event) {
        event.preventDefault();
        moduleId = $(this).data('id');

        $.get('/module/' + moduleId + '/edit', function(data) {
            $('#editForm').attr('action', '/module/' + moduleId);
            $('#code_module').val(data.code_module);
            $('#libelle_module').val(data.libelle_module)
        });
    });

    $('#editForm').on('submit', function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
        url: '/module/' + moduleId,
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updateModList();
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
        url: '/module/add',
        type: 'POST',
        data: uploadData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updateModList();
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

        deleteModId = $(this).data('id');

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
            url: '/module/delete/' + deleteModId, // Votre route de suppression
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
                updateModList(); // Recharger la liste des employés
                } else {
                Swal.fire(
                    'Echoué!',
                    "Échec de la suppression du module. Veuillez réessayer.",
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

    //Importer les modules
    document.getElementById('file-input1').addEventListener('change', function(){
        document.getElementById('upload-form').submit().addEventListener('click', function(e){
        e.preventDefault();

        var uploadData = $(this).serialize();

        $.ajax({
        url: '/module/add/import',
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
    function updateModList() {
        $.ajax({
        url: '/module/list',
        type: 'GET',
        success: function(response) {
            $('#datatable').html(response);
        },
        error: function() {
            showAlertModalError('Failed to reload module list.');
        }
        });
    }
});
