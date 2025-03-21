@extends('layouts.tpl')


@section('content')
<div class="px-2 pt-5">
  <div class="pt-5">
    <a href="/dashboard/courses/create" class="btn btn-success">Aggiungi nuovo corso</a>
  </div>
  @if(session()->has('success'))
  <div class="alert alert-success" id="call-mess">
    {{ session()->get('success') }}
  </div>
  @elseif(session()->has('delete'))
  <div class="alert alert-danger" id="call-mess">
    {{ session()->get('delete') }}
  </div>
  @endif
  <table id="example" class="display" style="width:100%">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Livello</th>
        <th>Giorni</th>
        <th>Durata</th>
        <th>Inizio</th>
        <th>Fine</th>

        <th style="width:8% !important;">Azioni</th>

      </tr>
    </thead>
    <tbody>
        @foreach($courses as $course)
        <tr>
          <td>{{$course->title}}</td>
          <td>{{$course->level}}</td>
          <td>{{$course->days}}</td>
          <td>{{$course->durability}}</td>
          <td>{{$course->course_start}}</td>
          <td>{{$course->course_end}}</td>
          <td style="width:12% !important;">
            <div class="d-flex justify-content-around">
              <a href="{{ route('courses.edit', $course->id) }}">
                <span class="material-symbols-outlined">border_color</span>
              </a>
              <button class="btn btn-primary" onclick="openAttendanceModal({{ $course->id }})">
                📅 Presenze
              </button>
              <form action="{{ route('courses.destroy', $course->id) }}" method="POST" onsubmit="return confirmDelete();">
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
        <th>Livello</th>
        <th>Giorni</th>
        <th>Durata</th>
        <th>Inizio</th>
        <th>Fine</th>

        <th style="width:8% !important;">Azioni</th>
      </tr>
    </tfoot>
  </table>

 
  <!-- Modale -->
  <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="attendanceModalLabel">Calendario Presenze</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="calendar"></div> <!-- Qui verrà caricato il calendario -->
          <hr>
          <h5>Lista Studenti</h5>
          <form id="attendanceForm">
            <div id="studentsList">
              <p>Seleziona un corso per vedere gli studenti.</p>
            </div>
            <button type="submit" class="btn btn-success mt-3">Salva Presenze</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="closeModalButton">Chiudi</button>

        </div>
      </div>
    </div>
      <!-- Campo nascosto per il Course ID -->
    <form id="attendanceForm">
     <!-- Campo nascosto per il Course ID e la Data -->
      <input type="hidden" id="course_id" name="course_id">
      <input type="hidden" id="attendance_date" name="attendance_date">

      <div id="studentsList">
          <p>Seleziona un corso per vedere gli studenti.</p>
      </div>
 
    </form>

  </div>

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
/* calendario */
   new DataTable('#example');

    // Nasconde il messaggio di successo dopo 3 secondi
    setTimeout(function() {
      $('#call-mess').fadeOut('slow');
    }, 3000);
    
  });
 
  let calendar;
function openAttendanceModal(courseId) {
    var modal = new bootstrap.Modal(document.getElementById('attendanceModal'));
    modal.show();

    document.getElementById('course_id').value = courseId;

    if (calendar) {
        calendar.destroy();
    }

    fetch(`/course-details/${courseId}`)
    .then(response => response.json())
    .then(data => {
        let calendarEl = document.getElementById('calendar');

        if (!data.course || !data.course.days.length) {
            calendarEl.innerHTML = "<p>Il corso non ha giorni assegnati.</p>";
            return;
        }

        let events = [];
        let today = new Date();
        let dayMap = {
            'lunedi': 1,
            'martedi': 2,
            'mercoledi': 3,
            'giovedi': 4,
            'venerdi': 5,
            'sabato': 6,
            'domenica': 0
        };

        data.course.days.forEach(day => {
            if (dayMap[day] !== undefined) {
                let nextDate = new Date();
                nextDate.setDate(today.getDate() + ((dayMap[day] - today.getDay() + 7) % 7));

                events.push({
                    title: data.course.title,
                    start: nextDate.toISOString().split('T')[0], 
                    backgroundColor: '#007bff',
                    borderColor: '#007bff',
                    extendedProps: {
                        course_id: data.course.id,
                        date: nextDate.toISOString().split('T')[0]
                    }
                });
            }
        });

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'it',
            events: events,
            eventClick: function(info) {
                loadStudents(info.event.extendedProps.course_id, info.event.extendedProps.date);
            }
        });

        calendar.render();

        // FORZA IL RIDISEGNO DEL CALENDARIO DOPO L'APERTURA DELLA MODALE
        setTimeout(function() {
            calendar.updateSize();
        }, 300);
    });
}


document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("closeModalButton").addEventListener("click", function() {
        var modal = document.getElementById("attendanceModal");
        var modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();
    });
    console.log(bootstrap);

});





function loadStudents(courseId, date) {
    let studentsList = document.getElementById('studentsList');

    if (!studentsList) {
        console.error("Errore: l'elemento #studentsList non esiste nella modale.");
        return;
    }

    studentsList.innerHTML = '<p>Caricamento studenti...</p>';

    fetch(`/api-attendances/${courseId}?date=${date}`)
    .then(response => response.json())
    .then(data => {
        studentsList.innerHTML = ''; // Pulisce la lista prima di aggiungere nuovi studenti

        if (!data.students || data.students.length === 0) {
            studentsList.innerHTML = '<p>Nessuno studente iscritto a questo corso.</p>';
            return;
        }

        // Assicura che il campo nascosto esista prima di assegnare valori
        let courseIdField = document.getElementById('course_id');
        let attendanceDateField = document.getElementById('attendance_date');

        if (courseIdField) courseIdField.value = courseId;
        else console.error("Errore: #course_id non trovato.");

        if (attendanceDateField) attendanceDateField.value = date;
        else console.error("Errore: #attendance_date non trovato.");

        // Genera la lista con le checkbox
        data.students.forEach(student => {
            let checked = student.status === 'present' ? 'checked' : '';

            studentsList.innerHTML += `
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="attendance[${student.id}]" id="student_${student.id}" value="present" ${checked}>
                    <label class="form-check-label" for="student_${student.id}">
                        ${student.name}
                    </label>
                </div>
            `;
        });
    })
    .catch(error => console.error("Errore nel caricamento degli studenti:", error));
}

document.getElementById('attendanceForm').addEventListener('submit', function(event) {
    event.preventDefault();

    let formData = new FormData(this);
    let courseIdField = document.getElementById('course_id');
    let attendanceDateField = document.getElementById('attendance_date');

    if (!courseIdField || !courseIdField.value) {
        alert("Errore: il corso non è stato selezionato.");
        return;
    }

    if (!attendanceDateField || !attendanceDateField.value) {
        alert("Errore: la data non è stata selezionata.");
        return;
    }

    formData.append('course_id', courseIdField.value);
    formData.append('attendance_date', attendanceDateField.value);

    let attendanceData = {};
    document.querySelectorAll('input[name^="attendance["]').forEach(checkbox => {
        let studentId = checkbox.name.match(/\d+/)[0];
        attendanceData[studentId] = checkbox.checked ? "present" : "absent";
    });

    if (Object.keys(attendanceData).length === 0) {
        alert("Errore: Nessuna presenza selezionata!");
        return;
    }

    Object.keys(attendanceData).forEach(studentId => {
        formData.append(`attendance[${studentId}]`, attendanceData[studentId]);
    });

    let csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        formData.append('_token', csrfToken.getAttribute('content'));
    } else {
        alert("Errore: CSRF token mancante.");
        return;
    }

    console.log("Dati inviati:", Object.fromEntries(formData));

    fetch('/api-attendances', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Errore nella richiesta: " + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        Swal.fire({
            title: "Successo!",
            text: data.message,
            icon: "success"
        });
        $('#attendanceModal').modal('hide');
    })
    .catch(error => {
        console.error("Errore nel salvataggio delle presenze:", error);
        alert("Errore nel salvataggio delle presenze. Controlla i log del server.");
    });
});

</script>
@endsection