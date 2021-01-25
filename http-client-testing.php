
Laravel's HTTP wrapper helps you easily and expressively write tests.

The Http facade's fake method allows you to instruct the HTTP client to return stubbed/dummy responses when requests are made.

Faking Responses
=================

To instruct the HTTP client to return empty, 200 status code responses for every request, call the fake method with no arguments:

<?
	use Illuminate\Support\Facades\Http;

	Http::fake();

	$response = Http::post(...);
?>

When faking requests, HTTP client middleware are not executed. You should define expectations for faked responses as if these middleware have run correectly.

Faking Specific URLs
======================

You can pass an array to the fake method. The keys should represent URL patterns that you wish to fake and their associated responses. The * character can be used as a wildcard.

Any requests made to URLs that haven't been faked will actually be executed. You can use the Http facade's response method to construct stub/fake responses to these endpoints:

<?
	Http::fake([
	    // Stub a JSON response for GitHub endpoints...
	    'github.com/*' => Http::response(['foo' => 'bar'], 200, $headers),

	    // Stub a string response for Google endpoints...
	    'google.com/*' => Http::response('Hello World', 200, $headers),

	    // Stub a JSON response for GitHub endpoints...
	    'github.com/*' => Http::response(['foo' => 'bar'], 200, ['Headers']),

	    // Stub a string response for all other endpoints...
	    '*' => Http::response('Hello World', 200, ['Headers']),
	]);
?>


Faking Response Sequences
==========================

Sometimes you need to specify that a single URL should return a series of fake responses in a specific order.

You can use the Http::sequence method to build the responses:

<?
	Http::fake([
		// Stub a series of responses for GitHub endpoints...
	    'github.com/*' => Http::sequence()
            ->push('Hello World', 200)
            ->push(['foo' => 'bar'], 200)
            ->pushStatus(404),
	]);
?>

When all of the responses in a response sequence have been consumed, any further requests will cause the response sequence to throw an exception.

If you want to specify a default response that should be returned when a sequence is empty, use the whenEmpty method:

<?
	Http::fake([
	    // Stub a series of responses for GitHub endpoints...
	    'github.com/*' => Http::sequence()
		    ->push('Hello World', 200)
		    ->push(['foo' => 'bar'], 200)
		    ->whenEmpty(Http::response()),
	]);


	// Or, fakeSequence for a sequence of responses but no specific URL pattern
	Http::fakeSequence()
        ->push('Hello World', 200)
        ->whenEmpty(Http::response());
?>

Fake Callback
===============

If you require more complicated logic to determine what responses to return for certain endpoints, pass a closure to the fake method.

This closure will receive an instance of Illuminate\Http\Client\Request and should return a response instance. Within the closure, perform whatever logic is necessary to determine what type of response to return:

<?
	Http::fake(function ($request) {
	    return Http::response('Hello World', 200);
	});
?>


Inspecting Requests
=====================

When faking responses, you might want to inspect the requests the client receives in order to make sure the app is sending the correct data or headers.

To do this, use the Http::assertSent method after calling Http::fake

The assertSent method accepts a closure that receive a Illuminate\Http\Client\Request instance and should return a boolean indicating if the request matches your expectations.

In order for the test to pass, at least one request must have been issed given the matching expections:

<?
	use Illuminate\Http\Client\Request;
	use Illuminate\Support\Facades\Http;

	// assertSent
	Http::fake();

	Http::withHeaders([
	    'X-First' => 'foo',
	])->post('http://example.com/users', [
	    'name' => 'Taylor',
	    'role' => 'Developer',
	]);

	Http::assertSent(function (Request $request) {
	    return $request->hasHeader('X-First', 'foo') &&
	           $request->url() == 'http://example.com/users' &&
	           $request['name'] == 'Taylor' &&
	           $request['role'] == 'Developer';
	});


	// Or, assertNotSent
	Http::fake();

	Http::post('http://example.com/users', [
	    'name' => 'Taylor',
	    'role' => 'Developer',
	]);

	Http::assertNotSent(function (Request $request) {
	    return $request->url() === 'http://example.com/posts';
	});


	// Or, assertNothingSent
	Http::fake();

	Http::assertNothingSent();
?>


