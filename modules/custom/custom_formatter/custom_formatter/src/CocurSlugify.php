<?php

namespace Drupal\custom_formatter;

use Cocur\Slugify\Slugify;

/**
* Class CocurSlugify
* @package Drupal\custom_formatter\Services
*/
class CocurSlugify {

  public function Convert($value,$separator) {
    if(isset($separator)&&!empty($separator)){
        $slugify = new Slugify();
        $slug = $slugify->slugify($value,$separator);
    }else{
        $slugify = new Slugify();
        $slug = $slugify->slugify($value);    
    }
    return $slug;
  }


}