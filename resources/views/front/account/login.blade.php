@extends('front.layouts.app')

@section('main')

<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>

        @include('front.message')

        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Hyr</h1>
                    <form action="{{ route('account.authenticate') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="mb-2">Email</label><span style="color: red !important; display: inline; float: none;">*</span>
                            <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="shembull@shembull.com">

                            @error('email')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="mb-2">Fjalëkalimi</label><span style="color: red !important; display: inline; float: none;">*</span>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Shkruaj fjalëkalimin">

                            @error('password')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="justify-content-between d-flex">
                            <button class="btn btn-primary mt-2">Hyr</button>
                            <a href="{{ route('account.forgotPassword') }}" class="mt-3">Keni harruar fjalëkalimin?</a>
                        </div>
                    </form>
                </div>
                <div class="mt-4 text-center">
                    <p>Nuk ke nje llogari? <a  href="{{ route('account.registration') }}">Regjistrohu</a></p>
                </div>
            </div>
        </div>
        <div class="py-lg-5">&nbsp;</div>
    </div>
</section>
@endsection
