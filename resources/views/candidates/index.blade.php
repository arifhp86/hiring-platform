@extends('layouts.app')

@section('title', 'Candidates')

@section('content')
    <div class="w-full p-6 bg-teal-100 text-right font-bold">Your wallet has: {{$coins ?? '?' }} coins</div>
    <candidates :candidates="{{ json_encode($candidates) }}" />
@endsection