<?php

class CuentaModel extends Mysql {
    private $intIdCuenta;
    private $intIdCliente;
    private $intIdProducto;
    private $intIdFrecuencia;
    private $intMonto;
    private $intCuotas;
    private $intMontoCuotas;
    private $intCargo;
    private $intSaldo;
    public function __construct() {
        parent::__construct();
    }

    public function setCuenta(int $idcliente, int $idproducto, int $idfrecuencia, float $monto, int $cuotas, float $montoCuotas, float $cargo, float $saldo) {
        $this->intIdCliente = $idcliente;
        $this->intIdProducto = $idproducto;
        $this->intIdFrecuencia = $idfrecuencia;
        $this->intMonto = $monto;
        $this->intCuotas = $cuotas;
        $this->intMontoCuotas = $montoCuotas;
        $this->intCargo = $cargo;
        $this->intSaldo = $saldo;

        // dep(get_object_vars($this));

        $sql = "INSERT INTO cuenta(clienteid, productoid, frecuenciaid, monto, cuotas, monto_cuotas, cargo, saldo) VALUES (:idc, :idp, :idf, :mon, :cuo, :mcuo, :car, :sal)";

        $arrData = array(
            ":idc" => $this->intIdCliente,
            ":idp" => $this->intIdProducto,
            ":idf" => $this->intIdFrecuencia,
            ":mon" => $this->intMonto,
            ":cuo" => $this->intCuotas,
            ":mcuo" => $this->intMontoCuotas,
            ":car" => $this->intCargo,
            ":sal" => $this->intSaldo
        );

        $request = $this->insert($sql, $arrData);
        return $request;
    }

    public function getCuenta(int $idcuenta) {
        $this->intIdCuenta = $idcuenta;

        $sql = "SELECT c.idcuenta, c.frecuenciaid, f.frecuencia, c.monto, c.cuotas, c.monto_cuotas, c.cargo, c.saldo,
                        DATE_FORMAT(c.datecreated, '%d-%m-%Y') AS fechaRegistro,
                        c.clienteid, cl.nombres, cl.apellidos, cl.telefono, cl.email, cl.direccion, cl.nit, cl.nombrefiscal, 
                        cl.direccionfiscal, c.productoid, p.codigo AS cod_producto, p.nombre
                        FROM cuenta c
                        INNER JOIN frecuencia f
                        ON c.frecuenciaid = f.idfrecuencia
                        INNER JOIN cliente cl
                        ON c.clienteid = cl.id_cliente
                        INNER JOIN producto p
                        ON c.productoid = p.idproducto
                        WHERE c.idcuenta = :idcuenta";

        $arrData = array(
            ":idcuenta" => $this->intIdCuenta
        );

        $request = $this->select($sql, $arrData);
        return $request;
    }

    public function getMovimientos(int $idcuenta) {
        $this->intIdCuenta = $idcuenta;

        $sql = "SELECT m.idmovimiento, m.monto, m.descripcion, DATE_FORMAT(m.datecreated, '%d-%m-%Y') AS fecha,
                        tm.idtipomovimiento, tm.movimiento, tm.tipo_movimiento
                        FROM movimiento m
                        INNER JOIN tipo_movimiento tm
                        ON m.tipomovimientoid = tm.idtipomovimiento
                        WHERE m.cuentaid = $this->intIdCuenta AND m.status != 0";

        $request = $this->select_all($sql);
        return $request;
    }

    public function getCuentass() {
        $sql = "SELECT c.idcuenta,
                        DATE_FORMAT(c.datecreated, '%d-%m-%Y') AS fechaRegistro,
                        concat(cl.nombres, ' ', cl.apellidos) AS cliente,
                        f.frecuencia,
                        c.cuotas, 
                        c.monto_cuotas,
                        c.cargo, 
                        c.saldo
                        FROM cuenta c
                        INNER JOIN frecuencia f
                        ON c.frecuenciaid = f.idfrecuencia
                        INNER JOIN cliente cl
                        ON c.clienteid = cl.id_cliente
                        WHERE c.status != 0 ORDER BY c.idcuenta DESC";

        $request = $this->select_all($sql);
        return $request;
    }
}


?>