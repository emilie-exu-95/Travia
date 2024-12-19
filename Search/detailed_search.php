<!DOCTYPE html>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Travia</title>
    <link rel="icon" type="image/x-icon" href="">
    <link rel="stylesheet" href="detailed_search.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">

</head>

<?php include("../header.html"); ?>

<body>

    <div class="window horizontal-alignment horizontal-center vertical-center">

        <!-- LEFT VERTICAL DIV -->
        <div class="left-div vertical-alignment horizontal-center">

            <!-- LOGO + ICON -->
            <div class="logo">
                <h1 class="text-light">TRAVIA</h1>
            </div>

            <!-- FORM (origin, destination)-->
            <form class="vertical-alignment" method="post">
                <!-- origin input-->
                <div class="input-bloc vertical-alignment text-light mb-3">
                    <input name="origin" type="text" id="origin" placeholder="e.g. Alderaan">
                    <label for="origin">Origin</label>
                </div>
                <!-- destination input-->
                <div class="input-bloc vertical-alignment text-light mb-3">
                    <input name="destination" type="text" id="destination" placeholder="e.g. Geonosis">
                    <label for="destination">Destination</label>
                </div>
                <!-- search button -->
                <button type="submit" class="blue-pink-gradient btn">Search</button>
            </form>


            <!-- BUTTONS UPDATE PLANETS/SHIPS-->
            <div class="update-imports vertical-alignment horizontal-center">
                <h5 class="separator-line-text">Imports</h5>

                <form class="vertical-alignment" action="../scripts/update_button.php" method="post">
                    <button class="btn update-button" type="submit" name="update" value="update-planets">Update Planets</button>
                    <button class="btn update-button" type="submit" name="update" value="update-ships">Update Ships</button>
                </form>
            </div>

        </div>

        <!-- RIGHT DIV -->
        <div class="right-div">

        </div>
    </div>

</body>

<footer>
    <script src="../scripts.js"></script>
</footer>

</html>