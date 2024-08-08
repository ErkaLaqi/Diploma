@extends('front.layouts.app')

@section('main')

<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>
                    <form action="" name="registrationForm" id="registrationForm">
                        <div class="mb-3">
                            <label for="name" class="mb-2">Name</label><span style="color: red !important; display: inline; float: none;">*</span>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                            <p></p>
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="mb-2">Lastname</label><span style="color: red !important; display: inline; float: none;">*</span>
                            <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Enter Lastname">
                            <p></p>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="mb-2">Email</label><span style="color: red !important; display: inline; float: none;">*</span>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email">
                            <p></p>
                        </div>
                        <div class="mb-3">
                            <label for="birthday" class="mb-2">Birthday</label><span style="color: red !important; display: inline; float: none;">*</span>
                            <input type="date" name="birthday" id="birthday" class="form-control" placeholder="Enter Birthday">
                            <p></p>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="mb-2">Password</label><span style="color: red !important; display: inline; float: none;">*</span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                            <p></p>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="mb-2">Confirm Password</label><span style="color: red !important; display: inline; float: none;">*</span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                            <p></p>
                        </div>
                        <button class="btn btn-primary mt-2">Register</button>
                    </form>
                </div>
                <div class="mt-4 text-center">
                    <p>Have an account? <a  href="{{ route('account.login') }}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJS')
    <script>
        $("#registrationForm").submit(function (e){
            e.preventDefault();
            $.ajax({
                url:'{{ route("account.processRegistration") }}',
                type:'POST',
                data: $("#registrationForm").serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if(response.status === false){
                        var errors = response.errors;
                        /*Name*/
                        if(errors.name){
                            $("#name").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.name[0])
                        }else{
                            $("#name").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        /*Lastname*/
                        if(errors.lastname){
                            $("#lastname").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.lastname[0])
                        }else{
                            $("#lastname").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        /*Email*/
                        if(errors.email){
                            $("#email").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.email[0])
                        }else{
                            $("#email").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        /*Birthday*/
                        if(errors.birthday){
                            $("#birthday").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.birthday[0])
                        }else{
                            $("#birthday").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        /*Password*/
                        if(errors.password){
                            $("#password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.password[0])
                        }else{
                            $("#password").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        /*Confirm Password*/
                        if(errors.confirm_password){
                            $("#confirm_password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.confirm_password[0])
                        }else{
                            $("#confirm_password").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                    }
                else{
                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('');

                    $("#lastname").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('');

                    $("#email").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('');

                    $("#birthday").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('');
                    $("#password").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('');

                    $("#confirm_password").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback')
                    .html('');
                    window.location.href='{{ route("account.login") }}'
                }
               }
            });
        });
    </script>
@endsection
