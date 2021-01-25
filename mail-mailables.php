
Generating Mailables
======================

When building Laravel apps, each type of email sent by your app is represented as a mailable class.

These classes are stored in the app/Mail directory. It will be generated when you create your first mailable class using the make:mail Artisan command:

	<? php artisan make:mail OrderShipped ?>


Writing Mailables
======================

Once you've generate a mailable class, note that all of a mailable class's configuration is done in the build method.

Within this method, you can call various methods such as from, subject, view, or attach to configure the email's presentation and delivery.


Configuring the Sender
=======================

There are two ways to configure who the mail is going to be from.

First, you can use the from method:

<?
	public function build()
	{
	    return $this
	    	->from('example@example.com')
	    	->view('emails.orders.shipped');
	}
?>

If your app uses the same from address for all of its emails, you might not want to call the from method in every mailable class you generate.

In this case, specify a global from address in your config/mail.php file. This address will be used if no other from address is specified within the mailable class:

<?
	// config/mail.php
	'from' => ['address' => 'example@example.com', 'name' => 'App Name'],

	// can also add a reply to address
	'reply_to' => ['address' => 'example@example.com', 'name' => 'App Name'],
?>


Configuring the View
======================

Within a mailable class's build method, you can use the view method to specify which template should be used when rendering the email's content.

Since each email typically uses a Blade template to render its contents, you have the full power of Blade when building your HTML's email:

<?
	public function build()
	{
    	return $this->view('emails.orders.shipped');
	}
?>

You may wish to create a resources/views/emails directory to house all of your email templates but can place them anywhere within resources/views.

Plain Text Emails
==================

If you want to define a plain-text version of your email, use the text method.

Like the view method, this method accepts a template name which will be used to render the contents of the email. You can define both an HTML and plain-text version of your message:

<?
	public function build()
	{
	    return $this
	    	->view('emails.orders.shipped')
	    	->text('emails.orders.shipped_plain');
	}
?>


View Data
===============

Typically, you'll want to pass some data to your view that you can utilize when rendering the email's HTML

There are two ways to make the data available to your view.

First, any public property defined on your mailable class will automatically be made available to the view so you can pass data into your mailable class's constructor and set that data to public properties defined on the class:

<?
	namespace App\Mail;

	use App\Models\Order;
	use Illuminate\Bus\Queueable;
	use Illuminate\Mail\Mailable;
	use Illuminate\Queue\SerializesModels;

	class OrderShipped extends Mailable
	{
	    use Queueable, SerializesModels;

	    public $order;

	    public function __construct(Order $order)
	    {
	        $this->order = $order;
	    }

	    public function build()
	    {
	        return $this->view('emails.orders.shipped');
	    }
	}
?>

Once the data has been set to a public property, it will automatically be available in the view so you can access it like you would any other data in Blade templates:

	<div>
	    Price: {{ $order->price }}
	</div>


The other way to make data available to your view is via the with method.

The with method will let you customize the format of your email's data before it's sent to the template.

You'll still typically pass data via the mailable class's constructor, but this data should be set to protected or private so the data isn't automatically made available to the template.

Then, call the with method and pass an array of data that you wish to make available to the template:

<?
	namespace App\Mail;

	use App\Models\Order;
	use Illuminate\Bus\Queueable;
	use Illuminate\Mail\Mailable;
	use Illuminate\Queue\SerializesModels;

	class OrderShipped extends Mailable
	{
	    use Queueable, SerializesModels;

	    protected $order;

	    public function __construct(Order $order)
	    {
	        $this->order = $order;
	    }

	    public function build()
	    {
	        return $this
	        	->view('emails.orders.shipped')
                ->with([
                    'orderName' => $this->order->name,
                    'orderPrice' => $this->order->price,
                ]);
	    }
	}
?>


Attachments
=================

To add attachments to an email, use the attach method within the mailable class's build method.

The attach method accepts the full path to the file as its first argument:

<?
	public function build()
	{
	    return $this
	    	->view('emails.orders.shipped')
	        ->attach('/path/to/file');
	}
?>

When attatching files to a message, you can also specify the display name and/or MIME type by passing an array as the 2nd argument to the attach method:

<?
	public function build()
	{
	    return $this
	    	->view('emails.orders.shipped')
           	->attach('/path/to/file', [
                'as' => 'name.pdf',
                'mime' => 'application/pdf',
            ]);
	}
?>

If you have a stored file on one of your filesystem disks, you can attach it to the email using the attachFromStorage method:

<?
	public function build()
	{
		return 
	   		$this->view('emails.orders.shipped')
	    	->attachFromStorage('/path/to/file'); //optional 2nd argument to give file a name
	}
?>

Use attachFromStorageDisk to specify a storage disk other than your default disk:

<?
	public function build()
	{
		return $this
			->view('emails.orders.shipped')
	    	->attachFromStorageDisk('s3', '/path/to/file');
	}
?>

Use attachData to attach a raw string of bytes as an attachment. This could be used for a generated PDF in memory but not write it to the disk.

<?
	public function build()
	{
	    return $this
	    	->view('emails.orders.shipped')
	        ->attachData($this->pdf, 'name.pdf', [
	            'mime' => 'application/pdf',
	        ]);
	}
?>


Inline Attachments
====================

Embedding inline images to your emails is typically cumbersome. Laravel provides a convenient way to attach images to your emails.

Use the embed method on the $message variable within your email template.

Laravel automatically makes the $message variable available to all of your templates.

	<body>
	    Here is an image:

	    <img src="{{ $message->embed($pathToImage) }}">
	</body>

If you already have a raw image data string you wish to embed into a template, call the embedData method on the $message variable.

When calling this method, provide a filename that should be assigned to the embedded image:

	<body>
	    Here is an image from raw data:

	    <img src="{{ $message->embedData($data, 'example-image.jpg') }}">
	</body>


Customizing the SwiftMailer Message
====================================

The withSwiftMessage method of the Mailable base class allows you to register a closure that will be invoked with the SwiftMailer message instance before sending the message.

This gives you an opportunity to deeply customize the message before it's delivered:

<?
	public function build()
	{
	    $this->view('emails.orders.shipped');

	    $this->withSwiftMessage(function ($message) {
	        $message->getHeaders()->addTextHeader(
	            'Custom-Header', 'Header Value'
	        );
	    });
	}
?>