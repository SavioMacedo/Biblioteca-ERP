<?php
/**
 * Created by PhpStorm.
 * User: tassio
 * Date: 2019-03-16
 * Time: 14:57
 */

class livro
{

    private $idLivro;
    private $titulo;
    private $isbn;
    private $edicao;
    private $ano;
    private $upload;
    private $editora;
    private $categoria;

    public function __construct($idLivro, $titulo, $isbn, $edicao, $ano, $upload, $editora, $categoria)
    {
        $this->idLivro = $idLivro;
        $this->titulo = $titulo;
        $this->isbn = $isbn;
        $this->edicao = $edicao;
        $this->ano = $ano;
        $this->upload = $upload;
        $this->editora = $editora;
        $this->categoria = $categoria;
    }

    public function getIdLivro()
    {
        return $this->idLivro;
    }

    public function setIdLivro($idLivro): void
    {
        $this->idLivro = $idLivro;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getIsbn()
    {
        return $this->isbn;
    }

    public function setIsbn($isbn): void
    {
        $this->isbn = $isbn;
    }

    public function getEdicao()
    {
        return $this->edicao;
    }

    public function setEdicao($edicao): void
    {
        $this->edicao = $edicao;
    }

    public function getAno()
    {
        return $this->ano;
    }

    public function setAno($ano): void
    {
        $this->ano = $ano;
    }

    public function getUpload()
    {
        return $this->upload;
    }

    public function setUpload($upload): void
    {
        $this->upload = $upload;
    }

    public function getEditora()
    {
        return $this->editora;
    }

    public function setEditora($editora): void
    {
        $this->editora = $editora;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function setCategoria($categoria): void
    {
        $this->categoria = $categoria;
    }

}