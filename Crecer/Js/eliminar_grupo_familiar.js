$(document).ready(function() {
    var deleteId = null;

    $('#confirmDeleteGrupoFamiliar').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        deleteId = button.data('id');
    });

    $('#confirmDeleteButtonGrupoFamiliar').on('click', function() {
        if (deleteId) {
            console.log("Deleting group with ID:", deleteId); 
            $.post('Eliminar_grupo_familiar.php', { id: deleteId }, function(response) {
                console.log("Server response:", response); 
                if (response.trim() === 'success') {
                    location.reload();
                } else {
                    alert('Error al eliminar el grupo familiar.');
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Request failed: ", textStatus, errorThrown); 
                alert('Error al eliminar el grupo familiar.');
            });
        }
    });
});
