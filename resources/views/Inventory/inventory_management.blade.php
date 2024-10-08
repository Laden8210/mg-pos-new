@extends('layout')
@section('title', 'Inventory Management')
<script src="{{ asset('js/popper.min.js') }}"></script>

@section('content')

    @livewire('inventory-management')
@stop
