<?php
defined('ABSPATH') || exit;
$keys = loadModel('Keys');
$keyVal = loadModel('KeyVal');
$tags = loadModel('Tags');
$tagValue = loadModel('TagValue');
$tagList = $tags->all();
$tagListFile = $tags->find(['type' => 'file']);
?>
<div class="wrap">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <h1 class="wp-heading-inline">Key Val Manager </h1>
    <div>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" placeholder="<?= get_option('valueLabel') ?>" name="value" />
            <select name="key_id">
                <option value="">select key</option>

                <?php foreach ($keys->all() as $key => $value) : ?>
                    <option value=" <?= $value->id ?>"> <?= $value->title ?> </option>
                <?php endforeach ?>

            </select>
            <button type="submit" name="submit" value="submit" class="button"> Add new Val + </button>
            <?php if ($tagList) : ?>
                <br />
                <?php foreach ($tagList as $key => $value) : $value->fieldName = "tags[{$value->id}]" ?>
                    <label><b> <?= $value->title ?> </b>:</label>
                    <?= renderTagInput($value) ?>
                <?php endforeach ?>

            <?php endif; ?>
        </form>
    </div>
    <br />


    <?php
    if (isset($_POST['delete']) && $_POST['delete']) {
        $keyVal->delete($_POST['delete']);
        echo "<div class='notice'>Value is deleted</div>";
    }



    if (isset($_POST['submit'])) {
        $value = $_POST['value'];
        $key = $_POST['key_id'];
        $tags = $_POST['tags'];

        
        if (!$key) {
            echo "<div class='notice notice-error'>key is required</div>";
        } else {
            $res = $keyVal->insert(['val' => $value, 'key_id' => $key]);
            if (isset($res)) {

                if ($tags){
                    foreach($tagListFile as $key => $val){
                        $uploadRes = upload($val->name);
                        
                        $tags[$val->id] = $uploadRes[0]? $uploadRes[1] : $tags[$val->id];
                    }
                    $tagValue->insertBulk($res->id, $tags);
                }
                echo "<div class='notice notice-success'> value submitted successfully</div>";
            } else {

                echo "<div class='notice notice-error'>Error in save value</div>";
            }
        }
    }

    // list of values
    $results = $keyVal->list();
    if (!$results) {
        echo '
     <p class="update-nag notice notice-warning inline">
              No Key Val Entered until now, please <b>Add new Val +</b> to show here
            </p>
    ';
    } else {
        $flg = true;

        echo '<table style="  border-collapse: collapse;width: 100%;" >
        <tr style="border: 1px solid #dddddd;padding: 8px;background:#fff">
          <th style="border: 1px solid #dddddd;padding: 8px;">id</th>
          <th style="border: 1px solid #dddddd;padding: 8px;">' . get_option('valueLabel') . '</th>
          <th style="border: 1px solid #dddddd;padding: 8px;">key</th>
          <th style="border: 1px solid #dddddd;padding: 8px;">tags</th>
          <th style="border: 1px solid #dddddd;padding: 8px;">creator</th>
          <th style="border: 1px solid #dddddd;padding: 8px;">date</th>
          <th style="border: 1px solid #dddddd;padding: 8px;">actions</th>
        </tr>';
        $data = [];
        $labels = [];
        foreach ($results as $result) {
            $creator = $result['created_by'] ? get_user_by('ID', $result['created_by']) : '';

            $data[$result['title']][] = $result['val'];
            $labels[] = date("Y-m-d", strtotime($result['created_at']));
            echo '<tr style="background:' . ($flg ? '#faf4ff' : '#f9f9f9') . '">
                <td style="border: 1px solid #dddddd;padding: 8px;">' . $result['vid']  . '</td>
                <td style="border: 1px solid #dddddd;padding: 8px;">' . $result['val']  . ($flg ? ' (فعال)' : '') . ' </td>
                <td style="border: 1px solid #dddddd;padding: 8px;">' . $result['title']  . '</td>
                <td style="border: 1px solid #dddd99;padding: 8px;">' .
                (isset($result['tags_print']) && is_array($result['tags_print'])
                    ? implode(',', $result['tags_print'])
                    : ''
                )
                . '</td>
                <td style="border: 1px solid #dddddd;padding: 8px;">' . ($creator->user_nicename ?? $result['created_type'] ?? '') . '</td>
                <td style="border: 1px solid #dddddd;padding: 8px;">' . $result['created_at']  . '</td>
                
                <td>
           
                <form method="post" onsubmit="return confirm(\'Are you sure to DELETE?\') ">
                <input type="hidden" name="delete" value="' . $result['vid']  . '" />
                <button class="button" style="background:#ffaaaa" >Delete</button>
                </form>
                </td>
                </tr>
                ';

            $flg = false;
        }
        echo '</table>   
     <div>
      <canvas id="myChart"></canvas>
     </div>
    </div>';

        //chart
        $labels = "'" . implode("','", array_reverse($labels)) . "'";
        $dataset = [];
        foreach ($data as $key => $value)
            $dataset[] = ['label' => "# $key", "data" => $value];
        $dataset = json_encode($dataset);

        echo '<script>
            const ctx = document.getElementById("myChart");

            new Chart(ctx, {
            type: "line",
            data: {
                labels: [' . $labels . '],
                datasets: ' . $dataset . '
            },
            options: {
                scales: {
                y: {
                    beginAtZero: true
                }
                }
            }
            });
        </script>';
    }
