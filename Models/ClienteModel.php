<?php

class ClienteModel extends Mysql
{

    private $intIdCliente;
    private $strIdentificacion;
    private $strNombres;
    private $strApellidos;
    private $intTelefono;
    private $strEmail;
    private $strDireccion;
    private $intNit;
    private $strNomFiscal;
    private $strDireFiscal;
    private $intStatus;

    public function __construct()
    {
        parent::__construct();
    }

    public function setCliente(string $identificacion, string $nombres, string $apellidos, int $telefono, string $email, string $direccion, string $nit, string $nomFiscal, string $dirFiscal)
    {
        $this->strIdentificacion = $identificacion;
        $this->strNombres = $nombres;
        $this->strApellidos = $apellidos;
        $this->intTelefono = $telefono;
        $this->strEmail = $email;
        $this->strDireccion = $direccion;
        $this->intNit = $nit;
        $this->strNomFiscal = $nomFiscal;
        $this->strDireFiscal = $dirFiscal;

        $sql = "SELECT identificacion, email FROM cliente WHERE (email = :email OR identificacion = :iden) AND status = :estado";
        $arrParams = array(
            ":email" => $this->strEmail,
            ":iden" => $this->strIdentificacion,
            ":estado" => 1
        );
        // Llamar al metodo select() en donde se pasaron los parametros $sql y $arrParams
        $request = $this->select($sql, $arrParams);

        if (!empty($request)) {
            return false;
        } else {
            $query_insert = "INSERT INTO cliente(identificacion, nombres, apellidos, telefono, email, direccion, nit, nombrefiscal, direccionfiscal) 
                            VALUES (:iden, :nombre, :apellido, :tel, :mail, :dire, :nit, :nomFiscal, :dirFiscal)";
            $arrData = array(
                ":iden" => $this->strIdentificacion,
                ":nombre" => $this->strNombres,
                ":apellido" => $this->strApellidos,
                ":tel" => $this->intTelefono,
                ":mail" => $this->strEmail,
                ":dire" => $this->strDireccion,
                ":nit" => $this->intNit,
                ":nomFiscal" => $this->strNomFiscal,
                ":dirFiscal" => $this->strDireFiscal
            ); // Al momento de enviar el array se debe de checar que las claves coincidan, porque de lo contrario no se realizara el insert o cualquier otro proceso.
            $request_insert = $this->insert($query_insert, $arrData);
            return $request_insert;
            // dep($arrData);
            // exit;
        }
        // Validar si realmente estan llegando los datos que se estan enviando desde el controlador ($this) -> se usa para hacer referencia a la misma clase
        // dep(get_object_vars($this));
    }

    public function putCliente(int $idcliente, string $identificacion, string $nombres, string $apellidos, int $telefono, string $email, string $direccion, string $nit, string $nomFiscal, string $dirFiscal)
    {
        $this->intIdCliente = $idcliente;
        $this->strIdentificacion = $identificacion;
        $this->strNombres = $nombres;
        $this->strApellidos = $apellidos;
        $this->intTelefono = $telefono;
        $this->strEmail = $email;
        $this->strDireccion = $direccion;
        $this->intNit = $nit;
        $this->strNomFiscal = $nomFiscal;
        $this->strDireFiscal = $dirFiscal;

        // dep(get_object_vars($this));

        $sql = "SELECT identificacion, email FROM cliente
        WHERE (email = :mail AND id_cliente != :id) 
        OR (identificacion = :iden AND id_cliente != :id) 
        AND status = 1";

        $arrData = array(
            ":mail" => $this->strEmail,
            ":id" => $this->intIdCliente,
            ":iden" => $this->strIdentificacion
        );

        $request_cliente = $this->select($sql, $arrData);

        if (empty($request_cliente)) {
            $sql = "UPDATE cliente SET 
            identificacion = :iden, 
            nombres = :nom, 
            apellidos = :ape, 
            telefono = :tel, 
            email = :mail, 
            direccion = :dire, 
            nit = :nit, 
            nombrefiscal = :nf, 
            direccionfiscal = :dirf 
            WHERE id_cliente = :id";

            $arrData = array(
                ":iden" => $this->strIdentificacion,
                ":nom" => $this->strNombres,
                ":ape" => $this->strApellidos,
                ":tel" => $this->intTelefono,
                ":mail" => $this->strEmail,
                ":dire" => $this->strDireccion,
                ":nit" => $this->intNit,
                ":nf" => $this->strNomFiscal,
                ":dirf" => $this->strDireFiscal,
                ":id" => $this->intIdCliente
            );

            // Invocar al metodo declarado en la clase Mysql
            $request = $this->update($sql, $arrData);
            return $request;

        } else {
            return false;
        }
    }

    public function getCliente(int $idcliente)
    {
        $this->intIdCliente = $idcliente;

        $sql = "SELECT 
        id_cliente, 
        nombres, 
        apellidos,
        telefono, 
        email, 
        direccion, 
        nit, 
        nombrefiscal, 
        direccionfiscal, 
        DATE_FORMAT(datecreated, '%d-%m-%Y') AS fechaRegistro
        FROM cliente WHERE id_cliente = :id AND status != 0";

        $arrData = array(
            ":id" => $this->intIdCliente
        );

        $request = $this->select($sql, $arrData);
        return $request;
    }

    public function getClientes()
    {
        $sql = "SELECT 
        id_cliente, 
        nombres, 
        apellidos,
        telefono, 
        email, 
        direccion, 
        nit, 
        nombrefiscal, 
        direccionfiscal, 
        DATE_FORMAT(datecreated, '%d-%m-%Y') AS fechaRegistro
        FROM cliente WHERE status != 0 ORDER BY id_cliente DESC";

        $request = $this->select_all($sql);
        return $request;
    }

    public function deleteCliente(int $idcliente) {
        /*
        // Codigo de ejemplo para eliminar fisicamente los registros de una base de datos
        $this->intIdCliente = $idcliente;

        $sql = "DELETE FROM cliente WHERE id_cliente = :id";

        $arrData = array(
            ":id" => $this->intIdCliente
        );

        $request = $this->delete($sql, $arrData);
        return $request;
        */

        // Codigo para actualizar solamente el status de los clientes a (0) -> suspendido-eliminado... etc...
        $this->intIdCliente = $idcliente;

        $sql = "UPDATE cliente SET status = :estado WHERE id_cliente = :id";

        $arrData = array(
            ":estado" => 0,
            ":id" => $this->intIdCliente
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }
}