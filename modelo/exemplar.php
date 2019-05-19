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
    private $livro;
    private $podeCircular;

    public function __construct($idExemplar, $livro, $podeCircular)
    {
        $this->idExemplar = $idExemplar;
        $this->livro = $livro;
        $this->podeCircular = $podeCircular;
    }

    public function getIdExemplar()
    {
        return $this->idExemplar;
    }

    public function setIdExemplar($idExemplar): void
    {
        $this->idExemplar = $idExemplar;
    }

    public function getLivro()
    {
        return $this->livro;
    }

    public function setLivro($livro): void
    {
        $this->livro = $livro;
    }

    public function podeCircular()
    {
        return $this->podeCircular;
    }

    public function setTipoLivro($podeCircular): void
    {
        $this->podeCircular = $podeCircular;
    }


}