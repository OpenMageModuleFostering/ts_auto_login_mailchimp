<?php
/**
 * Pereira, Thiago Santos
 * http://thiagosantos.com/
 *
 * @category   ThiagoSantos
 * @package    ThiagoSantos_LoginAutomatico
 */
class ThiagoSantos_LoginAutomatico_Model_Pageload_Observer
{

    public function __construct()
    {

    }

    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    protected function getRequest()
    {
        return Mage::app()->getRequest();
    }

    /**
     * _init manager referer information from the user
     * @param   Varien_Event_Observer $observer
     * @return  ThiagoSantos_LoginAutomatico_Model_Pageload_Observer
     */
    public function _init($observer)
    {

        $session = $this->_getSession();
        $id = $this->getRequest()->getParam('c');
        $url = $this->getRequest()->getParam('r');
        $store = $this->getRequest()->getParam('___store');

        if (isset($id)) {

            //0 - id //1 - email
            $csv = Mage::helper('loginautomatico')->decrypt($id);
            $_customerinfo = explode(';', $csv);


            $customer = Mage::getModel('customer/customer')->load((int)$_customerinfo[0]);

            //Verifica se o email bate com o email que foi criptografado
            if (!$customer->getId() || $customer->getEmail() != trim($_customerinfo[1])) {
                $session->addError('User invalid or does not exist');
                $session->logout();
            }
            else{
                //logout - necessário!
                $session->logout();
                //logando novamente
                $session->setCustomer($customer);
            }
        }
    }

}
