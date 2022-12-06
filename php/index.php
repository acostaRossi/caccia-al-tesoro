<?php

    session_start();

    //session_unset();

    //die();

    $maxAttempts = 5;
    $flowerbed = 9;

    function generateTreasures() {

        global $flowerbed;

        $n1 = rand(0, $flowerbed - 1);
        $n2 = rand(0, $flowerbed - 1);

        while($n1 === $n2) {
            $n2 = rand(0, $flowerbed - 1);
        }

        $_SESSION["treasures"] = [$n1, $n2];
        $_SESSION["treasures-to-find"] = [$n1, $n2];
    }

    function resetSessionData() {

        $_SESSION["status"] = "play";
        $_SESSION["treasures-found"] = 0;
        $_SESSION["selected"] = [];
    }

    function newMatch() {

        resetSessionData();

        generateTreasures();
    }

    function validateInput($value) {

        if(in_array($value, $_SESSION["selected"])) {
            return false;
        }

        $_SESSION["selected"][] = $_POST["flowerbed-selected"];

        return true;
    }

    function checkResult() {

        global $maxAttempts;

        if(isset($_POST["flowerbed-selected"])) {

            if(validateInput($_POST["flowerbed-selected"])) {

                $flowerbedSelected = intval($_POST["flowerbed-selected"]);

                $key = array_search($flowerbedSelected, $_SESSION["treasures-to-find"]);

                if($key !== false) {
                    unset($_SESSION["treasures-to-find"][$key]);
                    $_SESSION["treasures-found"]++;
                }

                if($_SESSION["treasures-found"] < 2 && count($_SESSION["selected"]) - $_SESSION["treasures-found"] >= $maxAttempts) {
                    $_SESSION["status"] = "game-over";
                }
                if($_SESSION["treasures-found"] >= 2) {
                    $_SESSION["status"] = "win";
                }
            }
        }
    }

    $startNewMatch = !$_SESSION || !$_SESSION["treasures"] || ($_POST && isset($_POST["new-game"]) && $_POST["new-game"] = "1");

    if($startNewMatch) {
        newMatch();
    } else {
        checkResult();
    }

    $matchStatus = isset($_SESSION["status"]) ? $_SESSION["status"] : "play";

    $attemptsAvailable = $maxAttempts - count($_SESSION["selected"]) + $_SESSION["treasures-found"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>The king garden game</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>The king garden game</h1>
    <h2>You have <?php echo $attemptsAvailable; ?> attempts</h2>

    <div id="king-garden">
        <?php
        for($i=0; $i<$flowerbed; $i++) {
            $class = "";
            if(in_array($i, $_SESSION["selected"]) || $matchStatus === "game-over") {
                // mark as selected
                $class = "selected";
                if(in_array($i, $_SESSION["treasures"])) {
                    // mark as a treasure found
                    $class = "treasure";
                }
            }
            echo "<div id='flowerbed-$i' class='flowerbed $class' number='$i'></div>";
        }
        ?>
    </div>
    <form action="" method="POST" id="main-form">
        <input type='hidden' id='flowerbed-selected' name='flowerbed-selected' value='-1' />
    </form>
    <form action="" method="POST" id="new-game-form" style="display:none;">
        <input type='hidden' name='new-game' value='1' />
        <input type='submit' value="New Game"/>
    </form>
    <img id="game-over-gif" src="https://media3.giphy.com/media/3ohjV8JRMcNVGYK10I/giphy.gif?cid=ecf05e47moxwqmoysi4s42adrpymj2iobcuoz1xliy887ikn&rid=giphy.gif&ct=g" />

    <img id="you-win-gif" src="https://media1.giphy.com/media/EYV3omA24nJWzbwyp1/giphy.gif?cid=ecf05e47rdw4znr5ao3cldo772u8wmnyfxcva907cu7umztb&rid=giphy.gif&ct=g" />
</div>
<script src="../assets/js/index.js"></script>
<script type="text/javascript">
    <?php
        if($matchStatus === "play") {
            echo 'bindEvents();';
        } else {
            if($matchStatus === "game-over")
                echo 'gameOver();';

            if($matchStatus === "win")
                echo 'youWin();';
        }
    ?>
</script>
</body>
</html>
