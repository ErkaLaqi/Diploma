@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Privatesia dhe Rregullorja</a></li>
                            <li class="breadcrumb-item active">Aplikimet per pune</li>
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
                        <div class="card-body card-form">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="fs-4 mb-1">Lista e Aplikimeve</h3>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="info1">
                                    <tr>
                                        <th scope="col">Titulli Punes</th>
                                        <th scope="col">Punemarresi</th>
                                        <th scope="col">Punedhenesi</th>
                                        <th scope="col">Data e Aplikimit</th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="border-0">
                                    @if($applications->isNotEmpty())
                                        @foreach($applications as $application)
                                            <tr>
                                                <td>
                                                    <p>{{ $application->job->title }}</p>
                                                <td>{{ ucfirst($application->user->name) }} {{ucfirst($application->user->lastname)}}</td>
                                                <td>{{ ucfirst($application->employer->name) }} {{ucfirst($application->employer->lastname)}}</td>
                                                <td>{{ Carbon\Carbon::parse($application->applied_date)->format('d M,Y') }}</td>
                                                <td></td>
                                                <td>
                                                    <div class="float-left">
                                                        <button class="btn btn-danger btn-sm" onclick="deleteJobApplication({{ $application->id }})">
                                                            <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                        </button>
                                                    </div>


                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                {{ $applications->links() }}
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
        function deleteJobApplication(id){
            Swal.fire({
                title: "Deshiron ta konfirmosh?",
                text: "Nuk mund ta ktheni kete veprim!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "PO, fshi!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request to delete the job
                    $.ajax({
                        url : '{{ route("admin.jobApplications.destroy") }}',
                        type : 'DELETE',
                        data: {id: id},
                        dataType: 'JSON',
                        success: function (response) {
                            // Show the success message
                            Swal.fire({
                                title: "U fshi!",
                                text: "Listimi u fshi me sukses.",
                                icon: "success"
                            }).then(() => {
                                // Redirect after deletion
                                window.location.href = '{{ route("admin.jobApplications") }}';
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
