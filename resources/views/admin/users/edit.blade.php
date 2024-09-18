@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Privatesia dhe Rregullorja</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.users')}}">Perdoruesit</a></li>
                            <li class="breadcrumb-item active">Modifiko</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.sidebar')
                </div>
                <div class="col-lg-9">
                    @include('front.message')
                    <div class="card border-0 shadow mb-4">
                        <form action="" method="post" id="userForm" name="userForm">
                            <div class="card-body  p-4">
                                <h3 class="fs-4 mb-1">Perditeso profilin |{{ ucfirst($user->name) }} {{ucfirst($user->lastname)}} </h3>
                                <div class="mb-4">
                                    <label for="name" class="mb-2">Emri</label>
                                    <input type="text" name="name" id="name"  class="form-control" value="{{ $user->name }}">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="lastname" class="mb-2">Mbiemri</label>
                                    <input type="text" name="lastname" id="lastname" class="form-control" value="{{ $user->lastname }}">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="mb-2">Email</label>
                                    <input type="text" name="email" id="email"  class="form-control"value="{{ $user->email }}">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="birthday" class="mb-2">Datelindja</label>
                                    <input type="date" name="birthday" id="birthday"  class="form-control" value="{{ $user->birthday }}">
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="designation" class="mb-2">Profesioni</label>
                                    <input type="text" name="designation" id="designation"  class="form-control" value="{{ $user->designation }}">
                                </div>
                                <div class="mb-4">
                                    <label for="mobile" class="mb-2">Nr.Kontakti</label>
                                    <input type="text" name="mobile" id="mobile"  class="form-control" value="{{ $user->mobile }}">
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Perditeso</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJS')
    <script type="text/javascript">
        $("#userForm").submit(function (e){
            e.preventDefault();

            $.ajax({
                url: '{{ route("admin.users.update",$user->id) }}',
                type: 'put',
                dataType: 'json',
                data: $("#userForm").serializeArray(),
                success: function (response){

                    if(response.status === true){

                        $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#lastname").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#email").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#birthday").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')
                        window.location.href = "{{ route('admin.users.update',$user->id) }}";

                    } else {
                        var errors = response.errors;
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
                    }
                }
            })
        });

    </script>

@endsection
