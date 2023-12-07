<?php
class HtmlHelper {
    static function select($name, $items, $value, $isEmptyRow = false, $attributes = []) {
        ?>
        <select name="<?= $name ?>" class="form-select <?= $attributes['class'] ?>">
        <?php
        if ($isEmptyRow) {
            echo '<option></option>';
        }
        foreach ($items as $item) {
            if (is_string($item) || is_numeric($item)) {
                echo '<option ' . (($item == $value) ? 'selected' : '') . ' value="' . $item . '">' . $item . ' rows </option>';
            } else {
                echo '<option ' . (($item['id'] == $value) ? 'selected' : '') . ' value="' . $item['id'] . '">' . $item['name'] . ' rows </option>';
            }
        }
        ?>
        </select>
        <?php
    }
}
?>
