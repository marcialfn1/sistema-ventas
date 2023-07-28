<?php

class FrecuenciaModel extends Mysql {
    private $intIdFrecuencia;
    private $strFrecuencia;
    private $strFecha;
    private $intStatus;
    public function __construct() {
        parent::__construct();
    }

    public function setFrecuencia(string $frecuencia) {
        $this->strFrecuencia = $frecuencia;
        // dep(get_object_vars($this));

        $sql = "SELECT * FROM frecuencia WHERE frecuencia = :fre AND status != 0";
        $arrData = array(
            ":fre" => $this->strFrecuencia
        );

        $request = $this->select($sql, $arrData);

        if (empty($request))  {
            $sql_insert = "INSERT INTO frecuencia(frecuencia) VALUES(:fre)";

            $arrData = array(
                ":fre" => $this->strFrecuencia
            );

            $request = $this->insert($sql_insert, $arrData);
            return $request;
        } else {
            return false;
        }
    }

    public function getFrecuencia(int $idfrecuencia) {
        $this->intIdFrecuencia = $idfrecuencia;

        $sql = "SELECT idfrecuencia, 
                        frecuencia, 
                        DATE_FORMAT(datecreated, '%d-%m-%Y') AS fechaRegistro
                        FROM frecuencia WHERE idfrecuencia = :id AND status != 0";
        
        $arrData = array(
            ":id" => $this->intIdFrecuencia
        );

        $request = $this->select($sql, $arrData);

        // dep($request);exit;
        return $request;
    }

    public function putFrecuencia(int $idfrecuencia, string $frecuencia) {
        $this->intIdFrecuencia = $idfrecuencia;
        $this->strFrecuencia = $frecuencia;

        $sql = "SELECT * FROM frecuencia WHERE (frecuencia = :fre AND idfrecuencia != :id) AND status != 0";

        $arrData = array(
            ":fre" => $this->strFrecuencia,
            ":id" => $this->intIdFrecuencia
        );

        $request = $this->select($sql, $arrData);
        
        if (empty($request)) {
            $sql = "UPDATE frecuencia SET frecuencia = :fre WHERE idfrecuencia = :id";

            $arrData = array(
                ":fre" => $this->strFrecuencia,
                ":id" => $this->intIdFrecuencia
            );

            $request = $this->update($sql, $arrData);
            return $request;
        } else {
            return false;
        }
    }

    public function getFrecuencias() {
        $sql = "SELECT idfrecuencia, 
                        frecuencia, 
                        DATE_FORMAT(datecreated, '%d-%m-%Y') AS fechaRegistro
                        FROM frecuencia WHERE status != 0 ORDER BY idfrecuencia DESC";

        $request = $this->select_all($sql);
        return $request;
    }

    public function deleteFrecuencia(int $idfrecuencia) {
        $this->intIdFrecuencia = $idfrecuencia;

        $sql = "UPDATE frecuencia SET status = :estado WHERE idfrecuencia = :id";

        $arrData = array(
            ":estado" => 0,
            ":id" => $this->intIdFrecuencia
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }
}


?>