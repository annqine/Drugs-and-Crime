<?php
//error_reporting(0);
include("connectToDB.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Office on Drugs and Crime</title>
</head>
<body>
    <?php
include("header.php");
$pagination = new Pagination();
$pagination->setLimit((isset($_REQUEST['limit']))?$_REQUEST['limit']:10);
$regionFilter = isset($_REQUEST['region']) ? $_REQUEST['region'] : '';
$pagination->setParams(['region' => $regionFilter]);
$db = new DB([
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'Borodina2005',
    'db' => 'relational_base',]);
?>
<form style="width:300px;" class="m-2 d-flex flex-row" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <?php
    HtmlHelper::select("limit", $pagination->limits, $pagination->getLimit(), false, ['class' => 'form-select']);
    ?>
    <input type="hidden" name="region" value="<?= isset($regionFilter) ? htmlspecialchars($regionFilter) : '' ?>">
    <input class="btn btn-outline-primary ms-2" type="submit" name="search" value="Поиск">
</form>
<?php
if (!app::cart()->isEmpty()) { ?>
    <a href="cartin.php" class="btn btn-outline-primary ms-2">Выбранное</a>
<?php } 
$wh = [];
$params = [];
if ($db->isConnect()) {
    $regionFilter = '';
    if (isset($_REQUEST['region'])) {
        $params['region'] = $_REQUEST['region'];
        $wh[] = "`region` LIKE '" . $_REQUEST['region'] . "%'";
    }

    $whClause = !empty($wh) ? ' WHERE ' . implode(' AND ', $wh) : '';
    $c = $db->queryOne("SELECT COUNT(*) as 'id' FROM `drugs`$whClause", $params);
    $pagination->setRowsCount($c['id']);
    $pagination->setPage((isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1);

    $sql = "SELECT * FROM drugs$whClause LIMIT " . $pagination->getFirst() . "," . $pagination->getLimit();
    $rows = $db->query($sql);
    $rows = $db->query($sql);

        echo '<div class="pagination m-2">';
        echo $pagination->show();
        echo '</div>';

    if ($rows === false) {
        echo 'Error select';
    } else {
        echo "<div class='fs-5 text-secondary'>Rows: {$pagination->getRowsCount()} Pages: {$pagination->getPageCount()}</div>";
        echo '<table class="table table-primary table-striped border=1" width="100%">
        <tr><th>N</th>
        <th>ID</th>
        <th>Region</th>
        <th>SubRegion</th>
        <th>Country/Territory</th>
        <th>Year</th>
        <th>Drug Group</th>
        <th>rank</th>
        <th>ISO3</th>
        </tr>';
        $num = $pagination->getFirst();
        foreach ($rows as $row) {
            echo '<tr>';
            echo '<td class="table-light">' . $num . '</td>';
            echo '<td class="table-light">' . $row['id'] . '</td>';
            echo '<td class="table-light">' . $row['Region'] . '</td>';
            echo '<td class="table-light">' . $row['SubRegion'] . '</td>';
            echo '<td class="table-light">' . $row['Country/Territory'] . '</td>';
            echo '<td class="table-light">' . $row['Year'] . '</td>';
            echo '<td class="table-light">' . $row['Drug Group'] . '</td>';
            echo '<td class="table-light">' . $row['rank'] . '</td>';
            echo '<td class="table-light">' . $row['ISO3'] . '</td>';
            echo '<td><a href="cartin.php'.$pagination->getParams($pagination->getPage()).$pagination->getParams($pagination->setParams($params)).'&cart=add&id='.$row['id'].'">+</a></td>';
            //echo '<td class="table-light"><form action="cart.php"><input type="hidden" name="id" value="' . $row['id'] . '"><button>Select</button></form></td>';
            echo '</tr>';
            $num++;
        }
        echo '</table>';
        $pagination->setParams($params);
        echo '<div class="pagination m-2">';
        echo $pagination->show();
        echo '</div>';
    }
} else {
    echo 'Error DB connection';
     }
?>
 </header>
</body>
</html>