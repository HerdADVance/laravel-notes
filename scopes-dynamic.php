
Dynamic Scopes
==============

Dynamic Scopes are used if you want to pass a parameter that could be anything. The below goes in the model, User in this case:

<?
	public function scopeOfType($query, $type){
    	return $query->where('type', $type);
	}
?>

It would be referenced in the controller or somewhere else like this:

<?
	User::ofType('admin')->get();
?>

This would select Users with "admin" in the "type" column.