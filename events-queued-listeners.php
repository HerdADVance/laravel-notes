
Queueing listeners can be beneficial if your listener is going to perform a slow task such as sending an email or making an HTTP request.

Before using them, make sure to configure your queue and start a queue worker on your server or local dev environment.

To specify that a listener should be queued, add the shouldQueue interface to the listener class.

Listeners generated by the event:generate and make:listener Artisan commands already have this interface imported into the current namespace so you can use it immediately:

<?
	namespace App\Listeners;

	use App\Events\OrderShipped;
	use Illuminate\Contracts\Queue\ShouldQueue;

	class SendShipmentNotification implements ShouldQueue
	{
	    //
	}
?>

That's all you have to do. When an event handled by this listener is dispatched, the listener will automatically be queued by the event dispatcher using the queue system. If no exceptions are thrown then executed, the queued job will automatically be deleted after it's done processing.


Customizing the Queue Connection and Name
==========================================

If ou want to customize the queue connection, name, or delay time of an event listener, define the $connection, $queue, or $delay properties on your listener class:

<?
	namespace App\Listeners;

	use App\Events\OrderShipped;
	use Illuminate\Contracts\Queue\ShouldQueue;

	class SendShipmentNotification implements ShouldQueue
	{
	    public $connection = 'sqs';

	    public $queue = 'listeners';

	    public $delay = 60;
	}
?>

Define a viaQueue method on the listener if you want to define the listener's queue at runtime:

<?
	public function viaQueue()
	{
	    return 'listeners';
	}
?>


Conditionally Queueing Listeners
=================================

If you want to determine whether a listener should be queued based on some data that's available only at runtime, add the shouldQueue method to a listener.

If this method returns false, the listener won't be executed:

<?
	namespace App\Listeners;

	use App\Events\OrderCreated;
	use Illuminate\Contracts\Queue\ShouldQueue;

	class RewardGiftCard implements ShouldQueue
	{
	    public function handle(OrderCreated $event)
	    {
	        //
	    }
	    public function shouldQueue(OrderCreated $event)
	    {
	        return $event->order->subtotal >= 5000;
	    }
	}
?>


Manually Interacting the Queue
===============================

To manually access the listener's underlying queue jobs delete and release methods, do so using the Illuminate\Queue\InteractsWithQueue trait.

This trait is imported by default on generated listeners and provides access to these methods:

<?
	namespace App\Listeners;

	use App\Events\OrderShipped;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Queue\InteractsWithQueue;

	class SendShipmentNotification implements ShouldQueue
	{
	    use InteractsWithQueue;

	    public function handle(OrderShipped $event)
	    {
	        if (true) {
	            $this->release(30);
	        }
	    }
	}
?>


Queued Event Listeners & Database Transactions
==============================================

When queued listeners are dispatched within database transactions, they may be processed by the queue before the transaction has committed.

When this happens, any updates you've made to models or DB records during the transaction may not yet be reflected in the DB.

In addition, any models or DB records created within the transaction may not exist in the DB.

If your listener depends on these models, unexpected errors can occur when the job that dispatches the queued listener is processed.

If your queue connection's after_commit config option is set to false, you may still indicate that a particular queued listener should be dispatched after all open DB transactions have been committed by defining an $afterCommit property on the listener class:

<?
	namespace App\Listeners;

	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Queue\InteractsWithQueue;

	class SendShipmentNotification implements ShouldQueue
	{
	    use InteractsWithQueue;

	    public $afterCommit = true;
	}
?>


Handling Failed Jobs
=======================

Sometimes your queued event listeners may fail. If a queued listener exceeds the max number of attempts as defined by your queue worker, the failed method will be called on your listener.

The failed method receives the event instance and the Throwable that caused the failure:

<?
	namespace App\Listeners;

	use App\Events\OrderShipped;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Queue\InteractsWithQueue;

	class SendShipmentNotification implements ShouldQueue
	{
	    use InteractsWithQueue;

	    public function handle(OrderShipped $event)
	    {
	        //
	    }

	    public function failed(OrderShipped $event, $exception)
	    {
	        //
	    }
	}
?>


If one of your queued listeners is encountering an error, you likely don't want to keep retrying indefinitely. Laravel provides various ways to specify a number of times or length of time a listener can be attempted.

Define the $tries property on your listener class to specify the number of times:

<?
	namespace App\Listeners;

	use App\Events\OrderShipped;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Queue\InteractsWithQueue;

	class SendShipmentNotification implements ShouldQueue
	{
	    use InteractsWithQueue;

	    public $tries = 5;
	}
?>

Or the retryUntil method for time:

<?
	public function retryUntil()
	{
	    return now()->addMinutes(5);
	}
?>