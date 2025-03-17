@extends('layouts.tpl')


@section('content')

<div class="p-2">

  <div class="pt-5">
    <a href="/dashboard/students/create" class="btn btn-success">Aggiungi nuovo allievo</a>
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
  <table id="example" class="display mt-5  " style="width:100%">

    <thead>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Durata</th>
        <th>Livello</th>

        <th style="width:8% !important;">Azioni</th>
      </tr>
    </thead>
    <tbody>
      @foreach($courses as $course)
      <tr>
        <td>{{$course->name}} </td>
        <td>{{$course->state}}</td>
        <td>{{$course->durability}}</td>
        <td>{{$course->level}}</td>

        <td style="width:8% !important;">
          <div class="d-flex justify-content-around">
            <a href="{{ route('courses.edit',$course->id) }}">
              <span class="material-symbols-outlined">
                border_color
              </span>
            </a>
            <form action="{{ route('courses.destroy', $course->id) }}" method="POST" onsubmit="return confirmDelete();">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-link p-0">
                <span class="material-symbols-outlined text-danger">
                  delete
                </span>
              </button>
            </form>

          </div>
        </td>
      </tr>
      @endforeach


    </tbody>
    <tfoot>
      <tr>
        <th>Nome</th>
        <th>Email</th>

        <th>Durata</th>
        <th>Livello</th>

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
    // Aggiungi un listener a tutti i form di eliminazione
    document.querySelectorAll('form[method="POST"]').forEach(function(form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault(); // Previeni l'invio immediato del modulo
        const form = e.target;

        // Mostra il popup di conferma
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
            form.submit(); // Invia il modulo se confermato
          }
        });
      });
    });

    // Nascondi il messaggio di successo dopo 5 secondi
    setTimeout(function() {
      $('#call-mess').fadeOut('slow'); // 'slow' è una velocità predefinita per il fadeOut, puoi usare un valore in millisecondi se preferisci
    }, 3000);
  });
</script>
@endsection