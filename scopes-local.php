
Local Scopes
=============

Local Scopes allow you to define common sets of constraints easily re-used throughout the application.

<?php
	// This goes in whichever Model you want. Local scopes should always return a query builder instance
	public function scopePopular($query){
	    return $query->where('votes', '>', 100);
	}
?>

To utilize a local scope, call it like such in your controller or wherever you're building a query.

<?php
	// it's named popular because it's called scopePopular in the model (always drop the scope and capital letter)
	User::popular()->get
	// This can also be chained with other methods
	User::popular()->active()->orderBy('created_at')->get();
?>