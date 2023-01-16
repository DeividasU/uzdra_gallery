<div>
    <hr />
    <div class="col-md-12">
        <div id="uzdra_gallery_alert" class="alert" role="alert" style="display: none">
            <p class="alert-text">
            </p>
        </div>
    </div>
    <h2>{l s='Additional images gallery' mod='uzdra_gallery'}</h2>
    <div class="row">
        <input id="uzdra_gallery_fileupload" type="file" name="uzdra_gallery_fileupload" accept="image/*" style="display: none"/>
        <input id="uzdra_gallery_id_product" type="hidden" name="uzdra_gallery_id_product" value="{$uzdra_gallery_id_product}" />
        <div class="col-md-4">
            <button type="button" class="btn btn-outline-primary sensitive add" id="uzdra_gallery_add_button">
                <i class="material-icons">add_circle</i>
                {l s='Add image' mod='uzdra_gallery'}
            </button>
        </div>
    </div>
    <div id="uzdra_gallery_images">
        {if count($uzdra_gallery_images) > 0}
            {foreach from=$uzdra_gallery_images item=$image}
                <div id="uzdra_gallery_image">
                    <img src="{$image['image_link']}" />
                   <br>
                    <a class="uzdra_gallery_delete" href="{$image['delete_url']}"><i class="material-icons">delete</i></a>
                </div>
            {/foreach}
        {else}
        {l s='No additional images found' mod='uzdra_gallery'}
        {/if}
    </div>
    <hr />
</div>