
Laravel's contracts are a set of interfaces that define the core services provided by the framework through implementation.

For example, the <? Illuminate\Contracts\Queue\Queue ?> contract defines the methods needed for queueing jobs while the <? Illuminate\Contracts\Mail\Mailer ?> contract defines the methods for sending email.

Each contract has a corresponding implementation provided by the framework.

For example, Laravel provides a queue implementation with a variety of drivers and a mailer implementation that is powered by SwiftMailer.


All of the Laravel contracts live in their own GitHub repo.

This provides a quick reference point for all available contracts as well as a single, decoupled package that may be utilized when building packages that interact with Laravel services.



Contracts vs. Facades
=======================

Laravel's facades and helper functions provide a simple way of utilizing Laravel's services WITHOUT needing to type-hint and resolve contracts out of the service container.

In most cases, each facade has an equivalent contract.

Unlike facades (which don't make you require them in your class's constructor), contracts allow you to define explicit dependencies for your classes.

Some devs prefer to explicitly define their dependencies in this way and therefore prefer to use contracts.

Other devs prefer the convenience of facades.

In general, most apps can use facades without issue during development.



When to Use Contracts
=======================

The decision to use contracts or facades comes down to personal taste and the tastes of your dev team.

Both can be used to create robust, well-tested Laravel apps.

They're not mutually exclusive. Some parts of your app may use both.

As long as you're keeping your class's responsibilities focused (SRP), you'll notice very few practical differences between using either.

If you're building a package that integrates with multiple PHP frameworks, you may wish tou use the illuminate/contracts pacahge to define your integration with Laravel's services without the need to require Laravel's concrete implementations in your package's composer.json file.


How to Use Contracts
=====================

Many types of classes in Laravel are resolved through the service container, including controllers, event listeners, middleware, queued jobs, and route closures.

To get an implementation of a contract, type-hint the interface in the constructor of the class being resolved.

Here's an example of an event listener:

<?
	namespace App\Listeners;

	use App\Events\OrderWasPlaced;
	use App\Models\User;
	use Illuminate\Contracts\Redis\Factory;

	class CacheOrderInformation
	{
	    protected $redis;

	    public function __construct(Factory $redis)
	    {
	        $this->redis = $redis;
	    }

	    public function handle(OrderWasPlaced $event)
	    {
	        //
	    }
	}
?>

When the event listener is resolved, the service container will read the type-hints on the constructor of the class and inject the appropriate value.



Contract Reference
======================

Here's a quick reference to all Laravel contracts and their equivalent facades:


Contract													References Facade
=========													==================

Illuminate\Contracts\Auth\Access\Authorizable	  
Illuminate\Contracts\Auth\Access\Gate						Gate
Illuminate\Contracts\Auth\Authenticatable	  
Illuminate\Contracts\Auth\CanResetPassword	 
Illuminate\Contracts\Auth\Factory							Auth
Illuminate\Contracts\Auth\Guard								Auth::guard()
Illuminate\Contracts\Auth\PasswordBroker					Password::broker()
Illuminate\Contracts\Auth\PasswordBrokerFactory	Password
Illuminate\Contracts\Auth\StatefulGuard	 
Illuminate\Contracts\Auth\SupportsBasicAuth	 
Illuminate\Contracts\Auth\UserProvider	 
Illuminate\Contracts\Bus\Dispatcher							Bus
Illuminate\Contracts\Bus\QueueingDispatcher					Bus::dispatchToQueue()
Illuminate\Contracts\Broadcasting\Factory					Broadcast
Illuminate\Contracts\Broadcasting\Broadcaster				Broadcast::connection()
Illuminate\Contracts\Broadcasting\ShouldBroadcast	 
Illuminate\Contracts\Broadcasting\ShouldBroadcastNow	 
Illuminate\Contracts\Cache\Factory							Cache
Illuminate\Contracts\Cache\Lock	 
Illuminate\Contracts\Cache\LockProvider	 
Illuminate\Contracts\Cache\Repository						Cache::driver()
Illuminate\Contracts\Cache\Store	 
Illuminate\Contracts\Config\Repository						Config
Illuminate\Contracts\Console\Application	 
Illuminate\Contracts\Console\Kernel							Artisan
Illuminate\Contracts\Container\Container					App
Illuminate\Contracts\Cookie\Factory							Cookie
Illuminate\Contracts\Cookie\QueueingFactory					Cookie::queue()
Illuminate\Contracts\Database\ModelIdentifier	 
Illuminate\Contracts\Debug\ExceptionHandler	 
Illuminate\Contracts\Encryption\Encrypter					Crypt
Illuminate\Contracts\Events\Dispatcher						Event
Illuminate\Contracts\Filesystem\Cloud						Storage::cloud()
Illuminate\Contracts\Filesystem\Factory						Storage
Illuminate\Contracts\Filesystem\Filesystem					Storage::disk()
Illuminate\Contracts\Foundation\Application					App
Illuminate\Contracts\Hashing\Hasher							Hash
Illuminate\Contracts\Http\Kernel	 
Illuminate\Contracts\Mail\MailQueue							Mail::queue()
Illuminate\Contracts\Mail\Mailable	 
Illuminate\Contracts\Mail\Mailer							Mail
Illuminate\Contracts\Notifications\Dispatcher				Notification
Illuminate\Contracts\Notifications\Factory					Notification
Illuminate\Contracts\Pagination\LengthAwarePaginator	 
Illuminate\Contracts\Pagination\Paginator	 
Illuminate\Contracts\Pipeline\Hub	 
Illuminate\Contracts\Pipeline\Pipeline	 
Illuminate\Contracts\Queue\EntityResolver	 
Illuminate\Contracts\Queue\Factory							Queue
Illuminate\Contracts\Queue\Job	 
Illuminate\Contracts\Queue\Monitor							Queue
Illuminate\Contracts\Queue\Queue							Queue::connection()
Illuminate\Contracts\Queue\QueueableCollection	 
Illuminate\Contracts\Queue\QueueableEntity	 
Illuminate\Contracts\Queue\ShouldQueue	 
Illuminate\Contracts\Redis\Factory							Redis
Illuminate\Contracts\Routing\BindingRegistrar				Route
Illuminate\Contracts\Routing\Registrar						Route
Illuminate\Contracts\Routing\ResponseFactory				Response
Illuminate\Contracts\Routing\UrlGenerator					URL
Illuminate\Contracts\Routing\UrlRoutable	 
Illuminate\Contracts\Session\Session						Session::driver()
Illuminate\Contracts\Support\Arrayable	 
Illuminate\Contracts\Support\Htmlable	 
Illuminate\Contracts\Support\Jsonable	 
Illuminate\Contracts\Support\MessageBag	 
Illuminate\Contracts\Support\MessageProvider	 
Illuminate\Contracts\Support\Renderable	 
Illuminate\Contracts\Support\Responsable	 
Illuminate\Contracts\Translation\Loader	 
Illuminate\Contracts\Translation\Translator					Lang
Illuminate\Contracts\Validation\Factory						Validator
Illuminate\Contracts\Validation\ImplicitRule	 
Illuminate\Contracts\Validation\Rule	 
Illuminate\Contracts\Validation\ValidatesWhenResolved	 
Illuminate\Contracts\Validation\Validator					Validator::make()
Illuminate\Contracts\View\Engine	 
Illuminate\Contracts\View\Factory							View
Illuminate\Contracts\View\View								View::make()



