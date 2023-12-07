<?php
include("pag.php");
include("db.php");
include("htmlHelper.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
    <?php
    include("header.php");
    ?>
    <a href="index.php">
        <input class="btn btn-outline-primary m-2" type="submit" name="back" value="Назад">
    </a>
    <?php 
    $pagination = new Pagination();
    $pagination->setLimit((isset($_REQUEST['limit']))?$_REQUEST['limit']:10);
    $regionFilter = isset($_REQUEST['region']) ? $_REQUEST['region'] : '';
    $pagination->setParams(['region' => $regionFilter]);
    $db = new DB([
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'Borodina2005',
        'db' => 'relational_base',]);
        
    $params = [];
    if ($db->isConnect()) {
    $wh = ""; 
    $regionFilter = '';
    if (isset ($_REQUEST['region'])){   
        $params['region'] = $_REQUEST['region'];
        if ($wh) $wh.= ' AND';
        $wh .= " `region_name` LIKE '" . $_REQUEST['region'] . "%'";
    } 
    if ($wh) $wh = ' WHERE '. $wh;
    $c = $db->queryOne("SELECT COUNT(*) as 'id' FROM country c
    JOIN subregion s ON c.subregion_id = s.subregion_id
    JOIN region r ON s.region_id = r.region_id
    $wh");

    $pagination->setRowsCount($c['id']);
    $pagination->setPage((isset($_REQUEST['page'])) ? $_REQUEST['page'] : 1);

    $sql = "SELECT c.country_id, c.country_name, s.subregion_name, r.region_name
    FROM country c
    JOIN subregion s ON c.subregion_id = s.subregion_id
    JOIN region r ON s.region_id = r.region_id
    $wh
    LIMIT ".$pagination->getFirst().",".$pagination->getLimit();

    $rows = $db->query($sql);

    echo '<div class="pagination m-2">';
    echo $pagination->show();
    echo '</div>';

    if ($rows === false) {
    echo 'Error select';
    } else {
    echo "<div class='fs-5 text-secondary'>Rows: {$pagination->getRowsCount()} Pages: {$pagination->getPageCount()}</div>";
    echo '<table class="table table-primary table-striped border="1" width="100%">
    <tr>
    <th>Country ID</th>
    <th>Country Name</th>
    <th>Region Name</th>
    <th>Subregion Name</th>
    </tr>';

    $num = $pagination->getFirst();

    foreach ($rows as $row) {
    echo '<tr>';
    echo '<td class="table-light">' . $row['country_id'] . '</td>';
    echo '<td class="table-light">' . $row['country_name'] . '</td>';
    echo '<td class="table-light">' . $row['region_name'] . '</td>';
    echo '<td class="table-light">' . $row['subregion_name'] . '</td>';
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>