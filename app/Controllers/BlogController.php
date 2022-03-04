<?php
/**
 * @author Ruben Ramirez Rivera
 */

 namespace App\Controllers;

 use App\Models\Blog;

 class BlogController extends BaseController{
    public function addBlogAction(){}

     public function showBlogAction($request){
         $uri = explode('/', $request->getRequestTarget());
         $id = end($uri);

         $blog = Blog::find($id);
         $comments = $blog->getComments();
         return $this->renderHTML('pages/showBlog.html.twig',[
         'blog' => $blog,
         'comments' => $comments
         ]);
     }
 }
?>