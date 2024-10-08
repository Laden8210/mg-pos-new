@extends(Auth::guard('employee')->user()->role === 'Manager' ? 'layout' : 'cashierlayout')

@section('title', 'User Account')

@section('content')
    @livewire('user.user-account-settings')
@endsection
