@extends('layouts.admin')

@section('title', 'Perfil')
@section('header', 'Perfil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-3">
                <div class="p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-3">
                <div class="p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-3">
                <div class="p-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
