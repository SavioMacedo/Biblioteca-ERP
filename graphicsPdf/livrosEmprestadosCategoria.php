<?php
    require_once "./classes/phplot/phplot.php";
    require_once "./db/Conexao.php";
    require_once "./dao/daoEmprestimo.php";

    $daoEmprestimo = new daoEmprestimo();
    $data = array();
    $dadosEmprestimo = $daoEmprestimo->getGraficoDataCategoria();

    foreach($dadosEmprestimo as $dadosLoop)
    {
        array_push($data, [$dadosLoop->nomeCategoria, $dadosLoop->quantidadeMes]);
    }
  
# Cria um novo objeto do tipo PHPlot com 500px de largura x 350px de altura                 
$plotLivrosEmprestadosCategoria = new PHPlot(450 , 325);     

// Organiza Gráfico -----------------------------
$plotLivrosEmprestadosCategoria->SetTitle('Emprestimo de Livros x Categoria / ultimos 3 meses');
# Precisão de uma casa decimal
$plotLivrosEmprestadosCategoria->SetPrecisionY(1);
# tipo de Gráfico em barras (poderia ser linepoints por exemplo)
$plotLivrosEmprestadosCategoria->SetPlotType("bars");
# Tipo de dados que preencherão o Gráfico text(label dos anos) e data (valores de porcentagem)
$plotLivrosEmprestadosCategoria->SetDataType("text-data");
# Adiciona ao gráfico os valores do array
$plotLivrosEmprestadosCategoria->SetDataValues($data);
// -----------------------------------------------

// Organiza eixo X ------------------------------
# Seta os traços (grid) do eixo X para invisível
$plotLivrosEmprestadosCategoria->SetXTickPos('none');
# Texto abaixo do eixo X
$plotLivrosEmprestadosCategoria->SetXLabel("Dados da quantidade de livros que foram emprestados por categoria \n Considerado os dados dos ultimos 3 meses ate o dia de hoje.");
# Tamanho da fonte que varia de 1-5
$plotLivrosEmprestadosCategoria->SetXLabelFontSize(2);
$plotLivrosEmprestadosCategoria->SetAxisFontSize(2);
// -----------------------------------------------

// Organiza eixo Y -------------------------------
# Coloca nos pontos os valores de Y
$plotLivrosEmprestadosCategoria->SetYDataLabelPos('plotin');
// -----------------------------------------------
$plotLivrosEmprestadosCategoria->SetPrintImage(false);
$plotLivrosEmprestadosCategoria->SetCallback('data_color', 'pickcolor2', $data);
$plotLivrosEmprestadosCategoria->SetDataColors(array('green', 'yellow', 'black', 'red', 'blue', 'gray', 'navy', 'DarkGreen', 'peru'));
// Desenha o Gráfico -----------------------------
$plotLivrosEmprestadosCategoria->DrawGraph();
// -----------------------------------------------
?>