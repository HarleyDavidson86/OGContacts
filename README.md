# OGContacts
Contact management plugin for bludit to manage contacts and show them on your website

## Setup
After activating this plugin you will find a link to the plugins page in the admin panel.

### Creating categories
In the second tab you can create categories to group contacts.

If you want to delete a category, just empty the input field and hit save.

### Set up contact fields
In the third tab you can create fields which are available later in the contacts form.

If you want to delete a field, just empty the input field and hit save.

Remember to adjust your contactcard.php that the new fields are
shown correctly.

## Set a link in content
To show a contact on a page, just copy the placeholder snippet you see in the contactlist.
This placeholder will be replaced with the contactcard.

## Customize the contact card
The plugin is looking for a file called ``contactcard.php`` in your themes folder. If you want to customize the card, just copy the ``layout/contactcard.php`` in your themes folder and edit it. In this file you have access to the object ``$fields`` and ``$contact`` to access all information you want to place. Just have a look to the default ``contactcard.php``.