
Blade provides convenient shortcuts for common PHP control structures such as conditionals and loops.

If Statements
==============

	@if (count($records) === 1)
	    I have one record!
	@elseif (count($records) > 1)
	    I have multiple records!
	@else
	    I don't have any records!
	@endif


	@unless (Auth::check())
    	You are not signed in.
	@endunless


	@isset($records)
    	// $records is defined and is not null...
	@endisset

	@empty($records)
    	// $records is "empty"...
	@endempty


	@auth
    	// The user is authenticated...
	@endauth

	@auth('admin')
	    // The user is authenticated...
	@endauth

	@guest
	    // The user is not authenticated...
	@endguest

	@guest('admin')
	    // The user is not authenticated...
	@endguest


	@production
	    // Production specific content...
	@endproduction

	@env('staging')
	    // The application is running in "staging"...
	@endenv

	@env(['staging', 'production'])
	    // The application is running in "staging" or "production"...
	@endenv


	@hasSection('navigation')
	    <div class="pull-right">
	        @yield('navigation')
	    </div>

	    <div class="clearfix"></div>
	@endif

	@sectionMissing('navigation')
	    <div class="pull-right">
	        @include('default-navigation')
	    </div>
	@endif



Switch Statements
=================

	@switch($i)
	    @case(1)
	        First case...
	        @break

	    @case(2)
	        Second case...
	        @break

	    @default
	        Default case...
	@endswitch


Loops
==============

	@for ($i = 0; $i < 10; $i++)
	    The current value is {{ $i }}
	@endfor

	@foreach ($users as $user)
	    <p>This is user {{ $user->id }}</p>
	@endforeach

	@forelse ($users as $user)
	    <li>{{ $user->name }}</li>
	@empty
	    <p>No users</p>
	@endforelse

	@while (true)
	    <p>I'm looping forever.</p>
	@endwhile


You can also use break and continue as you normally would:

	@foreach ($users as $user)
	    @if ($user->type == 1)
	        @continue
	    @endif

	    <li>{{ $user->name }}</li>

	    @if ($user->number == 5)
	        @break
	    @endif
	@endforeach


The loop variable provides useful info about the current index and whether it's the first or last iteration:

	$loop->index       starts at 0
		 ->iteration   starts at 1
		 ->remaining   iterations remaining
		 ->count       total items in loop
		 ->first/last  obvious
		 ->even/odd    obvious
		 ->depth       nesting level of current loop
		 ->parent      the parent's loop variable


Comments
===========

{{-- This comment will not be present in the rendered HTML --}}


Includes (Subviews)
===================

Blade's @include directive allows you to include a Blade view from within another view. 

All variables that are available to the parent view will be made available to the included view.

	<div>
	    @include('shared.errors')

	    <form>
	        <!-- Form Contents -->
	    </form>
	</div>

Laravel will throw an error on @include if the view doesn't exist. If you want to include a view that may or may not exist, use @includeIf:

	@includeIf('view.name', ['status' => 'complete'])


Similarly, use @includeWhen or @includeUnless to base whether a view is shown on the value of a boolean:

	@includeWhen($boolean, 'view.name', ['status' => 'complete'])
	@includeUnless($boolean, 'view.name', ['status' => 'complete'])


Rendering Views For Collections
==============================

You can combine loops and includes into one line with Blade's @each directive:

	@each('view.name', $jobs, 'job', 'view.empty')

This will render the view.name view for each item in the $jobs array. The 3rd variable ('job') will be the name given to the variable in the iteration of the view. The optional 4th variable ('view.empty') defines a view that will be shown if the $jobs array is empty.


The "Once" Directive
====================

This allows you to define a portion of the template that will only be evaluated once per rendering cycle. This can be useful for pushing a piece of JS into the page's header.

	@once
	    @push('scripts')
	        <script>
	            // Your custom JavaScript...
	        </script>
	    @endpush
	@endonce
	

Raw PHP
========

Sometimes to just need to do this:

	@php
	    $counter = 1;
	@endphp

	

