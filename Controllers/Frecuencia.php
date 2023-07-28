<?php
class Frecuencia extends Controllers {
    public function __construct() {
        parent::__construct();
    }

    public function frecuencia($idfrecuencia) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                if (empty($idfrecuencia) or !is_numeric($idfrecuencia)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $buscar_frecuencia = $this->model->getFrecuencia($idfrecuencia);

                if (empty($buscar_frecuencia)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El registro no existe'
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'content-data' => $buscar_frecuencia
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

    public function frecuencias() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                $request = $this->model->getFrecuencias();

                if (empty($request)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'No hay datos para mostrar',
                        'content-data' => ""
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'content-data' => $request
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
                if (empty($_POST['frecuencia'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'La Frecuencia es requerida'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $strFrecuencia = ucwords(strClean($_POST['frecuencia']));
                $request = $this->model->setFrecuencia($strFrecuencia);
                
                if ($request > 0) {
                    $arrFrecuencia = array(
                        'idFrecuencia' => $request,
                        'frecuencia' => $strFrecuencia
                    );

                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos guardados correctamente',
                        'content-data' => $arrFrecuencia
                    );
                } else {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'La frecuencia ya existe'
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

    public function actualizar($idfrecuencia) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "PUT") {
                $arrData = json_decode(file_get_contents('php://input'), true);
                if (empty($idfrecuencia) or !is_numeric($idfrecuencia)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                if (empty($arrData['frecuencia'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'La Frecuencia es requerida'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $strFrecuencia = ucwords(strClean($arrData['frecuencia']));
                $buscar_frecuencia = $this->model->getFrecuencia($idfrecuencia);

                if (empty($buscar_frecuencia)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El registro no existe'
                    );
                    jsonResponse($response, 200);
                    die(); // Sino se coloca el die() para detener el proceso causa detalles
                }

                $request = $this->model->putFrecuencia($idfrecuencia, $strFrecuencia);

                if ($request) {
                    $arrFrecuencia = array(
                        'idFrecuencia' => $idfrecuencia,
                        'frecuencia' => $strFrecuencia
                    );

                    $response = array(
                        'status' => false,
                        'mensaje' => 'Datos actualizados correctamente',
                        'content-data' => $arrFrecuencia
                    );
                } else {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El registro ya existe'
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

    public function eliminar($idfrecuencia) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "DELETE") {
                if (empty($idfrecuencia) or !is_numeric($idfrecuencia)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $buscar_frecuencia = $this->model->getFrecuencia($idfrecuencia);

                if (empty($buscar_frecuencia)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El registro no existe o ya fue eliminado'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $request = $this->model->deleteFrecuencia($idfrecuencia);

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


?>