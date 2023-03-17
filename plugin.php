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

    define('OGC_LISTVIEW', !isset($_GET['categories']) && !isset($_GET['fields']));
    define('OGC_CATEGORIESVIEW', isset($_GET['categories']));
    define('OGC_FIELDSVIEW', isset($_GET['fields']));

    define('OGC_CONFIGFILE_PATH', $this->phpPath() . 'data/config.json');
    define('OGC_CONTACTSFILE_PATH', $this->phpPath() . 'data/contacts.json');
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
      include($this->phpPath() . 'php/list.php');
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
        //Load config json
        $filecontent = file_get_contents(OGC_CONFIGFILE_PATH);
        $configData = json_decode($filecontent);
        //Replace categories with new ones
        $configData->categories = $categoryList;
        //Save categories
        $jser = json_encode($configData, JSON_PRETTY_PRINT);
        file_put_contents(OGC_CONFIGFILE_PATH, $jser);
      }
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

  public function siteHead()
  {
    //TODO Add CSS
  }




  public function pageBegin()
  {
    //TODO Replace placeholder with contact
    // global $page;
    // $newcontent = preg_replace_callback(
    //   '/\\[% carousel=(.*) %\\]/i',
    //   'runCarouselShortcode',
    //   $page->content()
    // );
    // global $page;
    // $page->setField('content', $newcontent);
  }
};
