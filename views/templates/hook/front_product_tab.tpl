{if $uzdra_gallery_images}
    <div id="uzdra_gallery_html">
        <h2 id="uzdra_gallery_title">{l s='Additional images' mod='uzdra_gallery'}</h2>
        <div id="uzdra_gallery_images" style="display: none">
            {foreach from=$uzdra_gallery_images item=$image}
                <div class="uzdra_gallery_image">
                    <img src="{$image['image_link']}" width="100" height="100" />
                </div>
            {/foreach}
        </div>
    </div>
    {literal}
        <script type="text/javascript">
            let uzdraGalleryHtml = document.getElementById("uzdra_gallery_html").innerHTML;
            document.getElementById("uzdra_gallery_html").remove();
            document.getElementsByClassName("page-content")[0].insertAdjacentHTML('afterend', uzdraGalleryHtml);
            document.getElementById("uzdra_gallery_images").style.display = 'flex';
        </script>
    {/literal}
{/if}