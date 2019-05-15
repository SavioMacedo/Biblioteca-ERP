<?php

    class Functions
    {
        public static function isValidFolderName($string)
        {
            return (strspn($string, "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789_-") == strlen($string));
        }

        public static function DropDownFor($data, $fieldName, $fieldKey, $fieldValue, $friendlyName, $selected = "")
        {
            $options = "<option>Selecione um $friendlyName</option>";

            foreach($data as $option)
            {
                $option = (array)$option;
                $selectedValue = ($selected == $option[$fieldKey]) ? "selected" : "";
                $options .= "<option value='$option[$fieldKey]' $selectedValue>$option[$fieldValue]</option>";
            }

            echo "
            <div class='form-group'>
                <label for='$fieldName'>$friendlyName</label>
                <select class='form-control' name='$fieldName' id='$fieldName' required>
                    $options
                </select>
            </div>
            ";
        }

        public static function constructGrid($data, $arrayCampos, $page)
        {
            $dataRam = System\Linq\Enumerable::createEnumerable($data);

            //endereço atual da página
            $endereco = $_SERVER ['PHP_SELF'];
            /* Constantes de configuração */
            define('QTDE_REGISTROS', 2);
            define('RANGE_PAGINAS', 3);
            /* Recebe o número da página via parâmetro na URL */
            $pagina_atual = (isset($_GET['pagination']) && is_numeric($_GET['pagination'])) ? $_GET['pagination'] : 1;
            /* Calcula a linha inicial da consulta */
            $linha_inicial = ($pagina_atual - 1) * QTDE_REGISTROS;

            /* Instrução de consulta para paginação com MySQL */
            $dataTemp = $dataRam->skip($linha_inicial)->take(QTDE_REGISTROS)->toArray();

            /* Idêntifica a primeira página */
            $primeira_pagina = 1;
            /* Cálcula qual será a última página */
            $ultima_pagina = ceil(count($data) / QTDE_REGISTROS);
            /* Cálcula qual será a página anterior em relação a página atual em exibição */
            $pagina_anterior = ($pagina_atual > 1) ? $pagina_atual - 1 : 0;
            /* Cálcula qual será a pŕoxima página em relação a página atual em exibição */
            $proxima_pagina = ($pagina_atual < $ultima_pagina) ? $pagina_atual + 1 : 0;
            /* Cálcula qual será a página inicial do nosso range */
            $range_inicial = (($pagina_atual - RANGE_PAGINAS) >= 1) ? $pagina_atual - RANGE_PAGINAS : 1;
            /* Cálcula qual será a página final do nosso range */
            $range_final = (($pagina_atual + RANGE_PAGINAS) <= $ultima_pagina) ? $pagina_atual + RANGE_PAGINAS : $ultima_pagina;
            /* Verifica se vai exibir o botão "Primeiro" e "Pŕoximo" */
            $exibir_botao_inicio = ($range_inicial < $pagina_atual) ? 'mostrar' : 'esconder';
            /* Verifica se vai exibir o botão "Anterior" e "Último" */
            $exibir_botao_final = ($range_final > $pagina_atual) ? 'mostrar' : 'esconder';

            if (!empty($dataTemp)):
                $column = "";
                foreach ($arrayCampos as $columName)
                {
                    if($columName != null)
                        $column .= "<th style='text-align: center; font-weight: bolder;'>".$columName[0]."</th>";
                }
                echo "
                        <table class='table table-striped table-bordered'>
                        <thead>
                        <tr style='text-transform: uppercase;' class='active'>
                            $column
                            <th style='text-align: center; font-weight: bolder;' colspan='2'>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                    ";
                    foreach ($dataTemp as $source):
                        $colunas = "";
                        $id = $source->{$arrayCampos[0][1]};
                        foreach($arrayCampos as $campo)
                        {
                            if($campo[0] != null)
                                $colunas .= "<td style='text-align: center'>".$source->{$campo[1]}."</td>";
                        }
                        echo "<tr>
                            $colunas
                            <td style='text-align: center'><a href='?page=$page&act=upd&id=$id' title='Alterar'><i class='ti-reload'></i></a></td>
                            <td style='text-align: center'><a href='?page=$page&act=del&id=$id' title='Remover'><i class='ti-close'></i></a></td>
                        </tr>";
                    endforeach;
                    echo "
                            </tbody>
                                </table>
                                <div class='box-paginacao' style='text-align: center'>
                                <a class='box-navegacao  $exibir_botao_inicio' href='?page=$page&pagination=$primeira_pagina' title='Primeira Página'> Primeira  |</a>
                                <a class='box-navegacao  $exibir_botao_inicio' href='?page=$page&pagination=$pagina_anterior' title='Página Anterior'> Anterior  |</a>
                            ";
                                        /* Loop para montar a páginação central com os números */
                                        for ($i = $range_inicial; $i <= $range_final; $i++):
                                            $destaque = ($i == $pagina_atual) ? 'destaque' : '';
                                            echo "<a class='box-numero $destaque' href='?page=$page&pagination=$i'> ( $i ) </a>";
                                        endfor;
                                        echo "<a class='box-navegacao $exibir_botao_final' href='?page=$page&pagination=$proxima_pagina' title='Próxima Página'>| Próxima  </a>
                                            <a class='box-navegacao $exibir_botao_final' href='?page=$page&pagination=$ultima_pagina'  title='Última Página'>| Última  </a>
                                </div>";
                                    else:
                                        echo "<p class='bg-danger'>Nenhum registro foi encontrado!</p>
                                ";
                            endif;
        }
    }
?>