@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active">Privatesia dhe Rregullorja</li>
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

                           <p>Në platformën e aplikimeve për punë, roli i administratorit është thelbësor për të siguruar funksionimin e qetë dhe menaxhimin e të dhënave që lidhen me përdoruesit dhe punët. Administratori ka akses në një pamje të plotë të të gjithë përdoruesve të regjistruar brenda aplikacionit. Kjo përfshin mundësinë për të parë profile të detajuara, të cilat përmbajnë informacione personale si emrat, adresat e emailit, të dhënat e kontaktit, CV-të dhe çdo të dhënë tjetër të rëndësishme që përdoruesit kanë ofruar. Administratori mund të monitorojë aktivitetin e përdoruesve, të verifikojë vërtetësinë e informacionit të dhënë dhe të sigurojë që të gjithë përdoruesit të përmbushin udhëzimet dhe politikat e platformës. Ky mbikëqyrje ndihmon në mbajtjen e një mjedisi të sigurt dhe të besueshëm si për kërkuesit e punës ashtu edhe për punëdhënësit.</p>
                           <p>Për më tepër, administratori ka autoritetin të redaktojë të dhënat personale të përdoruesve kur është e nevojshme. Kjo mund të përfshijë përditësimin e informacionit të kontaktit, korrigjimin e gabimeve në të dhënat e ofruara ose ndihmimin e përdoruesve që mund të kenë vështirësi në menaxhimin e profileve të tyre. Mundësia për të redaktuar informacionin e përdoruesve është thelbësore për të ruajtur saktësinë dhe rëndësinë e të dhënave brenda aplikacionit, e cila është vendimtare si për përdoruesit që kërkojnë punë ashtu edhe për punëdhënësit që kërkojnë kandidatë të përshtatshëm. Duke pasur kontroll mbi këto të dhëna, administratori mund të çaktivizojë ose ndalojë përdoruesit që shkelin kushtet e shërbimit ose që përfshihen në aktivitete mashtruese, duke mbrojtur kështu integritetin e platformës.</p>
                           <p>Përveç menaxhimit të përdoruesve, administratori luan një rol të rëndësishëm në mbikëqyrjen e të gjitha shpalljeve të punës në platformë. Kjo përfshin rishikimin e detajeve të çdo shpalljeje pune, si titujt e punës, përshkrimet, kërkesat, kualifikimet dhe informacionet mbi pagat. Administratori mund të bëjë ndryshimet e nevojshme për t'u siguruar që shpalljet e punës janë të qarta, të sakta dhe në përputhje me standardet e platformës. Ky rol është jetik për të garantuar që kërkuesit e punës të kenë akses në mundësi pune cilësore dhe legjitime. Duke redaktuar dhe përditësuar shpalljet e punës, administratori siguron që punëdhënësit të prezantojnë ofertat e tyre të punës në mënyrë efektive, çka ndihmon në tërheqjen e kandidatëve të duhur.</p>
                           <p>Për më tepër, administratori është përgjegjës për monitorimin e të gjitha aplikimeve të dorëzuara nga përdoruesit. Kjo përfshin aksesin në listën e aplikimeve të bëra për çdo shpallje pune, duke i mundësuar administratorit të ndjekë fluksin e aplikimeve, të identifikojë ndonjë problem dhe të sigurojë që procesi të zhvillohet pa pengesa. Administratori mund të ndihmojë gjithashtu në zgjidhjen e mosmarrëveshjeve ose problemeve që mund të lindin midis punëdhënësve dhe aplikantëve, si gabime në aplikime, keqkuptime ose vonesa në përgjigje. Duke mbikëqyrur këtë proces, administratori ndihmon në mbajtjen e një sistemi aplikimi të drejtë dhe efikas që përfiton si kërkuesit e punës ashtu edhe punëdhënësit.</p>
                           <p>Në përmbledhje, roli i administratorit në një platformë aplikimi për punë është shumëdimensional, duke përfshirë menaxhimin e të dhënave të përdoruesve, mbikëqyrjen e shpalljeve të punës dhe monitorimin e procesit të aplikimit. Duke siguruar saktësinë dhe integritetin e informacionit në platformë, administratori kontribuon në krijimin e një mjedisi të besueshëm dhe miqësor për përdoruesit, ku kërkuesit e punës mund të gjejnë mundësi të përshtatshme dhe punëdhënësit mund të lidhen me kandidatët potencialë. Aftësia e administratorit për të parë, redaktuar dhe monitoruar aspekte të ndryshme të platformës është thelbësore për funksionimin dhe suksesin e saj të përgjithshëm.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJS')
@endsection
