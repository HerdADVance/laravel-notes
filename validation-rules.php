
Go to the Validation docs for the long list of all the available validation rules.


Conditionally Adding Rules
===========================

You may sometimes not want to validate a given field if another field has a given value. Use the exclude_if validation rule to accomplish this.

In this example, the appointment_date and doctor_name fields won't be validated if the has_appointment field has a value of false:

<?
	use Illuminate\Support\Facades\Validator;

	$validator = Validator::make($data, [
	    'has_appointment' => 'required|bool',
	    'appointment_date' => 'exclude_if:has_appointment,false|required|date',
	    'doctor_name' => 'exclude_if:has_appointment,false|required|string',
	]);
?>

Alternatively, exclude_unless works with the opposite logic:

<?
	$validator = Validator::make($data, [
	    'has_appointment' => 'required|bool',
	    'appointment_date' => 'exclude_unless:has_appointment,true|required|date',
	    'doctor_name' => 'exclude_unless:has_appointment,true|required|string',
	]);
?>

The sometimes rule will only run a validation check only if the field is present:

<?
	$validator = Validator::make($request->all(), [
	    'email' => 'sometimes|required|email',
	]);
?>


For more complex conditional logic, create a Validator instance with your static rules that never change:

<?
	use Illuminate\Support\Facades\Validator;

	$validator = Validator::make($request->all(), [
	    'email' => 'required|email',
	    'games' => 'required|numeric',
	]);
?>

This example above assumes the app is for game collectors. If a collector registers for our app and owns more than 100 games, we want them to explain why. To conditionally add this requirement, we use the sometimes method on the Validator instance:

<?
	$validator->sometimes('reason', 'required|max:500', function ($input) {
	    return $input->games >= 100;
	});
?>

The 1st argument passed to the sometimes method is the name of the field we're conditionally validating. The 2nd argument is a list of the rules we want to add. If the closure passed as the 3rd argument returns true, the rules will be added.

You can also add conditional validations for several fields at once:

<?
	$validator->sometimes(['reason', 'cost'], 'required', function ($input) {
	    return $input->games >= 100;
	});
?>

The $input parameter passed to the closures above will be an instance of Illuminate\Support\Fluent



Validating Arrays
====================

Use dot notation to validate attibutes within an array. For example, if the incoming HTTP request contains a photos[profile] field, validate it like this:

<?
	use Illuminate\Support\Facades\Validator;

	$validator = Validator::make($request->all(), [
	    'photos.profile' => 'required|image',
	]);
?>

You can also validate each element of an array. For example, to validate that each email in a given array input field is unique, do this:

<?
	$validator = Validator::make($request->all(), [
	    'person.*.email' => 'email|unique:users',
	    'person.*.first_name' => 'required_with:person.*.last_name',
	]);
?>


Custom Validation Rules
==========================

Laravel provides a variety of validation rules, but you can also add your own.

One method of registering custom validation rules is using rule objects. To generate a new rule object, use the make:rule Artisan command.

	php artisan make:rule Uppercase

This command will place the new rule in app/Rules directory and create that directory if it doesn't exist.

Once the rule object has been created, it has two methods: passes and message.

The passes method receives the attribute value and name and should return true or false.

The message method should return the validation error message used on failure.

<?
	namespace App\Rules;

	use Illuminate\Contracts\Validation\Rule;

	class Uppercase implements Rule
	{
	    public function passes($attribute, $value)
	    {
	        return strtoupper($value) === $value;
	    }

	    public function message()
	    {
	        return 'The :attribute must be uppercase.';
	    }
	}
?>

Once the rule has been defined, you can attach it to a validator by passing an instance of the rule object with your other validation rules:

<?
	use App\Rules\Uppercase;

	$request->validate([
	    'name' => ['required', 'string', new Uppercase],
	]);
?>


If you only need the functionality of a custom rule once throughout your app, you can use a closure instead of a rule object. The closure receives the attribute's name, value, and a $fail callback that should be called if validation fails:

<?
	use Illuminate\Support\Facades\Validator;

	$validator = Validator::make($request->all(), [
	    'title' => [
	        'required',
	        'max:255',
	        function ($attribute, $value, $fail) {
	            if ($value === 'foo') {
	                $fail('The '.$attribute.' is invalid.');
	            }
	        },
	    ],
	]);
?>

By default, when an attribute being validated isn't present or contains an empty string, normal validation rules are not run. For example, the unique rule won't run against an empty string:

<?
	use Illuminate\Support\Facades\Validator;

	$rules = ['name' => 'unique:users,name'];

	$input = ['name' => ''];

	Validator::make($input, $rules)->passes(); // true
?>

To create an implicit rule, implement the Illuminate\Contracts\Validation\ImplicitRule interface. This will serve as a marker interface for the validator and does not contain any additional methods you need to implement beyond the methods required by a typical Rule interface.


