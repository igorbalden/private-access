<?php 

/**
 * In https://github.com/sebastianbergmann/phpunit/issues/3338
 * Sebastian Bergmann said about depricated assertions, 
 * which could test protected/private code:
 * "There is no replacement. The correct course of action is 
 * to refactor your code to not require dirty tricks like this."
 * 
 * Now, here is some dirty code. 
 */
trait PrivateAccess {

  /**
   * I call the protected/private method by
   * $testedObj = new TestedClass();
   * $this->invokeMethod(
   *   $testedObj, 'method_name', [$par1, $par2]
   * );
   * // instead of $testedObj->method_name($par1, $par2) 
   *
   * @param object &$object    Instantiated object that we will run method on.
   * @param string $methodName Method name to call
   * @param array  $parameters Array of parameters to pass into method.
   *
   * @return mixed Method return.
   */
  public function invokeMethod(
    &$object, $methodName, array $parameters = array()
  ) 
  {
    $reflection = new \ReflectionClass(get_class($object));
    $method = $reflection->getMethod($methodName);
    $method->setAccessible(true);
    return $method->invokeArgs($object, $parameters);
  }


  /**
   * I get the protected/private property value by
   * $testedObj = new TestedClass();
   * $this->getPrivateProperty(
   *   $testedObj, 'property_name'
   * );
   * // instead of $testedObj->property_name
   * 
   * @param object &$object  Instantiated object with the private property.
   * @param string $property Property name 
   *
   * @return mixed Property return.
   */
  public function getPrivateProperty(&$object, $property) {
    $reflection = new \ReflectionClass(get_class($object));
    $prop = $reflection->getProperty($property);
    $prop->setAccessible(true);
    return $prop->getValue($object);
  }

}
