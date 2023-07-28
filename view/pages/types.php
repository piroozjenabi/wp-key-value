<?php
defined('ABSPATH') || exit;
$keys = loadModel('Keys');
$keyVal = loadModel('KeyVal');

?>
<div class="wrap">
    <h1 class="wp-heading-inline">Manage Key types </h1>
    <div>
        <form action="" method="post">
            <input type="text" placeholder="name for slug api" name="name" />
            <input type="text" placeholder="title" name="title" />
            <input type="checkbox" name="hasApiKey" value="1" />
            <label for="apiKey">Has api key?</label>
            <button type="submit" name="submit" value="submit" class="button"> Add new Key </button>
        </form>
    </div>
    <br />


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

        echo '<table style="  border-collapse: collapse;width: 100%;" >
        <tr style="border: 1px solid #dddddd;padding: 8px;background:#fff">
          <th style="border: 1px solid #dddddd;padding: 8px;">id</th>
          <th style="border: 1px solid #dddddd;padding: 8px;">name</th>
          <th style="border: 1px solid #dddddd;padding: 8px;">title</th>
          <th style="border: 1px solid #dddddd;padding: 8px;">api key</th>
          <th style="border: 1px solid #dddddd;padding: 8px;">actions</th>
        </tr>';
        $data = [];
        $labels = [];

        foreach ($results as $result) {

            echo '<tr style="background:#f9f9f9">
            <td style="border: 1px solid #dddddd;padding: 8px;">' . $result->id  . '</td>
            <td style="border: 1px solid #dddddd;padding: 8px;">' . $result->name  . '</td>
            <td style="border: 1px solid #dddddd;padding: 8px;">' . $result->title  . '</td>
            <td style="border: 1px solid #dddddd;padding: 8px;">' . $result->api_key  . '</td>
            <td>
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
