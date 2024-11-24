
$(document).ready(function() {
    var deleteId = null;

    $('#confirmDeletePsicologo').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        deleteId = button.data('id');
    });

    $('#confirmDeleteButtonPsicologo').on('click', function() {
        if (deleteId) {
            console.log("Deleting psychologist with ID:", deleteId); 
            $.post('Eliminar_Psicologo.php', { id: deleteId }, function(response) {
                console.log("Server response:", response); 
                if (response.trim() === 'success') {
                    location.reload();
                } else {
                    alert('Error al eliminar el psicólogo.');
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Request failed: ", textStatus, errorThrown); 
                alert('Error al eliminar el psicólogo.');
            });
        }
    });
});
