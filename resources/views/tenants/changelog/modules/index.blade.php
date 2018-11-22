@extends('tenants.layouts.app')

@php
$channel = 'App.Log.Module.' . studly_case($module->name);
$title = 'Registre de canvis mòdul: ' . $module->name;
$returnUrl = '/' . $module->name;
$refresUrl = '/api/v1/changelog/module/' . $module->name;
@endphp

@section('content')
    <changelog refresh-url="{{ $refresUrl }}" return-url="{{ $returnUrl }}" title="{{ $title }}" channel="{{ $channel }}"  :logs="{{ $logs }}" :users="{{ $users }}"></changelog>
@endsection
