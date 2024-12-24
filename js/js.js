function folder_options(icon, folderId) {
    //relleno con ceros a la izquierda
    var totalLength = 10;
    defId = folderId.toString().padStart(10, "0");
    // Cerrar otros menús abiertos
    document.querySelectorAll('.folder-options-menu').forEach(menu => menu.style.display = 'none');

    // Obtener el contenedor del menú
    const menu = document.getElementById('folder-options-'+defId);

    // Mostrar el menú (si está oculto)
    if (menu.style.display === 'none') {
        // Posicionar el menú junto al ícono
        const rect = icon.getBoundingClientRect();
        menu.style.top = `${rect.bottom}px`;
        menu.style.left = `${rect.left}px`;

        // Mostrar el menú
        menu.style.display = 'block';

        // Cargar las opciones con AJAX
        fetch('functions/load_folder_options.php?folder_id='+defId)
            .then(response => response.text())
            .then(data => {
                menu.innerHTML = data; // Insertar las opciones en el menú
            })
            .catch(error => console.error('Error cargando opciones:', error));
    } else {
        menu.style.display = 'none';
    }
}

function rename_folder(folderId) {
    //relleno con ceros a la izquierda
    defId = folderId.toString().padStart(10, "0");
    const newName = prompt("Introduce el nuevo nombre de la carpeta:");
    if (newName) {
        fetch('functions/rename_folder.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ folder_id: defId, new_name: newName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Carpeta renombrada correctamente');
                location.reload(); // Recargar la página para reflejar los cambios
            } else {
                alert('Error al renombrar la carpeta');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function delete_folder(folderId) {
    defId = folderId.toString().padStart(10, "0");
    // Confirmar con el usuario antes de proceder
    var confirmation = confirm("¿Estás seguro de que deseas eliminar esta carpeta?");
    if (!confirmation) {
        return; // Salir si el usuario cancela
    }

    // Crear la solicitud HTTP
    var xhr = new XMLHttpRequest();

    // Configurar la solicitud como POST
    xhr.open("POST", "functions/delete_folder.php", true);

    // Establecer el encabezado Content-Type para enviar JSON
    xhr.setRequestHeader("Content-Type", "application/json");

    // Manejar la respuesta del servidor
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                try {
                    // Intentar analizar la respuesta como JSON
                    var response = JSON.parse(xhr.responseText);

                    if (response.success) {
                        alert("Carpeta eliminada correctamente");
                        location.reload(); // Recargar la página para reflejar los cambios
                    } else {
                        alert(response.error || "Error al eliminar la carpeta");
                    }
                } catch (e) {
                    console.error("Error al analizar la respuesta del servidor:", e);
                }
            } else {
                console.error("Error en la solicitud: " + xhr.status);
                alert("Error al conectar con el servidor. Intenta de nuevo más tarde.");
            }
        }
    };

    // Preparar los datos para enviar
    var data = JSON.stringify({ folder_id: defId });

    // Enviar la solicitud
    xhr.send(data);
}