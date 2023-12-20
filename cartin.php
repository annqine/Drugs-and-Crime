<?php
include("connectToDB.php");
$cart = app::cart();
if (isset($_REQUEST['cart']) && $_REQUEST['cart'] == 'add' && isset($_REQUEST['id'])) {
    $cart->add($_REQUEST['id']);
    unset($_REQUEST['id']);
    unset($_REQUEST['cart']);
    header('Location: index.php?' . http_build_query($_REQUEST)); 
    exit;
}
if (isset($_REQUEST['cart']) && $_REQUEST['cart'] == 'remove' && isset($_REQUEST['id'])) {
    $cart->remove($_REQUEST['id']);
    header('Location: cartin.php');
    exit;
}
if (isset($_REQUEST['cart']) && $_REQUEST['cart'] == 'clear') {
    $cart->clear();
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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
</div>   

</nav>
<div><a class="btn btn-outline-primary m-2" href="./">Товары</a> <a class="btn btn-outline-primary m-2" href="?cart=clear">Очистить</a></div>
<table class="table table-primary table-striped" width="100%">
    <tr>
        <th>N</th>
        <th>Страна</th>
        <th>Группа Лекарств</th>
        <th width="1">&nbsp;</th>
    </tr>

<?php
$rows = $cart->items();

if (!empty($rows)) {
    foreach ($rows as $i => $item) {
        echo '<tr>';
        echo '<td>' . ($i + 1) . '</td>';
        echo '<td>' . $item['country'] . '</td>';
        echo '<td>' . $item['drug'] . '</td>';
        echo '<td><a href="cartin.php?cart=remove&id=' . $item['id'] . '">Удалить</a></td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4">Корзина пуста</td></tr>';
}
echo '</table>';
?>
</body>
</html>