<?php
    require_once "../classes/phplot/phplot.php";
    require_once "../db/Conexao.php";
    require_once "../dao/daoReserva.php";

    $daoReserva = new daoReserva();
    $data = array();
    $dadosReserva = $daoReserva->getGraficoDataCategoria();

    foreach($dadosReserva as $dadosLoop)
    {
        array_push($data, [$dadosLoop->nomeCategoria, $dadosLoop->quantidadeMes]);
    }
  
# Cria um novo objeto do tipo PHPlot com 500px de largura x 350px de altura                 
$plot = new PHPlot(450 , 325);     

// Organiza Gráfico -----------------------------
$plot->SetTitle('Reserva de Livros x Categoria / ultimos 3 meses');
# Precisão de uma casa decimal
$plot->SetPrecisionY(1);
# tipo de Gráfico em barras (poderia ser linepoints por exemplo)
$plot->SetPlotType("bars");
# Tipo de dados que preencherão o Gráfico text(label dos anos) e data (valores de porcentagem)
$plot->SetDataType("text-data");
# Adiciona ao gráfico os valores do array
$plot->SetDataValues($data);
// -----------------------------------------------

// Organiza eixo X ------------------------------
# Seta os traços (grid) do eixo X para invisível
$plot->SetXTickPos('none');
# Texto abaixo do eixo X
$plot->SetXLabel("Dados da quantidade de livros que foram reservados por categoria \n Considerado os dados dos ultimos 3 meses ate o dia de hoje.");
# Tamanho da fonte que varia de 1-5
$plot->SetXLabelFontSize(2);
$plot->SetAxisFontSize(2);
// -----------------------------------------------

// Organiza eixo Y -------------------------------
# Coloca nos pontos os valores de Y
$plot->SetYDataLabelPos('plotin');
// -----------------------------------------------

// Desenha o Gráfico -----------------------------
$plot->DrawGraph();
// -----------------------------------------------
?>