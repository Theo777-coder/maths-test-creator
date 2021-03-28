<?php
require_once "../model/dataAccess.php";
require_once "../model/user.php";
require_once "../controller/tests.php";
require_once "topBar_view.php";
?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Tests</title>
    <link rel="stylesheet" href="/maths-test-creator/CSS/test.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="/maths-test-creator/AJAX/tests.js"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</head>


<body>
    <div class="outerDIv">
        <h1>Tests</h1>
        <h1 class="titlePrint">Test</h1>
        <div class="testCredentials">
            name: <input type="text" class="userAnswers normalBorder"><br>
            date: <input type="text" class="userAnswers normalBorder" placeholder="  /  /   ">
        </div>
        <div class="hiddenWhenPrinted">
            <?php
            if (isset($_SESSION["username"])) :
            ?>
                <p>Welcome <b><?= $_SESSION["username"] ?></b>!</p>
                <p>Your score is <b><?= $_SESSION["score"] ?></b>!</p>
            <?php
            else :
            ?>
                <p>Welcome guest!</p>
                <p>You can login up at <a href="login_view.php">Login</a></p>

            <?php
            endif;
            ?>
            <h2>Generate tests</h2>
            <form id="testCreationFrom" name="testCreationFrom" action="tests_view.php" method="GET">
                <div class="tooltip">
                    <input class="normalBorder" value=2 type="number" id="noOfTerms" name="noOfTerms" placeholder="number of terms">
                    <span class="tooltiptext tooltipTestPos">How many terms each question will have. Must be at least 2.</span>
                </div>
                <div class="tooltip">
                    <input class="normalBorder" value=1 type="number" id="magnitudeMin" name="magnitudeMin" placeholder="min number">
                    <span class="tooltiptext tooltipTestPos">Min number for the random generation.</span>
                </div>
                <div class="tooltip">
                    <input class="normalBorder" value=10 type="number" id="magnitudeMax" name="magnitudeMax" placeholder="max number">
                    <span class="tooltiptext tooltipTestPos">Max number for the random generation</span>
                </div>
                <div class="tooltip">
                    <input class="normalBorder" value=2 type="number" id="decimalAccuracy" name="decimalAccuracy" placeholder="decimal accuracy">
                    <span class="tooltiptext tooltipTestPos">Up to how many decimal figures a division will be rounded to.</span>
                </div><br>
                <div id="topicsDiv" name="topicsDiv" class="topics" tabindex="0">
                    <label class="container">addition
                        <input type="checkbox" name="topic[]" value="addition" checked>
                        <span class="checkmark checkmarkNormalPos"></span>
                    </label><br>
                    <label class="container">subtraction
                        <input type="checkbox" name="topic[]" value="subtraction">
                        <span class="checkmark checkmarkNormalPos"></span>
                    </label><br>
                    <label class="container">multiplication
                        <input type="checkbox" name="topic[]" value="multiplication">
                        <span class="checkmark checkmarkNormalPos"></span>
                    </label><br>
                    <label class="container">division
                        <input type="checkbox" name="topic[]" value="division">
                        <span class="checkmark checkmarkNormalPos"></span>
                    </label><br>
                </div>
                <input class="hidden" id="seedID" name="seedID" value="<?= rand(); ?>" />
                <input type="hidden" name="token" value="<?php echo (hash_hmac('sha256', 'createTests', $_SESSION['token'])); ?>" />
                <p class="warning" id="validationMessageTestCreation"></p>
                <input id="createTests" type="submit" name="createTests" value="Create test">
            </form>
        </div>
        <span id="timer" class="hidden hiddenWhenPrinted"> 00:00:00</span><br>
        <?php
        if (isset($_SESSION["timer"])) {
        ?>
            Test instructions:
            <p>Division: Write answers up to <b><?= $_SESSION["decimalAccuracy"] ?></b> decimal figures</p><br>
        <?php
        }
        ?>
        <div class="center">

            <form id="userAnswers" style="display:inline-block">
                <?php
                if (isset($_SESSION["questions"])) {
                    for ($i = 0; $i < count($_SESSION["questions"]); $i++) {
                ?>
                        <p class="ex4">
                            <b><?= ($i + 1) . ")" ?></b>
                            <?=
                            "\(" . $_SESSION["questions"][$i] . " = \)";
                            if (!isset($_SESSION["correctAnswers"])) {
                            ?>
                                <input name="result[]" type="text" class="questions userAnswers normalBorder">
                                <?php
                            } else if (isset($_SESSION["userAnswers"])) {
                                if ($_SESSION["wrongOrRight1"][$i] == "1") {
                                ?>
                                    <span class="rightAnswer"><?= "\(" . $_SESSION["userAnswers"][$i] . "\)" ?></span><?= ";" ?>
                                <?php } else if ($_SESSION["userAnswers"][$i] == null) {
                                ?>
                                    <span class="wrongAnswer test"><?= "empty" ?></span><span data-descr="<?= $_SESSION["correctAnswers"][$i] ?>" class="spoiler2 label test"></span><?= ";" ?>

                                <?php } else if ($_SESSION["wrongOrRight1"][$i] == "0") { ?>
                                    <span class="wrongAnswer test"><?= "\(" . $_SESSION["userAnswers"][$i] . "\)" ?></span><span data-descr="<?= $_SESSION["correctAnswers"][$i] ?>" class="spoiler2 label test"></span><?= ";" ?>
                                <?php } ?>
                        </p>
                <?php
                            }
                        }
                ?>
                <?php if (!isset($_SESSION["userAnswers"])) { ?>
                    <input class="hide" type="submit" name="checkTest" id="checkTest" value="Check test">
                <?php } else { ?>
                    <input class="hide" type="submit" name="retryTest" value="Retry test">
                <?php } ?>
            </form>
            <div class="hiddenWhenPrinted">
                <button id="printTest">Print test</button>
                <button id="shereTest">Share test</button>
                <input type="text" name="linkShare" id="linkShare" class="testCredentials normalBorder" />
            <?php }
            ?>
            </div>

        </div>


        <div id="messageBox" class="message box hidden">
            <div class="center attentionRedColor">Attention!</div><br>
            <p id="messageParagraph" class="center message">You have unfilled questions, empty questions will be marked as wrong. Continue?</p><br>
            <button id="yesButton" name="yesButton" class="prompt promptLeft promptPosition">Yes</button>
            <button id="noButton" name="noButton" class="prompt promptRight promptPosition">No</button>
        </div>

        <div class="testCredentials">
            <input type="text" class="userAnswers normalBorder score">/10
        </div>
        <div class="overlay hidden" id="overlay"></div>
    </div>
</body>

</html>