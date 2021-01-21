
When running applications behind a load balancer that terminates TLS/SSL certificates, you may notice your application sometimes does not generate HTTPS links when using the url helper. This is becaue your app is being forwarded traffic from your load balancer on port 80 and doesn't know it should generate secure links.

To solve this, use the TrustProxies middleware that is included in your Laravel app. It quickly allows you to customize the load balancers or proxies that should be trusted by your app.

Your trusted proxies should be listed as an array on the $proxies property of the middleware. You can also use $headers to configure proxy headers that should be trusted:

<?
	namespace App\Http\Middleware;

	use Fideloper\Proxy\TrustProxies as Middleware;
	use Illuminate\Http\Request;

	class TrustProxies extends Middleware
	{
	    protected $proxies = [
	        '192.168.1.1',
	        '192.168.1.2',
	    ];

	    // or might need to trust all proxies if using AWS or another cloud load balancer provider since you might not know actual IP addresses of balancers
	    protected $proxies = '*';


	    protected $headers = Request::HEADER_X_FORWARDED_ALL;

	    // or use this if using AWS Elastic Load Balancing
	    protected $headers = Request::HEADER_X_FORWARDED_AWS_ELB
	}
?>

