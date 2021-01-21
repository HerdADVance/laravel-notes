

Static - properties that can be called directly without creating an instance of a class

<?php
class pi {
  public static $value = 3.14159;
}

echo pi::$value; // 3.14159
?>