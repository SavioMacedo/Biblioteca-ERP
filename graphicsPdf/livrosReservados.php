<?php
    require_once "./classes/phplot/phplot.php";
    require_once "./db/Conexao.php";
    require_once "./dao/daoReserva.php";

    $daoReserva = new daoReserva();
    $data = array();
    $dadosReserva = $daoReserva->getGraficoData();

    foreach($dadosReserva as $dadosLoop)
    {
        array_push($data, [$dadosLoop->Mes, $dadosLoop->quantidadeMes]);
    }
  
# Cria um novo objeto do tipo PHPlot com 500px de largura x 350px de altura                 
$plotLivrosReservados = new PHPlot(450 , 325);     

// Organiza Gráfico -----------------------------
$plotLivrosReservados->SetTitle('Reserva de livros / ultimos 3 meses');
# Precisão de uma casa decimal
$plotLivrosReservados->SetPrecisionY(1);
# tipo de Gráfico em barras (poderia ser linepoints por exemplo)
$plotLivrosReservados->SetPlotType("bars");
# Tipo de dados que preencherão o Gráfico text(label dos anos) e data (valores de porcentagem)
$plotLivrosReservados->SetDataType("text-data");
# Adiciona ao gráfico os valores do array
$plotLivrosReservados->SetDataValues($data);
// -----------------------------------------------

// Organiza eixo X ------------------------------
# Seta os traços (grid) do eixo X para invisível
$plotLivrosReservados->SetXTickPos('none');
# Texto abaixo do eixo X
$plotLivrosReservados->SetXLabel("Dados da quantidade de livros que foram reservados por mes extraidos\n Considerado os dados dos ultimos 3 meses ate o dia de hoje.");
# Tamanho da fonte que varia de 1-5
$plotLivrosReservados->SetXLabelFontSize(2);
$plotLivrosReservados->SetAxisFontSize(2);
// -----------------------------------------------

// Organiza eixo Y -------------------------------
# Coloca nos pontos os valores de Y
$plotLivrosReservados->SetYDataLabelPos('plotin');
// -----------------------------------------------
$plotLivrosReservados->SetPrintImage(false);
$plotLivrosReservados->SetCallback('data_color', 'pickcolor2', $data);
$plotLivrosReservados->SetDataColors(array('green', 'yellow', 'black', 'red', 'blue', 'gray', 'navy', 'DarkGreen', 'peru'));
// Desenha o Gráfico -----------------------------
$plotLivrosReservados->DrawGraph();
// -----------------------------------------------
?>