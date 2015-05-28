<?php
namespace stagecoach\responder\admin;

/** @var $this \stagecoach\responder\admin\AdminLayout */
/** @var $menu string */
/** @var $content string */
/** @var $message string */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/stagecoach.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body role="document">
    <?= $this->renderPartial(new AdminMenuPartial($this)) ?>
    <div class="container content">
        <div class="starter-template">
            <?php if ($message): ?>
                <div style="border: 1px solid red;"><?= $this->escape($message) ?></div>
            <?php endif; ?>
            <?= $content ?>
        </div>
    </div>
</body>
</html>
