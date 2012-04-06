<?php
/**
 * Shortlink - A short to long links translator
 * @version 2.2
 * @copyright (C) 2004 by Tobias Gesellchen, www.gesellix.de - All rights reserved!
 * thanks to Daniel Janesch (admin@the-deejay.com, www.the-deejay.com)
 */

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Shortlink table class
 */
class mosShortlink extends mosDBTable
{
  /** @var int Primary key */
  var $id=null;
  /** @var string */
  var $phrase=null;
  /** @var string */
  var $link=null;
  /** @var string */
  var $target=null;
  /** @var string */
  var $counter=null;
  /** @var string */
  var $description=null;
  /** @var date */
  var $create_date=null;
  /** @var date */
  var $last_call=null;

  /**
   * @param database A database connector object
   */
  function mosShortlink( &$db )
  {
    $this->mosDBTable( '#__shortlink', 'id', $db );
  }
}

// HTML-Object class. A very simple HTML parser written for the Mambo/Joomla component Shortlink
//
// Copyright 2004 NetPresent.net (Autolinks)
//
// This class may be used freely for any purpose, but may not be changed in any way without
// explicit written permission by NtePresent.net.
class HTML_Object
{
  var $html;
  var $elems;
  var $noclosetags;

  function HTML_Object($htm)
  {
    $this->html = $htm;
    $this->elems = array();
    // noclosetags not used yet. to be used to track tags path per elem.
    //$this->noclosetags = explode(",","META,BR,HR,BASE");
  }

  // the number of tags of type $tag by which the content of elem[$eid] is surrounded
  function surroundedBy($eid, $tag)
  {
    if (!sizeof($this->elems[$eid]))
      return 0;
    $tag = strtoupper($tag);
    $surrounded = 0;
    foreach ($this->elems as $key=>$elem)
    {
      if ($key <= $eid)
      {
        if ($elem[type] == $tag)
          $surrounded++;

        // little trick to correct syntax errors. when a tag is closed before without ever having been opened.
        // surrounded will never be < 0;
        elseif (($elem[type] == "/$tag") && ($surrounded))
          $surrounded--;
      }
    }

    return max($surrounded,0);
  }

  // Fill the elems array with the html elements
  function parseHTML()
  {
    $leftsplit = explode("<", $this->html);
    $i = 0;
    foreach ($leftsplit as $key=>$opened)
    {
      // if ($key) is a trick to find what is before the first tag is opened.
      if ($key)
      {
        $rightsplit = explode(">", $opened);

        // $closed is a tag that was closed including its attributes
        $closed = $rightsplit[0];
        $typesplit = explode(" ", $closed);

        // type is P, BR, IMG, etc etc
        $type = strtoupper($typesplit[0]);

        // $content is the actual content of the tag until the opening of the next (maybe sub-)tag
        $content = $rightsplit[1];
        $this->elems[$i][tag] = "<$closed>";
        $this->elems[$i][content] = $content;
        $this->elems[$i][type] = $type;
        $i++;
      }

      // else we found stuff before the first tag was opened. if no length, this is the beginning
      // of the html, so ignore. else it's real text before the first tag is opened.
      elseif (strlen($opened))
      {
        $this->elems[$i][tag] = "";
        $this->elems[$i][content] = $opened;
        $this->elems[$i][type] = "";
        $i++;
      }
    }
  }

  // get the html back, typically after having played around with the tags.
  function getHTML()
  {
    $toreturn = "";
    foreach ($this->elems as $elem)
    {
      $toreturn .= $elem[tag].$elem[content];
    }
    return $toreturn;
  }
}
?>