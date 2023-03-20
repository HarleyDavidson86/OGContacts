<?php
include 'OGCHelper.php';
//Load categories from config.json
$filecontent = file_get_contents(OGC_CONFIGFILE_PATH);
$configData = json_decode($filecontent);
//Array of all categories
$categories = $configData->categories;
//Array of all contactfields
$contactfields = $configData->contactfields;
if (OGC_EDITVIEW) {
    //Load contacts
    $filecontent = file_get_contents(OGC_CONTACTSFILE_PATH);
    $contactData = json_decode($filecontent);
    //Find contact with id to edit
    foreach ($contactData->contacts as $contact) {
        if ($contact->id == $_GET['id']) {
            $editContact = $contact;
            break;
        }
    }
}
?>
<h2 class="my-3">
    <?php
    if (OGC_NEWCONTACTVIEW) {
        echo  $L->g("create new contact");
    } else {
        echo  $L->g("edit contact");
    }
    ?>
</h2>
<form method="POST" action="<?php echo OGC_PLUGIN_PATH; ?>">
    <!-- name field -->
    <input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $tokenCSRF; ?>">
    <?php if (OGC_EDITVIEW) { ?>
        <input type="hidden" name="id" value="<?php echo $editContact->id; ?>">
    <?php } ?>
    <div class="form-group">
        <label><?php echo $L->g("name"); ?></label>
        <input type="text" class="form-control" name="name" value="<?php echo $editContact->name; ?>" />
    </div>
    <!-- category dropdown -->
    <div class="form-group">
        <label for="inputCategory"><?php echo $L->g("category"); ?></label>
        <select id="inputCategory" name="category" class="form-control">
            <?php for ($i = 0; $i < count($categories); $i++) { ?>
                <option <?php echo $categories[$i]==$editContact->category ? 'selected' : ''; ?>><?php echo $categories[$i]; ?></option>
            <?php } ?>
        </select>
    </div>
    <hr class="my-4">
    <!-- Fields -->
    <?php foreach ($contactfields as $contactfield) { ?>
        <div class="form-group">
            <label><?php echo $contactfield; ?></label>
            <?php $contactFieldId = OGCHelper::toId($contactfield); ?>
            <input type="text" class="form-control" name="<?php echo $contactFieldId ?>" value="<?php echo $editContact->$contactFieldId ?>" />
        </div>
    <?php } ?>
    <input type="submit" name="submit" class="btn btn-success" value="<?php echo $L->g("save"); ?>">
    <a class="btn btn-primary" href="<?php echo OGC_PLUGIN_PATH; ?>"><?php echo $L->g("cancel"); ?></a>
</form>