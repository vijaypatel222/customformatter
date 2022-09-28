<?php

namespace Drupal\custom_formatter;

use Cocur\Slugify\Slugify;

/**
* Class CocurSlugify
* @package Drupal\custom_formatter\Services
*/
class CocurSlugify implements CocurSlugifyInterface {

  /**
   * The slugify object.
   */
  protected $obj_slugify;

   /**
   * Create an instance of Slugify.
   */
  public function __construct() {
    $this->obj_slugify = new Slugify();
  }


  public function Convert($value,$separator) {
    if(isset($separator)&&!empty($separator)){
        //$slugify = new Slugify();
        $slug = $this->obj_slugify->slugify($value,$separator);
    }else{
        //$slugify = new Slugify();
        $slug = $this->obj_slugify->slugify($value);    
    }
    return $slug;
  }


}
