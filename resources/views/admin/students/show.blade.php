@extends('layouts.tpl')

@section('content')
<div class="container">
    <h1>Storico Studente: {{ $student->first_name }} {{ $student->last_name }}</h1>
    <div class="card mt-4">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="">
            <div class="card-header">
                <h3>Dettagli di base</h3>
            </div>
            <div class="card-body">
                <p><strong>Data Iscrizione:</strong> {{ $student->created_at->format('d/m/Y') }}</p>
                <p><strong>Email:</strong> {{ $student->email }}</p>
                <p><strong>Numero di telefono:</strong> {{ $student->phone ?? 'Non disponibile' }}</p>
                <p>
                  <!-- <a href="{{ Storage::url($student->medical_certificate_path) }}" target="_blank" class="btn btn-sm btn-info">
                    Visualizza Certificato
                  </a> -->
                  <button class="btn btn-sm btn-info" id="toggle-pdf">Visualizza pdf</button>
                </p>
                
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-12">
          <div class="card-header">
              <h3>Frequenza</h3>
          </div>
          <div class="card-body">
            <p><strong>Corsi frequentati:</strong></p>
            
            <ul class="d-flex flex-wrap list-unstyled w-100">
              @foreach($student->courses as $course)
                <li>
                  <span class="badge 
                    @if($loop->index % 5 == 0) bg-primary 
                    @elseif($loop->index % 5 == 1) bg-success 
                    @elseif($loop->index % 5 == 2) bg-danger 
                    @elseif($loop->index % 5 == 3) bg-warning 
                    @else bg-info 
                    @endif mx-2">
                    {{ $course->title }}
                  </span>
                </li>
              @endforeach
            </ul>
              
          </div>
          
        </div>
 
        <div id="pdf">
          <object data="{{ Storage::url($student->medical_certificate_path) }}" type="application/pdf" width="100%" height="400">
            <a href="{{ Storage::url($student->medical_certificate_path) }}">test.pdf</a>
          </object>
        </div>
      </div>
       
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="card mt-4">
          <div class="card-header">
              <h3>Frequenza</h3>
          </div>
          <div class="card-body">
            <p><strong>Mesi frequentati:</strong></p>
          @php
            // Raccogliamo tutti i mesi/anni delle presenze
            $months = [];
            foreach ($student->attendances as $attendance) {
                $formattedMonth = \Carbon\Carbon::parse($attendance->attendance_date)
                    ->locale('it')
                    ->translatedFormat('F Y');
                $months[] = $formattedMonth;
            }
            // Eliminiamo i duplicati
            $uniqueMonths = array_unique($months);
          @endphp

          <ul>
              @foreach ($uniqueMonths as $month)
                  <li>{{ $month }}</li>
              @endforeach
          </ul>
        </div>
      </div>
      </div>
      <div class="col-md-6 col-sm-12">
       
      </div>
     

    <div class="card mt-4">
        <div class="card-header">
            <h3>Pagamenti</h3>
        </div>
        <div class="card-body">
            <p><strong>Pagamenti effettuati:</strong> </p>
            <ul>
              @foreach($student->memberships as $paydMonth)
              @php
                $month = $paydMonth->month;
                $year = $paydMonth->year;
                $date = \Carbon\Carbon::create($year, $month, 1);
              @endphp
                <li>
                 {{ $date->locale('it')->translatedFormat('F Y');}}
                </li>
              @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
$("#pdf").hide();
 
$( "#toggle-pdf" ).on( "click", function() {
  $( "#pdf" ).toggle( "slow", function() {
    // Animation complete.
  });
});
</script>
@endsection