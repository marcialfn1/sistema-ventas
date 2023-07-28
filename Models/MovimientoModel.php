<?php

class MovimientoModel extends Mysql
{
    private $intIdTipoMovimiento;
    private $strMovimiento;
    private $intTipoMovimiento;
    private $strDescripcionMov;

    private $intIdMovimiento;
    private $intCuentaId;
    private $descripcion;
    private $intMonto;
    private $strFecha;
    public function __construct()
    {
        parent::__construct();
    }

    public function setTipoMovimiento(string $movimiento, int $tipomovimiento, string $descripcion)
    {
        $this->strMovimiento = $movimiento;
        $this->intTipoMovimiento = $tipomovimiento;
        $this->strDescripcionMov = $descripcion;

        $sql = "SELECT * FROM tipo_movimiento WHERE movimiento = :mov AND status != 0";

        $arrData = array(
            ":mov" => $this->strMovimiento
        );

        $request = $this->select($sql, $arrData);

        if (empty($request)) {
            $sql = "INSERT INTO tipo_movimiento(movimiento, tipo_movimiento, descripcion) VALUES(:mov, :tipmov, :desc)";

            $arrData = array(
                ":mov" => $this->strMovimiento,
                ":tipmov" => $this->intTipoMovimiento,
                ":desc" => $this->strDescripcionMov
            );

            $request = $this->insert($sql, $arrData);
            return $request;
        } else {
            return false;
        }
    }

    public function getTiposMovimientos()
    {
        $sql = "SELECT idtipomovimiento, movimiento, tipo_movimiento FROM tipo_movimiento WHERE status != 0 ORDER BY idtipomovimiento DESC";

        $request = $this->select_all($sql);
        return $request;
    }

    public function setMovimiento(int $idcuenta, int $idmovimiento, int $movimiento, float $monto, string $descripcion)
    {
        $this->intCuentaId = $idcuenta;
        $this->intIdMovimiento = $idmovimiento;
        $this->intTipoMovimiento = $movimiento;
        $this->intMonto = $monto;
        $this->descripcion = $descripcion;

        $sql = "INSERT INTO movimiento(cuentaid, tipomovimientoid, movimiento, monto, descripcion) VALUES (:idc, :tpm, :mov, :mon, :desc)";

        $arrData = array(
            ":idc" => $this->intCuentaId,
            ":tpm" => $this->intIdMovimiento,
            ":mov" => $this->intTipoMovimiento,
            ":mon" => $this->intMonto,
            ":desc" => $this->descripcion
        );

        $request = $this->insert($sql, $arrData);
        return $request;
    }

    public function getMovimiento(int $idmovimiento)
    {
        $this->intIdMovimiento = $idmovimiento;

        $sql = "SELECT m.idmovimiento, m.cuentaid, m.movimiento, m.monto, m.descripcion,
                        DATE_FORMAT(m.datecreated, '%d-%m-%Y') AS fecha,
                        tm.idtipomovimiento, tm.movimiento AS nombreMovimiento
                        FROM movimiento m
                        INNER JOIN tipo_movimiento tm
                        ON m.tipomovimientoid = tm.idtipomovimiento
                        WHERE m.idmovimiento = :idm AND m.status != 0";

        $arrData = array(
            ":idm" => $this->intIdMovimiento
        );

        $request = $this->select($sql, $arrData);
        return $request;
    }

    public function getMovimientos() {
        $sql = "SELECT m.idmovimiento, m.cuentaid, m.monto,
                        DATE_FORMAT(m.datecreated, '%d-%m-%Y') AS fecha,
                        tm.movimiento AS nombreMovimiento
                        FROM movimiento m
                        INNER JOIN tipo_movimiento tm
                        ON m.tipomovimientoid = tm.idtipomovimiento
                        WHERE m.status != 0 ORDER BY m.idmovimiento DESC";
        
        $request = $this->select_all($sql);
        return $request;
    }

    public function anularMovimiento(int $idmovimiento) {
        $this->intIdMovimiento = $idmovimiento;

        $sql = "CALL anular_movimiento(:idmovimiento)";

        $arrData = array(
            ":idmovimiento" => $this->intIdMovimiento
        );

        $request = $this->call_execute($sql, $arrData);
        return $request;
    }
}
