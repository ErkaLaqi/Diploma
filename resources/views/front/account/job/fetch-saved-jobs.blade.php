@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Account Settings</li>
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
                                    <h3 class="fs-4 mb-1">Saved Jobs</h3>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Applicants</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="border-0">

                                    @if($fetchSavedJobs->isNotEmpty())
                                        @foreach($fetchSavedJobs as $fetchSavedJob)
                                            <tr class="active">
                                                <td>
                                                    <div class="job-name fw-500">{{ $fetchSavedJob->job->title }}</div>
                                                    <div class="info1">{{ $fetchSavedJob->job->jobType->name }} . {{ $fetchSavedJob->job->location }}</div>
                                                </td>
                                                <td>{{ $fetchSavedJob->job->applications->count() }} Applications</td>
                                                <td>
                                                    @if($fetchSavedJob->job->status == 1)
                                                        <div class="job-status text-capitalize">Active</div>
                                                    @else
                                                        <div class="job-status text-capitalize" style="color: red !important;" >Blocked</div>
                                                    @endif

                                                </td>
                                                <td>
                                                    <div class="action-dots float-end">
                                                        <button href="#" class=" btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="{{ route('jobDetail', $fetchSavedJob->job_id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                                            <li><a class="dropdown-item" href="#" onclick="removeSavedJob({{ $fetchSavedJob->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">There are no job applications registered.</td>
                                        </tr>
                                    @endif
                                    </tbody>

                                </table>
                            </div>
                            <div>
                                {{ $fetchSavedJobs->links() }}
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
        function removeSavedJob(id){
            Swal.fire({
                title: "Are you sure you want to remove?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, remove it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request to delete the job
                    $.ajax({
                        url : '{{ route("account.removeSavedJob") }}',
                        type : 'POST',
                        data: {id: id},
                        dataType: 'JSON',
                        success: function (response) {
                            // Show the success message
                            Swal.fire({
                                title: "Removed!",
                                text: "Your job  has been removed.",
                                icon: "success"
                            }).then(() => {
                                // Redirect after deletion
                                window.location.href = '{{ route("account.fetchSavedJobs") }}';
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
