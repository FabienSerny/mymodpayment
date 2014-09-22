<div class="row">
	<div class="col-xs-12 col-md-6">
        <p class="payment_module">
			<a href="{$link->getModuleLink('mymodpayment', 'payment')|escape:'html'}" class="mymodpayment">
                {l s='Pay with simple MyMod payment module' mod='mymodpayment'}
            </a>
        </p>
    </div>
</div>

{if $api_url ne ''}
<div class="row">
	<div class="col-xs-12 col-md-6">
		<p class="payment_module">
			<a href="#" id="mymodpayment-api-link" class="mymodpayment">
                {l s='Pay with MyMod Payment API' mod='mymodpayment'}
            </a>
		</p>
	</div>
</div>
<form action="{$api_url}" style="display:none" id="mymodpayment-api-form" method="POST">
	<input type="hidden" name="total_to_pay" value="{$total_to_pay}" />
	<input type="hidden" name="id_cart" value="{$id_cart}" />
	<input type="hidden" name="api_credentials_id" value="{$api_credentials_id}" />
	<input type="hidden" name="payment_token" value="{$payment_token}" />
	<input type="hidden" name="validation_url" value="{$validation_url}" />
	<input type="hidden" name="return_url" value="{$return_url}" />
	<input type="hidden" name="cancel_url" value="{$cancel_url}" />
</form>
<script>
	$('#mymodpayment-api-link').click(function() {
		$('#mymodpayment-api-form').submit();
		return false;
	});
</script>
{/if}