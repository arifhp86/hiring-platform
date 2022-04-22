@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="container">
        <h1 class="p-40 text-4xl font-bold text-center">Welcome to the hiring platform</h1>
        <div class="flex items-center justify-center">
            <a href="/candidates-list" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow align-middle">
                Go to candidates list
            </a>
        </div>
    </div>
@endsection