<?php
defined('ABSPATH') || exit;

if (isset($_POST['submit'])) {
    foreach (KEY_VAL_SETTINGS as $key => $value) {

        if (empty(get_option($value["name"])))
            add_option($value['name'], $_POST[$value['name']]);
        else
            update_option($value['name'], $_POST[$value['name']]);
    }
    echo "<div class='notice notice-success'> setting saved </div>";
}

?>

<div class="wrap">
    <h1>Key-val Setting</h1>
    <form method="post" action="">
        <div class="col-wrap">
            <form action="" method="post">
                <table class="form-table" role="presentation">

                    <tbody>
                        <?php foreach (KEY_VAL_SETTINGS as $key => $value) : ?>
                            <tr>
                                <th scope="row"><label for="<?= $value['name'] ?>"><?= $value['title'] ?>:</label></th>
                                <td><input name="<?= $value['name'] ?>" type="text" id="<?= $value['name'] ?>" value="<?= get_option($value['name'], $value['default']) ?>" class="regular-text"></td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>