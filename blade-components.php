
Components and slots provide similar benefits to sections, layouts, and includes.

There are two approaches when writing components: class-based and anonymous.

To create a class-based component, use the make:component Artisan command. The following command will create an Alert component in the App\View\Components directory:

	php artisan make:component Alert


This command will also create a view template for the component in resources/views/components.

To create subcomponents do:
	
	php artisan make:component Alert/SubAlert


Rendering Components
====================

To display a component, use the Blade component tag within one of your Blade templates.

Blade component tags start with the string x followed by the kebab case name of the component class:

	<x-alert/>
	<x-user-profile/>

If the component class is nested deeper within the App\View\Components directory, use dot notation. The example below would be located in App\View\Components\Inputs\Button.php

	<x-inputs.button/>


Passing Data to Components
==========================

You can pass data to Blade components using HTML attributes. Hard-coded, primitive values may be passed to the component using simple HTML attribute strings. PHP expressions and variables should be passed to the component via attribuets that use the : character as a prefix:

	<x-alert type="error" :message="$message"/>

You should define the component's required data in its class constructor. All public properties on a component will automatically be made available to the component's view. It's not necessary to pass the data to the view from the component's render method:

<?
	namespace App\View\Components;

	use Illuminate\View\Component;

	class Alert extends Component
	{
	    public $type;

	    public $message;

	    public function __construct($type, $message)
	    {
	        $this->type = $type;
	        $this->message = $message;
	    }

	    public function render()
	    {
	        return view('components.alert');
	    }
	}
?>


Casing
=============

Component constructor arguments should be camelCase, but kebab-case should be used when referencing the argument names in your HTML attributes:

<?
	public function __construct($alertType)
	{
	    $this->alertType = $alertType;
	}
?>

	<x-alert alert-type="danger" />


Component Methods
==================

Any public methods on the component may be invoked in a template:

<?
	public function isSelected($option)
	{
	    return $option === $this->selected;
	}
?>

	<option {{ $isSelected($value) ? 'selected="selected"' : '' }} value="{{ $value }}">
    	{{ $label }}
	</option>


Accessing Attributes and Slots Within Component Classes
======================================================

Blade components allow you to access the component name, attributes, and slot inside the class's render method. However, you should return a closure from your component's render method in order to access this data.

The closure will receive a $data array as its only argument. This array will contain several elements that provide info about the component:

<?
	public function render()
	{
	    return function (array $data) {
	        // $data['componentName'];
	        // $data['attributes'];
	        // $data['slot'];

	        return '<div>Components content</div>';
	    };
	}
?>

The componentName is equal to the name used in the HTML tag after the x-prefix. (x-alert becomes alert)

The attributes element will contain all of the attributes present on the HTML tag.

The slot element is an Illuminate\Support\HtmlString instance with the contents of the component's slot.

The closure should return a string. If the returned string corresponds to an existing view, that view will be rendered. Otherwise, the returned string will be evaluated as an inline blade view.


Additional Dependencies
=======================

If your component requires additional dependencies from Laravel's service container, list them before any of the component's data attributes and they will automatically be injected by the container:

<?
	use App\Services\AlertCreator

	public function __construct(AlertCreator $creator, $type, $message)
	{
	    $this->creator = $creator;
	    $this->type = $type;
	    $this->message = $message;
	}
?>


Component Attributes
====================

Sometimes you may need to specify additional HTML attributes (such as class) that are not part of the data required for a component to function.

You'll want to pass these additional attributes down to the root element of the component template. For example, we want to render an alert component like this:

	<x-alert type="error" :message="$message" class="mt-4"/>

All of the attributes that aren't part of the component's constructor will automatically be added to the component's "attribute bag."

This bag is automatically made available to the component via the $attributes variable, and all of the attribuets can be rendered within the component by echoing this variable:

	<div {{ $attributes }}>
	    <!-- Component content -->
	</div>

As of now, you can not use directives such as @env within component tags. <x-alert :live="@env('production')"/> will not be compiled.


Default/Merged Attributes
=========================

Sometimes you'll need to specify default values for attributes or merge additional values into some of the component's attributes. To do this, use the attribute bag's merge method. It's useful for defining a set of CSS classes that should always be applied to a component:

	<div {{ $attributes->merge(['class' => 'alert alert-'.$type]) }}>
	    {{ $message }}
	</div>

A component utilized like this:

	<x-alert type="error" :message="$message" class="mb-4"/>

Will have its final rendered HTML appear like this:

	<div class="alert alert-error mb-4">
    	<!-- Contents of the $message variable -->
	</div>


Non-Class Attribute Merging
===========================

When merging attributes that aren't class attributes, the values provided to the merge method will be considered the default values of the attribute. However, unlike, the class attribute, these will not be merged with injected attribute values. They'll be overwritten.

	<button {{ $attributes->merge(['type' => 'button']) }}>
    	{{ $slot }}
	</button>

To render the button with a custom type, it may be specified when consuming the component. If no type is specified, the button type will be used
	
	<x-button type="submit">
    	Submit
	</x-button>

The rendered HTML of the button component above would be:
	
	<button type="submit">
	    Submit
	</button>

If you want an attribute other than class to have its default value and injected values joined together, use the prepends method.

In this example, the data-controller attribute will always begin with profile-controller and any additional injected data-controller values will be placed after this default value:

	<div {{ $attributes->merge(['data-controller' => $attributes->prepends('profile-controller')]) }}>
	    {{ $slot }}
	</div>

Retrieving and Filtering Attributes
===================================

You can filter attributes with the filter method. This method accepts a closure which should return true if you wish to retain the attribute in the attribute bag:

	{{ $attributes->filter(fn ($value, $key) => $key == 'foo') }}

The whereStartsWith method retrieves all attributes whose key begins with a given string:
	
	{{ $attributes->whereStartsWith('wire:model') }}

The first method renders the first attribute in a given attribute bag:
	
	{{ $attributes->whereStartsWith('wire:model')->first() }}

The has method checks if an attribute is present on the component:

	@if ($attributes->has('class'))
    	<div>Class attribute is present</div>
	@endif

The get method retrieves a specific attribute's value:

	{{ $attributes->get('class') }}


Slots
==========================

You'll often need to pass additional content to your components via slots.

Component slots are rendered by echoing the $slot variable.

	<!-- /resources/views/components/alert.blade.php -->
	<div class="alert alert-danger">
	    {{ $slot }}
	</div>

We pass content to the slot by injecting content into the component:

	<x-alert>
    	<strong>Whoops!</strong> Something went wrong!
	</x-alert>

Sometimes a component may need to render multiple different slots in different locations within the component. Here's the alert component modified to allow for the injection of a title slot:

	<!-- /resources/views/components/alert.blade.php -->
	<span class="alert-title">{{ $title }}</span>

	<div class="alert alert-danger">
	    {{ $slot }}
	</div>

You can define the content of the named slot using x-slot tag.

Any content not within an explicit x-slot tag will be passed to the component in the $slot variable:

	<x-alert>
	    <x-slot name="title">
	        Server Error
	    </x-slot>

	    <strong>Whoops!</strong> Something went wrong!
	</x-alert>


Scoped Slots
================

If you've used a JS framework like Vue, you may be familiar with scoped slots, which allow you to access data or methods from the component within your slot.

You can achieve similar behavior in Laravel by defining public methods or properties on your component and accessing it within your slot via the $component variable.

In this example, we assume the x-alert component has a public formatAlert method defined on its component class:

	<x-alert>
	    <x-slot name="title">
	        {{ $component->formatAlert('Server Error') }}
	    </x-slot>

	    <strong>Whoops!</strong> Something went wrong!
	</x-alert>


Inline Component Views
======================

For very small components, it might feel cumbersome to manage both the component class and the component's view template. In these cases, you can return the component's markup directly from the render method:

<?
	public function render()
	{
	    return <<<'blade'
	        <div class="alert alert-danger">
	            {{ $slot }}
	        </div>
	    blade;
	}
?>

You can create a component that renders an inline view with the inline option on the make:component command

	php artisan make:component Alert --inline


Anonymous Components
====================

Similar to inline components, anyonymous components provide a mechanism for managing a component via a single file. However, anonymous components utilize a single view file and have no associated class.

To define an anonymous component, you only need to place a Blade template within your resources/views/components directory.

	<!-- if component is defined at resources/views/components/alert.blade.php -->
	<x-alert/>

Since anonymous components don't have any associated class, you may wonder how to differentiate which data should be passed into the component as variables and which attributes should be placed in the component's attribute bag.

You can specify which attributes should be considered data variables using the @props directive at the top of your component's Blade template.

All other attributes on the component will be available using the component's attribute bag. To give a data variable a default value, you can specify the variable's name as the array key and the default value as the array value:

	<!-- /resources/views/components/alert.blade.php -->
	@props(['type' => 'info', 'message'])

	<div {{ $attributes->merge(['class' => 'alert alert-'.$type]) }}>
	    {{ $message }}
	</div>

With the component definition above, we can render it like this:

	<x-alert type="error" :message="$message" class="mb-4"/>


Dynamic Components
==================

Sometimes you may need to render a component but not know which should be rendered until runtime. In this situation, use Laravel's built-in dynamic-component to render the component based on a runtime value or variable:

	<x-dynamic-component :component="$componentName" class="mt-4" />



Manually Registering Package Components
=======================================

When writing components for your app, they're automatically discovered within the app/View/Components directory and the resources/views/components directory.

However, if you're building a package that utilizes Blade components, you'll need to manually register your component class and its HTML tag alias. You should typically do this in the boot method of your package's service provider.

<?
	use Illuminate\Support\Facades\Blade;

	public function boot()
	{
	    Blade::component('package-alert', Alert::class);
	}
?>

Once it's been registered, your component can be rendered using its tag alias: <x-package-alert/>

Alternatively, you can use the componentNamespace method to autoload component classes by convention. For example, a Nightshade package might have Calendar and ColorPicker components that reside within the Package\Views\Components namespace:

<?
	use Illuminate\Support\Facades\Blade;

	public function boot()
	{
	    Blade::componentNamespace('Nightshade\\Views\\Components', 'nightshade');
	}

?>

This will allow usage of the package components by their vendor namespace:
	
	<x-nightshade::calendar />
	<x-nightshade::color-picker />

