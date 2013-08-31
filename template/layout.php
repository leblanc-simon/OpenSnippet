<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>OpenSnippet</title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/font-awesome.css" />
        <link type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic,500italic,500,300italic,300" rel="stylesheet" />
        <link type="text/css" href="http://fonts.googleapis.com/css?family=Fugaz+One|Leckerli+One" rel="stylesheet" />
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body class="show-sidebar">
        <div class="flex-container">
            <div class="flex-item sidebar">
                <div>
                    <span class="homepage-link"><a href="">Homepage</a></span>
                    <ul class="nav nav-list">
                        <li>
                            <a href=""><i class="icon-plus"></i> New Snippet</a>
                        </li>
                        <li class="nav-header">Catégories</li>
                        <li class="active">
                            <a href="">PHP<b class="label">6</b></a>
                        </li>
                        <li>
                            <a href="">Javascript<b class="label">5</b></a>
                        </li>
                        <li>
                            <a href="">Python<b class="label">5</b></a>
                        </li>
                        <li>
                            <a href="">CSS<b class="label">1</b></a>
                        </li>

                        <li class="nav-header">Tags</li>
                        <li>
                            <a href="">chiffrement<b class="label">2</b></a>
                        </li>
                        <li>
                            <a href="">administration<b class="label">2</b></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="flex-item">
                <div class="navbar navbar-static-tops">
                    <div class="navbar-inner">
                        <a href="javascript:void(0);" class="btn pull-left toggle-sidebar">
                            <i class="icon-reorder"></i>
                        </a>
                        <a class="brand" href="index.html">OpenSnippet</a>
                    </div>
                </div>

                <div class="content-head">
                    <ul class="breadcrumb">
                        <li><a href="#">Home</a> <span class="divider"><i class="icon-angle-right"></i></span></li>
                        <li class="active">Dashboard</li>
                    </ul>
                    <h2>Dashboard</h2>
                    <div class="muted"></div>
                </div>

                <div class="content-body">
                    <div class="row-fluid">
                        <h3>Last snippets</h3>
                        <section class="module">
                            <div class="module-head">
                                <b>Vérification de syntaxe</b>
                                <i>PHP</i>
                            </div>
                            <div class="module-body">
                                <div class="code not-all">
                                    <?php //echo $geshi_php->parse_code(); ?>
                                </div>
                            </div>
                            <div class="module-foot">
                                <div class="tags">
                                    <span>php</span><span>syntaxe</span><span>svn</span><span>subversion</span>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-primary">
                                        Copy
                                    </button>
                                    <button class="btn btn-success extends">
                                        Expand
                                    </button>
                                </div>
                            </div>
                        </section>
                        <section class="module">
                            <div class="module-head">
                                <b>Style administration</b>
                                <i>CSS</i>
                            </div>
                            <div class="module-body">
                                <div class="code not-all">
                                    <?php //echo $geshi_css->parse_code(); ?>
                                </div>
                            </div>
                            <div class="module-foot">
                                <div class="tags">
                                    <span>css</span><span>stylesheet</span><span>transparence</span><span>layout</span>
                                    <span>2 colonnes</span>
                                </div>
                                <div class="actions">
                                    <button class="btn btn-primary">
                                        Copy
                                    </button>
                                    <button class="btn btn-success extends">
                                        Expand
                                    </button>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="app.js"></script>
    </body>
</html>