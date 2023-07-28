<?php
class Producto extends Controllers {
    public function __construct() {
        parent::__construct();
    }

    public function producto($idproducto) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                if (empty($idproducto) or !is_numeric($idproducto)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $arrData = $this->model->getProducto($idproducto);

                if (empty($arrData)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Registro no encontrado'
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'content-data' => $arrData
                    );
                }

                $code = 200;
            } else {
                $response = array(
                    'status' => false,
                    'mensaje' => 'Error en la solicitud' . ' ' . $method
                );
                $code = 400;
            }

            jsonResponse($response, $code);
            die();
        } catch (Exception $e) {
            echo "Error en el proceso" . $e->getMessage();
        }
        die();
    }

    public function productos() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                $arrData = $this->model->getProductos();

                if (empty($arrData)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'No hay datos para mostrar',
                        'content-data' => ""
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'content-data' => $arrData
                    );
                }
                $code = 200;
            } else {
                $response = array(
                    'status' => false,
                    'mensaje' => 'Error en la solicitud' . ' ' . $method
                );
                $code = 400;
            }

            jsonResponse($response, $code);
            die();
        } catch (Exception $e) {
            echo "Error en el proceso" . $e->getMessage();
        }
        die();
    }

    public function registro() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "POST") {
                $_POST = json_decode(file_get_contents('php://input'), true);
                if (empty($_POST['codigo'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Codigo es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['nombre'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Nombre es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['descripcion'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Descripcion es requerida'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['precio']) OR !is_numeric($_POST['precio'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Precio'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $strCodigo = strClean($_POST['codigo']);
                $strNombre = strClean($_POST['nombre']);
                $strDescripcion = strClean($_POST['descripcion']);
                $strPrecio = $_POST['precio'];

                $request = $this->model->setProducto(
                    $strCodigo, 
                    $strNombre, 
                    $strDescripcion, 
                    $strPrecio
                );

                if ($request > 0) {
                    $arrProducto = array(
                        "idproducto" => $request,
                        'codigo' => $strCodigo,
                        'nombre' => $strNombre,
                        'descripcion' => $strDescripcion,
                        'precio' => $strPrecio
                    );
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos registrados correctamente',
                        'content-data' => $arrProducto
                    );
                } else {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Codigo del producto ya existe'
                    );
                }
                $code = 200;
            } else {
                $response = array(
                    'status' => false,
                    'mensaje' => 'Error en la solicitud' . ' ' . $method
                );
                $code = 400;
            }

            jsonResponse($response, $code);
            die();
        } catch (Exception $e) {
            echo "Error en el proceso" . $e->getMessage();
        }
        die();
    }

    public function actualizar($idproducto) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "PUT") {
                $arrData = json_decode(file_get_contents('php://input'), true);

                if (empty($idproducto) or !is_numeric($idproducto)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                if (empty($arrData['codigo'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Codigo es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($arrData['nombre'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Nombre es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($arrData['descripcion'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Descripcion es requerida'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($arrData['precio']) OR !is_numeric($arrData['precio'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Precio'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $strCodigo = strClean($arrData['codigo']);
                $strNombre = strClean($arrData['nombre']);
                $strDescripcion = strClean($arrData['descripcion']);
                $strPrecio = $arrData['precio'];

                $buscar_producto = $this->model->getProducto($idproducto);

                if (empty($buscar_producto)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El registro no existe'
                    );
                    jsonResponse($response, 200);
                    die(); // Es importante hacer uso de die(), porque sino el proceso continua.
                }

                $request = $this->model->putProducto(
                    $idproducto,
                    $strCodigo, 
                    $strNombre, 
                    $strDescripcion, 
                    $strPrecio
                );

                if ($request) {
                    $arrProducto = array(
                        "idproducto" => $idproducto,
                        'codigo' => $strCodigo,
                        'nombre' => $strNombre,
                        'descripcion' => $strDescripcion,
                        'precio' => $strPrecio
                    );
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos actualizados correctamente',
                        'content-data' => $arrProducto
                    );
                } else {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El codigo ya existe'
                    );
                }
                $code = 200;
            } else {
                $response = array(
                    'status' => false,
                    'mensaje' => 'Error en la solicitud' . ' ' . $method
                );
                $code = 400;
            }

            jsonResponse($response, $code);
            die();
        } catch (Exception $e) {
            echo "Error en el proceso" . $e->getMessage();
        }
        die();
    }

    public function eliminar($idproducto) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "DELETE") {
                if (empty($idproducto) or !is_numeric($idproducto)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $request = $this->model->getProducto($idproducto);

                if (empty($request)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El registro no existe o ya fue eliminado'
                    );

                    jsonResponse($response, 400);
                    die();
                } else {
                    $request = $this->model->deleteProducto($idproducto);

                    if ($request) {
                        $response = array(
                            'status' => true,
                            'mensaje' => 'Registro eliminado correctamente'
                        );
                    } else {
                        $response = array(
                            'status' => false,
                            'mensaje' => 'No es posible eliminar el registro'
                        );
                    }
                }
                $code = 200;
            } else {
                $response = array(
                    'status' => false,
                    'mensaje' => 'Error en la solicitud' . ' ' . $method
                );
                $code = 400;
            }

            jsonResponse($response, $code);
            die();
        } catch (Exception $e) {
            echo "Error en el proceso" . $e->getMessage();
        }
        die();
    }
}
