@extends('layouts.tpl')

@section('content')
<div class="mt-5 p-5">
  @if(session()->has('success'))
  <div class="alert alert-success text-center" id="call-mess">
    {{ session()->get('success') }}
  </div>
  @elseif(session()->has('delete'))
  <div class="alert alert-danger text-center" id="call-mess">
    {{ session()->get('delete') }}
  </div>
  @endif
  <table id="example" class="display" style="width:100%">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Ruolo</th>
        <th>Corsi</th>
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
          @if($user->courses->isEmpty())
          Nessun corso assegnato
          @else
          @foreach($user->courses as $course)
          <span class="badge badge-info">{{ $course->name }}</span>
          @endforeach
          @endif
        </td>
        <td style="width:8% !important;">
          <div class="d-flex justify-content-around">
            <a href="{{ route('trainers.edit', $user->id) }}">
              <span class="material-symbols-outlined">
                border_color
              </span>
            </a>
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirmDelete();">
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
        <th>Ruolo</th>
        <th>Corsi</th>
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