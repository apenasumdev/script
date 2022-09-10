<?php
/**
 * @var array $app
 * @var string $error
 * @var array $video
 * @var array $pages
 * @var array $menus
 * @var array $codes
 * @var array $page
 * @var array $socials
 */



    $THEME = config('app.theme');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token_" content="<?=config('app.token')?>">

	<?php if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'): ?>
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<?php endif; ?>

    <title><?=$video['title'] ?? $page[0]['title'] ?? $app['name']?></title>
    <base href="<?=base_url()?>">
    <link rel="shortcut icon" href="/assets/images/favicon.jpg" type="image/jpg">

    <meta name="description" content="<?=$video['caption'] ?? $page[0]['desc'] ?? $app['desc']?>">
    <meta name="keywords" content="<?=$app['keywords']?>">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@300;400;500;700">
    <!--GLOBAL STYLE: BEGIN-->

    
    <?php if($THEME === 'light'): ?>
        <link rel="stylesheet" href="<?=asset('css/skins/light.css?v='.$app['version'])?>">
    <?php elseif($THEME === 'dark'): ?>
        <link rel="stylesheet" href="<?=asset('css/skins/dark.css?v='.$app['version'])?>">
    <?php else: ?>
        <link rel="stylesheet" href="<?=asset('css/skins/dark.css?v='.$app['version'])?>" media="(prefers-color-scheme: dark)">
        <link rel="stylesheet" href="<?=asset('css/skins/light.css?v='.$app['version'])?>" media="(prefers-color-scheme: light)">
    <?php endif; ?>

    <link id="preloader_css" rel="stylesheet" href="/assets/css/preloader.min.css">
    <link rel="stylesheet" href="<?=asset('css/fontawesome.min.css?v='.$app['version'])?>">
    <link rel="stylesheet" href="<?=asset('css/app.css?v='.$app['version'])?>">
    <!--GLOBAL STYLE: END-->

    <!--EXTRA CODE: BEGIN-->

    <?php /* Cannot escape this code. ANALYTICS CODE SHOULD BE PASTED HERE */ ?>

    <?php if(isset($codes['code']))
            echo $codes['code'];
    ?>
    <!--EXTRA CODE: END-->



    <!-- SOCIAL META TAGS: BEGIN -->
    <meta property="og:title" content="<?= $video['title'] ?? $page[0]['title'] ?? $app['name'] ?>">
    <meta property="og:description" content="<?= $video['caption'] ?? $page[0]['desc'] ?? $app['desc'] ?>">
    <meta property="og:image" content="<?=  $video['cover'] ?? $app['cover'] ?>">
    <meta property="og:url" content="<?= $video['url'] ?? $page[0]['url'] ?? base_url() ?>">
    <meta name="twitter:card" content="<?= $video['cover'] ?? $app['cover'] ?>">
    <!-- SOCIAL META TAGS: END -->
</head>
<body>
    <!--App: BEGIN-->
    <div id="app"></div>
    <!--App: END-->
    <!--DUMP ALL DATA TO SPA:START-->
    <script id="__APP__">
        <?=output_json($app)?>
    </script>
    <script id="__PROPS__">
        <?=output_json([
                'video'=> $video ?? null,
                'error'=> $error ?? null,
                'menus'=> $menus ?? null,
                'codes'=> $codes ?? null,
                'socials'=> $socials ?? null,
        ])?>
    </script>
    <script id="__PAGES__">
        <?=output_json($pages)?>
    </script>
    <!--DUMP ALL DATA TO SPA:END-->

    <!-- PRELOADER : BEGIN-->
    <div class="preloader">
        <svg class="tea" width="37" height="48" viewbox="0 0 37 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M27.0819 17H3.02508C1.91076 17 1.01376 17.9059 1.0485 19.0197C1.15761 22.5177 1.49703 29.7374 2.5 34C4.07125 40.6778 7.18553 44.8868 8.44856 46.3845C8.79051 46.79 9.29799 47 9.82843 47H20.0218C20.639 47 21.2193 46.7159 21.5659 46.2052C22.6765 44.5687 25.2312 40.4282 27.5 34C28.9757 29.8188 29.084 22.4043 29.0441 18.9156C29.0319 17.8436 28.1539 17 27.0819 17Z" stroke="var(--secondary)" stroke-width="2"></path>
            <path d="M29 23.5C29 23.5 34.5 20.5 35.5 25.4999C36.0986 28.4926 34.2033 31.5383 32 32.8713C29.4555 34.4108 28 34 28 34" stroke="var(--secondary)" stroke-width="2"></path>
            <path id="teabag" fill="var(--secondary)" fill-rule="evenodd" clip-rule="evenodd" d="M16 25V17H14V25H12C10.3431 25 9 26.3431 9 28V34C9 35.6569 10.3431 37 12 37H18C19.6569 37 21 35.6569 21 34V28C21 26.3431 19.6569 25 18 25H16ZM11 28C11 27.4477 11.4477 27 12 27H18C18.5523 27 19 27.4477 19 28V34C19 34.5523 18.5523 35 18 35H12C11.4477 35 11 34.5523 11 34V28Z"></path>
            <path id="steamL" d="M17 1C17 1 17 4.5 14 6.5C11 8.5 11 12 11 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke="var(--secondary)"></path>
            <path id="steamR" d="M21 6C21 6 21 8.22727 19 9.5C17 10.7727 17 13 17 13" stroke="var(--secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </div>
    <script id="preloader_script" async>
        /**
         * NOTICE
         * THIS SCRIPT MAY NOT WORK PROPERLY IF LOADED
         * WITH ANY SCRIPT FILE
         * WHICH CAUSES INFINITE LOADING
         * THERE WE HAVE TO PUT THE SCRIPT IN HTML SCRIPT TAG
         * THIS SCRIPT WILL BE REMOVED AFTER EXECUTION
         */

        //Executes when document is loaded
        document.addEventListener('DOMContentLoaded', function(){
            //Select preloader element
            const preloader = document.querySelector('.preloader');
            //Add listener+[.hide]class to preloader if exists
            if(preloader){
                preloader.addEventListener('animationend',Preloader_AnimationEnd);
                preloader.classList.add('hide');
            }
            //This function runs when preloader hide animation is ended
            function Preloader_AnimationEnd(){
                //Remove listener from preloader
                preloader.removeEventListener('animationend',Preloader_AnimationEnd);
                //Select css style
                let css = document.querySelector('#preloader_css');
                //remove if exists
                if(css)
                    css.remove();
                //Remove preloader from hierarchy
                preloader.remove();

                let js = document.querySelector('#preloader_script');
                if(js)
                    js.remove();
            }
        }, false);
    </script>
    <!-- PRELOADER : END-->
    <!--Scripts: BEGIN-->
    <script src="<?=asset('js/vendors~main.js?v='.$app['version'])?>" defer></script>
    <script src="<?=asset('js/main.js?v='.$app['version'])?>" defer></script>
    <!--Scripts: END-->
</body>
</html>