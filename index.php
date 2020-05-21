<?php
require_once 'php/bootstrap.php';

$parts = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if (count($parts) === 2) {
    $params = array_combine(['domain', 'page'], $parts);
} else if (count($parts) === 3) {
    $params = array_combine(['domain', 'page', 'slug'], $parts);
} else {
    http_response_code(404);
    echo <<<HTML
        <h3>Page Not Found</h3>
HTML;

    return;

}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="/materialize/css/materialize.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <title>Poll</title>
</head>
<body>

<script src="/js/jquery.min.js"></script>

<nav>
    <div class="nav-wrapper container">
        <a href="/" class="brand-logo">Poll</a>

        <ul class="right hide-on-med-and-down">
            <li>
                <a href="/">Home</a>
            </li>
            <li>
                <a href="/create">Create</a>
            </li>
            <li>
                <a href="/polls">Polls</a>
            </li>
        </ul>
    </div>
</nav>

<main>
    <div class="container">
        <?php
        switch ($params['page']) {
            case 'create':
                include 'php/pages/create.php';
                break;
            case 'polls':
                include 'php/pages/polls.php';
                break;
            case 'poll':
                include 'php/pages/poll.php';
                break;
            default:
                include 'php/pages/home.php';
                break;
        }
        ?>
    </div>
</main>

<footer id="footer" class="page-footer">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">Poll</h5>
            </div>
            <div class="col l3 s12">
                <h5 class="white-text">Links</h5>
                <ul>
                    <li><a class="white-text" href="#">Link</a></li>
                    <li><a class="white-text" href="#">Link</a></li>
                </ul>
            </div>
            <div class="col l3 s12">
                <h5 class="white-text">Links</h5>
                <ul>
                    <li><a class="white-text" href="#">Link</a></li>
                    <li><a class="white-text" href="#">Link</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>


<script src="/materialize/js/materialize.min.js"></script>
<script src="/js/main.js"></script>

</body>
</html>
