<?php
//Load categories from config.json
$filecontent = file_get_contents($this->phpPath() . 'data/config.json');
$configData = json_decode($filecontent);
//Array of all categories
$categories = $configData->categories;
?>
<form method="POST" action="<?php echo OGC_PLUGIN_PATH_CATEGORIES; ?>">
    <input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF; ?>">
    <?php for ($i = 0; $i < count($categories); $i++) { ?>
        <div class="form-group">
            <label></label>
            <input type="text" class="form-control" id="category<?php echo $i; ?>" name="category<?php echo $i; ?>" value="<?php echo $categories[$i]; ?>" />
        </div>
    <?php } ?>
    <div id="categorycontainer"></div>
    <input type="submit" name="submit" class="btn btn-success" value="Speichern">
</form>

<script>
    //Create new empty category field
    $(document).ready(function() {
        createNext(<?php echo count($categories); ?>);
    });


    function createNext(index) {
        //If new category does not exist and the input element before
        //has some value
        if ($('#category' + index).length == 0 &&
            $('#category' + (index - 1)).val().length > 0) {
            // Create new input field
            var next = $('<div>', {
                class: 'form-group'
            });
            next.append($('<label>', {}))

            var input = $('<input>', {
                type: 'text',
                class: 'form-control',
                name: 'category' + (index + 1),
                id: 'category' + index
            })
            input.on('keypress', function() {
                createNext(index + 1)
            });

            next.append(input)
            next.appendTo($('#categorycontainer'));
        }
    }
</script>