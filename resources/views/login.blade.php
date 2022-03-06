
@extends('layouts.app')

@section('content')
<div id="login">
    <h3 class="text-center text-white pt-5">Login</h3>
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12">
                    <form id="login-form" class="form" action="/login" method="post">
                        @csrf
                        <h3 class="text-center text-dark">Login</h3>
                        <div class="form-group">
                            <label for="email" class="text-dark">Email:</label><br>
                            <input type="text" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-dark">Password:</label><br>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <br>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-dark btn-md" value="Aceptar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection