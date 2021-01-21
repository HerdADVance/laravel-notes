
When writing console commands, it's common to gather input from the user through arguments or options. Laravel makes it easy to define the input you expect from the user by using the signature property on your commands.

The signature property allows you to define the name, arguments, and options for the command in a single, expressive, route-like syntax.

Arguments
===========

All user supplied arguments and options are wrapped in curly braces. In this example, the command defines one argument: user

<?
	// user argument is required
	protected $signature = 'mail:send {user}';

	// or the user argument is optional
	protected $signature = 'mail:send {user?}';

	// or the user argument has a default value of foo
	protected $signature = 'mail:send {user=foo}';
?>


Options
=============

Options, like arguments, are another form of user input. They're prefixed by two hyphens on the command line.

There are two types of options: those that receive a value and those that don't.

Options that don't receive a value serve as a boolean switch.

<?
	// queue option doesn't require value and acts as boolean
	protected $signature = 'mail:send {user} {--queue}';
?>

In that example, the --queue switch may be specified when calling the Artisan command. If the --queue switch is passed, the value of the option will be true, otherwise false.

	<? php artisan mail:send 1 --queue ?>

If the user must specify a value for an option, suffix the option name with an equals sign:

<?
	// queue option expects a value from user because of equals sign 
	protected $signature = 'mail:send {user} {--queue=}';
?>

In that example, the user would make a command like:

	<? php artisan mail:send 1 --queue=default ?>

You can also assign a default option by specifying the default value after the option name. This will be used if the user doesn't pass a value for the option.

<?
	// queue option expects a value from user but will default to 'default' if none passed
	protected $signature = 'mail:send {user} {--queue=default}';
?>

To assign a shortcut when defining an option, specify it before the option name and use the pipe character as a delimiter to separate the shortcut from the full option name:

<?
	// now it can be called with --Q instead of --queue
	protected $signature = 'mail:send {user} {--Q|queue}';
?>



Input Arrays
=============

If you want to define arguments or options to expect multiple input values, use the * character.

	<? protected $signature = 'mail:send {user*}'; ?>

When calling this method, the user arguments are passed in order to the command line. 'foo' and 'bar' are the values in the array:

	<? php artisan mail:send foo bar ?>

When defining an option that expects an input array, each option value passed to the command should be prefixed with the option name:

	<? protected $signature = 'mail:send {user} {--id=*}'; ?>

Then the user can send:

	<? php artisan mail:send --id=1 --id=2 ?>


Input Descriptions
====================

You can assign descriptions to input arguments and options by separating the argument name from the description using a colon. The command can be spread over multiple lines for clarity:

<?
	protected $signature = 'mail:send
    	{user : The ID of the user}
    	{--queue= : Whether the job should be queued}';
?>

