@extends('template.base')

@section('title', 'Cities')

@section('keywords', 'cities, find, search, yourlocation')

@section('description', 'Find cities which are near to you')

@section('head')
 	<link rel="stylesheet" href="{{asset('css/home.css')}}" type="text/css">
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="container">
    	<div class="col-sm-12 margin-xs">
			<select class="form-control selectpicker" id="select-country" data-live-search="true">
				<option data-tokens="china">China</option>
				<option data-tokens="malayasia">Malayasia</option>
				<option data-tokens="singapore">Singapore</option>
			</select>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('js/home.js')}}"></script>
@endsection
