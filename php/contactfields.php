<?php
//Load categories from config.json
$filecontent = file_get_contents(OGC_CONFIGFILE_PATH);
$configData = json_decode($filecontent);
//Array of all fields
$contactfields = $configData->contactfields;
?>
<form method="POST" action="<?php echo OGC_PLUGIN_PATH_CONTACTFIELDS; ?>">
    <input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF; ?>">
    <?php for ($i = 0; $i < count($contactfields); $i++) { ?>
        <div class="form-group">
            <label></label>
            <input type="text" class="form-control" id="contactfield<?php echo $i; ?>" name="contactfield<?php echo $i; ?>" value="<?php echo $contactfields[$i]; ?>" />
        </div>
    <?php } ?>
    <div id="contactfieldcontainer"></div>
    <input type="submit" name="submit" class="btn btn-success" value="<?php echo $L->g("save"); ?>">
</form>

<script>
    //Create new empty contactfield
    $(document).ready(function() {
        createNext(<?php echo count($contactfields); ?>);
    });


    function createNext(index) {
        //If new contactfield does not exist and the input element before
        //has some value
        if ($('#contactfield' + index).length == 0 &&
            $('#contactfield' + (index - 1)).val().length > 0) {
            // Create new input field
            var next = $('<div>', {
                class: 'form-group'
            });
            next.append($('<label>', {}))

            var input = $('<input>', {
                type: 'text',
                class: 'form-control',
                name: 'contactfield' + (index + 1),
                id: 'contactfield' + index
            })
            input.on('keypress', function() {
                createNext(index + 1)
            });

            next.append(input)
            next.appendTo($('#contactfieldcontainer'));
        }
    }
</script>