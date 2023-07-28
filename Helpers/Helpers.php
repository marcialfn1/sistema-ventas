<?php
// Requerir una vez el archivo autoload.php para hacer uso de JWT
require_once "Libraries/jwt/vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Retornar la url del proyecto, definada en el archivo config.php
function base_url()
{
    return BASE_URL;
}

function media()
{
    return BASE_URL . "/Assets";
}

// Muestra informacion formateada
function dep($data)
{
    $format = print_r('<pre>');
    $format = print_r($data);
    $format = print_r('</pre>');

    return $format;
}

// Elimina exceso de espacios entre palabras
function strClean($strCadena)
{
    $string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena);
    $string = trim($string); //Elimina espacios en blanco al inicio y al final
    $string = stripslashes($string); // Elimina las \ invertidas
    $string = str_ireplace("<script>", "", $string);
    $string = str_ireplace("</script>", "", $string);
    $string = str_ireplace("<script src>", "", $string);
    $string = str_ireplace("<script type=>", "", $string);
    $string = str_ireplace("SELECT * FROM", "", $string);
    $string = str_ireplace("DELETE FROM", "", $string);
    $string = str_ireplace("INSERT INTO", "", $string);
    $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
    $string = str_ireplace("DROP TABLE", "", $string);
    $string = str_ireplace("OR '1'='1", "", $string);
    $string = str_ireplace('OR "1"="1"', "", $string);
    $string = str_ireplace('OR ´1´=´1´', "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("LIKE '", "", $string);
    $string = str_ireplace('LIKE "', "", $string);
    $string = str_ireplace("LIKE ´", "", $string);
    $string = str_ireplace("OR 'a'='a", "", $string);
    $string = str_ireplace('OR "a"="a', "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("--", "", $string);
    $string = str_ireplace("^", "", $string);
    $string = str_ireplace("[", "", $string);
    $string = str_ireplace("]", "", $string);
    $string = str_ireplace("==", "", $string);

    return $string;
}

function jsonResponse(array $arrData, int $code)
{
    if (is_array($arrData)) {
        header("HTTP/1.1 " . $code); // Tipo de respuesta que deseamos obtener (200)
        header("Content-Type: aplication/json"); // Formato de salida (json)
        echo json_encode($arrData, true);
    }
}

function testString(string $data)
{
    $re = '/[a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/m';

    if (preg_match_all($re, $data)) {
        return true;
    } else {
        return false;
    }
}

function testEntero($numero)
{
    $re = '/[0-9]+$/m';

    if (preg_match_all($re, $numero)) {
        return true;
    } else {
        return false;
    }
}

function testEmail($email) {
    $re = '/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/';

    if (preg_match_all($re, $email)) {
        return true;
    } else {
        return false;
    }
}

function fntAuthorization(array $arrHeaders) {
    if (empty($arrHeaders['Authorization']) || $arrHeaders['Authorization'] == 'Bearer') {
        $response = array(
            'status' => false,
            'mensaje' => 'Autorizacion requerida'
        );

        jsonResponse($response, 400);
        die();
    } else {
        $tokenBearer = $arrHeaders['Authorization'];
        $arrTokenBearer = explode(" ", $tokenBearer); // Convierte en array el contenido de la variable $tokenBearer, usando " " como el delimitador de cada array.

        if ($arrTokenBearer[0] != 'Bearer') {
            $response = array(
                'status' => false,
                'mensaje' => 'Error de autorizacion'
            );
    
            jsonResponse($response, 400);
            die();
        } else {
            $token = $arrTokenBearer[1];

            try {
                $decode = JWT::decode($token, new Key(KEY_SECRET, 'HS512'));
            } catch (\Firebase\JWT\ExpiredException $e) {
                $arrResponde = array(
                    'status' => false,
                    'mensaje' => $e->getMessage()
                );

                jsonResponse($arrResponde, 400);
                die();
            }
        }
    }
}

?>