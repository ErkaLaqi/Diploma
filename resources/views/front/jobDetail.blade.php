@extends('front.layouts.app')

@section('main')
    <section class="section-4 bg-2">
        <div class="container pt-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Njoftime pune</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container job_details_area">
            <div class="row pb-5">
                <div class="col-md-8">
                    @include('front.message')
                    <div class="card shadow border-0">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">

                                    <div class="jobs_conetent">
                                        <a href="#">
                                            <h4>{{ $job->title }}</h4>
                                        </a>
                                        <div class="links_locat d-flex align-items-center">
                                            <div class="location">
                                                <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                            </div>
                                            <div class="location">
                                                <p> <i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jobs_right">
                                    <div class="apply_now {{ ($savedJobCount == 1) ? 'saved-job' : '' }}">
                                        <a class="heart_mark" href="javascript:void(0);" onclick="savedJob( {{ $job->id }})"> <i class="fa fa-heart-o" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="descript_wrap white-bg">
                            <div class="single_wrap">
                                <h4>Pershkrimi i punes</h4>
                                {!! nl2br($job->description) !!}
                            </div>
                            <div class="single_wrap">

                                @if(!empty($job->responsibility))
                                    <div class="single_wrap">
                                    <h4>Pergjegjesite</h4>
                                    {!! nl2br($job->responsibility) !!}
                                    </div>
                                @endif

                            </div>
                            <div class="single_wrap">
                                @if(!empty($job->qualifications))
                                    <div class="single_wrap">
                                    <h4>Kualifikimet</h4>
                                    {!! nl2br($job->qualifications) !!}
                                    </div>

                                @endif

                            </div>
                            <div class="single_wrap">
                                @if(!empty($job->benefits))
                                    <div class="single_wrap">
                                    <h4>Benefitet</h4>
                                    {!! nl2br($job->benefits) !!}
                                    </div>
                                @endif

                            </div>
                            <div class="border-bottom"></div>
                            <div class="pt-3 text-end">
                                @if(Auth::check())
                                    <a href="#" onclick="savedJob({{ $job->id }})" class="btn btn-secondary">Ruaj</a>
                                @else
                                    <a href="javascript:void(0);" class="btn btn-secondary disabled">Hyr per te ruajtur njoftimin</a>
                                @endif
                                @if(Auth::check())
                                    <a href="#" onclick="applyJob({{ $job->id }})" class="btn btn-primary">Apliko</a>
                                @else
                                    <a href="javascript:void(0);" class="btn btn-primary disabled">Hyr per te aplikuar</a>
                                @endif

                            </div>
                        </div>
                    </div>
                    @if(Auth::user())
                        @if(Auth::user()->id == $job->user_id) {{-- Nese user i loguar eshte ai i cili e ka postuar punen( employer), shfaq tabelen e aplikanteve--}}

                    <div class="card shadow border-0">
                        <div class="job_details_header">
                            <div class="single_jobs white-bg d-flex justify-content-between">
                                <div class="jobs_left d-flex align-items-center">

                                    <div class="jobs_conetent">
                                        <a href="#">
                                            <h4>Aplikantet</h4>
                                        </a>

                                    </div>
                                </div>
                                <div class="jobs_right"></div>
                            </div>
                        </div>

                        <div class="descript_wrap white-bg">
                            <table class="table table-striped">
                                <tr>
                                    <th>Emri</th>
                                    <th>Email</th>
                                    <th>Numri telefonit</th>
                                    <th>Data e aplikimit</th>
                                </tr>
                                @if($applications->isNotEmpty())
                                    @foreach($applications as $application)
                                        <tr>
                                            <th>{{ $application->user->name }}</th>
                                            <th>{{ $application->user->email }}</th>
                                            <th>{{ $application->user->mobile }}</th>
                                            <th>{{ \Carbon\Carbon::parse($application->applied_date)->format('d M, Y') }}</th>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">Asnje aplikim</td>
                                    </tr>
                                @endif

                            </table>
                         </div>
                      </div>
                        @endif
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card shadow border-0">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Permbledhje rreth njoftimit te punes</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Publikuar me: <span>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</span></li>
                                    <li>Vende te lira: <span>{{ $job->vacancy }}</span></li>
                                    @if(!empty($job->salary))
                                    <li>Paga: <span>$ {{ $job->salary }}</span></li>
                                    @endif

                                    <li>Vendodhja: <span>{{ $job->location }}</span></li>

                                    <li>Tipi i punes: <span>{{ $job->jobType->name }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow border-0 my-4">
                        <div class="job_sumary">
                            <div class="summery_header pb-1 pt-4">
                                <h3>Detajet e Kompanise</h3>
                            </div>
                            <div class="job_content pt-3">
                                <ul>
                                    <li>Emri: <span>{{ $job->company_name }}</span></li>
                                    @if(!empty($job->company_location))
                                    <li>Vendodhja: <span>{{ $job->company_location }}</span></li>
                                    @endif
                                    @if(!empty($job->company_website))
                                    <li>Website: <span><a href="{{ $job->company_website }}"> {{ $job->company_website }} </a></span></li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJS')
    <script type="text/javascript">
        function applyJob(id){
            if(confirm("Je e sigurte se do te aplikosh ne kete pune?")){
                $.ajax({
                    url: '{{ route("applyJob") }}',
                    type: 'post',
                    data: {id:id},
                    dataType: 'json',
                    success: function (response) {
                        window.location.href = "{{ url()->current() }}";
                    }
                })
            }
        }


        function savedJob(id){
             $.ajax({
                    url: '{{ route("savedJob") }}',
                    type: 'post',
                    data: {id:id},
                    dataType: 'json',
                    success: function (response) {
                        window.location.href = "{{ url()->current() }}";
                    }
             })
        }
    </script>
@endsection
