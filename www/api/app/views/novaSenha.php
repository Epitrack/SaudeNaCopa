<html>
<title>SlimMidias</title>
<head>
    <base href="{{path}}"/>
    <!-- <base href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/" />-->
    <!--<link rel="stylesheet" href="css/bootstrap.min.css">-->

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://getbootstrap.com/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="http://getbootstrap.com/examples/theme/theme.css">

    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

</head>

<body role="document" style="">

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">

            <a class="navbar-brand" href="#">Saúde na Copa</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">


            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>

<div class="container theme-showcase" role="main">

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
        <h1>Mudança de senha</h1>

        <p>Olá, {{nome}}</p>



        <div class="auth-form login-form">
            <form accept-charset="UTF-8" action="{{path}}/novaSenha/{{cod}}" method="post">


                <div class="auth-form-body">
                    <label>Nova Senha</label>
                    <input autofocus="autofocus" class="input-block" id="senha" name="senha" tabindex="1"
                           type="password" value="">
<br>
                    <label>Confirme a Senha</label>
                    <input class="input-block" id="password_confirmation" name="password_confirmation" tabindex="2"
                           type="password" value="">
                    <br>

                    <input class="btn btn-primary" name="commit" tabindex="3" type="submit" value="Mudar Senha">
                </div>
            </form>
        </div>

    </div>


</div>


</div> <!-- /container -->


</body>

</html>

