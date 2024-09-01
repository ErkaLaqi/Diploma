@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active">Dashboard</li>
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
                    <div class="card border-0 shadow mb-4" style="border: 2px solid #ccc; padding: 20px;  max-width: 1000px; text-align: center;">

                           <p>In our job application platform, the role of the admin is pivotal in ensuring the smooth functioning and management of both user and job-related data. The admin has access to a comprehensive view of all the users registered within the application. This includes the ability to see detailed profiles, which consist of personal information such as names, email addresses, contact information, resumes, and any other relevant data the users have provided. The admin can monitor user activity, verify the authenticity of the information provided, and ensure that all users comply with the platform's guidelines and policies. This oversight helps maintain a secure and trustworthy environment for both job seekers and employers.</p>

                           <p> Moreover, the admin has the authority to edit users' personal data when necessary. This could involve updating contact information, correcting errors in the provided data, or assisting users who may have difficulty managing their profiles. The ability to edit user information is essential in maintaining the accuracy and relevance of the data within the application, which is crucial for both users seeking jobs and employers looking for suitable candidates. By having control over this data, the admin can also deactivate or ban users who violate terms of service or engage in fraudulent activities, thereby protecting the integrity of the platform.</p>

                           <p> In addition to managing users, the admin plays a crucial role in overseeing all job postings on the platform. This includes reviewing the details of each job listing, such as job titles, descriptions, requirements, qualifications, and salary information. The admin can make necessary edits to ensure that the job postings are clear, accurate, and in line with the platform's standards. This responsibility is vital for ensuring that job seekers have access to high-quality and legitimate job opportunities. By editing and updating job postings, the admin ensures that employers present their job offers effectively, which in turn helps attract the right candidates.</p>

                           <p> Furthermore, the admin is responsible for monitoring all job applications submitted by users. This involves having access to the list of applications made for each job posting, allowing the admin to track the flow of applications, identify any issues, and ensure that the process runs smoothly. The admin can also assist in resolving any disputes or problems that may arise between employers and applicants, such as application errors, miscommunications, or delays in responses. By overseeing this process, the admin helps maintain a fair and efficient application system that benefits both job seekers and employers.</p>

                           <p> In summary, the admin's role in a job application platform is multifaceted, encompassing the management of user data, the oversight of job postings, and the supervision of the application process. By ensuring the accuracy and integrity of the information on the platform, the admin contributes to creating a reliable and user-friendly environment where job seekers can find suitable opportunities and employers can connect with potential candidates. The admin's ability to view, edit, and monitor various aspects of the platform is essential in maintaining its overall functionality and success.</p>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJS')
@endsection
