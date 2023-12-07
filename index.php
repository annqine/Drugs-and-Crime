<?php
//error_reporting(0);
include("pag.php");
include("db.php");
include("htmlHelper.php");
//сделать чтобы количество выводящихся строк было в url и не сбрасывалось с обновлением страницы
//когда выбираю кол-во строк сбрасывается фильтр и наоборот
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
<form style="width:300px;" class="m-2 d-flex flex-row" action="<?php echo $_SERVER['PHP_SELF'] . '?' . http_build_query($_REQUEST); ?>" method="get">
    <?php
    HtmlHelper::select("limit", $pagination->limits, $pagination->getLimit(), false, ['class' => 'form-select']);
    ?>
    <input type="hidden" name="region" value="<?= isset($regionFilter) ? htmlspecialchars($regionFilter) : '' ?>">
    <input class="btn btn-outline-primary ms-2" type="submit" name="search" value="Поиск">
</form>
<?php
$params = [];
if ($db->isConnect()) {
    $wh = ""; 
    $regionFilter = '';
    if (isset ($_REQUEST['region'])){
        $params['region'] = $_REQUEST['region'];
        if ($wh) $wh.= ' AND';
        $wh .= " `region` LIKE '" . $_REQUEST['region'] . "%'";
    } 
    if ($wh) $wh = ' WHERE '. $wh;
    $c = $db->queryOne("SELECT COUNT(*) as 'id' FROM `drugs` $wh");
    $pagination->setRowsCount($c['id']);
    $pagination->setPage((isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1);

    $sql = "SELECT * FROM drugs $wh LIMIT ".$pagination->getFirst().",".$pagination->getLimit();
    $rows = $db->query($sql);

        echo '<div class="pagination m-2">';
        echo $pagination->show();
        echo '</div>';
    
    if ($rows === false) {
        echo 'Error select';
    } else {
        echo "<div class='fs-5 text-secondary'>Rows: {$pagination->getRowsCount()} Pages: {$pagination->getPageCount()}</div>";
        echo '<table class="table table-primary table-striped border="1" width="100%">
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
</div>
 </header>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>