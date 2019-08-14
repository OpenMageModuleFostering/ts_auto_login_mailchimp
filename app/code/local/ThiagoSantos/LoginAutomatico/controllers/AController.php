<?php
/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_LoginAutomatico
 *
 * Login Automatico
 * Expectativa de URL:
 * http://www.xxxxxx.com.br/la/a/do/?c=7iz3Sh0HXU6jxm2Y%2BBK%2BuXOfUSZylG7mZ7ngoZab1Y4%3D&___store=default&r=http://xxxx.com.br/
 * @param c             Equivale ao codigo criptografado pelo Helper do LoginAutomatico
 * @param ___store      Store id
 * @param r             Se necessário, url para redirecionar o usuário caso seja necessário
 * @todo    Parametros do Google Analytics no pelo sistema.
 *
 */

class ThiagoSantos_LoginAutomatico_AController extends Mage_Core_Controller_Front_Action {

      private $__getVars = array('c',     //hash 
                                 'r',     //redirect url
                                 '___store'); //store id

      protected function _getSession() {
            return Mage::getSingleton('customer/session');
      }

      /**
       * Delega para o accessAction
       */
      public function doAction() {

            $this -> accessAction();

      }

      public function _redirect($url,$params) {
            header('location: ' . $url);
      }

      /**
       * Converte o parametro, loga o usuário, redireciona para o /checkou/cart com o 
       * carrinho abandonado
       */
      public function accessAction() {

            //
            $session = $this -> _getSession();

            $id = $this -> getRequest() -> getParam('c');
            $url = $this -> getRequest() -> getParam('r');
            $store = $this -> getRequest() -> getParam('___store');

            
            if (!isset($id)) {
                  $session -> addError('Parametro inválido ou usuário inexistente.');
                  $this -> _redirect('/');
            }

            //0 - id //1 - email
            $csv = Mage::helper('loginautomatico') -> decrypt($id);
            $_customerinfo = explode(';', $csv);
            
            $customer = Mage::getModel('customer/customer') -> load($_customerinfo[0]);

            //Verifica se o email bate com o email que foi criptografado
            if (!$customer -> getId() || $customer -> getEmail() != trim($_customerinfo[1])) {
                  $session -> addError('Usuário inválido ou inexistente');
                  $this -> _redirect('/');
            }
            $session = Mage::getSingleton('customer/session');
            //logout - necessário!
            $session -> logout();
            //logando novamente
            $session -> setCustomer($customer);

            //Campanhas do Google Analytics
            //redirect
            //?utm_source=carrinho_abandonado&utm_medium=email&utm_campaign=Carrinho%2BAbandonado
           
            //$_params = array('_query' => array(     'utm_source' => 'mailchimp', 'utm_medium' => 'email', 'utm_campaign' => 'Login%2BAutomatico', '___store' => $store, ));
            $_params = array();
            if (isset($url)) {
                  $this -> _redirect($url, $_params);
                  exit();
            }

            $this -> _redirect("/", $_params);
             exit();
      }

}
