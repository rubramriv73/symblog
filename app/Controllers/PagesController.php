<?php
/**
 * @author Ruben Ramirez Rivera
 */

 namespace App\Controllers;

 class PagesController extends BaseController{
    public function aboutAction() {
        return $this->renderHTML('pages/about.html.twig');
    }

    public function contactAction() {
        return $this->renderHTML('pages/contact.html.twig');
    }

    public function contactActionSend($request) {
        $postData = array();
        if ($request->getMethod() == 'POST') {
            $postData = $request->getParsedBody();   
        }
        return $this->renderHTML('pages/contactSend.txt.twig', $postData);
    }
 }
?>