<?php
defined('ABSPATH') || exit;
$keyVal = loadModel('KeyVal');
$tags = loadModel('Tags');
$tagValue = loadModel('TagValue');
$tagList = $tags->all();
$tagService = loadService('TagService');
$groups = loadModel('Group');

$data['isTagEditable'] = $data['isTagEditable'] ?? true;
$data['isInline'] = $data['isInline'] ?? true;

$selectedGroup = $_GET['group'] ?? $_POST['group'] ?? 0;
// kvdd($data);

if (isset($_POST['add_group']) && $_POST['add_group']) {
    $values = $_POST['value'];
    $group = $groups->insert(['name' => 'tmp']);
    foreach ($values as $key => $val) {
        $groups->pivot->insert([
            'group_id' => $group->id,
            'key_val_id' => $val
        ]);
    }

    $selectedGroup = $group->id;
    echo "<div class='notification is-primary is-light'>" . get_option('groupLabel') . " {$group->id} created successful</div>";
} elseif (isset($_POST['edit']) && $_POST['edit'] && $data['isTagEditable']) {
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
}
//  elseif (isset($_POST['deleteSubmit']) && $_POST['delete'] > 0 ) {

//     $keyVal->delete($_POST['delete']);
//     echo "<div class='notice'>Value is deleted</div>";
// }

$list = $groups->getListByGroup($selectedGroup);

?>

<div class="box">
    <form method="post" action="" enctype="multipart/form-data">
        <div class="box">
            <div class="columns">
                <div class="column is-6" style="text-align:left" S>
                    <div class="field has-addons">
                        <div class="control">
                            <input class="input is-primary is-small" value="" type="text" placeholder="<?= get_option('groupLabel') ?> number" name="group" />
                        </div>
                        <div class="control">
                            <button type="submit" class="button is-primary is-small"> Go </button>
                        </div>
                    </div>
                </div>
                <div class="column is-6" style="text-align:right">
                    <?php if (!$selectedGroup) : ?>
                        <button type="submit" name="add_group" value='1' class="field button is-small is-primary"> Add to <?= get_option('groupLabel') ?> + </button>
                    <?php else : ?>
                        <a href="<?= getCurrentUrl("group=" . $selectedGroup + 1) ?>" class="field button is-small is-dark"> Next > </a>
                        <a href="<?= getCurrentUrl("group =" . ($selectedGroup - 1 ? $selectedGroup - 1 : 1)) ?>" class="field button is-small is-dark"> Prev < </a>
                            <?php endif; ?>
                </div>


            </div>
        </div>
        <?php if (isset($key->title) && $key->title) : ?>
            <h4><?= $key->title ?></h4>
        <?php endif; ?>
</div>
<?php if ($selectedGroup) : ?>
    <h4> <?= get_option('groupLabel') ?> : <?= $selectedGroup ?></h4>
<?php endif; ?>
<div>
    <?php if ($tagList && $data['isTagEditable']) : ?>
        <div class="field <?= $data['isInline'] ? ' is-grouped is-grouped-multiline ' : '' ?>
                    <div class=" control">
            <br />
            <button type="submit" name="edit" value="submit" class="button is-primary is-rounded "> <span class="dashicons dashicons-saved"></span> </button>
        </div>
        <?php foreach ($tagList as $key => $value) : $value->fieldName = "tags[{$value->id}]" ?>
            <?php
            if (!empty($data['tags']))
                if (!(strstr($data['tags'], $value->name)))
                    continue;
            ?>
            <div class="control">
                <label for="<?= $value->name ?>"><b> <?= $value->title ?> </b>
                    <?php if ($value->type == 'textarea') $value->type = "text"; ?>
                    <?= $tagService->renderGet($value) ?>
                </label>
            </div>
        <?php endforeach ?>
</div>
<?php endif; ?>
</div>
<table class="table is-bordered is-striped is-fullwidth is-narrow is-hoverable">
    <thead>
        <tr>
            <td> <input type="checkbox" id="select_all" /> </td>
            <td>Type</td>
            <td><?= get_option('valueLabel') ?></td>
            <td>Date</td>
            <td>Tags</td>
        </tr>
    </thead>
    <?php foreach ($list as $key => $value) : ?>

        <tr>
            <?php if ($data['isTagEditable']) : ?>
                <td>
                    <input type="checkbox" id="select_all" name="value[]" value="<?= $value['vid'] ?>" />
                </td>
            <?php endif; ?>
            <td><?= $value['title'] ?></td>
            <td><?= $value['val'] ?></td>
            <td><?= $value['created_at'] ?></td>
            <td>
                <?= (is_array($value['tags_print']) ? implode(',', $value['tags_print']) : '') ?>
            </td>


        </tr>
    <?php endforeach; ?>

</table>
</form>
<script>
    jQuery('#select_all').change(function() {
        var checkboxes = jQuery(this).closest('form').find(':checkbox');
        checkboxes.prop('checked', jQuery(this).is(':checked'));
    });
</script>
</div>