<?php

class UsuarioModel extends Mysql
{
    private $intIdUsuario;
    private $strNombre;
    private $strApellido;
    private $strEmail;
    private $strPassword;
    public function __construct()
    {
        parent::__construct();
    }

    public function setUser(string $nombre, string $apellido, string $email, string $password)
    {
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strEmail = $email;
        $this->strPassword = $password;

        // dep(get_object_vars($this));

        $sql = "SELECT email FROM usuario WHERE email = '$this->strEmail' AND status != 0";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $sql = "INSERT INTO usuario(nombre, apellido, email, password) VALUES(:nom, :ape, :mail, :pass)";

            $arrData = array(
                ":nom" => $this->strNombre,
                ":ape" => $this->strApellido,
                ":mail" => $this->strEmail,
                ":pass" => $this->strPassword
            );

            $request = $this->insert($sql, $arrData);
            return $request;
        } else {
            return false;
        }
    }

    public function getUsuario($idusuario)
    {
        $this->intIdUsuario = $idusuario;

        $sql = "SELECT id_usuario,
                        nombre,
                        apellido,
                        email,
                        DATE_FORMAT(datecreated, '%d-%m-%Y') AS fechaRegistro
                        FROM usuario WHERE id_usuario = :id_user AND status != 0";
        $arrData = array(
            ":id_user" => $this->intIdUsuario
        );

        $request = $this->select($sql, $arrData);
        return $request;
    }

    public function getUsuarios()
    {
        $sql = "SELECT id_usuario,
        nombre,
        apellido,
        email,
        DATE_FORMAT(datecreated, '%d-%m-%Y') AS fechaRegistro
        FROM usuario WHERE status != 0 ORDER BY id_usuario DESC";

        $request = $this->select_all($sql);
        return $request;
    }

    public function putUser($idusuario, $nombre, $apellido, $email, $password)
    {
        $this->intIdUsuario = $idusuario;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strEmail = $email;
        $this->strPassword = $password;

        // dep(get_object_vars($this));

        $sql = "SELECT email FROM usuario
        WHERE (email = :mail AND id_usuario != :id) AND status != 0";

        $arrData = array(
            ":mail" => $this->strEmail,
            ":id" => $this->intIdUsuario
        );

        $request = $this->select($sql, $arrData);

        if (empty($request)) {
            if ($this->strPassword == "") {
                $sql = "UPDATE usuario SET 
                nombre = :nom, 
                apellido = :ape, 
                email = :mail
                WHERE id_usuario = :id";

                $arrData = array(
                    ":nom" => $this->strNombre,
                    ":ape" => $this->strApellido,
                    ":mail" => $this->strEmail,
                    ":id" => $this->intIdUsuario
                );
            } else {
                $sql = "UPDATE usuario SET 
                nombre = :nom, 
                apellido = :ape, 
                email = :mail,
                password = :pass
                WHERE id_usuario = :id";

                $arrData = array(
                    ":nom" => $this->strNombre,
                    ":ape" => $this->strApellido,
                    ":mail" => $this->strEmail,
                    ":pass" => $this->strPassword,
                    ":id" => $this->intIdUsuario
                );
            }

            $request = $this->update($sql, $arrData);
            return $request;
        } else {
            return false;
        }
    }

    public function deleteUsuario($idusuario) {
        $this->intIdUsuario = $idusuario;

        $sql = "UPDATE usuario SET status = :status WHERE id_usuario = :id";

        $arrData = array(
            ":status" => 0,
            ":id" => $this->intIdUsuario
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function loginUser($email, $password) {
        $this->strEmail = $email;
        $this->strPassword = $password;

        // dep(get_object_vars($this));
        // Uso de BINARY para identificar (mayusculas & minusculas en la base de datos)
        $sql = "SELECT id_usuario, status FROM usuario WHERE email = BINARY :mail AND password = BINARY :pass AND status != 0";

        $arrData = array(
            ":mail" => $this->strEmail,
            ":pass" => $this->strPassword
        );

        $request = $this->select($sql, $arrData);
        return $request;
    }
}
