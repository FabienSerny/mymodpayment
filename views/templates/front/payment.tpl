{capture name=path}
    {l s='MyMod Payment' mod='mymodpayment'}
{/capture}


<h1 class="page-heading">
{l s='Order summary' mod='mymodpayment'}
</h1>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nb_products <= 0}
    <p class="alert alert-warning">
        {l s='Your shopping cart is empty.' mod='mymodpayment'}
    </p>
{else}
    <form action="{$link->getModuleLink('mymodpayment', 'validation', [], true)|escape:'html'}" method="post">
	<div class="box cheque-box">
		<h3 class="page-subheading">
            {l s='MyMod Payment' mod='mymodpayment'}
		</h3>
		<p class="cheque-indent">
			<strong class="dark">
                {l s='You have chosen to pay with MyMod Payment.' mod='mymodpayment'} {l s='Here is a short summary of your order:' mod='mymodpayment'}
			</strong>
		</p>
		<p>
			- {l s='The total amount of your order is' mod='mymodpayment'}
			<span id="amount" class="price">{displayPrice price=$total_amount}</span>
            {if $use_taxes == 1}
                {l s='(tax incl.)' mod='mymodpayment'}
            {/if}
		</p>
	<p>
		-
        {if $currencies|@count > 1}
            {l s='We allow several currencies to be sent via MyMod Payment.' mod='mymodpayment'}
			<div class="form-group">
				<label>{l s='Choose one of the following:' mod='mymodpayment'}</label>
				<select id="currency_payment" class="form-control" name="currency_payment">
                    {foreach from=$currencies item=currency}
						<option value="{$currency.id_currency}" {if $currency.id_currency == $cart_currency}selected="selected"{/if}>
                            {$currency.name}
						</option>
                    {/foreach}
				</select>
			</div>
            {else}
            {l s='We allow the following currency to be sent via MyMod Payment:' mod='mymodpayment'}&nbsp;<b>{$currencies.0.name}</b>
			<input type="hidden" name="currency_payment" value="{$currencies.0.id_currency}" />
        {/if}
		</p>
		<p>
			- {l s='MyMod Payment account information will be displayed on the next page.' mod='mymodpayment'}
			<br />
			- {l s='Please confirm your order by clicking "I confirm my order."' mod='mymodpayment'}.
		</p>
	</div><!-- .cheque-box -->

	<p class="cart_navigation clearfix" id="cart_navigation">
		<a
				class="button-exclusive btn btn-default"
				href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}">
			<i class="icon-chevron-left"></i>{l s='Other payment methods' mod='mymodpayment'}
		</a>
		<button
				class="button btn btn-default button-medium"
				type="submit">
			<span>{l s='I confirm my order' mod='mymodpayment'}<i class="icon-chevron-right right"></i></span>
		</button>
	</p>
    </form>
{/if}







