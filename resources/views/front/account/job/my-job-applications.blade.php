@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Faqja Kryesore</a></li>
                            <li class="breadcrumb-item active">Aplikimet e mia</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('front.account.sidebar')
                </div>
                <div class="col-lg-9">
                    {{--@include('front.message')--}}
                    <div class="card border-0 shadow mb-4 p-3">
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1">Aplikimet e mia</h3>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Titulli</th>
                                        <th scope="col">Data e aplikimit</th>
                                        <th scope="col">Nr Aplikimeve</th>
                                        <th scope="col">Statusi</th>
                                        <th scope="col">Veprimtarite</th>
                                    </tr>
                                    </thead>
                                    <tbody class="border-0">

                                    @if($jobApplications->isNotEmpty())
                                        @foreach($jobApplications as $jobApplication)
                                            <tr class="active">
                                                <td>
                                                    <div class="job-name fw-500">{{ $jobApplication->job->title }}</div>
                                                    <div class="info1">{{ $jobApplication->job->jobType->name }} . {{ $jobApplication->job->location }}</div>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($jobApplication->applied_date)->format('d M, Y') }}</td>
                                                <td>{{ $jobApplication->job->applications->count() }} Aplikim</td>
                                                <td>
                                                    @if($jobApplication->job->status == 1)
                                                        <p class="text-success">Aktive</p>
                                                    @else
                                                        <p class="text-danger">Bllokuar</p>
                                                    @endif

                                                </td>
                                                <td>
                                                    <div class="action-dots float-end">
                                                        <button href="#" class=" btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="{{ route('jobDetail', $jobApplication->job_id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> Detajet</a></li>
                                                            <li><a class="dropdown-item" href="#" onclick="removeJob({{ $jobApplication->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Fshi</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">Nuk ka aplikime pune te regjistruara.</td>
                                        </tr>
                                    @endif
                                    </tbody>

                                </table>
                            </div>
                            <div>
                                {{ $jobApplications->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJS')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        function removeJob(id){
            Swal.fire({
                title: "Konfirmo nese deshiron ta fshish!",
                text: "Nuk mund ta kthesh kete veprim!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Po,fshi!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request to delete the job
                    $.ajax({
                        url : '{{ route("account.removeJobs") }}',
                        type : 'POST',
                        data: {id: id},
                        dataType: 'JSON',
                        success: function (response) {
                            // Show the success message
                            Swal.fire({
                                title: "U fshi!",
                                text: "Aplikimi juaj u fshi me sukses!",
                                icon: "success"
                            }).then(() => {
                                // Redirect after deletion
                                window.location.href = '{{ route("account.myJobApplications") }}';
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
