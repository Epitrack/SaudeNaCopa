<html>
<title>SlimMidias</title>
<head>
    <base href="{{path}}"/>
    <!-- <base href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/" />-->
    <!--<link rel="stylesheet" href="css/bootstrap.min.css">-->

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

</head>

<body>

{% if flash['error'] %}
<div class="alert alert-warning alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Warning!</strong> {{flash['error']}}.
</div>
{%endif%}

<div class="jumbotron">
    <div class="container">
        <h1>{{title}}</h1>
        <p>{{texto}}</p>

    </div>
</div>



</body>

</html>