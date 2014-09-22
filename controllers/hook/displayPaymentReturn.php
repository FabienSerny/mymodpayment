<?php

class MyModPaymentDisplayPaymentReturnController
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
		if ($params['objOrder']->payment != $this->module->displayName)
			return '';

		$reference = $params['objOrder']->id;
		if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
			$reference = $params['objOrder']->reference;
		$total_to_pay = Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false);

		$this->context->smarty->assign(array(
			'MYMOD_CH_ORDER' => Configuration::get('MYMOD_CH_ORDER'),
			'MYMOD_CH_ADDRESS' => Configuration::get('MYMOD_CH_ADDRESS'),
			'MYMOD_BA_OWNER' => Configuration::get('MYMOD_BA_OWNER'),
			'MYMOD_BA_DETAILS' => Configuration::get('MYMOD_BA_DETAILS'),
			'reference' => $reference,
			'total_to_pay' => $total_to_pay,
		));

		return $this->module->display($this->file, 'displayPaymentReturn.tpl');
	}
}
