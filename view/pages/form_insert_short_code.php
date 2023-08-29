<?php
defined('ABSPATH') || exit;
$keyVal = loadModel('KeyVal');
$tag = loadModel('Tags');
$tagList = $tag->all();
$keys = loadModel('Keys');
$tagValue = loadModel('TagValue');
$tagService = loadService('TagService');


$keyList = $keys->whereIn('name', $data['name']);
if (isset($_POST['submit'])) {
    $value = $_POST['value'];
    $key = $_POST['key_id'];
    $tags = $_POST['tags'];
    if (!$key) {
        echo "<div class='notification is-danger is-light'>key is required</div>";
    } else {
        $res = $keyVal->insert(['val' => $value, 'key_id' => $key]);
        if (isset($res)) {
            if (isset($tags) && $tags)
                $tagValue->insertBulk($res->id, $tags);
            echo "<div class='notification is-primary is-light'> The data has been submitted. </div>";
            if($data['showLookupAfterSubmit']){
                echo do_shortcode('[key_val_search search="'.$res_id.'" ]');
            }
        } else {

            echo "<div class='notification is-danger is-light'>Error in save</div>";
        }
    }
}

if ($keyList) {
?>
    <div class="box">
        <form action="" method="post" enctype="multipart/form-data">

            <div class="field">
                <label class="label"><?= get_option('valueLabel') ?></label>
                <div class="control">
                    <input type="text" class="input" placeholder="<?= get_option('valueLabel') ?>" name="value" />
                </div>
            </div>


            <?php if (count($keyList) > 1) : ?>
                <div class="field">
                    <label class="label">key</label>
                    <div class="control">
                        <select class="input select" name="key_id">
                            <option value="">select key</option>

                            <?php foreach ($keyList as $key => $value) : ?>
                                <option value=" <?= $value->id ?>"> <?= $value->title ?> </option>
                            <?php endforeach ?>

                        </select>
                    </div>
                </div>
            <?php elseif (count($keyList) == 1) : ?>
                <input type="hidden" name="key_id" value="<?= $keyList[0]->id ?>" />
            <?php endif; ?>

            <?php if ($tagList) : ?>
                <?php foreach ($tagList as $key => $value) : $value->fieldName = "tags[{$value->id}]" ?>
                    <?php
                    if (!empty($data['tags']))
                        if (!(strstr($data['tags'], $value->name)))
                            continue;
                    ?>
                    <div class="field" style="<?= $value->is_active ? '' : 'display:none' ?>">
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


	<script>


	jQuery(function() {
		
		var $form = jQuery( "form" );
		var $input = $form.find( "input[type=text]" );

		$input.on( "keyup", function( event ) {
			
			
			// When user select text in the document, also abort.
			var selection = window.getSelection().toString();
			if ( selection !== '' ) {
				return;
			}
			
			// When the arrow keys are pressed, abort.
			if ( jQuery.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
				return;
			}
			
			
			var $this = jQuery( this );
			
			// Get the value.
			var input = $this.val();
			
			var input = input.replace(/[\D\s\._\-]+/g, "");
					input = input ? parseInt( input, 10 ) : 0;

					$this.val( function() {
						return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
					} );
		} );
		
		
	});

</script>
<?php } else { ?>
    <div class='notice notice-error'> key not found! </div>
<?php } ?>
