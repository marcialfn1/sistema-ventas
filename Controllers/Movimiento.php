<?php
class Movimiento extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function registroTipoMovimiento()
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "POST") {
                $_POST = json_decode(file_get_contents('php://input'), true);

                if (empty($_POST['movimiento'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Movimiento es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['tipo_movimiento']) or ($_POST['tipo_movimiento'] != 1 and $_POST['tipo_movimiento'] != 2)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Tipo de Movimiento'
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

                $strMovimiento = ucwords(strClean($_POST['movimiento']));
                $intTipoMovimiento = $_POST['tipo_movimiento'];
                $strDescripcion = strClean($_POST['descripcion']);

                $request = $this->model->setTipoMovimiento($strMovimiento, $intTipoMovimiento, $strDescripcion);

                if ($request > 0) {
                    $arrMovimiento = array(
                        'idTipoMovimiento' => $request,
                        'movimiento' => $intTipoMovimiento,
                        'descripcion' => $strDescripcion
                    );

                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos guardados correctamente',
                        'data-content' => $arrMovimiento
                    );
                } else {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Tipo de Movimiento ya existe'
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

    public function tiposMovimiento()
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                $request = $this->model->getTiposMovimientos();

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

    // Metodos movimiento
    public function registroMovimiento()
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "POST") {
                $_POST = json_decode(file_get_contents('php://input'), true);

                if (empty($_POST['cuentaid']) or !is_numeric($_POST['cuentaid'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el ID de la Cuenta'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['tipomovimientoid']) or !is_numeric($_POST['tipomovimientoid'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el ID del Tipo de Movimiento'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['movimiento']) or ($_POST['movimiento'] != 1 and $_POST['movimiento'] != 2)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Movimiento'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['monto']) or !is_numeric($_POST['monto'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Monto'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['descripcion'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en la Descripcion'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $intCuentaID = strClean($_POST['cuentaid']);
                $intTipoMovimientoID = strClean($_POST['tipomovimientoid']);
                $intMovimiento = strClean($_POST['movimiento']);
                $strMonto = strClean($_POST['monto']);
                $strDescripcion = strClean($_POST['descripcion']);

                $arrMovimiento = $this->model->setMovimiento(
                    $intCuentaID,
                    $intTipoMovimientoID,
                    $intMovimiento,
                    $strMonto,
                    $strDescripcion
                );

                if (is_numeric($arrMovimiento) and $arrMovimiento > 0) {
                    $arrMovimiento = array(
                        'idMovimiento' => $arrMovimiento
                    );

                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos guardados correctamente',
                        'content-data' => $arrMovimiento
                    );
                } else {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'No es posible registrar el movimiento',
                        'msg_tecnico' => $arrMovimiento
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

    public function movimiento($idmovimiento)
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                if (empty($idmovimiento) or !is_numeric($idmovimiento)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $idmovimiento = strClean($idmovimiento);
                $arrMovimiento = $this->model->getMovimiento($idmovimiento);

                if (empty($arrMovimiento)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Registro no encontrado'
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'content-data' => $arrMovimiento
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

    public function movimientos() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                $arrMovimientos = $this->model->getMovimientos();

                if (empty($arrMovimientos)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'No hay datos para mostrar',
                        'content-data' => ""
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'content-data' => $arrMovimientos
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

    public function anular($idmovimiento) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "DELETE") {
                if (empty($idmovimiento) or !is_numeric($idmovimiento)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $request = $this->model->getMovimiento($idmovimiento);

                if (empty($request)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El registro no existe o ya fue eliminado'
                    );

                    jsonResponse($response, 400);
                    die();
                } else {
                    $request = $this->model->anularMovimiento($idmovimiento);

                    if (!empty($request)) {
                        $response = array(
                            'status' => true,
                            'mensaje' => 'Movimiento anulado',
                            'content-data' => $request[0]
                        );
                    } else {
                        $response = array(
                            'status' => false,
                            'mensaje' => 'No es posible eliminar el movimiento'
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
