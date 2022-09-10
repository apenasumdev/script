<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Installer - TikTok Video Downloader</title>
    <base href="<?=base_url().'/install'?>">
    <link rel="stylesheet" href="../assets/css/skins/light.css">
    <link rel="stylesheet" href="<?='/install'.asset('css/font.css')?>">
    <link rel="stylesheet" href="<?='install'.asset('css/installer.css')?>">

    <script src="<?='/install'.asset('js/core.js')?>" defer></script>
    <script src="<?='/install'.asset('js/installer.js')?>" defer></script>
</head>
<body>
    <main>
        <div class="logo">
        <img src="../assets/images/logos/logo-wide.png" alt="TikTokInstaller">
        </div>

        <?php include_once path(VIEW_PATH . '/steps/welcome.php'); ?>
        <?php include_once path(VIEW_PATH . '/steps/agreement.php'); ?>
        <?php include_once path(VIEW_PATH . '/steps/requirements.php'); ?>
        <?php include_once path(VIEW_PATH . '/steps/install.php'); ?>
	    <?php include_once path(VIEW_PATH . '/steps/success.php'); ?>
    </main>
</body>
</html>