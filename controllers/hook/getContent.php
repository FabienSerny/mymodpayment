<?php

class MyModPaymentGetContentController
{
	public function __construct($module, $file, $path)
	{
		$this->file = $file;
		$this->module = $module;
		$this->context = Context::getContext(); $this->_path = $path;
	}

	public function processConfiguration()
	{
		if (Tools::isSubmit('mymodpayment_form'))
		{
			Configuration::updateValue('MYMOD_CH_ORDER', Tools::getValue('MYMOD_CH_ORDER'));
			Configuration::updateValue('MYMOD_CH_ADDRESS', Tools::getValue('MYMOD_CH_ADDRESS'));
			Configuration::updateValue('MYMOD_BA_OWNER', Tools::getValue('MYMOD_BA_OWNER'));
			Configuration::updateValue('MYMOD_BA_DETAILS', Tools::getValue('MYMOD_BA_DETAILS'));
			Configuration::updateValue('MYMOD_API_URL', Tools::getValue('MYMOD_API_URL'));
			Configuration::updateValue('MYMOD_API_CRED_ID', Tools::getValue('MYMOD_API_CRED_ID'));
			Configuration::updateValue('MYMOD_API_CRED_SALT', Tools::getValue('MYMOD_API_CRED_SALT'));
			$this->context->smarty->assign('confirmation', 'ok');
		}
	}

	public function renderForm()
	{
		$inputs = array(
			array('name' => 'MYMOD_CH_ORDER', 'label' => $this->module->l('Check order'), 'type' => 'text'),
			array('name' => 'MYMOD_CH_ADDRESS', 'label' => $this->module->l('Check address'), 'type' => 'textarea'),
			array('name' => 'MYMOD_BA_OWNER', 'label' => $this->module->l('Bankwire owner'), 'type' => 'text'),
			array('name' => 'MYMOD_BA_DETAILS', 'label' => $this->module->l('Bankwire details'), 'type' => 'textarea'),
			array('name' => 'MYMOD_API_URL', 'label' => $this->module->l('API URL'), 'type' => 'text'),
			array('name' => 'MYMOD_API_CRED_ID', 'label' => $this->module->l('API credentials ID'), 'type' => 'text'),
			array('name' => 'MYMOD_API_CRED_SALT', 'label' => $this->module->l('API credentials SALT'), 'type' => 'text'),
		);

		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->module->l('My Module configuration'),
					'icon' => 'icon-wrench'
				),
				'input' => $inputs,
				'submit' => array('title' => $this->module->l('Save'))
			)
		);

		$helper = new HelperForm();
		$helper->table = 'mymodpayment';
		$helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$helper->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
		$helper->submit_action = 'mymodpayment_form';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->module->name.'&tab_module='.$this->module->tab.'&module_name='.$this->module->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => array(
				'MYMOD_CH_ORDER' => Tools::getValue('MYMOD_CH_ORDER', Configuration::get('MYMOD_CH_ORDER')),
				'MYMOD_CH_ADDRESS' => Tools::getValue('MYMOD_CH_ADDRESS', Configuration::get('MYMOD_CH_ADDRESS')),
				'MYMOD_BA_OWNER' => Tools::getValue('MYMOD_BA_OWNER', Configuration::get('MYMOD_BA_OWNER')),
				'MYMOD_BA_DETAILS' => Tools::getValue('MYMOD_BA_DETAILS', Configuration::get('MYMOD_BA_DETAILS')),
				'MYMOD_API_URL' => Tools::getValue('MYMOD_API_URL', Configuration::get('MYMOD_API_URL')),
				'MYMOD_API_CRED_ID' => Tools::getValue('MYMOD_API_CRED_ID', Configuration::get('MYMOD_API_CRED_ID')),
				'MYMOD_API_CRED_SALT' => Tools::getValue('MYMOD_API_CRED_SALT', Configuration::get('MYMOD_API_CRED_SALT')),
			),
			'languages' => $this->context->controller->getLanguages()
		);

		return $helper->generateForm(array($fields_form));
	}

	public function run()
	{
		$this->processConfiguration();
		$html_confirmation_message = $this->module->display($this->file, 'getContent.tpl');
		$html_form = $this->renderForm();
		return $html_confirmation_message.$html_form;
	}
}
