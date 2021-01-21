
Artisan is the command line interface included with Laravel. It exists at the root of your application as the artisan script and provides a number of helpful commands that can assist you.

To view a list of all available Artisan commands:
	
	<? php artisan list ?>

Every command also includes a help screen which displays and describes the command's available arguments and options. To view a help screen, precede the name of the command with help

	<? php artisan help migrate ?>


Tinker
=========

Laravel Tinker is a powerful REPL (Read-evel-print loop) that is included by default. It allows you interact with your Laravel app on the command line, including Eloquent models, jobs, events, and more.

To enter the Tinker environment:
	
	<? php artisan tinker ?>

Tinker utilizes an allow list to determine which Artisan commands are allowed to be run within its shell. By default, clear-compiled, down, env, inspire, migrate, optimize, and up can be used. To allow more, add them to the commands array in your tinker.php config file:

<?
	'commands' => [
    	// App\Console\Commands\ExampleCommand::class,
	],
?>

Tinker automatically aliases classes as you interact with them in Tinker. If you wish to never alias some classes, list those classes in the dont_alias array of your tinker.php config file:

<?
	'dont_alias' => [
	    App\Models\User::class,
	],
?>


Writing Commands
===================

In addition to the commands provided with Artisan, you may build your own custom commands. These are typically stored in app/Console/Commands.

To create a new command, use the make:command Artisan command. This will create a new command class in the app/Console/Commands directory and create the directory if needed.

	<? php artisan make:command SendEmails ?>

After generating your command, define appropriate values for the signature and description properties of the class. These properties will be used when displaying your command on the list screen.

The signature property allows you to define your command's input expectation.

The handle method will be called when your command is executed. Place your command logic in this method. It is also able to request any dependencies we need.

<?
	namespace App\Console\Commands;

	use App\Models\User;
	use App\Support\DripEmailer;
	use Illuminate\Console\Command;

	class SendEmails extends Command
	{
	    protected $signature = 'mail:send {user}';

	    protected $description = 'Send a marketing email to a user';

	    public function __construct()
	    {
	        parent::__construct();
	    }
	    public function handle(DripEmailer $drip)
	    {
	        $drip->send(User::find($this->argument('user')));
	    }
	}
?>

Closure-Based Commands
======================

Closure-based commands provide an alternative to defining console commands as classes. In the same way that route closures are an alternative to controllers, think of command closures as an alternative to command classes.

Within the commands method of your console's kernel (different from HTTP kernel), Laravel loads the routes/console.php file:

<?
	protected function commands()
	{
	    require base_path('routes/console.php');
	}
?>

This file defines console based entry points into your app. Within this file, you can define all of your closure based console commands using the Artisan::command method.

This method accepts the command signature as the 1st argument and a closure which receives the commands arguments and options as the 2nd:

<?
	Artisan::command('mail:send {user}', function ($user) {
    	$this->info("Sending email to: {$user}!");
	});
?>

The closure is bound to the underlying command instance so you have full access to all of the helper methods you would typically be able to access on a full command class.

Command closures can also type-hint additional dependencies that you need resolved from the service container:

<?
	use App\Models\User;
	use App\Support\DripEmailer;

	Artisan::command('mail:send {user}', function (DripEmailer $drip, $user) {
	    $drip->send(User::find($user));
	});
?>

The purpose method will add a description to the command that's displayed on php artisan list or php artisan help:

<?
	Artisan::command('mail:send {user}', function ($user) {
    	// ...
	})->purpose('Send a marketing email to a user');
?>

