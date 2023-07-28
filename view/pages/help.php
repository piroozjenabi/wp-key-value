<?php
defined('ABSPATH') || exit;


$url = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://" . $_SERVER['HTTP_HOST'];
$url .= "/wp-json/key_val/v1/";

$key = loadModel('Keys');
$keys = $key->all();
$names = null;
?>

<div class="wrap">


    <h1 class="wp-heading-inline">Help for wordpress key val</h1>
    <h3 class="wp-heading-inline">Use in api</h3>
    <p>
        With this url can get history or last value
        <b>if your data is important use "has api key?" in data type</b>.
        <br />
        <hr />
    <h5>Method for get history</h5>
    <code>
        <?= $url ?>history/?name={{key name}}
    </code>

    <h5>Method for get last entered</h5>
    <code>
        <?= $url ?>last/?name={{key name}}
    </code>

    <hr />
    <b>if it has Api Key must sent X-API_KEY in header request</b>
    </p>
    <h3>search shortcut</h3>
    <div>
        <code>[key_val_search]</code><br />
        <code>[key_val_search tag="tag1"]</code><br />

        <?php echo do_shortcode("[key_val_search tag='order_id']"); ?>

        <p>
            <b>below params:</b> <br />
            tags:search in tag <br />
        </p>
    </div>
    <hr />
    <p>
    <h3>For short codes LAST VAL</h3>
    <code>[key_val_last name="{name}"]</code><br />
    <?php foreach ($keys as $key => $value) : ?>
        <p>for show last VAL of <?= $value->name ?></p>
        <code>[key_val_last name="<?= $value->name ?>" number_format='true|false' postfix="$" prefix="."]</code><br />
        <?php echo do_shortcode("[key_val_last name='{$value->name}' number_format='true']"); ?>
    <?php endforeach; ?>
    <hr />
    <h3>For short codes HISTORY VAL</h3>
    <code>[key_val_last name="{name}" isTagEditable="true|false"]</code><br />
    <?php foreach ($keys as $key => $value) : ?>
        <p>for show last VAL of <?= $value->name ?></p>
        <code>[key_val_history name="<?= $value->name ?>"]</code><br />
        <?php echo do_shortcode("[key_val_history name='{$value->name}']"); ?>
    <?php endforeach; ?>
    </p>


    <hr />


    <p>
    <h3>Form for add value </h3>
    <code>[key_val_form_insert name="{name or names of key separated by ','}"]</code><br />


    <?php foreach ($keys as $key => $value) : $names .= $value->name . ','; ?>
        <h4>insert code for <?= $value->name ?></h4>
        <code>[key_val_form_insert name="<?= $value->name ?>"]</code><br />
        <?php echo do_shortcode("[key_val_form_insert name='{$value->name}']"); ?>
        <br />
    <?php endforeach;
    $names = rtrim($names, ','); ?>

    <hr />
    <h4>for all</h4>
    <code>[key_val_form_insert name="<?= $names ?>"]</code><br />
    <?php echo do_shortcode("[key_val_form_insert name='{$names}']"); ?>

</div>