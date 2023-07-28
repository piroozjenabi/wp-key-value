<?php
defined('ABSPATH') || exit;
$keyVal = loadModel('KeyVal');
$list = null;
if (isset($_POST['search']) && $_POST['search']) {
    $value = $_POST['keyword'];
    if ($data['tag']) {


        $list = $keyVal->list(1, [
            't4.name' => $data['tag'],
            't3.value' => $value
        ]);
    }
}

?>

<div class="wrap">
    <form action="" method="post">
        <input type="text" placeholder="type Here ..." name="keyword" />
        <button type="submit" name="search" value="search" class="button"> Search </button>
    </form>
</div>

<?php if ($list) : ?>
    <table>
        <?php foreach($list[10] as $key => $val):
            if($key == 'id') continue;
            if($key == 'tags') continue;
            
            ?>
            <tr>
                <td><?= $key ?></td>

                <td><?=  ( $key =='tags_print' 
                    ? implode(',', $val)
                    : $val )
                  ?></td>
            </tr>
        <?php endforeach; ?>    
    </table>

<?php else : ?>
    <div class='notice'>data not found</div>
<?php endif; ?>