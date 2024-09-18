@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.jobs')}}">Listimet per pune</a></li>
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

                        <form action="" method="post" id="updateJobForm" name="updateJobForm" >
                            <div class="card border-0 shadow mb-4 ">
                                <div class="card-body card-form p-4">
                                    <h3 class="fs-4 mb-1">Modifiko detajet e punes</h3>
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="title" class="mb-2">Titulli<span class="req">*</span></label>
                                            <input value="{{ $job->title }}" type="text" id="title" name="title" class="form-control">
                                            <p></p>
                                        </div>
                                        <div class="col-md-6  mb-4">
                                            <label for="category" class="mb-2">Kategoria<span class="req">*</span></label>
                                            <select name="category" id="category" class="form-select">
                                                <option value="0">Zgjidh kategorine e punes</option>
                                                @if($categories->isNotEmpty())
                                                    @foreach($categories as $category)
                                                        <option {{ ($job->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="jobType" class="mb-2">Tipi i punes<span class="req">*</span></label>
                                            <select name="jobType" id="jobType" class="form-select">
                                                <option value="0">Zgjidh tipin e punes</option>
                                                @if($jobTypes->isNotEmpty())
                                                    @foreach($jobTypes as $jobType)
                                                        <option {{ ($job->job_type_id == $jobType->id) ? 'selected' : '' }} value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                        <div class="col-md-6  mb-4">
                                            <label for="vacancy" class="mb-2">Vende te lira<span class="req">*</span></label>
                                            <input value="{{ $job->vacancy }}" type="number" min="1"  id="vacancy" name="vacancy" class="form-control">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-4 col-md-6">
                                            <label for="salary" class="mb-2">Paga</label>
                                            <input value="{{ $job->salary }}" type="text"  id="salary" name="salary" class="form-control">
                                        </div>

                                        <div class="mb-4 col-md-6">
                                            <label for="location" class="mb-2">Vendodhja<span class="req">*</span></label>
                                            <input value="{{ $job->location }}" type="text"  id="location" name="location" class="form-control">
                                            <p></p>
                                        </div>
                                    </div>

                                 <div class="row">

                                    <div class="col-md-6  mb-4">
                                        <div class="form-check">
                                           <input {{ ($job->isFeatured == 1) ? 'checked' : '' }} class="form-check-input" type="checkbox" value="1" id="isFeatured" name="isFeatured">
                                           <label class="form-check-label" for="isFeatured">
                                            Promovo
                                           </label>
                                         </div>
                                    </div>

                                     <div class="col-md-6  mb-4">
                                         <div class="form-check-inline">
                                             <p>Statusi i Punes</p>
                                             <input {{ ($job->status == 1) ? 'checked' : '' }} class="form-check-input" type="radio" value="1" id="status-active" name="status">
                                             <label class="form-check-label" for="status">
                                                 Aktiv
                                             </label>
                                         </div>
                                         <div class="form-check-inline">
                                             <input {{ ($job->status == 0) ? 'checked' : '' }} class="form-check-input" type="radio" value="0" id="status-block" name="status">
                                             <label class="form-check-label" for="status">
                                                 Bllokuar
                                             </label>
                                         </div>
                                     </div>

                                    </div>

                                    <div class="row">
                                        <div class="mb-4">
                                            <label for="description" class="mb-2">Pershkrimi<span class="req">*</span></label>
                                            <textarea class="textarea" name="description" id="description" cols="5" rows="5" >{{ $job->description }}</textarea>
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="mb-4">
                                        <label for="benefits" class="mb-2">Benefitet</label>
                                        <textarea class="textarea" name="benefits" id="benefits" cols="5" rows="5" >{{ $job->benefits }}</textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="responsibility" class="mb-2">Pergjegjesite</label>
                                        <textarea class="textarea" name="responsibility" id="responsibility" cols="5" rows="5" >{{ $job->responsibility }}</textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="qualifications" class="mb-2">Kualifikimet</label>
                                        <textarea class="textarea" name="qualifications" id="qualifications" cols="5" rows="5" >{{ $job->qualifications }}</textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="experience" class="mb-2">Eksperienca<span class="req">*</span></label>
                                        <select name="experience" id="experience" class="form-control">
                                            <option value="0" {{ ($job->experience == 0) ? 'selected' : '' }}>Pa eksperience</option>
                                            <option value="1" {{ ($job->experience == 1) ? 'selected' : '' }}>1 Vit</option>
                                            <option value="2" {{ ($job->experience == 2) ? 'selected' : '' }}>2 Vite</option>
                                            <option value="3" {{ ($job->experience == 3) ? 'selected' : '' }}>3 Vite</option>
                                            <option value="4" {{ ($job->experience == 4) ? 'selected' : '' }}>4 Vite</option>
                                            <option value="5" {{ ($job->experience == 5) ? 'selected' : '' }}>5 Vite</option>
                                            <option value="6" {{ ($job->experience == 6) ? 'selected' : '' }}>6 Vite</option>
                                            <option value="7" {{ ($job->experience == 7) ? 'selected' : '' }}>7 Vite</option>
                                            <option value="8" {{ ($job->experience == 8) ? 'selected' : '' }}>8 Vite</option>
                                            <option value="9" {{ ($job->experience == 9) ? 'selected' : '' }}>9 Vite</option>
                                            <option value="10" {{ ($job->experience == 10) ? 'selected' : '' }}>10 Vite</option>
                                            <option value="10_plus" {{ ($job->experience == '10_plus' ) ? 'selected' : '' }}>+10 Vite</option>
                                        </select>
                                        <p></p>
                                    </div>

                                    <div class="mb-4">
                                        <label for="keywords" class="mb-2">Fjale Kyce</label>
                                        <input value="{{ $job->keywords }}" type="text"  id="keywords" name="keywords" class="form-control">
                                    </div>

                                    <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Detajet e Kompanise</h3>

                                    <div class="row">
                                        <div class="mb-4 col-md-6">
                                            <label for="company_name" class="mb-2">Emri<span class="req">*</span></label>
                                            <input value="{{ $job->company_name }}" type="text" id="company_name" name="company_name" class="form-control">
                                            <p></p>
                                        </div>

                                        <div class="mb-4 col-md-6">
                                            <label for="company_location" class="mb-2">Vendodhja</label>
                                            <input value="{{ $job->company_location }}" type="text" id="company_location" name="company_location" class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="website" class="mb-2">Website</label>
                                        <input value="{{ $job->company_website }}" type="text" id="website" name="website" class="form-control">
                                    </div>
                                </div>
                                <div class="card-footer  p-4">
                                    <button type="submit" class="btn btn-primary">Perditeso</button>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJS')
    <script type="text/javascript">
        $("#updateJobForm").submit(function (e){
            e.preventDefault();

            $("button[type='submit]").prop('disabled',true);

            $.ajax({
                url: '{{ route("admin.jobs.update", $job->id) }}',
                type: 'PUT',
                dataType: 'json',
                data: $("#updateJobForm").serializeArray(),
                success: function (response){

                    $("button[type='submit]").prop('disabled',false);

                    if(response.status === true){

                        $("#title").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#category").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#jobType").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#vacancy").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#location").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        $("#description").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')
                        $("#company_name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback')
                            .html('')

                        window.location.href = "{{ route('admin.jobs') }}";


                    } else {
                        var errors = response.errors;
                        /*title*/
                        if(errors.title){
                            $("#title").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.title[0])
                        }else{
                            $("#title").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }

                        if(errors.category){
                            $("#category").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.category[0]);
                        } else {
                            $("#category").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if(errors.jobType){
                            $("#jobType").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.jobType[0]);
                        } else {
                            $("#jobType").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        /*vacancy*/
                        if(errors.vacancy){
                            $("#vacancy").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.vacancy[0])
                        }else{
                            $("#vacancy").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        /*location*/
                        if(errors.location){
                            $("#location").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.location[0])
                        }else{
                            $("#location").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        /*description*/
                        if(errors.description){
                            $("#description").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.description[0])
                        }else{
                            $("#description").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('')
                        }
                        /*company_name*/
                        if(errors.company_name){
                            $("#company_name").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.company_name[0])
                        }else{
                            $("#company_name").removeClass('is-invalid')
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
