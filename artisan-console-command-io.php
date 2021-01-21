
While your command is executing, you'll likely need access to the values for the arguments and options accepted by your command.

To do so, use the argument and option methods. If an argument or option doesn't exist, null will be returned.

<?
	public function handle()
	{
		// Retrieve an argument
	    $userId = $this->argument('user');

	    // Retrieve all arguments as an array
		$arguments = $this->arguments();

		// Retrieve a specific option
		$queueName = $this->option('queue');

		// Retrieve all options as an array
		$options = $this->options();
	}
?>

In addition to displaying output, you can ask the user to provide input during the execution of your command.

The ask method will prompt the user with the given question, accept their input, and then return the user's input back to your command:

<?
	public function handle()
	{
    	$name = $this->ask('What is your name?');
	}
?>

The secret method is similar to ask, but the user's input won't be visible to them as they type:

	<? $password = $this->secret('What is the password?');?>

The confirm method can be used to ask a simple yes or no question. It returns false by default or true if the user types y or yes.

<?
	if ($this->confirm('Do you wish to continue?')) {
    	//
	}

	// or add 2nd optional argument to return true by default
	if ($this->confirm('Do you wish to continue?', true)) {
    	//
	}
?>

The anticipate method is used to provide auto-completion for possible choices. The user can still provide any answer regardless of the hints:

<?
	$name = $this->anticipate('What is your name?', ['Taylor', 'Dayle']);

	// Or, a 2nd argument of a closure can be passed and will be called on each keystroke
	$name = $this->anticipate('What is your address?', function ($input) {
	    // Return auto-completion options...
	});
?>

The choice method allows multiple choice questions:

<?
	$name = $this->choice(
	    'What is your name?',
	    ['Taylor', 'Dayle'],
	    $defaultIndex,
	    $maxAttempts = null, // optional
    	$allowMultipleSelections = false //optional
	);
?>


Writing Output
=================

To send output to the console, use the following commands that use appropriate ANSI (American National Standard Institute) colors for their purpose:

<?
	public function handle()
	{
		// Info: green colored text
    	$this->info('The command was successful!');

    	// Error: red text
    	$this->error('Something went wrong!');

    	// Line: plain, uncolored text
    	$this->line('Display this on the screen');

    	// New Line: blank line
    	$this->newLine(3); // optional number of blank lines as arg

    	// Comment and Question are also available
	}
?>

The table method makes it easy to correctly format multiple rows/columns of data.

Just provide the column names and the data for the table, and Laravel will automatically calculate the appropriate width and height od the table for you:

<?
	use App\Models\User;

	$this->table(
	    ['Name', 'Email'],
	    User::all(['name', 'email'])->toArray()
	);
?>

The withProgressBar method does exactly what you'd think. There are more ways to manually control the bar. Read the docs if you ever actually need that for some reason.

<?
	use App\Models\User;

	$users = $this->withProgressBar(User::all(), function ($user) {
    	$this->performTask($user);
	});
?>

