@extends('layouts.tpl')

@section('content')

<div class="p-2">

  <div class="pt-5">
    <a href="/trainer-dashboard/students/create" class="btn btn-success">Aggiungi nuovo allievo</a>
  </div>
  <div class="message py-4">
    @if(session()->has('success'))
    <div class="alert alert-success" id="call-mess">
      {{ session()->get('success') }}
    </div>
    @elseif(session()->has('delete'))
    <div class="alert alert-danger" id="call-mess">
      {{ session()->get('delete') }}
    </div>
    @endif
  </div>
  
  <table id="example" class="display mt-5" style="width:100%">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Corsi Associati</th>  <!-- 🔥 Nuova colonna -->
        <th style="width:8% !important;">Azioni</th>
      </tr>
    </thead>
    
    <tbody>
      @foreach($students as $student)
      <tr>
        <td>{{$student->first_name}} {{$student->last_name}}</td>
        <td>{{$student->email}}</td>
        <td>
            @if($student->courses->isEmpty())
                <span class="badge bg-secondary">Nessun corso</span>
            @else
              @foreach($student->courses as $course)
                <span class="badge 
                  @if($loop->index % 5 == 0) bg-primary 
                  @elseif($loop->index % 5 == 1) bg-success 
                  @elseif($loop->index % 5 == 2) bg-danger 
                  @elseif($loop->index % 5 == 3) bg-warning 
                  @else bg-info 
                  @endif">
                  {{ $course->title }}
                </span>
                @endforeach
            @endif
        </td>


        <td style="width:8% !important;">
          <div class="d-flex justify-content-center">
            <a href="{{ route('trainer.students.show', $student->id) }}">
              <span class="material-symbols-outlined">
                info
              </span>
            </a>
            
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>

    <tfoot>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Corsi Associati</th>
        <th style="width:8% !important;">Azioni</th>
      </tr>
    </tfoot>
  </table>
</div>

@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    new DataTable('#example');

    document.querySelectorAll('form[method="POST"]').forEach(function(form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;

        Swal.fire({
          title: 'Sei sicuro?',
          text: "Questa azione non può essere annullata!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sì, elimina!',
          cancelButtonText: 'Annulla'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    setTimeout(function() {
      $('#call-mess').fadeOut('slow');
    }, 3000);
  });
</script>
@endsection
