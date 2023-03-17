<?php
//Load categories from config.json
$filecontent = file_get_contents(OGC_CONTACTSFILE_PATH);
$contactsData = json_decode($filecontent);
//Array of all fields
$contacts = $contactsData->contacts;
?>
<div class="my-3">
    <a class="btn btn-primary" href="<?php echo OGC_PLUGIN_PATH_ADDNEW; ?>"><?php echo $L->g("add"); ?></a>
</div>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"><?php echo $L->g("name"); ?></th>
            <th scope="col"><?php echo $L->g("category"); ?></th>
            <th scope="col"><?php echo $L->g("actions"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < count($contacts); $i++) { ?>
            <tr>
                <th scope="row"><?php echo $contacts[$i]->id; ?></th>
                <td><?php echo $contacts[$i]->name; ?></td>
                <td><?php echo $contacts[$i]->category; ?></td>
                <td>
                    <a href="#"><i class="fa fa-edit"></i><?php echo $L->g("edit"); ?></a>
                    <a href="#" class="text-danger"><i class="fa fa-trash"></i><?php echo $L->g("delete"); ?></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>