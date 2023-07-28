<?php
class Cliente extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cliente($idcliente)
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                //===================Validar Token ===================//
                $arrHeaders = getallheaders();
                $response = fntAuthorization($arrHeaders);
                //===================Validar Token ===================//

                if (empty($idcliente) or !is_numeric($idcliente)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    // Si no se pasa el parametro (idcliente) o tiene un formato incorrecto devuelve la respuesta anterior
                    jsonResponse($response, $code);
                    die();
                }

                $arrCliente = $this->model->getCliente($idcliente);

                if (empty($arrCliente)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'No hay datos para mostrar'
                    );
                } else {
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos encontrados',
                        'data-content' => $arrCliente
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
            $arrResponde = array(
                'status' => false,
                'mensaje' => $e->getMessage()
            );

            jsonResponse($arrResponde, 400);
        }
        die();
    }

    public function registro()
    {
        try {
            // dep($_SERVER); // Debuguear el server
            // exit;
            // Lo que hace $_SERVER es obtener el metodo que se estara enviando en una peticion HTTP
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "POST") {
                //===================Validar Token ===================//
                $arrHeaders = getallheaders();
                $response = fntAuthorization($arrHeaders);
                //===================Validar Token ===================//

                // Decodificar los inputs para especificar que los datos que estamos enviando por POST sea de tipo json con json_decode()
                $_POST = json_decode(file_get_contents('php://input'), true); // Con true confirmamos que los datos sean un array

                if (empty($_POST['identificacion'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'La identificacion es obligatoria'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['nombres']) or !testString($_POST['nombres'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los Nombre(s)'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['apellidos']) or !testString($_POST['apellidos'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los Apellidos'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($_POST['telefono']) or !testEntero($_POST['telefono'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Telefono'
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

                if (empty($_POST['direccion'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'La direccion es obligatoria'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                /* 
                Mediante las variables asignamos los que contenga $_POST en el elemento correspondiente
                La funcion strtolower convierte toda una cadena de string a minusculas
                La funcion ucwords convierte la primera letra de una cadena a mayusculas
                */

                $strIdentificacion = $_POST['identificacion'];
                $strNombres = ucwords(strtolower($_POST['nombres']));
                $strApellidos = ucwords(strtolower($_POST['apellidos']));
                $intTelefono = $_POST['telefono'];
                $strEmail = strtolower($_POST['email']);
                $strDireccion = $_POST['direccion'];
                // Como los siguientes datos no son obligatorios se usa la funcion strClean (declarada en los HELPERS) para limpiar las cadenas y evitar inyecciones SQL
                $strNit = !empty($_POST['nit']) ? strClean($_POST['nit']) : "";
                $strNomFiscal = !empty($_POST['nombrefiscal']) ? strClean($_POST['nombrefiscal']) : "";
                $strDirFiscal = !empty($_POST['direccionfiscal']) ? strClean($_POST['direccionfiscal']) : "";

                $request = $this->model->setCliente(
                    $strIdentificacion,
                    $strNombres,
                    $strApellidos,
                    $intTelefono,
                    $strEmail,
                    $strDireccion,
                    $strNit,
                    $strNomFiscal,
                    $strDirFiscal
                );

                if ($request > 0) {
                    $arrCliente = array(
                        "idcliente" => $request,
                        'identificacion' => $strIdentificacion,
                        'nombres' => $strNombres,
                        'apellidos' => $strApellidos,
                        'telefono' => $intTelefono,
                        'email' => $strEmail,
                        'direccion' => $strDireccion,
                        'nit' => $strNit,
                        'nombreFiscal' => $strNomFiscal,
                        'direccionFiscal' => $strDirFiscal
                    );
                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos registrados correctamente',
                        'content-data' => $arrCliente
                    );
                } else {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'La Identificacion o el Email ya existen.'
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

            jsonResponse($response, $code); // A la funcion declarada en los Helpers se envian los parametros (formato JSON y la respuesta)
            die(); // Detener el proceso

        } catch (Exception $e) {
            $arrResponde = array(
                'status' => false,
                'mensaje' => $e->getMessage()
            );

            jsonResponse($arrResponde, 400);
        }
        die(); // Detener el proceso
    }

    public function clientes()
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "GET") {
                // getallheader() esta funcion obtiene todo lo que se esta enviando en los headers de la solicitud http GET, POST...
                //===================Validar Token ===================//
                $arrHeaders = getallheaders();
                $response = fntAuthorization($arrHeaders);
                //===================Validar Token ===================//

                $arrData = $this->model->getClientes();

                if (empty($arrData)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'No hay datos para mostrar',
                        'data-content' => ''
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
            $arrResponde = array(
                'status' => false,
                'mensaje' => $e->getMessage()
            );

            jsonResponse($arrResponde, 400);
        }
        die();
    }

    public function actualizar($idcliente)
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "PUT") {
                $arrData = json_decode(file_get_contents('php://input'), true); // Obtener los datos que se estan enviando & con json_decode (JSON -> ARRAY),

                // empty(esta vacia), !(negacion)
                if (empty($idcliente) or !is_numeric($idcliente)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    // Si no se pasa el parametro (idcliente) o tiene un formato incorrecto devuelve la respuesta anterior
                    jsonResponse($response, $code);
                    die();
                }


                if (empty($arrData['identificacion'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'La identificacion es obligatoria'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($arrData['nombres']) or !testString($arrData['nombres'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los Nombre(s)'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($arrData['apellidos']) or !testString($arrData['apellidos'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los Apellidos'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($arrData['telefono']) or !testEntero($arrData['telefono'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Telefono'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($arrData['email']) or !testEmail($arrData['email'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en el Email'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                if (empty($arrData['direccion'])) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'La direccion es obligatoria'
                    );
                    jsonResponse($response, 200);
                    die();
                }

                $strIdentificacion = $arrData['identificacion'];
                $strNombres = ucwords(strtolower($arrData['nombres']));
                $strApellidos = ucwords(strtolower($arrData['apellidos']));
                $intTelefono = $arrData['telefono'];
                $strEmail = strtolower($arrData['email']);
                $strDireccion = $arrData['direccion'];
                $strNit = !empty($arrData['nit']) ? strClean($arrData['nit']) : "";
                $strNomFiscal = !empty($arrData['nombrefiscal']) ? strClean($arrData['nombrefiscal']) : "";
                $strDirFiscal = !empty($arrData['direccionfiscal']) ? strClean($arrData['direccionfiscal']) : "";

                $buscar_cliente = $this->model->getCliente($idcliente);

                if (empty($buscar_cliente)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El cliente no existe'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }
                // Si despues de verificar que el cliente si exista se realiza el proceso de actualizacion
                $request = $this->model->putCliente(
                    $idcliente,
                    $strIdentificacion,
                    $strNombres,
                    $strApellidos,
                    $intTelefono,
                    $strEmail,
                    $strDireccion,
                    $strNit,
                    $strNomFiscal,
                    $strDirFiscal
                );

                if ($request) {
                    $arrCliente = array(
                        "idcliente" => $idcliente,
                        'identificacion' => $strIdentificacion,
                        'nombres' => $strNombres,
                        'apellidos' => $strApellidos,
                        'telefono' => $intTelefono,
                        'email' => $strEmail,
                        'direccion' => $strDireccion,
                        'nit' => $strNit,
                        'nombreFiscal' => $strNomFiscal,
                        'direccionFiscal' => $strDirFiscal
                    );

                    $response = array(
                        'status' => true,
                        'mensaje' => 'Datos actualizados correctamente',
                        'content-data' => $arrCliente
                    );
                } else {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'La Identificacion o el Email ya existen',
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
        } catch (Exception $e) {
            echo "Error en el proceso" . $e->getMessage();
        }
        die(); // Detener el proceso
    }

    public function eliminar($idcliente)
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $response = [];

            if ($method == "DELETE") {
                if (empty($idcliente) or !is_numeric($idcliente)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'Error en los parametros'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                // Validar que el id del cliente exista en la bd
                $buscar_cliente = $this->model->getCliente($idcliente);
                if (empty($buscar_cliente)) {
                    $response = array(
                        'status' => false,
                        'mensaje' => 'El registro no existe o ya fue eliminado'
                    );
                    $code = 400;

                    jsonResponse($response, $code);
                    die();
                }

                $request = $this->model->deleteCliente($idcliente);
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
}
