<?php
/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_LoginAutomatico
 */

class ThiagoSantos_LoginAutomatico_Helper_Data extends Mage_Core_Helper_Abstract {

      public function encrypt($customer, $urlencode = false) {

            //montar hash
            $id = $customer -> getId();
            $email = $customer -> getEmail();
            $crypt = Mage::getModel('core/encryption');
            return $crypt -> encrypt($id . ';' . $email);
      }

      public function decrypt($d) {
      	
            $crypt = Mage::getModel('core/encryption');
            return $crypt -> decrypt($d);

      }

}
