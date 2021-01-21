
Scopes allow for adding constraints to all queries for a given model.

Global Scopes
=============

Global Scopes go in the App/Scopes folder. The Apply method is where the query is defined.


<?php
class AgeScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('age', '>', 200);
    }
}
?>


To add the scope to a model, override the Booted method in the Model (the User model in this case):

<?php
	// This example calls the Scope from the Scopes folder
	protected static function booted(){
	    static::addGlobalScope(new AgeScope);
	}

	// This example does the same thing but anonymously - better for simpler use cases (doesn't require Scope from Scopes folder)
	protected static function booted(){
	    static::addGlobalScope('age', function (Builder $builder) {
	        $builder->where('age', '>', 200);
	    });
	}
?>

After implementing the above, "WHERE age > 200" will be added to a query on any group of users.

If you're building a query and want to disregard a scope, do the following:

<?php
// if created scope in Scopes folder
User::withoutGlobalScope(AgeScope::class)->get();
// if created scope anonymously (age would correlate to the first arg in addGlobalScope function)
User::withoutGlobalScope('age')->get();
// to remove all scopes:
User::withoutGlobalScopes()->get();
// to remove more than one but not all scope
User::withoutGlobalScopes([FirstScope::class, SecondScope::class ])->get();
?>



