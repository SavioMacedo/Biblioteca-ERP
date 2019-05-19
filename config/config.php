<?php
//General Configuration
    $config['General']['SiteName'] = "Biblioteca Lpp";


//Configuration owner.
    $config['Owner']['URL'] = "";
    $config['Owner']['Nome'] = "";


    //Menu (Nome Menu, link arquivo, icone link)
    $config['Menu'] = [
        ["Tela Inicial", "index", "ti-user"],
        ["Emprestimos", "emprestimo", "ti-user"],
        ["Reservas", "reserva", "ti-user"],
        ["Autores", "autores", "ti-user"],
        ["Categoria","categoria", "ti-user"],
        ["Editora", "editora", "ti-user"],
        ["Livro", "livro", "ti-user"],
        ["Exemplar", "exemplar", "ti-user"],
        ["Usuarios", "usuarios", "ti-user"],
        ["Relatórios Gerenciais", "relatorioLivros", "ti-user"]
    ];

    //Graficos
    $config['Graficos'] = [
        "livroReservadoEmprestado.php",
        "livrosReservados.php",
        "livrosReservadosCategoria.php",
        "livrosEmprestados.php",
        "livrosEmprestadosCategoria.php"
    ];

    $config['DataNull'] = "0000-00-00 00:00:00";
    $config["RegistrosPorPagina"] = 10;
?>