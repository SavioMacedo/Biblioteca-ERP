<?php
    include './classes/fpdf/PDF_MemImage.php';
    include './db/Conexao.php';
    include './dao/daoReserva.php';
    include './dao/daoEmprestimo.php';

    include './graphicsPdf/livroReservadoEmprestado.php';
    include './graphicsPdf/livrosEmprestados.php';
    include './graphicsPdf/livrosEmprestadosCategoria.php';
    include './graphicsPdf/livrosReservados.php';
    include './graphicsPdf/livrosReservadosCategoria.php';

    $daoReserva = new daoReserva();
    $daoEmprestimo = new daoEmprestimo();

    $dados = $daoEmprestimo->getGraficoEmprestadoReservado();

    $pdf = new PDF_MemImage();

    //LivroReservadoEmprestado
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190,10,utf8_decode('Relatório de Livros Reservados e Emprestados'), 1, 0, "C");
    $pdf->Ln(15);

    //Cabeçalho
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(95, 7, utf8_decode("Mês"),1,0,"C");
    $pdf->Cell(95, 7, "Quantidade",1,0,"C");
    $pdf->Ln();

    //Dados
    foreach($dados as $dado)
    {
        $pdf->Cell(95, 7, utf8_decode($dado->Mes),1,0,"C");
        $pdf->Cell(95, 7, $dado->quantidadeMes,1,0,"C");
        $pdf->Ln();
    }
    $pdf->GDImage($plotLivroReservadoEmprestado->img, 30, 50, 140);

    //LivroEmprestado
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190,10,utf8_decode('Relatório de Livros Emprestados nos ultimos 3 Meses'), 1, 0, "C");
    $pdf->Ln(15);

    $dados = $daoEmprestimo->getGraficoData();

    //Cabeçalho
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(95, 7, utf8_decode("Mês"),1,0,"C");
    $pdf->Cell(95, 7, "Quantidade",1,0,"C");
    $pdf->Ln();

    //Dados
    foreach($dados as $dado)
    {
        $pdf->Cell(95, 7, utf8_decode($dado->Mes),1,0,"C");
        $pdf->Cell(95, 7, $dado->quantidadeMes,1,0,"C");
        $pdf->Ln();
    }
    $pdf->GDImage($plotLivrosEmrpestados->img, 30, 50, 140);

    //LivroEmprestadoCategoria
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190,10,utf8_decode('Relatório de Livros Emprestados x Categoria'), 1, 0, "C");
    $pdf->Ln(15);

    $dados = $daoEmprestimo->getGraficoDataCategoria();

    //Cabeçalho
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(95, 7, utf8_decode("Categoria"),1,0,"C");
    $pdf->Cell(95, 7, "Quantidade",1,0,"C");
    $pdf->Ln();

    //Dados
    foreach($dados as $dado)
    {
        $pdf->Cell(95, 7, utf8_decode($dado->nomeCategoria),1,0,"C");
        $pdf->Cell(95, 7, $dado->quantidadeMes,1,0,"C");
        $pdf->Ln();
    }
    $pdf->GDImage($plotLivrosEmprestadosCategoria->img, 30, 50, 140);

    //LivroReservado
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190,10,utf8_decode('Relatório de Livros Reservados nos ultimso 3 Meses'), 1, 0, "C");
    $pdf->Ln(15);

    $dados = $daoReserva->getGraficoData();

    //Cabeçalho
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(95, 7, utf8_decode("Mês"),1,0,"C");
    $pdf->Cell(95, 7, "Quantidade",1,0,"C");
    $pdf->Ln();

    //Dados
    foreach($dados as $dado)
    {
        $pdf->Cell(95, 7, utf8_decode($dado->Mes),1,0,"C");
        $pdf->Cell(95, 7, $dado->quantidadeMes,1,0,"C");
        $pdf->Ln();
    }
    $pdf->GDImage($plotLivrosReservados->img, 30, 50, 140);

    //LivroReservadosCategoria
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190,10,utf8_decode('Relatório de Livros Reservados por Categoria'), 1, 0, "C");
    $pdf->Ln(15);

    $dados = $daoReserva->getGraficoDataCategoria();

    //Cabeçalho
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(95, 7, utf8_decode("Categoria"),1,0,"C");
    $pdf->Cell(95, 7, "Quantidade",1,0,"C");
    $pdf->Ln();

    //Dados
    foreach($dados as $dado)
    {
        $pdf->Cell(95, 7, utf8_decode($dado->nomeCategoria),1,0,"C");
        $pdf->Cell(95, 7, $dado->quantidadeMes,1,0,"C");
        $pdf->Ln();
    }
    $pdf->GDImage($plotLivrosReservadosCategorias->img, 30, 50, 140);

    $pdf->Output();
?>