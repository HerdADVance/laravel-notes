
Private channels require to authorize that the current authenticated user can actually listen on the channel. 

This is accomplished by making an HTTP request to your Laravel app with the channel name and allowing your app to determine if the user can listen on that channel.

When using Echo, the HTTP request to authorize subscriptions to private channels will be made automatically. However, you need to define proper routes to respond to those requests.


Defining Authorization Routes
=============================

In the App\Providers\BroadcastServiceProvider class, there's a call to the <? Broadcast::routes ?> method. 

This will register the broadcasting/auth route to handle authorization requests.

This method will automatically place its routes within the web middleware group. You can also pass an array of route attributes to the method if you want to customize the assigned attributes:

	<? Broadcast::routes($attributes); ?>


Customizing the Authorization Endpoint
=====================================

By default, Echo uses the broadcasting/auth endpoint to authorize channel access. However, you can specify your own authorization endpoint by passing the authEndpoint config option to your Echo instance:

<script>
	window.Echo = new Echo({
	    broadcaster: 'pusher',
	    // ...
	    authEndpoint: '/custom/endpoint/auth'
	});
</script>


Defining Authorization Callbacks
================================

Next, we define the logic that will determine if the currently authenticated user can listed to a given channel.

This is done in the routes/channels.php file.

In this file, use the Broadcast::channel method to register channel authorization callbacks:

<?
	Broadcast::channel('orders.{orderId}', function ($user, $orderId) {
	    return $user->id === Order::findOrNew($orderId)->user_id;
	});
?>

The channel methods accepts two arguments: the name of the channel and a callback which returns true or false that indicates if the user is authorized to listen to the channel.

All authorization callbacks receive the currently authenticated user as their first argument and any additional wildcard parameters as their subsequent arguments.

In the example above, the {orderId} placeholder indicates that the ID portion of the channel name is a wildcard.


Authorization Callback Model Binding
=====================================

Just like HTTP routes, channel routes may also take advantage of implicit and explicit route model binding.

For example, instead of receving a string or numeric order ID, you may request an actual Order model instance:

<?
	use App\Models\Order;

	Broadcast::channel('orders.{order}', function ($user, Order $order) {
	    return $user->id === $order->user_id;
	});
?>

Unlike, HTTP route model binding, channel model binding doesn't support automatic implicit model binding scoping.

However, this is rarely a problem because most channels can be scoped based on a single model's unique primary key.


Authorization Callback Authentication
=====================================

Private and presence broadcast channels authenticate the current user via your app's default authentication guard. 

If the user isn't authenticated, channel authorization is automatically denied and the authorization callback is never executed.

However, you can assign multiple, custom guards that should authenticate the incoming request if necessary:

<?
	Broadcast::channel('channel', function () {
	    // ...
	}, ['guards' => ['web', 'admin']]);
?>


Defining Channel Classes
=========================

If your app is consuming many different channels, your routes/channels.php file can become bulky.

Instead of using closures to authorize channels, you can use channel classes.

To generate a channel class, use the make:channel Artisan command. This will place a new channel class in the App/Broadcasting directory.

	<? php artisan make:channel OrderChannel ?>

Then register your channel in the routes/channels.php file:

<?
	use App\Broadcasting\OrderChannel;

	Broadcast::channel('orders.{order}', OrderChannel::class);
?>

Finally, place the authorization logic for your channel in the channel class's join method.

The join method will house the same logic you would have typically placed in your authorization closure. You can also take advantage of channel model binding:

<?
	namespace App\Broadcasting;

	use App\Models\Order;
	use App\Models\User;

	class OrderChannel
	{
	    public function __construct()
	    {
	        //
	    }

	    public function join(User $user, Order $order)
	    {
	        return $user->id === $order->user_id;
	    }
	}
?>


