
Testing Requests
================

HTTP requests done in testing don't actually issue real requests but are simulated internally.

They return a TestResponse instance from Illuminate instead of a Response.

CSRF middleware is automatically disabled when running tests.

<?
// This test just makes sure the homepage is working
public function test_a_basic_request(){
   	$response = $this->get('/');
    $response->assertStatus(200);
}

// Simulating a post request with headers
public function test_interacting_with_headers()
{
    $response = $this->withHeaders([
        'X-Header' => 'Value',
    ])->post('/user', ['name' => 'Sally']);

    $response->assertStatus(201);
}

// Simulating a request with cookie or cookies set
public function test_interacting_with_cookies()
{
    $response = $this->withCookie('color', 'blue')->get('/');

    $response = $this->withCookies([
        'color' => 'blue',
        'name' => 'Taylor',
    ])->get('/');
}

// Request with session variable(s) set
public function test_interacting_with_the_session()
{
    $response = $this->withSession(['banned' => false])->get('/');
}

// Here we're using a Factory to generate a User and using actingAs to authenticate them along with their session variable
public function test_an_action_that_requires_authentication()
{
    $user = User::factory()->create();

    // 2nd optional var on actingAs specifies which guard should be used for auth
    $response = $this->actingAs($user)
                     ->withSession(['banned' => false])
                     ->get('/');
}

// Various options to dump info from request for debugging purposes
public function testBasicTest()
{
    $response = $this->get('/');
    $response->dumpHeaders();
    $response->dumpSession();
    $response->dump();
}

?>

