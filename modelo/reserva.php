<?php

class reserva
{

    private $usuario;
    private $exemplar;
    private $dataReserva;
    private $dataLimite;
    private $observacao;

    public function __construct($usuario, $exemplar, $dataReserva, $dataLimite, $observacao)
    {
        $this->usuario = $usuario;
        $this->exemplar = $exemplar;
        $this->dataReserva = $dataReserva;
        $this->dataLimite = $dataLimite;
        $this->observacao = $observacao;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getExemplar()
    {
        return $this->exemplar;
    }

    public function setExemplar($exemplar): void
    {
        $this->exemplar = $exemplar;
    }

    public function getDataEmprestimo()
    {
        return $this->dataReserva;
    }

    public function setDataEmprestimo($dataReserva): void
    {
        $this->dataReserva = $dataReserva;
    }

    public function getDataLimite()
    {
        return $this->dataLimite;
    }

    public function setDataLimite($dataLimite): void
    {
        $this->dataLimite = $dataLimite;
    }

    public function getObservacao()
    {
        return $this->observacao;
    }

    public function setObservacao($observacao): void
    {
        $this->observacao = $observacao;
    }

}