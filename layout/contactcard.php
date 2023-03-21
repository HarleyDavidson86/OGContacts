<!-- Contact Card -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?php echo $contact->name ?></h5>
        <?php
        //Replace placeholder
        foreach ($fields as $field) {
            $fieldid = OGCHelper::toId($field);
            echo '<p class="card-text">'.$field.': '.$contact->$fieldid.'</p>';
        }
        ?>
    </div>
</div>
<!-- End -->