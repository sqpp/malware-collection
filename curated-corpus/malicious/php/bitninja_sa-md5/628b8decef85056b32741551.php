<?php
define('_PATH', dirname(__FILE__));
$GLOBALS["m"] = "";
$GLOBALS["e"] = "";
$files = readZips();

if (isset($_POST["unzipfile"])) {
    doAction($_POST);
}

function doAction($d) {
    if (!is_file($d["unzipfile"])) {
        $GLOBALS["e"] = "Datei (<b>" . $d["unzipfile"] . "</b>) nicht gefunden";
        return;
    }

    if ($d["dir"] != "") {
        if (!file_exists($d["dir"])) {
            mkdir($d["dir"], 0777, true);
        }
    }

    $zip = new ZipArchive;
    $res = $zip->open($d["unzipfile"]);
    if ($res === TRUE) {
        if (substr($d["dir"], 0, 1) != "/") {
            $d["dir"] = "/" . $d["dir"];
        }
        $zip->extractTo(_PATH . $d["dir"]);
        $zip->close();
        $GLOBALS["m"] = 'Erfolgreich entpackt!';
    } else {
        $GLOBALS["e"] = 'ZIP konnte nicht geöffnet werden!';
        return;
    }

    if (isset($d["delZip"]) && $d["delZip"] == "on") {
        unlink($d["unzipfile"]);
        $GLOBALS["m"] .= '<br>ZIP-Datei erfolgreich gel&ouml;scht!';
    }

    if (isset($d["delPhp"]) && $d["delPhp"] == "on") {
        unlink("unzip.php");
        $GLOBALS["m"] .= '<br><b>unzip.php</b> erfolgreich gel&ouml;scht!';
    }

}

function readZips() {
    $fileList = glob('*');
    $files = [];
    foreach ($fileList as $filename) {
        $file = pathinfo($filename);

        if (isset($file["extension"]) && $file["extension"] == "zip") {
            array_push($files, $filename);
        }
    }

    return $files;
}

function d($s) {
    echo "<pre>";
    print_r($s);
    echo "</pre>";
}

?>


<html>
<head>
    <title>UnZ!p3r by Chr!s</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import 'https://fonts.googleapis.com/css?family=Open+Sans';

        pre {
            background-color: #cccccc;
            border: 1px solid red;
        }

        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            line-height: 1.75em;
            font-size: 16px;
            background-color: #222;
            color: #767676;
        }

        .simple-container {
            max-width: 675px;
            margin: 0 auto;
            padding-top: 70px;
            padding-bottom: 20px;
        }

        .simple-print {
            fill: white;
            stroke: white;
        }

        .simple-print svg {
            height: 100%;
        }

        .simple-close {
            color: white;
            border-color: white;
        }

        .simple-ext-info {
            border-top: 1px solid #aaa;
        }

        p {
            font-size: 16px;
        }

        h1 {
            font-size: 30px;
            line-height: 34px;
        }

        h2 {
            font-size: 20px;
            line-height: 25px;
        }

        h3 {
            font-size: 16px;
            line-height: 27px;
            padding-top: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #D8D8D8;
            border-top: 1px solid #D8D8D8;
        }

        hr {
            height: 1px;
            background-color: #d8d8d8;
            border: none;
            width: 100%;
            margin: 0px;
        }

        a[href] {
            color: #1e8ad6;
        }

        a[href]:hover {
            color: #3ba0e6;
        }

        img {
            max-width: 100%;
        }

        li {
            line-height: 1.5em;
        }

        aside,
        [class *= "sidebar"],
        [id *= "sidebar"] {
            max-width: 90%;
            margin: 0 auto;
            border: 1px solid lightgrey;
            padding: 5px 15px;
        }

        .form-check * {
            cursor: pointer;
        }

        @media (min-width: 1921px) {
            body {
                font-size: 18px;
            }
        }

        /* loading dots */

        .loading:after {
            content: ' .';
            animation: dots 1s steps(5, end) infinite;
        }

        @keyframes dots {
            0%, 20% {
                color: rgba(0, 0, 0, 0);
                text-shadow: .25em 0 0 rgba(0, 0, 0, 0),
                .5em 0 0 rgba(0, 0, 0, 0);
            }
            40% {
                color: black;
                text-shadow: .25em 0 0 rgba(0, 0, 0, 0),
                .5em 0 0 rgba(0, 0, 0, 0);
            }
            60% {
                text-shadow: .25em 0 0 black,
                .5em 0 0 rgba(0, 0, 0, 0);
            }
            80%, 100% {
                text-shadow: .25em 0 0 black,
                .5em 0 0 black;
            }
        }

        .hidden {
            display: none;
        }

    </style>
</head>

<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <a class="navbar-brand" href="#">UnZ!p3r by Chr!s</a>
</nav>

<main role="main" class="container">
    <div class="jumbotron">
        <form method="post" action="unzip.php">
            <div class="row">


                <div class="col-sm-12 hidden" id="load">
                    <div class="alert alert-success" role="alert">
                        <span class="loading">Entpacke ZIP</span>
                    </div>
                </div>

                <?php
                if ($GLOBALS["m"] !== ""):
                    ?>
                    <div class="col-sm-12">
                        <div class="alert alert-success" role="alert">
                            <?= $GLOBALS["m"] ?>
                        </div>
                    </div>
                <?php
                endif;
                ?>

                <?php
                if ($GLOBALS["e"] !== ""):
                    ?>
                    <div class="col-sm-12">
                        <div class="alert alert-danger" role="alert">
                            <?= $GLOBALS["e"] ?>
                        </div>
                    </div>
                <?php
                endif;
                ?>

                <div class="col-sm-6">
                    <h1>ZIP ausw&auml;hlen</h1>
                    <br>

                    <?php
                    if (sizeof($files) == 0):
                        echo "<b>Keine ZIP-Dateien gefunden.</b>";
                    else:
                        foreach ($files as $file):
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="unzipfile" id="<?= $file ?>" value="<?= $file ?>" checked>
                                <label class="form-check-label" for="<?= $file ?>"><?= $file ?></label>
                            </div>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </div>

                <div class="col-sm-6">
                    <h1>Einstellungen</h1>
                    <br>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked id="delZip" name="delZip">
                        <label class="form-check-label" for="delZip">ZIP Datei nach entpacken l&ouml;schen? </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked id="delPhp" name="delPhp">
                        <label class="form-check-label" for="delPhp">Unziper nach entpacken l&ouml;schen? </label>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="dir">Verzeichnis <small>(Unterverzeichnis, in welches entpackt werden soll z.B. <i>unziped<b>/</b>files</i>)</small></label>
                        <input type="text" class="form-control" id="dir" name="dir" value="">
                    </div>

                </div>

                <div class="col-sm-12">
                    <br>
                    <button class="btn btn-success" type="submit" onclick="load()">Unzip !</button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    function load() {
        var el = document.getElementById("load");
        el.classList.remove("hidden");
    }
</script>
</body>
</html>