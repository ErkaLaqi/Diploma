@extends('front.layouts.app')

@section('main')

    <section class="section-5">
        <div class="container my-5">
            <div class="py-lg-2">&nbsp;</div>

            @include('front.message')

            <div class="row d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow border-0 p-5">
                        <h1 class="h3">Rivendos Fjalëkalimin?</h1>
                        <form action="{{ route('account.processResetPassword') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $tokenString }}">
                            <div class="mb-3">
                                <label for="new_password" class="mb-2">Fjalëkalimi i Ri</label><span style="color: red !important; display: inline; float: none;">*</span>
                                <input type="password" name="new_password" id="new_password" value="" class="form-control @error('new_password') is-invalid @enderror" placeholder="New Password">
                                @error('new_password')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="mb-2">Konfirmo Fjalëkalimin</label><span style="color: red !important; display: inline; float: none;">*</span>
                                <input type="password" name="confirm_password" id="confirm_password" value="" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm Password">
                                @error('confirm_password')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="justify-content-between d-flex">
                                <button class="btn btn-primary mt-2">Ruaj</button>
                            </div>
                        </form>

                    </div>
                    <div class="mt-4 text-center">
                        <p>Ke nje llogari? <a  href="{{ route('account.login') }}">Hyr</a></p>
                    </div>
                </div>
            </div>
            <div class="py-lg-5">&nbsp;</div>
        </div>
    </section>
@endsection
