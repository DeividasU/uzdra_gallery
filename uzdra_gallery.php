<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Uzdra_Gallery extends Module
{
    public function __construct()
    {
        $this->name = 'uzdra_gallery';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Deividas Uzdra';
        $this->displayName = $this->l('Custom gallery for product');
        $this->description = $this->l('Products custom gallery feature');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.8');
        $this->token = Tools::hash($this->name . date("Ymd"));
        $this->file_upload_location = _PS_MODULE_DIR_ . $this->name . "/images/";
        parent::__construct();
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('backOfficeHeader')
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayAfterProductThumbs')
            && $this->registerHook('displayAdminProductsMainStepLeftColumnMiddle');
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->unregisterHook('backOfficeHeader')
            && $this->unregisterHook('displayHeader')
            && $this->unregisterHook('displayAfterProductThumbs')
            && $this->unregisterHook('displayAdminProductsMainStepLeftColumnMiddle');
    }

    /**
     * Custom gallery template on Admin Products page
     * @param $params
     * @return mixed
     */
    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params)
    {
        $this->context->smarty->assign(
            array(
                'uzdra_gallery_id_product' => $params['id_product'],
                'uzdra_gallery_images' => $this->getProductCustomGallery($params['id_product'])
            )
        );
        return $this->display(__FILE__, "/views/templates/hook/admin_product_tab.tpl");
    }

    /**
     * FrontOffice product page hook
     * @param $params
     * @return mixed
     */
    public function hookDisplayAfterProductThumbs($params)
    {
        $this->context->smarty->assign(
            array(
                'uzdra_gallery_images' => $this->getProductCustomGallery(Tools::getValue('id_product')),
            )
        );
        return $this->display(__FILE__, "/views/templates/hook/front_product_tab.tpl");
    }

    /**
     * Custom gallery images for product
     * @param $id_product
     * @return array
     */
    public function getProductCustomGallery($id_product)
    {
        $images = [];

        // Check if product has any additional images
        if (!is_dir($this->file_upload_location . $id_product))
            return $images;

        // Get image list
        $imagesList = array_diff(scandir($this->file_upload_location . $id_product), array('..', '.'));

        $link = new Link();
        $imagesUrl = Context::getContext()->shop->getBaseURL(true) . "modules/" . $this->name . "/images/" . $id_product . "/";

        foreach ($imagesList as $image)
        {
            $images[] = [
                'filename' => $image,
                'image_link' => $imagesUrl . $image,
                'delete_url' => $link->getModuleLink('uzdra_gallery', 'ajax', array(
                    'ajax' => true,
                    'action' => 'delete',
                    'token' => $this->token,
                    'id_product' => $id_product,
                    'filename' => $image
                ))
            ];
        }
        return $images;
    }


    /**
     * Javascript/CSS backend
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('controller') == 'AdminProducts'){
            $this->context->controller->addCSS($this->_path.'views/css/uzdra_gallery_back.css');
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path.'views/js/uzdra_gallery.js');
            $link = new Link();
            $ajax_url = $link->getModuleLink('uzdra_gallery', 'ajax', array(
                'ajax' => true,
                'action' => 'upload',
                'token' => $this->token
            ));
            Media::addJsDef(array('uzdra_gallery_fileupload_url' => $ajax_url));
        }
    }

    /**
     * Javascript/CSS frontend
     */
    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/uzdra_gallery_front.css');
        $this->context->controller->addJS($this->_path.'views/js/uzdra_gallery_front.js');
    }

}
