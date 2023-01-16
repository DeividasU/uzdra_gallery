window.addEventListener('DOMContentLoaded', async (event) => {
    // Upload file
    const uploadFile = async() =>
    {
        // Check if file was selected
        if (uzdra_gallery_fileupload.files[0])
        {
            let formData = new FormData();
            formData.append("file", uzdra_gallery_fileupload.files[0]);
            let id_product = document.getElementById("uzdra_gallery_id_product").value;
            const data = await fetch(uzdra_gallery_fileupload_url + "&id_product=" + id_product, {
                method: "POST",
                body: formData
            });
            const response = await data.json();
            let alertBox = document.getElementById("uzdra_gallery_alert");

            // Error handling on product page backend
            if (response.file_was_uploaded)
            {
                alertBox.getElementsByClassName("alert-text")[0].innerHTML = response.success_message;
                alertBox.className = "alert";
                alertBox.classList.add("alert-success");
                alertBox.style.display = "block";
                // Add new image to the images container
                let imagesContainer = document.getElementById("uzdra_gallery_images");
                newImage = '<div id="uzdra_gallery_image" class="uzdra_gallery_hidden" style="display: none">\n';
                newImage += '<img src="' + response.image_link + '">\n';
                newImage += '<br>\n';
                newImage += '<a class="uzdra_gallery_delete" href="' + response.delete_url + '">\n';
                newImage += '<i class="material-icons">delete</i>\n';
                newImage += '</a>\n';
                newImage += '</div>\n';
                if (response.total_images == 1)
                    imagesContainer.innerHTML = newImage;
                else
                    imagesContainer.innerHTML += newImage;
                newHiddenImage = document.getElementsByClassName("uzdra_gallery_hidden")[0];
                $(newHiddenImage).show('slow');
                newHiddenImage.className = '';

                // Add event listeners on delete button
                let elements = document.getElementsByClassName("uzdra_gallery_delete");
                for (var i = 0; i < elements.length; i++) {
                    elements[i].addEventListener('click', deleteFile, false);
                }
            } else {
                alertBox.getElementsByClassName("alert-text")[0].innerHTML = response.errors.join('\r\n');
                alertBox.className = "alert";
                alertBox.classList.add("alert-warning");
                alertBox.style.display = "block";
            }
        }
    }

    // Delete file
    const deleteFile = async(event) =>
    {
        event.preventDefault();
        if (confirm("Are you sure?"))
        {
            let image = event.currentTarget.parentNode;
            let ajax_url = event.currentTarget.getAttribute("href");
            const data = await fetch(ajax_url, {
                method: "GET"
            });
            const response = await data.json();
            if (response.status) {
                $(image).hide('slow');
                setTimeout(() => {
                    image.remove();
                }, "2000")
            }
        }
    }

    // Add image button
    document.getElementById("uzdra_gallery_add_button").addEventListener("click", () => {
        document.getElementById("uzdra_gallery_fileupload").click();
    });

    // When file was selected execute upload
    document.getElementById("uzdra_gallery_fileupload").addEventListener("change", uploadFile);

    // Delete image buttons
    var elements = document.getElementsByClassName("uzdra_gallery_delete");
    for (var i = 0; i < elements.length; i++) {
        elements[i].addEventListener('click', deleteFile, false);
    }


});