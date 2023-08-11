<?php
defined('ABSPATH') || exit;
$keyVal = loadModel('KeyVal');
$tags = loadModel('Tags');
$tagValue = loadModel('TagValue');
$tagList = $tags->all();
$tagService = loadService('TagService');


if (isset($_POST['edit']) && $_POST['edit'] && $data['isTagEditable']) {
    $tags = $_POST['tags'];
    $values = $_POST['value'];
    foreach ($values as $key => $value) {
        foreach ($tags as $k => $v) {
            $tag = $tagValue->find(['tag_id' => $k, 'key_val_id' => $value]);
            if (isset($tag[0]) && $tag[0])
                $tagValue->update($tag[0]->id, ['value' => $v]);
        }
    }
    echo "<div class='notice'>Tags is updated</div>";
} elseif (isset($_POST['delete']) && $_POST['delete'] > 0) {

    $keyVal->delete($_POST['delete']);
    echo "<div class='notice'>Value is deleted</div>";
}


$list = $keyVal->getListByKeys($data['name']);

?>

<div class="wrap">
    <form method="post" action="" enctype="multipart/form-data">

        <h4><?= $key->title ?></h4>
        <div>
            <?php if ($tagList && $data['isTagEditable']) : ?>
                <br />
                <?php foreach ($tagList as $key => $value) : $value->fieldName = "tags[{$value->id}]" ?>
                    <div class="tags">
                        <label for="<?= $value->name ?>"><b> <?= $value->title ?> </b>:
                            <?= $tagService->renderGet($value) ?>
                        </label>
                    </div>
                <?php endforeach ?>

                <button type="submit" name="edit" value="submit" class="button"> Edit </button>
            <?php endif; ?>
        </div>
        <table class='table' style='width:100%' class='key-val-table'>
            <?php foreach ($list as $key => $value) : ?>

                <tr>
                    <?php if ($data['isTagEditable']) : ?>
                        <td>
                            <input type="checkbox" name="value[]" value="<?= $value['vid'] ?>" />
                        </td>
                    <?php endif; ?>
                    <td><?= $value['title'] ?></td>
                    <td><?= $value['val'] ?></td>
                    <td><?= $value['created_at'] ?></td>
                    <td>
                        <?= (is_array($value['tags_print']) ? implode(',', $value['tags_print']) : '') ?>
                    <td>

                        <form method="post" onsubmit="return confirm('Are you sure to DELETE?') ">
                            <input type="hidden" name="delete" value="<?= $value['vid'] ?>" />
                            <button class="button" style="background:#ffaaaa">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </form>

</div>