<?php

namespace App\Controllers;

use App\Models\producto_model;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Files\File;

class Producto extends BaseController
{

    ///////////// JSON /////////////////////////
    public function getjson_ListadoProductos($ArrayName) {
        $model = new producto_model();
        $datos = $model->getListado();
        if ($datos) 
            if($ArrayName!="")
                 echo json_encode([$ArrayName => $datos]);
            else
            echo json_encode($datos);
    
    }


    public function insertProducto()
    {
        $input = $this->getRequestInput($this->request);

        $rules = [
            'idcategoria' => [
                'rules'  => 'required|numeric',
                'errors' => ['required' => 'IDMarca del producto requerido','numeric' => 'IDMarca debe ser numérica'],
            ],
            'descripcion' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => ['required' => 'Descripción del Producto  requerida', 'min_length' => 'La descripción debe tener al menos 3 caracteres' ],
            ]
        ];


        if (!$this->validateRequest($input, $rules))
            return $this->sendResponse(['validaciones' => $this->getErrorsAsArray($this->validator->getErrors())], ResponseInterface::HTTP_BAD_REQUEST);

        try {
            $model = new producto_model();
            $model->insert($input);
            return $this->sendResponse(['message' => 'Producto creado correctamente. ID: ' . $model->getInsertID()]);
        } catch (Exception $e) {
            return $this->sendResponse(['error' => $e->getMessage()], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProducto($id)
    {
        if (!isset($id))         return $this->sendBadRequest('Parámetro ID requerido');
        if (!is_numeric($id))    return $this->sendBadRequest('Parámetro ID numérico');
        if ($id < 1)             return $this->sendBadRequest('Parámetro ID numérico mayor a 0');

        $input = $this->getRequestInput($this->request);

        $model = new producto_model();
        $subc = $model->findById($id); 
        if(!$subc) return $this->sendBadRequest("Producto a actualizar No existe");

        $rules = [
            'idcategoria' => [
                'rules'  => 'required|numeric',
                'errors' => ['required' => 'IDMIDCategoríaarca del producto requerido','numeric' => 'IDCategoría debe ser numérica'],
            ],
            'descripcion' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => ['required' => 'Descripción del Producto  requerida', 'min_length' => 'La descripción debe tener al menos 3 caracteres' ],
            ]
        ];

        if (!$this->validateRequest($input, $rules))
            return $this->sendResponse(['validaciones' => $this->getErrorsAsArray($this->validator->getErrors())], ResponseInterface::HTTP_BAD_REQUEST);

     

       try {
            $model->update($id, $input);
            return $this->sendResponse(['message' => 'Producto editado correctamente']);
        } catch (Exception $e) {
            return $this->sendResponse(['error' => $e->getMessage()], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteProducto($id)
    {
        if (!isset($id))         return $this->sendBadRequest('Parámetro ID requerido');
        if (!is_numeric($id))    return $this->sendBadRequest('Parámetro ID numérico');
        if ($id < 1)             return $this->sendBadRequest('Parámetro ID numérico mayor a 0');


        $model = new producto_model();
        $subc = $model->findById($id); 
        if(!$subc) return $this->sendBadRequest("Producto a eliminar No existe");
        
        
       try {
            $model->delete($id);
            return $this->sendResponse(['message' => 'Producto eliminado correctamente']);
        } catch (Exception $e) {
            return $this->sendResponse(['error' => $e->getMessage()], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
      
}