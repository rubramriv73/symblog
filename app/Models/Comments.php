<?php
/**
 * @author Ruben Ramirez Rivera
 */

 namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

 class Comments extends Model{
     protected $table = 'comments';

     public function blog(){
         return $this->belongsTo(Blog::class);
     }
 }

?>