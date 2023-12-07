<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<nav class="navbar bg-primary-subtle d-flex justify-content-center flex-row">

    <div class="d-flex flex-row justify-content-between align-items-center">
        <div class="d-flex flex-row w-25">
            <img class="m-1" src="un-emblem.png" style="height: 90px;" alt="">
            <h1 class="m-1">United Nations</h1>
        </div>
        <div class="d-flex" style="height: 150px;">
            <div class="vr "></div>
        </div>
        <div class="d-flex flex-row">
            <h2 class="">Office on Drugs and Crime</h2>
        </div>
            <div class="dropdown">
                <select class="form-select" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <ul class="dropdown-menu">
                        <option selected value=""><li><a class="dropdown-item" href="index.php">First</a></li></option>
                        <option value=""><li><a class="dropdown-item" href="index.php">Second</a></li></option>
                        <option value=""><li><a class="dropdown-item" href="index.php">Third</a></li></option>
                    </ul>
                </select>
                
            </div>
            <form class="input-group w-25" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                <input type="text" name="region" value="<?= isset($regionFilter) ? htmlspecialchars($regionFilter) : ''; ?>" placeholder="Введите название региона" class="form-control" aria-label="Recipient's username" aria-describedby="button-addon2">
                <input type="hidden" name="limit" value="<?= isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10; ?>">
                <input type="submit" name="search" value="Поиск" class="btn btn-outline-primary" id="button-addon2">
            </form>
    </div>   

</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>