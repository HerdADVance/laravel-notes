
You'll want to define a layout as a single Blade component and use it throughout your application.

Here's an example layout component for a Todo app:

	<!-- resources/views/components/layout.blade.php -->
	<html>
	    <head>
	        <title>{{ $title ?? 'Todo Manager' }}</title>
	    </head>
	    <body>
	        <h1>Todos</h1>
	        <hr/>
	        {{ $slot }}
	    </body>
	</html>

One the layout component has been defined, we'll create a Blade view that utilizes the component:

	<!-- resources/views/tasks.blade.php -->
	<x-layout>
	    @foreach ($tasks as $task)
	        {{ $task }}
	    @endforeach
	</x-layout>

If we use the following instead of the above, we'll inject a custom $title variable into the title tag of the layout at the top:

	<!-- resources/views/tasks.blade.php -->
	<x-layout>
	    <x-slot name="title">
	        Custom Title
	    </x-slot>

	    @foreach ($tasks as $task)
	        {{ $task }}
	    @endforeach
	</x-layout>


Layouts Using Template Inheritance
==================================

Template inheritance was the primary way of building applications prior to the intro of components.

Notice the @show directive below. It will immediately yield the section as opposed to the @endsection directive which only defines a section.

The @yield directive accepts a 2nd default parameter that will be rendered if the section being yielded is undefined.

	<!-- resources/views/layouts/app.blade.php -->
	<html>
	    <head>
	        <title>App Name - @yield('title')</title>
	    </head>
	    <body>
	        @section('sidebar')
	            This is the master sidebar.
	        @show

	        <div class="container">
	            @yield('content')
	        </div>
	    </body>
	</html>

In the child view, the @extends Blade directive specifies which layout the child view should inherit.

	<!-- resources/views/child.blade.php -->
	@extends('layouts.app')

	@section('title', 'Page Title')

	@section('sidebar')
	    @parent

	    <p>This is appended to the master sidebar.</p>
	@endsection

	@section('content')
	    <p>This is my body content.</p>
	@endsection