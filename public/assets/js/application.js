var appId = null;
        var deleteAppId = null;

        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //Editer un employé
            $(document).on('click','.openModal', function (event) {
                event.preventDefault();
                appId = $(this).data('id');

                $.get('/application/' + appId + '/edit', function(data) {
                    $('#editForm').attr('action', '/application/' + appId);
                    $('#code_application').val(data.code_application);
                    $('#libelle_application').val(data.libelle_application)
                });
            });

            $('#editForm').on('submit', function(event) {
                event.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                url: '/application/' + appId,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showAlertModalSuccess(response.message);
                        updateAppList();
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
                url: '/application/add',
                type: 'POST',
                data: uploadData,
                success: function(response) {
                    if (response.success) {
                        showAlertModalSuccess(response.message);
                        updateAppList();
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

                deleteAppId = $(this).data('id');

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
                    url: '/application/delete/' + deleteAppId, // Votre route de suppression
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
                        updateAppList(); // Recharger la liste des employés
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

            //Importer les applications
            document.getElementById('file-input1').addEventListener('change', function(){
                document.getElementById('upload-form').submit().addEventListener('click', function(e){
                    e.preventDefault();

                var uploadData = $(this).serialize();

                console.log('upload',uploadData);

                $.ajax({
                url: '/application/add/import',
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
            function updateAppList() {
                $.ajax({
                url: '/application/list',
                type: 'GET',
                success: function(response) {
                    $('#datatable').html(response);
                },
                error: function() {
                    showAlertModalError('Failed to reload application list.');
                }
                });
            }
        });