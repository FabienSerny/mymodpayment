<?php

class MyModPayment extends PaymentModule
{
	public function __construct()
	{
		$this->name = 'mymodpayment';
		$this->tab = 'payments_gateways';
		$this->version = '0.1';
		$this->author = 'Fabien Serny';
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('MyMod payment');
		$this->description = $this->l('A simple payment module');
	}

	public function install()
	{
		if (!parent::install() ||
			!$this->registerHook('paymentOptions') ||
			!$this->registerHook('displayPayment') ||
			!$this->registerHook('displayPaymentReturn'))
			return false;

		if (!$this->installOrderState())
			return false;

		return true;
	}

	public function getTemplateVarInfos()
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
	}

	public function hookPaymentOptions($params)
	{
		if (!$this->active) {
			return;
		}

		$this->getTemplateVarInfos();

		$payment_options = [];

		$newOption = new \PrestaShop\PrestaShop\Core\Payment\PaymentOption();
		$newOption->setCallToActionText($this->l('Pay by MyModPayment'))
			->setAction($this->context->link->getModuleLink($this->name, 'validation', array(), true));
		$payment_options[] = $newOption;

		$newOption = new \PrestaShop\PrestaShop\Core\Payment\PaymentOption();
		$newOption->setCallToActionText($this->l('Pay by MyModPayment API'))
			->setAction($this->context->link->getModuleLink($this->name, 'validation', array(), true))
			->setAdditionalInformation($this->context->smarty->fetch('module:mymodpayment/views/templates/hook/apiPaymentOptions.tpl'));
		$payment_options[] = $newOption;

		return $payment_options;
	}

	public function installOrderState()
	{
		if (Configuration::get('PS_OS_MYMOD_PAYMENT') < 1)
		{
			$order_state = new OrderState();
			$order_state->send_email = true;
			$order_state->module_name = $this->name;
			$order_state->invoice = false;
			$order_state->color = '#98c3ff';
			$order_state->logable = true;
			$order_state->shipped = false;
			$order_state->unremovable = false;
			$order_state->delivery = false;
			$order_state->hidden = false;
			$order_state->paid = false;
			$order_state->deleted = false;
			$order_state->name = array((int)Configuration::get('PS_LANG_DEFAULT') => pSQL($this->l('MyMod Payment - Awaiting confirmation')));
			$order_state->template = array();
			foreach (LanguageCore::getLanguages() as $l)
				$order_state->template[$l['id_lang']] = 'mymodpayment';

			// We copy the mails templates in mail directory
			foreach (LanguageCore::getLanguages() as $l)
			{
				$module_path = dirname(__FILE__).'/views/templates/mails/'.$l['iso_code'].'/';
				$application_path = dirname(__FILE__).'/../../mails/'.$l['iso_code'].'/';
				if (!copy($module_path.'mymodpayment.txt', $application_path.'mymodpayment.txt') ||
					!copy($module_path.'mymodpayment.html', $application_path.'mymodpayment.html'))
					return false;
			}

			if ($order_state->add())
			{
				// We save the order State ID in Configuration database
				Configuration::updateValue('PS_OS_MYMOD_PAYMENT', $order_state->id);

				// We copy the module logo in order state logo directory
				copy(dirname(__FILE__).'/logo.gif', dirname(__FILE__).'/../../img/os/'.$order_state->id.'.gif');
				copy(dirname(__FILE__).'/logo.gif', dirname(__FILE__).'/../../img/tmp/order_state_mini_'.$order_state->id.'.gif');
			}
			else
				return false;
		}
		return true;
	}


	public function getHookController($hook_name)
	{
		// Include the controller file
		require_once(dirname(__FILE__).'/controllers/hook/'. $hook_name.'.php');

		// Build dynamically the controller name
		$controller_name = $this->name.$hook_name.'Controller';

		// Instantiate controller
		$controller = new $controller_name($this, __FILE__, $this->_path);

		// Return the controller
		return $controller;
	}

	public function hookDisplayPayment($params)
	{
		$controller = $this->getHookController('displayPayment');
		return $controller->run($params);
	}

	public function hookDisplayPaymentReturn($params)
	{
		$controller = $this->getHookController('displayPaymentReturn');
		return $controller->run($params);
	}

	public function getContent()
	{
		$controller = $this->getHookController('getContent');
		return $controller->run();
	}
}

