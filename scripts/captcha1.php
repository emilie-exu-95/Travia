<?php

// Create values and store them in session
$x = rand(1, 10);
$y = rand(1, 10);
$_SESSION["captcha_result"] = $x + $y;

?>

<link rel="stylesheet" href="../utils/utils.css">
<link rel="stylesheet" href="../utils/components.css">

<style>
    #captcha1 {
        width: 2.5em;
        height: 1.4em;
        font-size: 0.8em;
        padding: 0.1em 0.2em;
        text-align: center;
    }
    .captcha-div {
        font-size: 1.5em;
        gap: 0.6em;
        margin: 0 0 0.8em 0.3em;
    }
</style>

<!-- DISPLAY CAPTCHA -->
<div class="horizontal-alignment horizontal-center captcha-div">

    <label for="captcha1"><?php echo $x . " + " . $y . " = "; ?></label>
    <input type="text" name="captcha1" id="captcha1" required/>

</div>