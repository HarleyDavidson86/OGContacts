<?php

class ogcontacts extends Plugin
{
  public function init()
  {
    //define constants
    $pluginName = Text::lowercase(__CLASS__);
    $url = HTML_PATH_ADMIN_ROOT . 'plugin/' . $pluginName;
    define('OGC_PLUGIN_PATH', $url);
    define('OGC_PLUGIN_PATH_CATEGORIES', $url . '?categories');
    define('OGC_PLUGIN_PATH_CONTACTFIELDS', $url . '?fields');
    define('OGC_PLUGIN_PATH_ADDNEW', $url . '?addnew');
    define('OGC_PLUGIN_PATH_EDIT', $url . '?edit');
    define('OGC_PLUGIN_PATH_DELETE', $url . '?delete');

    define('OGC_LISTVIEW', !isset($_GET['categories']) && !isset($_GET['fields']));
    define('OGC_CATEGORIESVIEW', isset($_GET['categories']));
    define('OGC_FIELDSVIEW', isset($_GET['fields']));
    define('OGC_NEWCONTACTVIEW', isset($_GET['addnew']));
    define('OGC_EDITVIEW', isset($_GET['edit']));
    define('OGC_DELETECONTACT', isset($_GET['delete']));

    define('OGC_CONFIGFILE_PATH', $this->phpPath() . 'data/config.json');
    define('OGC_CONTACTSFILE_PATH', $this->phpPath() . 'data/contacts.json');
    define('OGC_DEFAULT_CARD', $this->phpPath() . 'layout/contactcard.php');
  }

  public function adminView()
  {
    global $L;
    global $security;
    $tokenCSRF = $security->getTokenCSRF();

    echo '<ul class="nav nav-tabs">';
    echo  '<li class="nav-item">';
    echo  '<a class="nav-link ' . (OGC_LISTVIEW ? 'active' : '') . '" href="' . OGC_PLUGIN_PATH . '">' . $L->g("contactlist") . '</a>';
    echo  '</li>';
    echo  '<li class="nav-item">';
    echo  '<a class="nav-link ' . (OGC_CATEGORIESVIEW ? 'active' : '') . '" href="' . OGC_PLUGIN_PATH_CATEGORIES . '">' . $L->g("categories") . '</a>';
    echo  '</li>';
    echo  '<li class="nav-item">';
    echo  '<a class="nav-link ' . (OGC_FIELDSVIEW ? 'active' : '') . '" href="' . OGC_PLUGIN_PATH_CONTACTFIELDS . '">' . $L->g("contactfields") . '</a>';
    echo  '</li>';
    echo  '</ul>';
    if (OGC_LISTVIEW) {
      if (OGC_NEWCONTACTVIEW || OGC_EDITVIEW) {
        include($this->phpPath() . 'php/addnew.php');
      } else {
        include($this->phpPath() . 'php/list.php');
      }
    }
    if (OGC_CATEGORIESVIEW) {
      include($this->phpPath() . 'php/categories.php');
    }
    if (OGC_FIELDSVIEW) {
      include($this->phpPath() . 'php/contactfields.php');
    }
  }

  //Function for handling crud operations
  public function adminController()
  {
    if ($_POST['submit']) {
      //Load config json
      $filecontent = file_get_contents(OGC_CONFIGFILE_PATH);
      $configData = json_decode($filecontent);
      if (OGC_CATEGORIESVIEW || OGC_FIELDSVIEW) {
        if (OGC_CATEGORIESVIEW) {
          //Put categories in list
          $categoryList = array();
          //We limit this to max 20 categories
          for ($i = 0; $i < 20; $i++) {
            if (isset($_POST['category' . $i])) {
              //Empty inputfields are ignored
              if (strlen($_POST['category' . $i]) > 0) {
                array_push($categoryList, $_POST['category' . $i]);
              }
            }
          }
          //Replace categories with new ones
          $configData->categories = $categoryList;
        } else {
          //Put fields in list
          $contactfieldsList = array();
          //We limit this to max 20 fields
          //Field 0 is always name. We ignore this.
          for ($i = 1; $i < 20; $i++) {
            if (isset($_POST['contactfield' . $i])) {
              //Empty inputfields are ignored
              if (strlen($_POST['contactfield' . $i]) > 0) {
                array_push($contactfieldsList, $_POST['contactfield' . $i]);
              }
            }
          }
          //Replace categories with new ones
          $configData->contactfields = $contactfieldsList;
        }
        //Save configfile
        $jser = json_encode($configData, JSON_PRETTY_PRINT);
        file_put_contents(OGC_CONFIGFILE_PATH, $jser);
      } else {
        //User Actions
        include $this->phpPath() . './php/OGCHelper.php';
        //Load data json
        $filecontent = file_get_contents(OGC_CONTACTSFILE_PATH);
        $contactData = json_decode($filecontent);

        if (isset($_POST['id'])) {
          //We have an id: Update
          $newuser = array();
          $newuser['id'] = $_POST['id'];
          $newuser['name'] = $_POST['name'];
          $newuser['category'] = $_POST['category'];
          foreach ($configData->contactfields as $field) {
            $fieldid = OGCHelper::toId($field);
            $newuser[$fieldid] = $_POST[$fieldid];
          }
          //Find User with id in db and replace
          for ($i = 0; $i < count($contactData->contacts); $i++) {
            if ($contactData->contacts[$i]->id == $_POST['id']) {
              $replacement = array($i => $newuser);
              $contactData->contacts = array_replace($contactData->contacts, $replacement);
              break;
            }
          }
        } else {
          //We have no id: New User
          $newuser = array();
          $newuser['id'] = 1;
          //Generate new id (current max id + 1) if there are contacts already
          if (count($contactData->contacts) > 0) {
            $newuser['id'] = max(array_column($contactData->contacts, 'id')) + 1;
          }
          $newuser['name'] = $_POST['name'];
          $newuser['category'] = $_POST['category'];
          foreach ($configData->contactfields as $field) {
            $fieldid = OGCHelper::toId($field);
            $newuser[$fieldid] = $_POST[$fieldid];
          }
          //Add to data
          array_push($contactData->contacts, $newuser);
        }
        //Save datafile
        $jser = json_encode($contactData, JSON_PRETTY_PRINT);
        file_put_contents(OGC_CONTACTSFILE_PATH, $jser);
      }
    }
    if (OGC_DELETECONTACT) {
      //Load data json
      $filecontent = file_get_contents(OGC_CONTACTSFILE_PATH);
      $contactData = json_decode($filecontent);
      $contacts = $contactData->contacts;
      //Find User with id in db and replace
      $newcontacts = array();
      foreach ($contacts as $contact) {
        if ($contact->id != $_GET['id']) {
          array_push($newcontacts, $contact);
        }
      }
      $contactData->contacts = $newcontacts;
      //Save datafile
      $jser = json_encode($contactData, JSON_PRETTY_PRINT);
      file_put_contents(OGC_CONTACTSFILE_PATH, $jser);
    }
  }

  //Show link to plugin in admin sidebar for fast access
  public function adminSidebar()
  {
    $pluginName = Text::lowercase(__CLASS__);
    $url = HTML_PATH_ADMIN_ROOT . 'plugin/' . $pluginName;
    $html = '<a id="current-version" class="nav-link" href="' . $url . '">OG Contacts</a>';
    return $html;
  }

  public function pageBegin()
  {
    //Replace placeholder with contact
    global $page;
    $newcontent = preg_replace_callback(
      '/CONTACT{(?<ID>[0-9]+)(?<NAME>.*)}/',
      'replaceContactCard',
      $page->content()
    );
    global $page;
    $page->setField('content', $newcontent);
  }
};

function replaceContactCard($matches)
{
  include 'php/OGCHelper.php';
  $id = $matches[1];
  $html = file_get_contents(THEME_DIR . 'contactcard.php');
  if (!$html) {
    //Load default layout
    $html = file_get_contents(OGC_DEFAULT_CARD);
  }
  //Load data
  //Load data json
  $filecontent = file_get_contents(OGC_CONTACTSFILE_PATH);
  $contactData = json_decode($filecontent);
  $contacts = $contactData->contacts;

  foreach ($contacts as $contact) {
    if ($contact->id == $id) {
      $filecontent = file_get_contents(OGC_CONFIGFILE_PATH);
      $configData = json_decode($filecontent);
      $fields = $configData->contactfields;
      //Replace placeholder
      $html = str_replace('{{name}}', $contact->name, $html);
      foreach ($fields as $field) {
        $fieldid = OGCHelper::toId($field);
        $html = str_replace('{{' . $fieldid . '}}', $contact->$fieldid, $html);
      }
      break;
    }
  }
  return $html;
}
