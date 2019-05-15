<?php
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 2019-03-16
 * Time: 14:56
 */

class exemplar
{
    private $idExemplar;
    private $idLivro;
    private $tipoLivro;

    public function __construct($idExemplar, $idLivro, $tipoLivro)
    {
        $this->idExemplar = $idExemplar;
        $this->idLivro = $idLivro;
        $this->tipoLivro = $tipoLivro;
    }

    public function getIdExemplar()
    {
        return $this->idExemplar;
    }

    public function setIdExemplar($idExemplar): void
    {
        $this->idExemplar = $idExemplar;
    }

    public function getIdLivro()
    {
        return $this->idLivro;
    }

    public function setIdLivro($idLivro): void
    {
        $this->idLivro = $idLivro;
    }

    public function getTipoLivro()
    {
        return $this->tipoLivro;
    }

    public function setTipoLivro($tipoLivro): void
    {
        $this->tipoLivro = $tipoLivro;
    }


}