<?php
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 2019-03-16
 * Time: 14:49
 */

class emprestimo
{

    private $usuario;
    private $exemplar;
    private $dataEmprestimo;
    private $dataDevolucao;
    private $observacao;

    public function __construct($usuario, $exemplar, $dataEmprestimo, $dataDevolucao, $observacao)
    {
        $this->usuario = $usuario;
        $this->exemplar = $exemplar;
        $this->dataEmprestimo = $dataEmprestimo;
        $this->dataDevolucao = $dataDevolucao;
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
        return $this->dataEmprestimo;
    }

    public function setDataEmprestimo($dataEmprestimo): void
    {
        $this->dataEmprestimo = $dataEmprestimo;
    }

    public function getDataDevolucao()
    {
        return $this->dataDevolucao;
    }

    public function setDataDevolucao($dataDevolucao): void
    {
        $this->dataDevolucao = $dataDevolucao;
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