<?php

class MyModPaymentDisplayPaymentController
{
	public function __construct($module, $file, $path)
	{
		$this->file = $file;
		$this->module = $module;
		$this->context = Context::getContext();
		$this->_path = $path;
	}

	public function run($params)
	{
		$api_url = Configuration::get('MYMOD_API_URL');
		$api_credentials_id = Configuration::get('MYMOD_API_CRED_ID');
		$api_credentials_salt = Configuration::get('MYMOD_API_CRED_SALT');
		$total_to_pay = (float)$this->context->cart->getOrderTotal(true, Cart::BOTH);
		$id_cart = $this->context->cart->id;
		$payment_token = md5($api_credentials_salt.$id_cart.$total_to_pay);

		$validation_url = $this->context->link->getModuleLink('mymodpayment', 'validationapi');
		$shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
		$return_url = Tools::getShopProtocol().$shop->domain.$shop->getBaseURI();
		$cancel_url = Tools::getShopProtocol().$shop->domain.$shop->getBaseURI();

		$this->context->smarty->assign('api_url', $api_url);
		$this->context->smarty->assign('api_credentials_id', $api_credentials_id);
		$this->context->smarty->assign('total_to_pay', $total_to_pay);
		$this->context->smarty->assign('id_cart', $id_cart);
		$this->context->smarty->assign('payment_token', $payment_token);

		$this->context->smarty->assign('validation_url', $validation_url);
		$this->context->smarty->assign('return_url', $return_url);
		$this->context->smarty->assign('cancel_url', $cancel_url);

		$this->context->controller->addCSS($this->_path.'views/css/mymodpayment.css', 'all');
		return $this->module->display($this->file, 'displayPayment.tpl');
	}
}
