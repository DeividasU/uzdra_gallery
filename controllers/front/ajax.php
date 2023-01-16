<?php

class Uzdra_GalleryAjaxModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {
        $this->ajax = true;
        parent::initContent();
        $this->parseActions();
    }

    public function parseActions()
    {
        if (!$this->checkProcessToken()) {
            die('Wrong token');
        }
        switch (Tools::getValue('action')) {
            case 'upload':
                $this->fileUpload();
                break;
            case 'delete':
                $this->deleteFile();
                break;
            default:
                die(json_encode(['error' => 'Method ' . Tools::getValue('action').' not exist']));
        }
        die();
    }

    /**
     * All magic happens here
     */
    public function fileUpload()
    {
        $status = false;
        $errors = [];
        $id_product = Tools::getValue('id_product');
        $filename = $_FILES['file']['name'];

        // Check images folder exist
        if (!is_dir($this->module->file_upload_location))
        {
            try {
                mkdir($this->module->file_upload_location);
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        // Check folder with product_id exist
        if (!is_dir($this->module->file_upload_location . $id_product))
        {
            try {
                mkdir($this->module->file_upload_location . $id_product);
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        // Check if image exist
        if (file_exists($this->module->file_upload_location . $id_product . "/" . $filename))
        {
            try {
                $errors[] = $this->module->l('Image with such file name already exist.');
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        $location = $this->module->file_upload_location . $id_product . "/" . $filename;

        // Try to upload file
        if (!$errors)
        {
            try {
                move_uploaded_file($_FILES['file']['tmp_name'], $location);
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        // Check if file was uploaded
        if (!$errors && file_exists($this->module->file_upload_location . $id_product . "/" . $filename))
            $status = true;

        $link = new Link();
        $imagesUrl = Context::getContext()->shop->getBaseURL(true) . "modules/" . $this->module->name . "/images/" . $id_product . "/";

        // Response
        die(json_encode([
            'errors' => $errors,
            'filename' => $_FILES['file']['name'],
            'file_was_uploaded' => $status,
            'success_message' => $this->module->l('Image was successfully uploaded.'),
            'id_product' => $id_product,
            'image_link' => $imagesUrl . $_FILES['file']['name'],
            'total_images' => count($this->module->getProductCustomGallery($id_product)),
            'delete_url' => $link->getModuleLink('uzdra_gallery', 'ajax', array(
                'ajax' => true,
                'action' => 'delete',
                'token' => $this->module->token,
                'id_product' => $id_product,
                'filename' => $_FILES['file']['name']
            ))
        ]));
    }

    /**
     * Delete file
     */
    public function deleteFile()
    {
        $status = false;
        $errors = [];
        $id_product = Tools::getValue('id_product');
        $filename = Tools::getValue('filename');

        // Remove file
        try {
            unlink($this->module->file_upload_location . $id_product . "/" . $filename);
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (!$errors)
            $status = true;

        // Response
        die(json_encode([
            'errors' => $errors,
            'status' => $status
        ]));
    }

    /**
     * Ajax token check
     * @return bool
     */
    public function checkProcessToken()
    {
        if (Tools::getValue('token') === $this->module->token) {
            return true;
        }
        return false;
    }
}
