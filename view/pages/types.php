<?php
defined('ABSPATH') || exit;
$keys = loadModel('Keys');
$keyVal = loadModel('KeyVal');
$tags = loadModel('Tags');

// delete type
if (isset($_POST['delete']) && $_POST['delete']) {
    $keys->delete($_POST['delete']);
    $keyVal->delete($_POST['delete'], 'key_id');
    echo "<div class='notice'>Key is deleted</div>";
}

//add type
if (isset($_POST['submit'])) {
    $name = slug($_POST['name']);
    $title = $_POST['title'];
    $api_key = $_POST['hasApiKey'] ? ApiKeyGen() : '';
    $tags = $_POST['tags'];
    if (!$name) {
        echo "<div class='notice notice-error'>name is required</div>";
    } else {

        $res = $keys->insert(['name' => $name, 'title' => $title, 'api_key' => $api_key, 'tags' => $tags]);
        if (isset($res))
            echo "<div class='notice notice-success'> value submitted successfully</div>";
        else
            echo "<div class='notice notice-error'>Error in save value</div>";
    }
}

$results = $keys->all();
?>
<div class="wrap">
    <h1 class="wp-heading-inline">Manage Key types </h1>
    <div class="columns">
        <div class="column is-3 is-12-mobile ">

            <div class="box">
                <form action="" method="post">

                    <div class="field">
                        <label class="label">Name for slug api</label>
                        <div class="control">
                            <input class="input " type="text" placeholder="name for slug api" name="name" />
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Title</label>
                        <div class="control">
                            <input class="input " type="text" placeholder="title" name="title" />
                        </div>
                    </div>
                    <div class="field">
                        <label for="apiKey">
                            <input type="checkbox" name="hasApiKey" value="1" />
                            Has api key?</label>

                    </div>
                    <div class="field">
                        <label class="label">tags</label>
                        <div class="control">
                            <div class="select is-multiple is-fullwidth">
                                <select multiple size="4" name="tags[]">
                                    <?php foreach ($tags->all() as $tag) : ?>
                                        <option value="<?= $tag->id ?>"> <?= $tag->title ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <p><small>For all fields don't change</small></p>
                            <p><small>Press ctrl for select multiple</small></p>
                        </div>
                    </div>

                    <button type="submit" name="submit" value="submit" class="button is-primary is-fullwidth is-rounded"> Submit <span class="dashicons dashicons-saved"></span> </button>
                </form>
            </div>
        </div>
        <div class="column ">

            <div class="box">

                <?php if ((!$results)) : ?>
                    <p class="update-nag notice notice-warning inline">
                        No Key Entered until now, please <b>Add new Type</b> to show here
                    </p>
                <?php else : ?>
                    <table class="table is-bordered is-striped is-fullwidth is-narrow is-hoverable">
                        <tr>
                            <th>id</th>
                            <th>name</th>
                            <th>title</th>
                            <th>api key</th>
                            <th>tags</th>
                            <th>actions</th>
                        </tr>
                        <?php foreach ($results as $result) : ?>
                            <tr>
                                <td><?= $result->id ?></td>
                                <td><?= $result->name ?></td>
                                <td><?= $result->title ?></td>
                                <td><?= $result->api_key ?></td>
                                <td><?= $keys->__getTags($result->id) ?></td>
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