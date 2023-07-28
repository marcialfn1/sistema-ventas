<?php
class Cuenta extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cuenta($idcuenta)
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                if (empty($idcuenta) or !is_numeric($idcuenta)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $arrCuenta = $this->model->getCuenta($idcuenta);
                if (empty($arrCuenta)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Registro no encontrado'
                    );
                } else {
                    $arrMovimientos = $this->model->getMovimientos($idcuenta);
                    $arrCuenta['moivmientos'] = $arrMovimientos;
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'content-data' => $arrCuenta
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

    public function cuentas()
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {

                $arrCuentas = $this->model->getCuentass();
                if (empty($arrCuentas)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'No hay datos para mostrar',
                        'content-data' => ""
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'content-data' => $arrCuentas
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

    public function registro()
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "POST") {
                $_POST = json_decode(file_get_contents('php://input'), true);

                if (empty($_POST['clienteId']) or !is_numeric($_POST['clienteId'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Cliente es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['productoId']) or !is_numeric($_POST['productoId'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Producto es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['frecuenciaId']) or !is_numeric($_POST['frecuenciaId'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'La Frecuencia es requerida'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['monto']) or !is_numeric($_POST['monto'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Monto es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['cuotas']) or !is_numeric($_POST['cuotas'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'La Cuota es requerida'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['monto_cuota']) or !is_numeric($_POST['monto_cuota'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Monto de la Cuota es requerida'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['cargo']) or !is_numeric($_POST['cargo'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Cargo es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['saldo']) or !is_numeric($_POST['saldo'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Saldo es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $intClienteId = strClean($_POST['clienteId']);
                $intProductoId = strClean($_POST['productoId']);
                $intFrecuenciaId = strClean($_POST['frecuenciaId']);
                $strMonto = strClean($_POST['monto']);
                $strCuotas = strClean($_POST['cuotas']);
                $strMontoCuotas = strClean($_POST['monto_cuota']);
                $strCargo = strClean($_POST['cargo']);
                $strSaldo = strClean($_POST['saldo']);

                $request = $this->model->setCuenta(
                    $intClienteId,
                    $intProductoId,
                    $intFrecuenciaId,
                    $strMonto,
                    $strCuotas,
                    $strMontoCuotas,
                    $strCargo,
                    $strSaldo
                );

                if (is_numeric($request) and $request > 0) {
                    $arrCuenta = array(
                        'idContrato' => $request
                    );

                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos guardados correctamente',
                        'content-data' => $arrCuenta
                    );
                } else {
                    $arrCuenta = array(
                        'msg_tecnico' => $request
                    );

                    $response = array(
                        'status' => false,
                        'mensaje' => 'No es posible crear la cuenta',
                        'content-data' => $arrCuenta
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

    public function orden($idcuenta)
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                if (empty($idcuenta) or !is_numeric($idcuenta)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $arrCuenta = $this->model->getCuenta($idcuenta);
                if (empty($arrCuenta)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Registro no encontrado'
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'content-data' => $arrCuenta
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
