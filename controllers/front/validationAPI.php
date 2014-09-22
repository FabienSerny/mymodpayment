<?php

class MyModPaymentValidationAPIModuleFrontController extends ModuleFrontController
{
	public function postProcess()
	{
		// Check if cart is valid
		$cart = new Cart((int)Tools::getValue('id_cart'));
		if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 ||
			$cart->id_address_invoice == 0 || !$this->module->active)
			$this->returnError('Invalid cart');

		// Check if customer exists
		$customer = new Customer($cart->id_customer);
		if (!Validate::isLoadedObject($customer))
			$this->returnError('Invalid customer');

		$currency = new Currency((int)$cart->id_currency);
		$total_paid = Tools::getValue('total_paid');
		$extra_vars = array('transaction_id' => Tools::getValue('transaction_id'));

		// Build the validation token
		$validation_token = md5(Configuration::get('MYMOD_API_CRED_SALT').Tools::getValue('id_cart').$total_paid.Tools::getValue('transaction_id'));

		// Check validation token
		if (Tools::getValue('validation_token') != $validation_token)
			$this->returnError('Invalid token');

		// Validate order
		$this->module->validateOrder($cart->id, Configuration::get('PS_OS_PAYMENT'), $total_paid, $this->module->displayName.' API', NULL, $extra_vars, (int)$currency->id, false, $customer->secure_key);

		// Redirect on order confirmation page
		$shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
		$return_url = Tools::getShopProtocol().$shop->domain.$shop->getBaseURI();
		$this->returnSuccess($return_url.'index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='.$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
	}

	public function returnError($result)
	{
		echo json_encode(array('error' => $result));
		exit;
	}

	public function returnSuccess($result)
	{
		echo json_encode(array('return_link' => $result));
		exit;
	}
}
