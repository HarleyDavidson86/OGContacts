<!-- Category List -->
<table class="table">
    <thead>
        <tr>
            <th scope="col">Name</th>
            <?php
            foreach ($fields as $field) {
                echo '<th scope="col">' . $field . '</th>';
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filteredcontacts as $contact) { ?>
            <tr>
                <td><?php echo $contact->name ?></td>
                <?php
                //Replace placeholder
                foreach ($fields as $field) {
                    $fieldid = OGCHelper::toId($field);
                    echo '<td>'.$contact->$fieldid. '</td>';
                }
                ?>
            </tr>
        <?php } ?>
    </tbody>
</table>
<!-- End -->