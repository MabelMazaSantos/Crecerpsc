
$(document).ready(function() {
    var deleteId = null;

    $('#confirmDeleteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        deleteId = button.data('id');
    });

    $('#confirmDeleteButton').on('click', function() {
        if (deleteId) {
            console.log("Deleting client with ID:", deleteId); 
            $.post('Eliminar_Paciente.php', { id: deleteId }, function(response) {
                console.log("Server response:", response); 
                if (response.trim() === 'success') {
                    location.reload();
                } else {
                    alert('Error al eliminar el paciente.');
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Request failed: ", textStatus, errorThrown); 
                alert('Error al eliminar el paciente.');
            });
        }
    });
});
