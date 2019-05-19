<div class='content' xmlns="http://www.w3.org/1999/html">
    <div class='container-fluid'>
        <div class='row'>
            <div class='col-md-12'>
                <div class='card'>
                    <div class='header'>
                        <h1 class='title' style="text-align: center">Biblioteca</h1>
                        <p class='category'></p>
                    </div>
                    <div class='content table-responsive'>
                          <h4 class="title">Seja bem-vindo </h4> <br/>
                          <div class="row">
                            <div class="col-md-12">
                                <button class='btn btn-info' style="width: 100%;" onclick="gerarPDF()">Exportar Dados para PDF</button> <br />
                            </div>
                          <?php
                           $graficos = $config['Graficos'];
                           foreach($graficos as $grafico)
                           {
                               echo "<div class='col-md-6'> <img src='./graphics/$grafico' /> </div>";
                           }
                          ?>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function gerarPDF()
    {
        var url = window.location.origin + "/gerarPDFGraficos.php";
        var win = window.open(url, '_blank');
        win.focus();
    }
</script>