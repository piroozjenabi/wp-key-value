<?php
defined('ABSPATH') || exit;
$tags = loadModel('Tags');
$type = $tags->type;
$tagService = loadService('TagService');

if (isset($_POST['delete']) && $_POST['delete']) {
    $tags->delete($_POST['delete']);
    echo "<div class='notice'>tag is deleted</div>";
}

$editData = [];
if (isset($_POST['edit']) && $_POST['edit']) {
    $editData = $tags->findById($_POST['edit'])[0] ?? [];
    // kvdd($editData);
    echo "<div class='notification is-primary is-light'>{$editData->title} is editing</div>";
}
if (isset($_POST['edit_confirm']) && $_POST['edit_confirm']) {
    $editData = $tags->findById($_POST['edit_confirm'])[0] ?? null;
    if (!$editData)
        echo "<div class='notification is-danger is-light'>Data not found !! </div>";
    $tags->update($editData->id, [
        'name' => $_POST['name'],
        'title' => $_POST['title'],
        'default_value' => $_POST['default'],
        'type' => $_POST['type'],
        'params' => $_POST['params'],
    ]);
    echo "<div class='notification is-primary is-light'>{$editData->title} is updated</div>";
} else if (isset($_POST['submit'])) {
    //add price
    $name = $_POST['name'];
    $title = $_POST['title'];
    $type = $_POST['type'];
    $params = $_POST['params'];
    $readonly = $_POST['readonly'];
    $is_active = $_POST['is_active'];
    $default = $_POST['default'];
    if (!$name) {
        echo "<div class='notice notice-error'>name is required</div>";
    } else {

        $res = $tags->insert([
            'name' => $name,
            'title' => $title,
            'type' => $type,
            'params' => $params,
            'is_active' => $is_active,
            'class' => $class,
            'style' => $style,
            'default_value' => $default
        ]);
        if (isset($res))
            echo "<div class='notice notice-success'> value submitted successfully</div>";
        else
            echo "<div class='notice notice-error'>Error in save value</div>";
    }
}

$results = $tags->all();

?>
<div class="wrap">
    <h1 class="wp-heading-inline">Manage Key TAGS </h1>
    <div class="columns">

        <div class="column is-4 is-12-mobile ">
            <div class="box">
                <form action="" method="post">
                    <button type="submit" name="submit" value="submit" class="button is-primary is-fullwidth is-rounded"> Submit <span class="dashicons dashicons-saved"></span> </button>

                    <div class="field">
                        <label class="label">Name</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="name" name="name" value="<?= old('name', $editData->name ?? "field" . time())  ?>" />
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Title</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="title" value="<?= old('title', $editData->title ?? '') ?>" name="title" />
                        </div>
                    </div>
                    <div class="field">
                        <div class="field">
                            <label class="label">default value</label>
                            <div class="control">
                                <input class="input" type="text" placeholder="default" name="default" value="<?= old('default', $editData->default ?? '') ?>" />
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Class</label>
                            <div class="control">
                                <input class="input " type="text" placeholder="class" name="class" value="<?= old('class', $editData->class ?? '') ?>" />
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Type</label>
                            <div class="control">
                                <select name="type" class="select">
                                    <?php
                                    $i = 0;
                                    foreach ($type as $key => $val) : $i++;  ?>
                                        <option value="<?= $val['key'] ?>"> <?= "{$i} - {$val['value']}" ?> </option>
                                    <?php endforeach ?>
                                </select>
                                <p>
                                    Select Type of tag
                                    Woocomerce order id : show in woocomerce detail order

                                </p>
                            </div>
                        </div>

                        <div class="field">
                            <label for="isActive">
                                <input type="checkbox" name="is_active" value="1" value="<?= old('readonly', $editData->is_active ?? '') ?>" />
                                show in submit form (active)</label>

                        </div>
                        <div class="field">
                            <label for="readonly">
                                <input type="checkbox" name="readonly" value="1" value="<?= old('readonly', $editData->readonly ?? '') ?>" />
                                Read only</label>

                        </div>

                        <div class="field">
                            <label class="label">Params</label>
                            <div class="control">
                                <textarea name="params" placeholder="params for type" class="textarea"><?= old('params', $editData->params ?? '') ?></textarea>
                                <table class="table is-fullwidth is-narrow">
                                    <tr>
                                        <td>
                                            <p><b>select box options </b></p>
                                            <code>
                                                {"key1" : "value1", "key2" : "value2"}
                                            </code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><b>api</b></p>
                                            <code>
                                                {"url" : "https://example.com", "value" : "price"}
                                            </code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><b>formula</b></p>
                                            <code>
                                                {"formula" : "fieldname1240*fieldname1735"}
                                            </code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><b>Remote key value</b></p>
                                            <code>
                                                {"site" : "https://www.example.com","type" : "default","api_key" : ""}
                                            </code>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>

                        <input type="hidden" name="edit_confirm" value="<?= $editData->id ?>" />
                </form>
            </div>

        </div>
    </div>
    <div class="column">
        <div class="box">

            <?php if ((!$results)) : ?>
                <p class="update-nag notice notice-warning inline">
                    No Key Entered until now, please <b>Add new tag</b> to show here
                </p>
            <?php else : ?>
                <table class="table is-bordered is-striped is-fullwidth is-narrow is-hoverable">
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>type</th>
                        <th>preview</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($results as $result) : ?>

                        <tr>
                            <td> <?= $result->id ?></td>
                            <td> <?= $result->title ?>(<?= $result->name ?>) </td>
                            <td> <?= $result->type ?></td>
                            <td> <?= $tagService->renderGet($result) ?></td>
                            <td>
                                <div class="field has-addons">
                                    <form method="post" class="control" onsubmit="return confirm('Are you sure to DELETE?') ">
                                        <input type="hidden" name="delete" value="<?= $result->id  ?>" />
                                        <button class="button is-danger "><span class="dashicons dashicons-trash "></span></button>
                                    </form>
                                    <form method="post">
                                        <input type="hidden" class="control" name="edit" value="<?= $result->id  ?>" />
                                        <button class="button is-primary"><span class="dashicons dashicons-edit"></span></button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach ?>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>