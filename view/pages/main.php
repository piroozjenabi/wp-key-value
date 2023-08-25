<?php
defined('ABSPATH') || exit;
$keys = loadModel('Keys');
$keyVal = loadModel('KeyVal');
$tags = loadModel('Tags');
$tagValue = loadModel('TagValue');
$tagList = $tags->all();
$tagListFile = $tags->find(['type' => 'file']);

$tagService = loadService('TagService');

?>

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

            if ($tags) {
                foreach ($tagListFile as $key => $val) {
                    $uploadRes = upload($val->name);

                    $tags[$val->id] = $uploadRes[0] ? $uploadRes[1] : $tags[$val->id];
                }
                $tagValue->insertBulk($res->id, $tags);
            }
            echo "<div class='notice notice-success'> value submitted successfully</div>";
        } else {

            echo "<div class='notice notice-error'>Error in save value</div>";
        }
    }
}

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="wrap">
    <h1 class="wp-heading-inline">Key Val Manager </h1>
    <div>

        <div class="columns">
            <div class="column is-3 is-12-mobile ">
                <div class="box">
                    <form action="" method="post" enctype="multipart/form-data">

                        <div class="field">
                            <label class="label"><?= get_option('valueLabel') ?></label>
                            <div class="control">
                                <input class="input " type="text" placeholder="<?= get_option('valueLabel') ?>" name="value" />
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">key</label>
                            <div class="control">
                                <select class="input select" name="key_id">
                                    <option value="">select key</option>

                                    <?php foreach ($keys->all() as $key => $value) : ?>
                                        <option value=" <?= $value->id ?>"> <?= $value->title ?> </option>
                                    <?php endforeach ?>

                                </select>
                            </div>
                        </div>
                        <?php if ($tagList) : ?>
                            <?php foreach ($tagList as $key => $value) : $value->fieldName = "tags[{$value->id}]" ?>
                                <div class="field">
                                    <label class="label"><?= $value->title ?></label>
                                    <div class="control">
                                        <?= $tagService->renderGet($value) ?>
                                    </div>
                                </div>
                            <?php endforeach ?>

                        <?php endif; ?>
                        <button type="submit" name="submit" value="submit" class="button is-primary is-fullwidth is-rounded">
                            Submit <span class="dashicons dashicons-saved"></span> </button>
                    </form>
                </div>


                </form>
            </div>
            <div class="column">
                <div class="box">
                    <div class="tabs is-small is-toggle" id="tabs">
                        <ul>
                            <li class="is-active" data-tab="1">
                                <a>
                                    <span class="dashicons dashicons-editor-table"></span>
                                    <span>Data</span>
                                </a>
                            </li>
                            <li data-tab="2">
                                <a>
                                    <span class="dashicons dashicons-chart-bar"></span>
                                    <span>Chart</span>
                                </a>
                            </li>

                        </ul>
                    </div>

                    <div id="tab-content">
                        <div class="is-active table-container" data-content="1">
                            <?php
                            // list of values
                            $results = $keyVal->list(100, null, null, false);
                            
                            if (!$results) {
                                echo '
                                <p class="update-nag notice notice-warning inline">
                                    No Key Val Entered until now, please <b>Add new Value</b> to show here
                                </p>
                                ';
                            } else {
                                $flg = true;

                                echo '<table class="table is-bordered is-striped is-fullwidth is-narrow is-hoverable">
                                <tr>
                                    <th >id</th>
                                    <th >' . get_option('valueLabel') . '</th>
                                    <th >key</th>
                                    <th >tags</th>
                                    <th >creator</th>
                                    <th >date</th>
                                    <th >actions</th>
                                </tr>';
                                $data = [];
                                $labels = [];
                                
                                foreach ($results as $result) {
                                    kvdd($result, 0);
                                    $creator = $result['created_by'] ? get_user_by('ID', $result['created_by']) : '';

                                    $data[$result['title']][] = $result['val'];
                                    $labels[] = date("Y-m-d", strtotime($result['created_at']));
                                    echo '<tr style="' . ($flg ? 'border:1px solid red' : '') . '">
                                            <td >' . $result['vid'] . '</td>
                                            <td >' . $result['val'] . ($flg ? ' (فعال)' : '') . ' </td>
                                            <td >' . $result['title'] . '</td>
                                            <td >' .
                                        (isset($result['tags_print']) && is_array($result['tags_print'])
                                            ? implode(',', $result['tags_print'])
                                            : ''
                                        )
                                        . '</td>
                                        <td >' . ($creator->user_nicename ?? $result['created_type'] ?? '') . '</td>
                                        <td >' . $result['created_at'] . '</td>

                                        <td>

                                            <form method="post" onsubmit="return confirm(\'Are you sure to DELETE?\') ">
                                                <input type="hidden" name="delete" value="' . $result['vid']  . '" />
                                                <button class="button is-danger" >Delete</button>
                                            </form>
                                        </td>
                        </tr>
                        ';
                                    $flg = false;
                                }
                                echo ' </table>'; ?>
                        </div>
                        <div data-content="2">

                            <canvas id="myChart" style="width:100%"></canvas></canvas>
                        <?php
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
                        ?>
                        </div>
                    </div>





                </div>

            </div>

        </div>

    </div>



    <script>
        jQuery(document).ready(function() {
            jQuery('#tabs li').on('click', function() {
                var tab = jQuery(this).data('tab');

                jQuery('#tabs li').removeClass('is-active');
                jQuery(this).addClass('is-active');

                jQuery('#tab-content div').removeClass('is-active');
                jQuery('div[data-content="' + tab + '"]').addClass('is-active');
            });
        });
    </script>
    <style>
        #tab-content div {
            display: none;
        }

        #tab-content div.is-active {
            display: block;
        }
    </style>