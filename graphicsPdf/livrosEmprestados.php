<?php
    require_once "./classes/phplot/phplot.php";
    require_once "./db/Conexao.php";
    require_once "./dao/daoEmprestimo.php";

    $daoEmprestimo = new daoEmprestimo();
    $data = array();
    $dadosEmprestimo = $daoEmprestimo->getGraficoData();

    foreach($dadosEmprestimo as $dadosLoop)
    {
        array_push($data, [$dadosLoop->Mes, $dadosLoop->quantidadeMes]);
    }
  
# Cria um novo objeto do tipo PHPlot com 500px de largura x 350px de altura                 
$plotLivrosEmrpestados = new PHPlot(450 , 325);     

// Organiza Gráfico -----------------------------
$plotLivrosEmrpestados->SetTitle('Emprestimo de livros / ultimos 3 meses');
# Precisão de uma casa decimal
$plotLivrosEmrpestados->SetPrecisionY(1);
# tipo de Gráfico em barras (poderia ser linepoints por exemplo)
$plotLivrosEmrpestados->SetPlotType("bars");
# Tipo de dados que preencherão o Gráfico text(label dos anos) e data (valores de porcentagem)
$plotLivrosEmrpestados->SetDataType("text-data");
# Adiciona ao gráfico os valores do array
$plotLivrosEmrpestados->SetDataValues($data);
// -----------------------------------------------

// Organiza eixo X ------------------------------
# Seta os traços (grid) do eixo X para invisível
$plotLivrosEmrpestados->SetXTickPos('none');
# Texto abaixo do eixo X
$plotLivrosEmrpestados->SetXLabel("Dados da quantidade de livros que foram emprestados por mes extraidos\n Considerado os dados dos ultimos 3 meses ate o dia de hoje.");
# Tamanho da fonte que varia de 1-5
$plotLivrosEmrpestados->SetXLabelFontSize(2);
$plotLivrosEmrpestados->SetAxisFontSize(2);
// -----------------------------------------------

// Organiza eixo Y -------------------------------
# Coloca nos pontos os valores de Y
$plotLivrosEmrpestados->SetYDataLabelPos('plotin');
// -----------------------------------------------
$plotLivrosEmrpestados->SetPrintImage(false);
$plotLivrosEmrpestados->SetCallback('data_color', 'pickcolor2', $data);
$plotLivrosEmrpestados->SetDataColors(array('green', 'yellow', 'black', 'red', 'blue', 'gray', 'navy', 'DarkGreen', 'peru'));
// Desenha o Gráfico -----------------------------
$plotLivrosEmrpestados->DrawGraph();
// -----------------------------------------------
?>