@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Privatesia dhe Rregullorja</a></li>
                            <li class="breadcrumb-item active">Listimet per pune</li>
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
                                    <h3 class="fs-4 mb-1">Lista e puneve</h3>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="info1">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Titulli</th>
                                        <th scope="col">Punedhenesi</th>
                                        <th scope="col">Data e postimit</th>
                                        <th scope="col">Statusi</th>
                                        <th scope="col">Veprimtarite</th>
                                    </tr>
                                    </thead>
                                    <tbody class="border-0">
                                       @if($jobs->isNotEmpty())
                                           @foreach($jobs as $job)
                                               <tr>
                                                   <td>{{ $job->id }}</td>
                                                   <td>
                                                       <p>{{ $job->title }}</p>
                                                       <p>Applicants: {{ $job->applications->count() }}</p>
                                                   </td>
                                                   <td>{{ ucfirst($job->user->name) }} {{ucfirst($job->user->lastname)}}</td>
                                                   <td>{{ Carbon\Carbon::parse($job->created_at)->format('d M,Y') }}</td>
                                                   <td>
                                                       @if($job->status == 1)
                                                           <p class="text-success">Aktive</p>
                                                       @else
                                                           <p class="text-danger">Bllokuar</p>
                                                       @endif
                                                   </td>

                                                   <td></td>
                                                   <td>
                                                       <div class="action-dots">
                                                           <button href="#" class=" btn" data-bs-toggle="dropdown" aria-expanded="false">
                                                               <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                           </button>
                                                           <ul class="dropdown-menu dropdown-menu-end">
                                                               <li><a class="dropdown-item" href="{{ route('admin.jobs.edit', $job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Modifiko</a></li>
                                                               <li><a class="dropdown-item" onclick="deleteJob({{ $job->id }})" href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i> Fshi</a></li>
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
        function deleteJob(id){
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
                        url : '{{ route("admin.jobs.destroy") }}',
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
                                window.location.href = '{{ route("admin.jobs") }}';
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
