
For more complex validation scenarios, you may wish to create a form request.

Form requests are custom request classes that encapsulate their own validation and authorization logic. To create one, use this artisan command:

	php artisan make:request StorePostRequest

This will create a file in the app/Http/Requests directory and create that directory if it doesn't already exist.

Each form request generated by Laravel two methods: authorize and rules

The authorize method is responsible for determining if the currently authenticated user can perform the action represented by the request.

The rules method returns the validation rules that should apply to the request's data.

<?
	// You can type-hint dependencies in the rules method's signature
	public function rules()
	{
	    return [
	        'title' => 'required|unique:posts|max:255',
	        'body' => 'required',
	    ];
	}
?>

To evaluate the validation rules, type-hint the request on your controller method. The incoming form request is validated before the controller method is called, meaning you don't need to clutter your controller with any validation logic:

<?
	public function store(StorePostRequest $request)
	{
	    // The incoming request is valid...

	    // Retrieve the validated input data...
	    $validated = $request->validated();
	}
?>

If validation fails, a redirect response will be generated to send the user back to their previous location. The errors will also be flashed to the session so they're available for display.


Adding After Hooks to Form Requests
====================================

Use the withValidator method to form a validation hook after a form request. 

This method receives the fully constructed validator which allows you call any of its methods before the validation rules are actually evaluated.

<?
	public function withValidator($validator)
	{
	    $validator->after(function ($validator) {
	        if ($this->somethingElseIsInvalid()) {
	            $validator->errors()->add('field', 'Something is wrong with this field!');
	        }
	    });
	}
?>


Authorizing Form Requests
==========================

The form request class also contains an authorize method. Within this method, you may determine if the authenticated user actually has the authority to update a given resource.

In this example, we're determining if a user owns a blog comment they're attempting to update. Most likely, you'll interact with your authorization gates and policies within this method:

<?
	use App\Models\Comment;

	public function authorize()
	{
	    $comment = Comment::find($this->route('comment'));

	    return $comment && $this->user()->can('update', $comment);
	}
?>

Since all form requests extend the base Laravel request class, we can use the user method to access the currently authenticated user.

The call to the route method above grants you access to the URI parameters defined on the route being called such as the {comment} parameter in the example below:

	<? Route::post('/comment/{comment}'); ?>

If the authorize method returns false, a 403 error will be called and the controller method won't execute.

If you plan to handle authorization logic for the request in another part of your app, simply return true from the authorize method.


Customizing Error Messages
===========================

You can customize the error messages used by the form request by overriding the messages method. This methodshould return an array of attribute/rule paris and their corresponding messages:

<?
	public function messages()
	{
	    return [
	        'title.required' => 'A title is required',
	        'body.required' => 'A message is required',
	    ];
	}
?>

Many of Laravel's built-in validation rule error messages contain an :attribute placeholder. To replace that with a custom attribute name, you can specify the custom names by overriding the attributes method. This method should return an array of attribute/name pairs:

<?
	public function attributes()
	{
	    return [
	        'email' => 'email address',
	    ];
	}
?>


Preparing Input for Validation
===============================

if you need to prepare or sanitize any data from the request before you apply your validation rules, use the prepareForValidation method:

<?
	use Illuminate\Support\Str;

	protected function prepareForValidation()
	{
	    $this->merge([
	        'slug' => Str::slug($this->slug),
	    ]);
	}
?>


