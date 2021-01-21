
Testing JSON API's
=================

json
getJson
postJson
putJson
patchJson
deleteJson
optionsJson

When using (most of) the above methods, the 1st argument is the URL for the request, and the 2nd argument is the body of the request. The simplest json method requires the verb as the 1st of 3 arguments.

assertJson can then be used to test the response. This method converts the response to an array and utilizes PHPUnit::assertArraySubset to verify that the given array exists within the JSON response.

<?
public function test_making_an_api_request()
{
    $response = $this->postJson('/api/user', ['name' => 'Sally']);

    $response
        ->assertStatus(201)
        ->assertJson([
            'created' => true,
        ]);

    // This is another way to go about the above test.
    $this->assertTrue($response['created'])
}
?>

assertExactJson (different than simply assertJson above) checks to see if the entire response array matches.

<?
public function test_asserting_an_exact_json_match()
{
    $response = $this->json('POST', '/user', ['name' => 'Sally']);

    $response
        ->assertStatus(201)
        ->assertExactJson([
            'created' => true,
        ]);
}
?>

Use assertJsonPath to test a specified path containing certain data

<? 
public function test_asserting_a_json_paths_value()
{
    $response = $this->json('POST', '/user', ['name' => 'Sally']);

    $response
        ->assertStatus(201)
        ->assertJsonPath('team.owner.name', 'Darian');
}
?>
