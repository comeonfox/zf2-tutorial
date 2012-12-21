<?php
namespace Blog\View\Helper;
use Zend\View\Helper\AbstractHelper;

class TidyHtml extends AbstractHelper
{
    public function __invoke($userHtml){
        $config = array(
                   'indent'         => true,
                   'output-xhtml'   => true,
                   'wrap'           => 200);

        $userHtml = '<div >'.$userHtml.'</div>';
        $tidy = new \tidy();
        $tidy->parseString($userHtml,$config,'utf8');

        $tidy->cleanRepair();
        $dom = new \DOMDocument();
        $dom->loadHTML($tidy);

        $node = $dom->getElementsByTagName("div");
        $newdoc = new \DOMDocument();
        $cloned = $node->item(0)->cloneNode(TRUE);
        $newdoc->appendChild($newdoc->importNode($cloned,TRUE));
        return $newdoc->saveHTML();
    }
}
