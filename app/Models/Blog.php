<?php
/**
 * @author Ruben Ramirez Rivera
 */

 namespace App\Models;

 use Illuminate\Database\Eloquent\Model;

 class Blog extends Model{
     protected $table = 'blog';

     public function getComments(){
         return $this->hasMany(Comments::class);
     }
 }

?>