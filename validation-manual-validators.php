
If you don't want to use the validate method on the request, you can create a validator instance manually using the Validator facade.

The make method on the facade generates a new validator instance:

<?
	namespace App\Http\Controllers;

	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Validator;

	class PostController extends Controller
	{
	    public function store(Request $request)
	    {
	        $validator = Validator::make($request->all(), [
	            'title' => 'required|unique:posts|max:255',
	            'body' => 'required',
	        ]);

	        if ($validator->fails()) {
	            return redirect('post/create')
	            	->withErrors($validator)
	            	->withInput();
	        }

	        // Store the blog post...
	    }
	}
?>

The 1st argument passed to the make method is the data under validation. The 2nd argument is an array of validation rules that should be applied to the data.

After determining whether the request validation failed, you can use the withErrors method to flash the error messages to the session. When using this method, the $errors variable will be automatically shared with your views after redirection, allowing you to easily display them back to the user.

The withErrors method accepts a validator, a MessageBag, or a PHP array.


Automatic Redirection
=====================

If you want to create a validator instance manually but still take advantage of the automatic redirection offered by the HTTP request's validate method, you can call the validate method on an existing validator instance. If validation fails, the user will be automatically redirected.

<?
	Validator::make($request->all(), [
	    'title' => 'required|unique:posts|max:255',
	    'body' => 'required',
	])->validate();
?>

You can use the validateWithBag method to store the error messages in a named error bag if validation fails:

<?
	Validator::make($request->all(), [
	    'title' => 'required|unique:posts|max:255',
	    'body' => 'required',
	])->validateWithBag('post');
?>


Named Error Bags
===================

If you have multiple forms on a single page, you may wish to name the MessageBag containing the validation errors so you can retrieve the error messages for a specific form.

To achieve this, pass a name as the 2nd argument to the withErrors method:

	<? return redirect('register')->withErrors($validator, 'login'); ?>

Then you can access the named MessageBag instance from the $errors variable:

	{{ $errors->login->first('email') }}


Customizing the Error Messages
==============================

If needed, you can provide custom error messages that a validator instance should use instead of the default error messages provided by Laravel. There are several ways to do this.

You could pass the custom messages as the 3rd argument to the Validator::make method

<?
	$validator = Validator::make($input, $rules, $messages = [
	    'required' => 'The :attribute field is required.',
	]);
?>

You could utilize other placeholders in validation messages:

<?
	$messages = [
	    'same' => 'The :attribute and :other must match.',
	    'size' => 'The :attribute must be exactly :size.',
	    'between' => 'The :attribute value :input is not between :min - :max.',
	    'in' => 'The :attribute must be one of the following types: :values',
	];
?>

Use dot notation to specify a custom error message for a specific attribute:

<?
	$messages = [
	    'email.required' => 'We need to know your email address!',
	];
?>

The 4th argument of the Validtor::make method can customize values used to replace the :attribute placeholders on specific fields:

<?
	$validator = Validator::make($input, $rules, $messages, [
	    'email' => 'email address',
	]);
?>


After Validation Hook
========================

You can attach callbacks to be run after validation is completed. This allows you to easily perform further validation and even add more error messages to the message collection.

Call the after method on a validator instance:

<?
	$validator = Validator::make(...);

	$validator->after(function ($validator) {
	    if ($this->somethingElseIsInvalid()) {
	        $validator->errors()->add(
	            'field', 'Something is wrong with this field!'
	        );
	    }
	});

	if ($validator->fails()) {
	    //
	}
?>