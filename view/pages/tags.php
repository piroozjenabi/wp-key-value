<?php
defined('ABSPATH') || exit;
$tags = loadModel('Tags');
$type = $tags->type;
$tagService = loadService('TagService');

if (isset($_POST['delete']) && $_POST['delete']) {
    $tags->delete($_POST['delete']);
    echo "<div class='notice'>tag is deleted</div>";
}

//add price
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $type = $_POST['type'];
    $params = $_POST['params'];
    $readonly = $_POST['readonly'];
    $default = $_POST['default'];
    if (!$name) {
        echo "<div class='notice notice-error'>name is required</div>";
    } else {

        $res = $tags->insert([
            'name' => $name,
            'title' => $title,
            'type' => $type,
            'params' => $params,
            'readonly' => $readonly,
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

        <div class="column is-3 is-12-mobile ">
            <div class="box">
                <h2>Add new Tag</h2>
                <form action="" method="post">
                    <button type="submit" name="submit" value="submit" class="button is-primary is-fullwidth is-rounded"> Submit <span class="dashicons dashicons-saved"></span> </button>

                    <div class="field">
                        <label class="label">Name</label>
                        <div class="control">
                            <input class="input " type="text" placeholder="name" name="name" value="<?= "field" . time() ?>" />
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Title</label>
                        <div class="control">
                            <input class="input " type="text" placeholder="title" name="title" />
                        </div>
                    </div>
                    <div class="field">
                        <div class="field">
                            <label class="label">default value</label>
                            <div class="control">
                                <input class="input " type="text" placeholder="default" name="default" />
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Class</label>
                            <div class="control">
                                <input class="input " type="text" placeholder="class" name="class" />
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Type</label>
                            <div class="control">
                                <select name="type" class="select">
                                    <?php foreach ($type as $key => $val) : ?>
                                        <option value="<?= $val['key'] ?>"> <?= $val['value'] ?> </option>
                                    <?php endforeach ?>
                                </select>
                                <p>
                                    Select Type of tag
                                    Woocomerce order id : show in woocomerce detail order

                                </p>
                            </div>
                        </div>

                        <div class="field">
                            <label for="readonly">
                                <input type="checkbox" name="readonly" value="1" />
                                Read only</label>

                        </div>

                        <div class="field">
                            <label class="label">Params</label>
                            <div class="control">
                                <textarea name="params" placeholder="params for type" class="textarea"> </textarea>
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
                                <form method="post" onsubmit="return confirm('Are you sure to DELETE?') ">
                                    <input type="hidden" name="delete" value="<?= $result->id  ?>" />
                                    <button class="button is-danger">Delete</button>
                                </form>
                            </td>

                        </tr>
                    <?php endforeach ?>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>