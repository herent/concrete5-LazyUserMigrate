<?php
defined('C5_EXECUTE') or die("Access Denied.");
$nh = Loader::helper('navigation');
Loader::model('user_list');

$dom = new DOMDocument('1.0', 'UTF-8');

$dnode = $dom->createElement('Users');
$docNode = $dom->appendChild($dnode);

foreach((new UserList())->get(3000) as $user) {
  $ui = UserInfo::getByID($user->getUserID());
  $node = $dom->createElement('User');
  $userNode = $docNode->appendChild($node);

  $node = $dom->createElement('name');
  $cdata = $node->ownerDocument->createCDATASection($user->getUserName());
  $node->appendChild($cdata);
  $userNode->appendChild($node);

  $node = $dom->createElement('email');
  $cdata = $node->ownerDocument->createCDATASection($user->getUserEmail());
  $node->appendChild($cdata);
  $userNode->appendChild($node);

  $node = $dom->createElement('raw_pass');
  $cdata = $node->ownerDocument->createCDATASection($user->getUserPassword());
  $node->appendChild($cdata);
  $userNode->appendChild($node); 

  $node = $dom->createElement('Attributes');
  $attrNode = $userNode->appendChild($node);
  foreach(UserAttributeKey::getAttributes($user->getUserID()) as $key=>$uak) {
    if(is_string($uak)) {
      $node = $dom->createElement($key);
      $cdata = $node->ownerDocument->createCDATASection($uak);
      $node->appendChild($cdata);
      $attrNode->appendChild($node);
    } else {
      // Put special cases for custom attribute objects here
      switch(get_class($uak)) {
      default:
        break;
      }
    }
  }
}

header('Content-type: application/xml');
$xmlOutput = $dom->saveXML();
echo $xmlOutput;
exit;
?>
