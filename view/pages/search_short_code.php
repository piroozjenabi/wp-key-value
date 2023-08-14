<?php
defined('ABSPATH') || exit;
$keyVal = loadModel('KeyVal');
$list = null;

$data['tag'] = $data['tag'] ?? null;
$data['search_text'] = $data['search_text'] ?? 'Type Here ...';
$data['search_button'] = $data['search_button'] ?? 'search';
if (isset($_POST['search']) && $_POST['search']) {
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
<?php if (isset($_POST['search']) && $_POST['search']) :  ?>
    <?php if ($list) : ?>
        <table class="table is-bordered is-striped is-fullwidth is-narrow is-hoverable">
            <?php foreach ($list[$list['indexes'][0]] as $key => $val) :
                if ($key == 'id') continue;
                if ($key == 'tags') continue;

            ?>
                <tr>
                    <td><?= $key ?></td>

                    <td><?= ($key == 'tags_print'
                            ? implode(',', $val)
                            : $val)
                        ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php else : ?>
        <div class='notification'>data not found</div>
    <?php endif; ?>
<?php endif; ?>