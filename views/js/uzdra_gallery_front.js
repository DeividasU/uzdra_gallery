window.addEventListener('DOMContentLoaded', async (event) => {
    // Show image
    const uzdraShowImage = async(event) =>
    {
        let image = event.currentTarget.getElementsByTagName('img')[0];
        let image_url = image.getAttribute("src");
        let cover = document.getElementsByClassName("product-cover")[0].getElementsByTagName("img")[0];
        cover.height = cover.height;
        cover.width = cover.width;
        cover.src = image_url;
    }

    var elements = document.getElementsByClassName("uzdra_gallery_image");
    for (var i = 0; i < elements.length; i++) {
        elements[i].addEventListener('click', uzdraShowImage, false);
    }
});