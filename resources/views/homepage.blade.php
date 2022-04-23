@extends('layouts.app')

@section('content')
    <transition
        enter-active-class="transition ease-out duration-200 transform"
        enter-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-out duration-200 transform"
        leave-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <router-view></router-view>
    </transition>
@endsection