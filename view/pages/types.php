<?php
defined('ABSPATH') || exit;
$keys = loadModel('Keys');
$keyVal = loadModel('KeyVal');

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
                        <label class="label">title</label>
                        <div class="control">
                            <input class="input " type="text" placeholder="title" name="title" />
                        </div>
                    </div>
                  
                    <div class="field">
                        <label for="apiKey">
                            <input type="checkbox" name="hasApiKey" value="1" />
                            Has api key?</label>

                    </div>
                    <button type="submit" name="submit" value="submit" class="button is-primary is-fullwidth is-rounded"> Submit <span class="dashicons dashicons-saved"></span> </button>
                </form>
            </div>
        </div>
        <div class="column ">

            <div class="box">
                <?php
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
                    if (!$name) {
                        echo "<div class='notice notice-error'>name is required</div>";
                    } else {

                        $res = $keys->insert(['name' => $name, 'title' => $title, 'api_key' => $api_key]);
                        if (isset($res))
                            echo "<div class='notice notice-success'> value submitted successfully</div>";
                        else
                            echo "<div class='notice notice-error'>Error in save value</div>";
                    }
                }

                // list of prices
                $results = $keys->all();
                if (!$results) {
                    echo '
                    <p class="update-nag notice notice-warning inline">
                            No Key Entered until now, please <b>Add new Key</b> to show here
                            </p>
                    ';
                } else {
                    $flg = true;

                    echo '<table  class="table is-bordered is-striped is-fullwidth is-narrow is-hoverable" >
                        <tr >
                        <th >id</th>
                        <th >name</th>
                        <th >title</th>
                        <th >api key</th>
                        <th >actions</th>
                        </tr>';
                    $data = [];
                    $labels = [];

                    foreach ($results as $result) {

                        echo '<tr >
                        <td >' . $result->id  . '</td>
                        <td >' . $result->name  . '</td>
                        <td >' . $result->title  . '</td>
                        <td >' . $result->api_key  . '</td>
                        <td>
                            <form method="post" onsubmit="return confirm(\'Are you sure to DELETE?\') ">
                            <input type="hidden" name="delete" value="' . $result->id  . '" />
                            <button class="button is-danger" >Delete</button>
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
    </div>