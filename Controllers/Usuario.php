<?php
class Usuario extends Controllers {
    public function __construct() {
        parent::__construct();
    }

    public function usuario($idusuario) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                if (empty($idusuario) or !is_numeric($idusuario)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $arrUser = $this->model->getUsuario($idusuario);

                if (empty($arrUser)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'No hay datos para mostrar'
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'data-content' => $arrUser
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

    public function usuarios() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                $arrUser = $this->model->getUsuarios();

                if (empty($arrUser)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'No hay datos para mostrar',
                        'content-data' => ''
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'content-data' => $arrUser
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

                if (empty($_POST['nombre']) or !testString($_POST['nombre'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los Nombre(s)'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['apellido']) or !testString($_POST['apellido'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los Apellidos'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['email']) or !testEmail($_POST['email'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Email'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['password'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Password es requerido'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $strNombre = ucwords(strClean($_POST['nombre']));
                $strApellido = ucwords(strClean($_POST['apellido']));
                $strEmail = strClean($_POST['email']);
                $strPassword = hash("SHA256", $_POST['password']);

                $request = $this->model->setUser(
                    $strNombre,
                    $strApellido,
                    $strEmail,
                    $strPassword
                );

                if ($request > 0) {
                    $arrUser = array(
                        'id' => $request
                    );
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos registrados correctamente',
                        'content-data' => $arrUser
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'El Email ya esta registrado',
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

    public function actualizar($idusuario) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "PUT") {
                $data = json_decode(file_get_contents('php://input'), true);

                if (empty($idusuario) or !is_numeric($idusuario)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                if (empty($data['nombre']) or !testString($data['nombre'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los Nombre(s)'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($data['apellido']) or !testString($data['apellido'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los Apellidos'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($data['email']) or !testEmail($data['email'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Email'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $strNombre = ucwords(strClean($data['nombre']));
                $strApellido = ucwords(strClean($data['apellido']));
                $strEmail = strClean($data['email']);
                $strPassword = !empty($data['password']) ? hash("SHA256", $data['password']) : "";

                $buscar_usuario = $this->model->getUsuario($idusuario);

                if (empty($buscar_usuario)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Usuario no existe'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $request = $this->model->putUser(
                    $idusuario,
                    $strNombre,
                    $strApellido,
                    $strEmail,
                    $strPassword
                );

                if ($request > 0) {
                    $arrUser = array(
                        'idusuario' => $idusuario,
                        'nombre' => $strNombre,
                        'apellidos' => $strApellido,
                        'email' => $strEmail
                    );

                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos actualizados correctamente',
                        'content-data' => $arrUser
                    );
                } else {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Email ya esta registrado',
                        'content-data' => ""
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
        } catch (\Throwable $e) {
            echo "Error en el proceso" . $e->getMessage();
        }
        die();
    }

    public function eliminar($idusuario) {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "DELETE") {
                if (empty($idusuario) or !is_numeric($idusuario)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $buscar_usuario = $this->model->getUsuario($idusuario);

                if (empty($buscar_usuario)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Usuario no existe o ya fue eliminado'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }
                
                $request = $this->model->deleteUsuario($idusuario);
                if ($request) {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Registro eliminado correctamente'
                    );
                } else {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'No es posible eliminar el registro',
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

    public function  login() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "POST") {
                $data = json_decode(file_get_contents('php://input'), true);

                if (empty($data['email']) || empty($data['password'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error de datos'
                    );

                    jsonResponse($response, 400);
                    die();
                }

                $strEmail = strClean($data['email']);
                $strPassword = hash("SHA256", $data['password']);

                $request = $this->model->loginUser($strEmail, $strPassword);

                if (empty($request)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El Usuario o el Password es incorrecto'
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => '¡Bienvenido al sistema!',
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
}


?>