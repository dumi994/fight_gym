@section('content')
<h1>Registro delle Presenze</h1>
<form action="{{ route('attendances.store') }}" method="POST">
  @csrf
  <div>
    <label for="course">Corso:</label>
    <select name="course_id" id="course">
      @foreach ($corsi as $corso)
      <option value="{{ $corso->id }}">{{ $corso->nome }}</option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="attendance_date">Data:</label>
    <input type="date" name="attendance_date" id="attendance_date" required>
  </div>

  <table>
    <thead>
      <tr>
        <th>Studente</th>
        <th>Presente</th>
        <th>Assente</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($corsi->first()->students as $student)
      <tr>
        <td>{{ $student->nome }} {{ $student->cognome }}</td>
        <td>
          <input type="radio" name="students[{{ $student->id }}]" value="present" checked>
        </td>
        <td>
          <input type="radio" name="students[{{ $student->id }}]" value="absent">
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <button type="submit">Salva Presenze</button>
</form>
@endsection