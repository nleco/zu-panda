<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" href="/img/favicon.ico"/>

    <title>{$page_title|escape|default:'Portal'}</title>

    {* CSS Includes *}
    <link rel="stylesheet" href="/css/main.css"/>
    {foreach $css_files as $file}
        <link rel="stylesheet" href="/css/{$file}"/>
    {/foreach}

    {* JS includes *}
    {literal}
    <script>
        var ZU = {};
    </script>
    {/literal}

    <script src="/js/jquery-3.4.1.js"></script>
    <script src="/js/bootstrap-4.3.1.bundle.js"></script>
    {foreach $js_files as $file}
        <script src="/js/{$file}"></script>
    {/foreach}

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">
            <img class="d-inline-block align-top navbar-cnc-logo" alt="CNC" src="/img/cnc-live-inverse.png" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {if $controller eq 'index'}active{/if}">
                    <a class="nav-link" href="/">Status</a>
                </li>

                <li class="nav-item {if $controller eq 'presets'}active{/if}">
                    <a class="nav-link" href="/presets">Presets</a>
                </li>

                <li class="nav-item{if $controller eq 'settings'}active{/if}">
                    <a class="nav-link" href="/settings">Settings</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-2">
        {block name=content}{/block}
    </div>
</body>
</html>