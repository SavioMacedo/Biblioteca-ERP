<?php
    require_once "./classes/phplot/phplot.php";
    require_once "./db/Conexao.php";
    require_once "./dao/daoEmprestimo.php";

    $daoEmprestimo = new daoEmprestimo();
    $data = array();
    $dadosEmprestimo = $daoEmprestimo->getGraficoEmprestadoReservado();

    foreach($dadosEmprestimo as $dadosLoop)
    {
        array_push($data, [$dadosLoop->Mes, $dadosLoop->quantidadeMes]);
    }
    
    function pickcolor2($img, $data_array, $row, $col)
    {
        return mt_rand(0, 8);
    }
  
# Cria um novo objeto do tipo PHPlot com 500px de largura x 350px de altura                 
$plotLivroReservadoEmprestado = new PHPlot(450 , 325);     

// Organiza Gráfico -----------------------------
$plotLivroReservadoEmprestado->SetTitle('Emprestimo de Livros x Reserva / ultimo 1 mes');
# Precisão de uma casa decimal
$plotLivroReservadoEmprestado->SetPrecisionY(1);
# tipo de Gráfico em barras (poderia ser linepoints por exemplo)
$plotLivroReservadoEmprestado->SetPlotType("bars");
# Tipo de dados que preencherão o Gráfico text(label dos anos) e data (valores de porcentagem)
$plotLivroReservadoEmprestado->SetDataType("text-data");
# Adiciona ao gráfico os valores do array
$plotLivroReservadoEmprestado->SetDataValues($data);
// -----------------------------------------------

// Organiza eixo X ------------------------------
# Seta os traços (grid) do eixo X para invisível
$plotLivroReservadoEmprestado->SetXTickPos('none');
# Texto abaixo do eixo X
$plotLivroReservadoEmprestado->SetXLabel("Dados da quantidade de livros que foram emprestados e reservados \n Considerado os dados do ultimo 1 meses ate o dia de hoje.");
# Tamanho da fonte que varia de 1-5
$plotLivroReservadoEmprestado->SetXLabelFontSize(2);
$plotLivroReservadoEmprestado->SetAxisFontSize(2);
// -----------------------------------------------

// Organiza eixo Y -------------------------------
# Coloca nos pontos os valores de Y
$plotLivroReservadoEmprestado->SetYDataLabelPos('plotin');
// -----------------------------------------------
$plotLivroReservadoEmprestado->SetPrintImage(false);
$plotLivroReservadoEmprestado->SetCallback('data_color', 'pickcolor2', $data);
$plotLivroReservadoEmprestado->SetDataColors(array('green', 'yellow', 'black', 'red', 'blue', 'gray', 'navy', 'DarkGreen', 'peru'));
// Desenha o Gráfico -----------------------------
$plotLivroReservadoEmprestado->DrawGraph();
// -----------------------------------------------
?>