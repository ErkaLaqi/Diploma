@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Faqja Kryesore</a></li>
                            <li class="breadcrumb-item active">Listimet e mia</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('front.account.sidebar')
                </div>
                <div class="col-lg-9">
                    @include('front.message')

                        <div class="card border-0 shadow mb-4 p-3">
                            <div class="card-body card-form">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3 class="fs-4 mb-1">Listimet e mia</h3>
                                    </div>
                                    <div style="margin-top: -10px;">
                                        <a href="{{ route('account.createJob') }}" class="btn btn-primary">Listo nje njoftim pune</a>
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table class="table ">
                                        <thead class="bg-light">
                                        <tr>
                                            <th scope="col">Titulli</th>
                                            <th scope="col">Data e postimit</th>
                                            <th scope="col">Nr Aplikimeve</th>
                                            <th scope="col">Statusi</th>
                                            <th scope="col">Veprimtarite</th>
                                        </tr>
                                        </thead>
                                        <tbody class="border-0">

                                        @if($jobs->isNotEmpty())
                                            @foreach($jobs as $job)
                                                <tr class="active">
                                                    <td>
                                                        <div class="job-name fw-500">{{ $job->title }}</div>
                                                        <div class="info1">{{ $job->jobType->name }} . {{ $job->location }}</div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</td>
                                                    <td>{{ $job->applications->count() }} Aplikime</td>
                                                    <td>
                                                        @if($job->status == 1)
                                                            <div class="job-status text-capitalize">Aktive</div>
                                                        @else
                                                            <div class="job-status text-capitalize" style="color: red !important;" >Bllokuar</div>
                                                        @endif

                                                    </td>
                                                    <td>
                                                        <div class="action-dots float-end">
                                                            <button href="#" class=" btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="{{ route('jobDetail', $job->id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> Detajet</a></li>
                                                                <li><a class="dropdown-item" href="{{ route('account.editJobs', $job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Modifiko</a></li>
                                                                <li><a class="dropdown-item" href="#" onclick="deleteJob({{ $job->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Fshi</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        @endif
                                </tbody>

                                </table>
                            </div>
                               <div>
                                   {{ $jobs->links() }}
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
        function deleteJob(jobId){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request to delete the job
                    $.ajax({
                        url : '{{ route("account.deleteJob") }}',
                        type : 'POST',
                        data: {jobId: jobId},
                        dataType: 'JSON',
                        success: function (response) {
                            // Show the success message
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your job has been deleted.",
                                icon: "success"
                            }).then(() => {
                                // Redirect after deletion
                                window.location.href = '{{ route("account.myJobs") }}';
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
