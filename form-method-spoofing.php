
HTML forms don't support PUT, PATCH, or DELETE actions. So when defining one of those routes that are called from an HTML form, you need to add a hidden _method field to the form.

<form action="/example" method="POST">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>

For convenience, you can use the @method Blade directive to generate the hidden _method field:

<form action="/example" method="POST">
    @method('PUT')
    @csrf
</form>