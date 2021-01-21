
After calling the errors method on a Validator instance, you will receive an Illuminate\Support\MessageBag instance which has a variety of convenient methods for working with error messages.

The $errors variable that is automatically made available to all views is also an instance of the MessageBag class.

<?
	// Always start with this
	$errors = $validator->errors();

	// First error message for a given field
	echo $errors->first('email');

	// Array of all messages for a given field
	foreach ($errors->get('email') as $message) {
    	//
    }

    // Array of all messages for each array form field
    foreach ($errors->get('attachments.*') as $message) {
    	//
	}

	// Array of all messages for all fields
	foreach ($errors->all() as $message) {
    	//
	}

	// Determine if messages exist for a field
	if ($errors->has('email')) {
    	//
	}
?>


Specifying Custom Messages in Language Files
============================================

Laravel's built-in validation rules each have an error message that is located in resources/lang/en/validation.php

Within this file, you'll find a translation entry for each validation rule. You can change these as needed.

To customize the error messages used for specified attribute and rule combinations within your app's validation language files, add your customizations to the custom array of that validation.php file:

<?
	'custom' => [
	    'email' => [
	        'required' => 'We need to know your email address!',
	        'max' => 'Your email address is too long!'
	    ],
	],
?>


Specifying Attributes in Language Files
========================================

Many of Laravel's built-in error messages include an :attribute placeholder. To replace it with a custom value, specify the custom attribute name in the attributes array of your validation.php file

<?
	'attributes' => [
    	'email' => 'email address',
	],
?>


Specifying Values in Language Files
===================================

Many of Laravel's built-in error messages include a :value placeholder.

<?
	Validator::make($request->all(), [
	    'credit_card_number' => 'required_if:payment_type,cc'
	]);
?>

If the above fails, it produces an error message of:
	
	"The credit card number field is required when payment type is cc."


You can modify or define the values array in the validation.php file to make this more readable:

<?
	'values' => [
	    'payment_type' => [
	        'cc' => 'credit card'
	    ],
	],
?>

Then the error message will say:

	"The credit card number field is required when payment type is cc."


