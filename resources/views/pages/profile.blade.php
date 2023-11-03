{{-- Extends MainLayout --}}
@extends('layouts.mainLayout')

{{-- Change Title --}}
@section('title', 'Exp. Mgr. | User Profile')

{{-- Content Start --}}
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <div class="row">
            <h1 class="h3 mb-4 text-gray-800">User Profile</h1>
        </div>

        <div class="card shadow mb-4">

            <div class="card-body">

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Name :</label>
                    <div class="col-sm-10">
                        <label class="col col-form-label">{{ Auth::user()->name }}</label>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Email :</label>
                    <div class="col-sm-10">
                        <label class="col col-form-label">{{ Auth::user()->email }}</label>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">About :</label>
                    <div class="col-sm-10">
                        <label class="col col-form-label">Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos tempore odit ipsum accusamus corporis aliquid nihil deleniti odio ipsam corrupti? Eum dolorem hic architecto non recusandae, autem fugit nam in.</label>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
