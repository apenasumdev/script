<?php
/**
 * @var string $error
 * @var string $msg
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=config('app.name')?></title>
    <link rel="stylesheet" href="<?=asset('css/404.fallback.css')?>">
</head>
<body>
    <main>
        <h1><?=htmlentities($error)?></h1>
        
        <?php if(isset($msg)): ?>
        <h4><?=htmlentities($msg)?></h4>
        <?php endif; ?>
    </main>
</body>
</html>