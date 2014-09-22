<div class="box">
	<p class="cheque-indent">
		<strong class="dark">{l s='Your order on %s is complete.' sprintf=$shop_name mod='mymodpayment'}</strong>
	</p><br>

	<p>
        {l s='Please make a check of %s payable to the order of' sprintf=$total_to_pay mod='mymodpayment'} <strong>{$MYMOD_CH_ORDER}</strong><br>
		{l s='And send it to:' mod='mymodpayment'} <span class="price"> <strong>{$MYMOD_CH_ADDRESS}</strong></span>
	</p><br>

    <p>
        {l s='Or send us a bank wire with' mod='mymodpayment'}<br>
        - {l s='Amount' mod='mymodpayment'} <span class="price"> <strong>{$total_to_pay}</strong></span><br>
        - {l s='Name of account owner' mod='mymodpayment'}  <strong>{$MYMOD_BA_OWNER}</strong><br>
        - {l s='Include these details:' mod='mymodpayment'}  <strong>{$MYMOD_BA_DETAILS}</strong>
    </p><br>

    <p>
    {if !isset($reference)}
	    {l s='Do not forget to insert your order number #%d in the subject of your bank wire' sprintf=$id_order mod='mymodpayment'}
    {else}
	    {l s='Do not forget to insert your order reference %s in the subject of your bank wire.' sprintf=$reference mod='mymodpayment'}
    {/if}
	</p>

	<p><strong>{l s='Your order will be sent as soon as we receive payment.' mod='mymodpayment'}</strong></p><br>
	<p>{l s='If you have questions, comments or concerns, please contact our' mod='mymodpayment'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='expert customer support team.' mod='mymodpayment'}</a>.</p>
</div>