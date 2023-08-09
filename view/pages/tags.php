<?php
defined('ABSPATH') || exit;
$tags = loadModel('Tags');
$type = $tags->type;

?>
<div class="wrap">
    <h1 class="wp-heading-inline">Manage Key TAGS </h1>
    <div id="col-container" class="wp-clearfix">

        <div id="col-left">
            <h2>Add new Tag</h2>
            <div class="col-wrap">
                <form action="" method="post">
                    <div class="form-field form-required term-name-wrap">
                        <label for="tag-name">Name</label>

                        <input type="text" placeholder="name" name="name" />
                    </div>
                    <div class="form-field form-required term-name-wrap">
                        <label for="tag-name">Title</label>
                        <input type="text" placeholder="title" name="title" />
                    </div>
                    <br />
                    <div class="form-field form-required term-name-wrap">
                        <label for="tag-name">Type</label>
                        <select name="type" class="postform">
                           <?php foreach($type as $key => $val): ?>
                                <option value="<?= $val['key'] ?>"> <?= $val['value'] ?> </option>
                            <?php endforeach ?>
                        </select>
                        <p>
                            Select Type of tag
                            Woocomerce order id : show in woocomerce detail order

                        </p>
                    </div>
                    <div class="form-field form-required term-name-wrap">
                        <label for="tag-name">Params</label>
                        <textarea name="params" placeholder="params for type" id="tag-description" rows="5" cols="40" aria-describedby="description-description">

                        </textarea>
                        <h4>Params:</h4>
                        <p>
                            <b>-select box options </b> 
                            <br />
                            {"key1" : "value1", "key2" : "value2"}
                            <hr />
                        </p>
                    </div>
                    <p class="submit">
                        <button type="submit" name="submit" value="submit" class="button"> Add new Tag </button>

                    </p>
                </form>
            </div>

            <br />
        </div>
        <div id="col-right">
            <div class="col-wrap">
                <?php

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
                    if (!$name) {
                        echo "<div class='notice notice-error'>name is required</div>";
                    } else {

                        $res = $tags->insert(['name' => $name, 'title' => $title, 'type' => $type, 'params' => $params]);
                        if (isset($res))
                            echo "<div class='notice notice-success'> value submitted successfully</div>";
                        else
                            echo "<div class='notice notice-error'>Error in save value</div>";
                    }
                }

                // list of prices
                $results = $tags->all();
                if (!$results) {
                    echo '
                        <p class="update-nag notice notice-warning inline">
                                No Key Entered until now, please <b>Add new tag</b> to show here
                                </p>
                        ';
                } else {
                    $flg = true;

                    echo '<table style="  border-collapse: collapse;width: 100%;" >
                            <tr style="border: 1px solid #dddddd;padding: 8px;background:#fff">
                            <th style="border: 1px solid #dddddd;padding: 8px;">id</th>
                            <th style="border: 1px solid #dddddd;padding: 8px;">name</th>
                            <th style="border: 1px solid #dddddd;padding: 8px;">type</th>
                            <th style="border: 1px solid #dddddd;padding: 8px;">title</th>
                            <th style="border: 1px solid #dddddd;padding: 8px;">preview</th>
                            <th style="border: 1px solid #dddddd;padding: 8px;">Delete</th>
                            </tr>';
                    $data = [];
                    $labels = [];

                    foreach ($results as $result) {

                        echo '<tr style="background:#f9f9f9">
                                <td style="border: 1px solid #dddddd;padding: 8px;">' . $result->id  . '</td>
                                <td style="border: 1px solid #dddddd;padding: 8px;">' . $result->name  . '</td>
                                <td style="border: 1px solid #dddddd;padding: 8px;">' . $result->type  . '</td>
                                <td style="border: 1px solid #dddddd;padding: 8px;">' . $result->title  . '</td>
                                <td style="border: 1px solid #dddddd;padding: 8px;">' . renderTagInput($result) . '</td>
                                <td style="border: 1px solid #dddddd;padding: 8px;"> 
                                    <form method="post" onsubmit="return confirm(\'Are you sure to DELETE?\') ">
                                        <input type="hidden" name="delete" value="' . $result->id  . '" />
                                        <button class="button" style="background:#ffaaaa" >Delete</button>
                                    </form>
                            </td>

                            </tr> ';
                    }
                    echo '</table>   

    </div>';
                }
                ?>
            </div>
        </div>