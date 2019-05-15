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
        ["Autores", "autores", "ti-user"],
        ["Categoria","categoria", "ti-user"],
        ["Editora", "editora", "ti-user"],
        ["Livro", "livro", "ti-user"],
        ["Exemplar", "exemplar", "ti-user"],
        ["Usuarios", "usuarios", "ti-user"]
    ];

    //Graficos
    $config['Graficos'] = [
        "livrosEmprestados.php"
    ]
?>