

function folder_options(icon, folderId) {
    // Cerrar otros menús abiertos
    document.querySelectorAll('.folder-options-menu').forEach(menu => menu.style.display = 'none');

    // Obtener el contenedor del menú
    const menu = document.getElementById('folder-options-'+folderId);

    // Mostrar el menú (si está oculto)
    if (menu.style.display === 'none') {
        // Posicionar el menú junto al ícono
        const rect = icon.getBoundingClientRect();
        menu.style.top = `${rect.bottom}px`;
        menu.style.left = `${rect.left}px`;

        // Mostrar el menú
        menu.style.display = 'block';

        // Cargar las opciones con AJAX
        fetch('functions/load_folder_options.php?folder_id='+folderId)
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
    const newName = prompt("Introduce el nuevo nombre de la carpeta:");
    if (newName) {
        fetch('functions/rename_folder.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ folder_id: folderId, new_name: newName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                //alert('Carpeta renombrada correctamente');
                location.reload(); // Recargar la página para reflejar los cambios
            } else {
                alert('Error al renombrar la carpeta');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function delete_folder(folderId) {
    //Me aseguro de que el ID tenga el formato correcto
    defId = folderId.toString().padStart(10, "0");
    //Compruebo si la carpeta está vacía o no
    xhrCheck = new XMLHttpRequest();
    xhrCheck.open("POST", "functions/check_if_empty.php", true);
    xhrCheck.setRequestHeader("Content-Type", "application/json");
    xhrCheck.onreadystatechange = function(){
        if(xhrCheck.readyState === XMLHttpRequest.DONE){
            if(xhrCheck.status === 200){
                try{
                    var response = JSON.parse(xhrCheck.responseText);
                    if(response.success){
                        //Si la carpeta está vacía, preguntamos al usuario si está seguro/a de querer borrarla
                        var confirmation = confirm("¿Estás seguro de que deseas eliminar esta carpeta?");
                        deleteFolderRequest(defId, "delete");
                    }else{
                        const choice = confirm("La carpeta contiene escritos. ¿Quieres eliminarla junto con su contenido o mover los escritos al Escritorio?");
                        if (choice) {
                            // Confirmación adicional
                            const deleteWithContent = confirm("¿Eliminar la carpeta y todos sus escritos?");
                            if (deleteWithContent) {
                                deleteFolderRequest(defId, "delete_with_content"); // Eliminar con contenido
                            } else {
                                deleteFolderRequest(defId, "move_to_desktop"); // Mover al Escritorio
                            }
                        }
                    }
                } catch (e) {
                    console.error("Error al analizar la respuesta del servidor: ", e);
                }
            }else {
                console.error("Error en la solicitud: " + xhr.status);
                alert("Error al conectar con el servidor. Intenta de nuevo más tarde.");
            }
        }
        
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

    // Preparar los datos para enviar
    var data = JSON.stringify({ folder_id: defId });
    
    // Enviar la solicitud
    xhrCheck.send(data);
}   

function savePost(status){
    var title = document.getElementById("title").value;
    var content = document.getElementById("content").value;
    var folder = document.getElementById("folders").value;
    // Validar entradas
    if (!title || !content || !status) {
        alert("Por favor, completa todos los campos requeridos.");
        return;
    }
    //console.log(status);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "functions/create_post.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        window.location.href = "https://letterwinds.com/app/escritorio"; // Redirección
                    } else {
                        console.log(response.error);
                    }
                } catch (e) {
                    console.error("Error al analizar la respuesta:", e, xhr.responseText);
                }
            } else {
                console.error("Error en la solicitud: " + xhr.status);
                alert("Error al conectar con el servidor. Intenta de nuevo más tarde.");
            }
        }
    };

    // Crear objeto JSON para enviar al servidor
    var data = JSON.stringify({
        title: title,
        content: content,
        status: status,
        folder: folder // Cambié "folder" a "folders" para coincidir con PHP
    });
    xhr.send(data);
}

function deletePost(id){
    if(confirm('Seguro que quiere borrar el escrito?')){
        //console.log(id);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "functions/delete_post.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            window.location.href = "https://letterwinds.com/app/escritorio"; // Redirección
                        } else {
                            console.log(response.error);
                        }
                    } catch (e) {
                        console.error("Error al analizar la respuesta:", e, xhr.responseText);
                    }
                } else {
                    console.error("Error en la solicitud: " + xhr.status);
                    alert("Error al conectar con el servidor. Intenta de nuevo más tarde.");
                }
            }
        };
        var data = JSON.stringify({
            postId: id
        });
        xhr.send(data);
        // xhr.send(`postId=${id}`);
    }
}

function updatePost(){
    var title = document.getElementById("title").value;
    var content = document.getElementById("content").value;
    var folder = document.getElementById("folders").value;
    var status = document.getElementById("status").value;
    var idpost = document.getElementById("idpost").value;
    // Validar entradas
    if (!title || !content || !status || !idpost) {
        alert("Por favor, completa todos los campos requeridos.");
        return;
    }
    //console.log(status);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "functions/update_post.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        window.location.href = 'https://letterwinds.com/app/?loc=dash&action=readpost&idpost='+idpost; // Redirección
                    } else {
                        alert(response.error);
                    }
                } catch (e) {
                    console.error("Error al analizar la respuesta:", e, xhr.responseText);
                }
            } else {
                console.error("Error en la solicitud: " + xhr.status);
                alert("Error al conectar con el servidor. Intenta de nuevo más tarde.");
            }
        }
    };

    // Crear objeto JSON para enviar al servidor
    var data = JSON.stringify({
        title: title,
        content: content,
        status: status,
        folders: folder,
        idpost: idpost
    });
    xhr.send(data);
}

function check(t, c) {
    if (t === 'all') {
        // Obtener todos los checkboxes de la lista
        const checkboxes = document.getElementsByClassName('post_checkbox');
        for (let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = c.checked; // Actualizar estado de todos según el encabezado
        }
    }
}

function vote(postId){
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "functions/vote_post.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                try {
                    // Intentar analizar la respuesta como JSON
                    var response = JSON.parse(xhr.responseText);

                    if (response.success) {
                        location.reload(); // Recargar la página para reflejar los cambios
                    } else {
                        alert(response.error || "Error al votar");
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
    var data = JSON.stringify({ post_id: postId });

    // Enviar la solicitud
    xhr.send(data);
}

function seePost(postId) {
    // Vaciar el div antes de hacer la petición
    const readingContainer = document.getElementById("reading_container");
    readingContainer.innerHTML = "";

    // Realizar la petición AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "functions/get_post_info.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) { // Petición completada
            if (xhr.status === 200) {
                // Insertar el contenido devuelto en el div
                readingContainer.innerHTML = xhr.responseText;
            } else {
                // Manejar errores
                readingContainer.innerHTML = "<p>Error al cargar el contenido. Intenta nuevamente.</p>";
            }
        }
    };

    xhr.send(`postid=${postId}`);
    let containerId = 'container-post-'+postId;
    postContainersToHide = [];
    postContainers = document.getElementsByClassName('container-post');
    arrayContainers = Array.prototype.slice.call(postContainers);
    // console.log(arrayContainers);
    for(i=0;i<arrayContainers.length;i++){
        arrayContainers[i].classList.remove("container-post-disabled");
        // console.log(arrayContainers[i].id);
        if(arrayContainers[i].id != containerId){
            postContainersToHide.push(arrayContainers[i]);
        }
    }
    console.log(postContainersToHide);
    for(i=0;i<postContainersToHide.length;i++){
        postContainersToHide[i].classList.add("container-post-disabled");
    }
    // postContainersToHide.forEach(cambiarClase());
    // function cambiarClase(item){
    //     item.classList.add("container-post-disabled");
    // }
}

function loadFolder(folderId) {
    // Vaciar el div antes de hacer la petición
    const postContainer = document.querySelector("#dash_posts_container");
    const foldersContainer = document.querySelector("#folders-list-container");
    const folder = document.querySelector(`#folder-${folderId}`);
    let folders;
    postContainer.innerHTML = "";

    // Realizar la petición AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "functions/get_folder_content.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) { // Petición completada
            if (xhr.status === 200) {
                // Insertar el contenido devuelto en el div
                postContainer.innerHTML = xhr.responseText;
                //restablecemos el aspecto de las carpetas
                folders = foldersContainer.children;
                for(i=0;i<folders.length;i++){
                    folders[i].classList.replace('jr-btn-opened-folder', 'btn-light');
                    textNode = folders[i].firstChild;
                    let oldIcon = textNode.nextSibling;
                    let newIcon = document.createElement("i");
                    newIcon.setAttribute("class", "bi bi-folder");
                    folders[i].replaceChild(newIcon, oldIcon);
                    i++;
                }
                //Actualizamos el aspecto de la carpeta clicada
                folder.classList.replace('btn-light', 'jr-btn-opened-folder');
                textNode = folder.firstChild;
                    let oldIcon = textNode.nextSibling;
                    let newIcon = document.createElement("i");
                    newIcon.setAttribute("class", "bi bi-folder2-open");
                    folder.replaceChild(newIcon, oldIcon);
            } else {
                // Manejar errores
                postContainer.innerHTML = "<p>Error al cargar el contenido. Intenta nuevamente.</p>";
            }
        }
    };

    xhr.send(`folderId=${folderId}`);
}

// // Limpiar el div si la página se refresca
// window.addEventListener("beforeunload", function () {
//     document.getElementById("reading_container").innerHTML = "";
// });

function addComment(event, postId) {
    event.preventDefault();

    const inputField = event.target.querySelector("input[name='comentario']");
    const commentText = inputField.value.trim();
    const commentsList = document.getElementById(`comments_${postId}`);

    if (commentText === "") {
        alert("El comentario no puede estar vacío.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "functions/add_comment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Actualizar la lista de comentarios
                    commentsList.innerHTML = response.comments;
                    // commentsList.innerHTML = response.comments;
                    // commentsList.innerHTML = "";
                    // response.comments.forEach(comment => {
                    //     const commentItem = document.createElement("div");
                    //     commentItem.classList.add("comment-item");
                    //     commentItem.innerHTML = `
                    //         <p><strong>${comment.user}</strong></p>
                    //         <p>${comment.content}</p>
                    //     `;
                    //     commentsList.appendChild(commentItem);
                    // });

                    // Limpiar el campo de texto
                    inputField.value = "";
                } else {
                    alert(xhr.error || "Error al guardar el comentario.");
                }
            } else {
                alert("Error al procesar la solicitud.");
            }
        }
    };

    xhr.send(`post_id=${postId}&comment=${encodeURIComponent(commentText)}`);
}
