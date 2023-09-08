<?php
defined('ABSPATH') || exit;
$keyVal = loadModel('KeyVal');
$tagService = loadService('TagService');

$list = null;

$data['tag'] = $data['tag'] ?? null;
$data['search_text'] = $data['search_text'] ?? 'Type Here ...';
$data['search_button'] = $data['search_button'] ?? 'search';
$data['search'] = $data['search'] ?? '';

if ($data['search']) {
    $list = $keyVal->list(1, [
        't1.val' => $data['search'],
    ]);
} else if (isset($_POST['search']) && $_POST['search']) {
    $value = $_POST['keyword'];
    if ($data['tag']) {
        $list = $keyVal->list(1, [
            't4.name' => $data['tag'],
            't3.value' => $value
        ]);
    } else {

        $list = $keyVal->list(1, [
            't1.val' => $value,
        ]);
    }
}

?>
<?php if (!$data['search']) : ?>
    <div class="wrap">
        <form action="" method="post">
            <div class="field has-addons">
                <div class="control">
                    <input class="input is-primary is-medium" value="<?= old('keyword') ?>" type="text" placeholder="<?= $data['search_text'] ?>" name="keyword" />
                </div>
                <div class="control">
                    <button type="submit" name="search" value="search" class="button is-primary is-medium"> <?= $data['search_button'] ?> </button>
                </div>
            </div>

        </form>
    </div>
<?php endif; ?>
<?php if ((isset($_POST['search']) && $_POST['search']) || $data['search']) :  ?>
    <?php if ($list) : ?>
        <table class="table is-bordered is-striped is-fullwidth is-narrow is-hoverable">

            <?php foreach ($list[$list['indexes'][0]] as $key => $val)  : ;
                if (in_array($key,[ 
                    'id' , 'created_by' , 'created_by' , 'name' ,
                    'vid' , 'tags_print' , 'title', 'created_type',
                    'tag_type',
                    ]))
                    continue;
            ?>

                <?php if ($key == 'tags') : ?>
                    <?php foreach ($val as $k => $v) :  ?>
                        <tr>
                            <td> <?= $v['title'] ?></td>

                            <td> <?= $tagService->renderShow($v['value'], $v['type'], $v) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php continue;
                endif; ?>
                <?php if ($key == 'val') : ?>
                    <tr>
                        <td><?= get_option('valueLabel') ?></td>
                        <td> <?= $val ?></td>
                    </tr>
                <?php continue;
                endif; ?>
                <?php if ($key == 'group_id') : ?>
                    <tr>
                        <td><?= get_option('groupLabel') ?></td>
                        <td> <?= $val ?></td>
                    </tr>
                <?php continue;
                endif; ?>
                <tr>
                    <td><?= $key ?></td>
                    <td><?= $val ?></td>
                </tr>
            <?php endforeach; ?>

        </table>

    <?php else : ?>
        <div class='notification'>Data not found</div>
    <?php endif; ?>
<?php endif; ?>