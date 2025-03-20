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
        <h1 class="text-center">Report Presenze Studenti - {{ now()->format('F Y') }}</h1>
    </div>

    <table id="example" class="display mt-5" style="width:100%">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Presenze</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->first_name ." ". $student->last_name  }}</td>
                    <td>{{ $student->email }}</td>
                    <td>
                        @if(optional($student->attendances)->count() > 0)
                            <span class="badge bg-success">Presente</span>
                        @else
                            <span class="badge bg-danger">Assente</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Presenze</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    new DataTable('#example');

    // Nascondi il messaggio di successo dopo 3 secondi
    setTimeout(function() {
      $('#call-mess').fadeOut('slow');
    }, 3000);
  });
</script>
@endsection
