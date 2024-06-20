
var employeId = null;
var deleteEmployeId = null;

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Editer un employé
    $(document).on('click','.openModal', function (event) {
        event.preventDefault();
        employeId = $(this).data('id');

        $.get('/employe/' + employeId + '/edit', function(data) {
            $('#editForm').attr('action', '/employe/' + employeId);
            $('#nom').val(data.nom);
            $('#matricule').val(data.matricule)
        });
    });

    $('#editForm').on('submit', function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
        url: '/employe/' + employeId,
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                showAlertModalSuccess(response.message);
                updateEmployeList();
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

    //Ajouter un employé
    $('#addForm').on('submit', function(event) {
        event.preventDefault();

        var uploadData = $(this).serialize();

        $.ajax({
        url: '/employe/add',
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

    // Supprimer un employe
    $('.delete-button').on('click', function() {

        deleteEmployeId = $(this).data('id');

        console.log('Employe',deleteEmployeId);

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
            url: '/employe/delete/' + deleteEmployeId, // Votre route de suppression
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
                updateEmployeList(); // Recharger la liste des employés
                } else {
                Swal.fire(
                    'Echoué!',
                    "Échec de la suppression de l'employé. Veuillez réessayer.",
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

    //Importer les employés
    document.getElementById('file-input1').addEventListener('change', function(){
        document.getElementById('upload-form').submit().addEventListener('click', function(e){
            e.preventDefault();

        //var uploadData = $(this).serialize();
        var formData = new FormData(this);

        console.log('upload',formData);

        $.ajax({
        url: '/employe/add/import',
        type: 'POST',
        data: formData,
        contentType: false, // Nécessaire pour envoyer les fichiers
        processData: false, // Nécessaire pour envoyer les fichiers
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Si vous utilisez Laravel
        },
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
    function updateEmployeList() {
        $.ajax({
        url: '/employe/list',
        type: 'GET',
        success: function(response) {
            $('#employesList').html(response);
        },
        error: function() {
            showAlertModalError('Failed to reload employe list.');
            console.error();
        }
        });
    }

});