
<form action="{$api_url}" style="display:none" id="mymodpayment-api-form" method="POST">
	<input type="hidden" name="total_to_pay" value="{$total_to_pay}" />
	<input type="hidden" name="id_cart" value="{$id_cart}" />
	<input type="hidden" name="api_credentials_id" value="{$api_credentials_id}" />
	<input type="hidden" name="payment_token" value="{$payment_token}" />
	<input type="hidden" name="validation_url" value="{$validation_url}" />
	<input type="hidden" name="return_url" value="{$return_url}" />
	<input type="hidden" name="cancel_url" value="{$cancel_url}" />
</form>
