<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo $title ?></title>
  <meta name="author" content="SavioMacedo" />
  <meta http-equiv="content-language" content="pt" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <meta name='viewport' content='width=device-width' />
  <!-- Bootstrap core CSS     -->
  <link href='../assets/css/bootstrap.min.css' rel='stylesheet' />

  <!-- Animation library for notifications   -->
  <link href='../assets/css/animate.min.css' rel='stylesheet'/>

  <!--  Paper Dashboard core CSS    -->
  <link href='../assets/css/paper-dashboard.css' rel='stylesheet'/>

  <!--  Fonts and icons     -->
  <link href='http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' rel='stylesheet'>
  <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
  <link href='../assets/css/themify-icons.css' rel='stylesheet'>
</head>
<body>
<div class="wrapper">
        <div class="sidebar" data-background-color="white" data-active-color="danger">
        <!--
            Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
            Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
        -->
        <div class="sidebar-wrapper">
            <div class="logo">
                <a href='index.php'><img src="assets/img/logo.jpg" height="150" width="200"></a>
                <h4>Biblioteca</h4>
                <small></small>
                <a class='btn btn-success'  href="logout.php">Logout</a>
            </div>

            <?php
            foreach($config['Menu'] as $menu)
            {
                $state = ($menu[1] == $page) ? "class='active'" : "";

                echo "
                    <ul class=\"nav\">
                        <li $state>
                            <a href='?page=$menu[1]'>
                                <i class=\"$menu[2]\"></i>
                                <p>$menu[0]</p>
                            </a>
                        </li>
                    </ul>
                ";
            }
            
            ?>
        </div>
    </div>
    <?php 
        date_default_timezone_set('America/Sao_Paulo');
        $data = date("F j, Y");
    ?>
        <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="#">Boas vindas!</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                    </ul>

                </div>
            </div>
        </nav>
    <?php include $main_content; ?>
    <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    <ul>

                        <li>
                            <a href="<?php echo $config['Owner']['URL']; ?>" target='_blank'>
                                <?php echo $config['Owner']['Nome']; ?>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="copyright pull-right">
                    &copy; <script>document.write(new Date().getFullYear())</script>, template made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com" target='_blank'>Creative Tim</a>
                </div>
            </div>
        </footer>

    </div>
</div>
</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio.js"></script>

</html>