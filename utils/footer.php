<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Footer</title>
</head>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }
    footer {
        font-weight: bold;
        position: fixed;
        width: 100vw;
        bottom: 0;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap: 0.8em;
        color: white;
        background-color: rgba(0, 0, 0, 0.5);
        padding: 0.5em 3em;
    }
    .footer-item {
        color: white;
        text-decoration: none;
        display: flex;
        flex-direction: row;
        gap: 0.8em;
        font-size: 0.8em;
        letter-spacing: 0.05em;
    }
    .footer-icon {
        height: 1.5em;
    }
    img[alt="GitHub"] {
        filter: invert(1);
    }

</style>

<body>

    <?php
        // Redirection to prevent access to this page
        if (basename($_SERVER["PHP_SELF"]) == "footer.php") {
            header("Location: ../index.php");
        }
    ?>

    <footer>

        <!-- GitHub -->
        <a class="footer-item" href="https://github.com/emilie-exu-95/Travia" target="_blank">
            <img class="footer-icon" src="https://cdn-icons-png.flaticon.com/512/25/25231.png" alt="GitHub">
            <p>View project</p>
        </a>

    </footer>


</body>
</html>