<?php
/**
 * Ajax interface to xoadGetMeta
 *
 * <p>Example:</p>
 * <code>
 * <?php
 *
 * class AMClass implements AMAjax {
 *   public classMethod() {
 *     retunr "HELLO WORLD! =D";
 *   }
 *   public function xoadGetMeta() {
 *      XOAD_Client::mapMethods($this, array('classMethod'));
 *      XOAD_Client::publicMethods($this, array('classMethod'));
 *   }
 * }
 *
 * ?>
 * </script>
 * </code>
 *
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @access public
 * @version 1.0
 * @package AMADIS
 * @subpackage Core
 *
 */

interface AMAjax {
  public function xoadGetMeta();
}