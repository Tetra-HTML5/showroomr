@extends('frontend.template')

@section('content')

        <div class="article">
        	<div data-role="collapsible-set">	
	      	@foreach ($faqs as $faq)
	      	 
			
	
			        <div data-role="collapsible" data-collapsed-icon="carat-r" data-expanded-icon="carat-d">
			          <h3>{{ $faq->faq_question }}</h3>
			          <p>{{ $faq->faq_answer }}</p>
			        </div>
		     
			@endforeach
			</div>
        </div>
        
        @if (count($faqs) == 0)
			<div class="exclamation">
				!
			</div>
			
			
			<div class="exclamation_text">
				<p> Er zijn momenteel geen FAQ's.</p>
			</div>
				
		
		@endif


@endsection
