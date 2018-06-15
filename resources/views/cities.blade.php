@extends('template.base')

@section('title', 'Cities')

@section('keywords', 'cities, find, search, yourlocation')

@section('description', 'Find cities which are near to you')

@section('head')
 	<link rel="stylesheet" href="{{asset('css/home.css')}}" type="text/css">
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@endsection

@section('content')
	<div class="container">
		<div class="col-sm-12">
			<div class="searchCityContent">
				<div class="form-group has-feedback">
					{{-- {{ Form::text('cityInput', null, ['class' => 'form-control', 'placeholder'=> 'Search your city', 'id'=>'cityInput']) }} --}}
					<label for="cityInput">Search for near 20 locations(cities)</label>
					<input type="text" class="form-control" id="cityInput" placeholder="Search your city">
					<span class="reset-search glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
					{{-- need to run npm intall, npm run production --}}
					<ul id="allCountriesList" class="list-group"></ul>
				</div>
			</div>
		</div>
		<div class="col-sm-12">
			<div id="map"></div>
		</div>
	</div>
@endsection

@section('script')
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgBy_DHNpUMeEMYPlN7hrtpzCNZQ8sFDI&libraries=places&callback=initMap"
        async defer></script>
    <script src="{{asset('js/home.js')}}"></script>
@endsection
