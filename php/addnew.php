<?php
include 'OGCHelper.php';
//Load categories from config.json
$filecontent = file_get_contents(OGC_CONFIGFILE_PATH);
$configData = json_decode($filecontent);
//Array of all categories
$categories = $configData->categories;
//Array of all contactfields
$contactfields = $configData->contactfields;
?>
<h2 class="my-3"><?php echo  $L->g("create new contact"); ?></h2>
<form method="POST" action="<?php echo OGC_PLUGIN_PATH; ?>">
    <!-- name field -->
    <input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF; ?>">
    <div class="form-group">
        <label><?php echo $L->g("name"); ?></label>
        <input type="text" class="form-control" name="name" value="" />
    </div>
    <!-- category dropdown -->
    <div class="form-group">
        <label for="inputCategory"><?php echo $L->g("category"); ?></label>
        <select id="inputCategory" name="category" class="form-control">
            <?php for ($i = 0; $i < count($categories); $i++) { ?>
                <option><?php echo $categories[$i]; ?></option>
            <?php } ?>
        </select>
    </div>
    <hr class="my-4">
    <!-- Fields -->
    <?php for ($i = 0; $i < count($contactfields); $i++) { ?>
        <div class="form-group">
            <label><?php echo $contactfields[$i]; ?></label>
            <input type="text" class="form-control" name="<?php echo OGCHelper::toId($contactfields[$i]) ?>" value="" />
        </div>
    <?php } ?>
    <input type="submit" name="submit" class="btn btn-success" value="<?php echo $L->g("save"); ?>">
    <a class="btn btn-primary" href="<?php echo OGC_PLUGIN_PATH; ?>"><?php echo $L->g("cancel"); ?></a>
</form>