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
    define('OGC_PLUGIN_PATH_FIELDS', $url . '?fields');

    define('OGC_LISTVIEW', !isset($_GET['categories']) && !isset($_GET['fields']));
    define('OGC_CATEGORIESVIEW', isset($_GET['categories']));
    define('OGC_FIELDSVIEW', isset($_GET['fields']));
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
    echo  '<a class="nav-link ' . (OGC_FIELDSVIEW ? 'active' : '') . '" href="' . OGC_PLUGIN_PATH_FIELDS . '">' . $L->g("contactfields") . '</a>';
    echo  '</li>';
    echo  '</ul>';
    if (OGC_LISTVIEW) {
      echo '<p>List View</p>';
    }
    if (OGC_CATEGORIESVIEW) {
      include($this->phpPath() . 'php/categories.php');
    }
    if (OGC_FIELDSVIEW) {
      echo '<p>Fields View</p>';
    }
  }

  //Function for handling crud operations
  public function adminController()
  {
    if ($_POST['submit']) {
      if (OGC_CATEGORIESVIEW) {
        //Save categories
        echo print_r($_POST);
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
