<?php
defined('ABSPATH') || exit;
$keyVal = loadModel('KeyVal');
$tag = loadModel('Tags');
$tagList = $tag->all();
$keys = loadModel('Keys');
$tagValue = loadModel('TagValue');
$keyList = $keys->whereIn('name', $data['name']);
if (isset($_POST['submit'])) {
    $value = $_POST['value'];
    $key = $_POST['key_id'];
    $tags = $_POST['tags'];
    if (!$key) {
        echo "<div class='notice notice-error'>key is required</div>";
    } else {
        $res = $keyVal->insert(['val' => $value, 'key_id' => $key]);
        if (isset($res)) {
            if (isset($tags) && $tags)
                $tagValue->insertBulk($res->id, $tags);
            echo "<div class='notice notice-success'> value submitted successfully</div>";
        } else {

            echo "<div class='notice notice-error'>Error in save value</div>";
        }
    }
}

if ($keyList) {
?>

    <div class="wrap">
        <form action="" method="post">
            <input type="number" placeholder="value" name="value" />
            <?php if (count($keyList) > 1) : ?>
                <select name="key_id">

                    <option value="">select key</option>
                    <?php foreach ($keyList as $key => $value) : ?>
                        <option value=" <?= $value->id ?>"> <?= $value->title ?> </option>
                    <?php endforeach ?>

                </select>
            <?php elseif (count($keyList) == 1) : ?>
                <input type="hidden" name="key_id" value="<?= $keyList[0]->id ?>" />
            <?php endif; ?>

            <button type="submit" name="submit" value="submit" class="button"> Add new Val + </button>
            <?php if ($tagList) : ?>
                <hr />
                <?php foreach ($tagList as $key => $value) : $value->name = "tags[{$value->id}]" ?>
                    <div>
                        <label for="<?= $value->name ?>"><b> <?= $value->title ?> </b>:
                            <?= renderTagInput($value) ?>
                        </label>
                    </div>
                <?php endforeach ?>

            <?php endif; ?>
        </form>
    </div>
<?php } else { ?>
    <div class='notice notice-error'> key not found! </div>
<?php } ?>