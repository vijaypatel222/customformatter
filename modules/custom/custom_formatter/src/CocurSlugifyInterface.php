<?php

namespace Drupal\custom_formatter;

/**
 * An interface for CocurSlugify.
 */
interface CocurSlugifyInterface {

  /**
   * Convert a text into slug.
   *
   * @return string
   *   The text value.
   */
  public function Convert($string, $separator);

}
