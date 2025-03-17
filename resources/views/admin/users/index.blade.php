@extends('layouts.tpl')


@section('content')
<div class="p-2 pt-5">
  @if(session()->has('success'))
  <div class="alert alert-success" id="call-mess">
    {{ session()->get('success') }}
  </div>
  @elseif(session()->has('delete'))
  <div class="alert alert-danger" id="call-mess">
    {{ session()->get('delete') }}
  </div>
  @endif
  <div class="pt-5">
    <a href="/dashboard/users/create" class="btn btn-success">Aggiungi nuovo utente</a>
  </div>
  <table id="example" class="display mt-5" style="width:100%">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Ruolo</th>
        <th>Corsi Assegnati</th> <!-- Nuova colonna -->
        <th style="width:8% !important;">Azioni</th>

      </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>
                @foreach ($user->roles as $role)
                    {{ $role->name == 'admin' ? 'Amministratore' : 'Trainer'}}
                @endforeach
            </td>
            <td>
              @php
                  $allCourses = $user->mainCourses->merge($user->trainerCourses);
              @endphp

              @if($allCourses->isEmpty())
                  <span class="badge bg-secondary">Nessun corso</span>
              @else
                  @foreach($allCourses as $course)
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
                <div class="d-flex justify-content-around">
                    <a href="{{ route('users.edit',$user->id) }}">
                        <span class="material-symbols-outlined">border_color</span>
                    </a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirmDelete();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0">
                            <span class="material-symbols-outlined text-danger">delete</span>
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
        <th>Ruolo</th>
        <th>Corsi Assegnati</th> <!-- Nuova colonna -->
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