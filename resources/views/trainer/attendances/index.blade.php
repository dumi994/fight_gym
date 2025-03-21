@extends('layouts.tpl')

@section('content')
<div class="p-3 ">
  <h1 class="text-center mt-5">Registro delle Presenze</h1>

  <div id="calendar"></div>
</div>

<!-- Modale Presenze -->
<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Segna Presenze - <span id="courseTitle"></span> <br>
        <small id="courseTime"></small></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="attendanceForm">
          <input type="hidden" id="course_id" name="course_id">
          <input type="hidden" id="attendance_date" name="attendance_date">
          <div id="studentsList">
            <p>Caricamento studenti...</p>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script>
  document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');

    if (!calendarEl) {
      console.error("Errore: #calendar non trovato.");
      return;
    }

    let calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'it',
      firstDay: 1,
      events: function (fetchInfo, successCallback, failureCallback) {
        fetch('/api/trainer-courses')
          .then(response => response.json())
          .then(data => {
            console.log("📊 Eventi ricevuti da API:", data);
            successCallback(data);
          })
          .catch(error => {
            console.error("⚠️ Errore nel caricamento eventi:", error);
            failureCallback(error);
          });
      },

      // ✅ AGGIUNGI QUESTO:
      eventClick: function (info) {
        console.log("📅 Evento cliccato:", info.event);

        if (!info.event.id || !info.event.start) {
          console.error("❌ Errore: evento senza ID o data!", info.event);
          return;
        }

        openAttendanceModal(info.event.id, info.event.startStr);
      },

      eventDidMount: function (info) {
        console.log("✅ Evento aggiunto:", info.event);
      }
    });

    calendar.render();
  });


  function openAttendanceModal(courseId, date) {
    var modal = new bootstrap.Modal(document.getElementById('attendanceModal'));
    modal.show();

    document.getElementById('course_id').value = courseId;
    document.getElementById('attendance_date').value = date;

    // 🔄 Recupera i dettagli del corso (titolo + orario)
    fetch(`/course-details/${courseId}`)
        .then(response => response.json())
        .then(data => {
            if (data && data.course) {
                document.getElementById("courseTitle").textContent = data.course.title; // Nome del corso
                document.getElementById("courseTime").textContent = 
                    `${data.course.course_start} - ${data.course.course_end}`; // Ora di inizio e fine
            } else {
                document.getElementById("courseTitle").textContent = "Corso Sconosciuto";
                document.getElementById("courseTime").textContent = "Orario non disponibile";
            }
        })
        .catch(error => {
            console.error("❌ Errore nel recupero del corso:", error);
            document.getElementById("courseTitle").textContent = "Errore nel caricamento";
            document.getElementById("courseTime").textContent = "";
        });

    console.log(`📡 Richiesta API: /api-attendances/${courseId}?date=${date}`);

    fetch(`/api-attendances/${courseId}?date=${date}`)
        .then(response => response.json())
        .then(data => {
            console.log("📊 Dati ricevuti:", data);

            let studentsList = document.getElementById('studentsList');
            studentsList.innerHTML = '';

            if (!data || !data.students) {
                console.error("⚠️ Errore: Nessun dato ricevuto o struttura errata", data);
                studentsList.innerHTML = '<p>Errore nel caricamento studenti.</p>';
                return;
            }

            if (data.students.length === 0) {
                studentsList.innerHTML = '<p>Nessuno studente iscritto a questo corso.</p>';
                return;
            }

            data.students.forEach(student => {
                let color = student.status === 'present' ? '🟢' : '🔴';

                studentsList.innerHTML += `
                    <div class="d-flex align-items-center">
                        <span class="me-2">${color}</span>
                        <span>${student.name}</span>
                    </div>
                `;
            });
        })
        .catch(error => {
            console.error("❌ Errore nel recupero dei dati:", error);
            document.getElementById('studentsList').innerHTML = '<p>Errore nel caricamento degli studenti.</p>';
        });
}



</script>
@endsection