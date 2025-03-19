@extends('layouts.tpl')

@section('content')
<div class="p-3 mt-5">
    <h1 class="text-center">Registro delle Presenze</h1>

    <div id="calendar"></div>
</div>

<!-- Modale Presenze -->
<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Segna Presenze</h5>
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
document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');

    if (!calendarEl) {
        console.error("Errore: #calendar non trovato.");
        return;
    }

    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'it',
        firstDay: 1,
        events: '/api/courses',
        eventClick: function(info) {
            console.log("📅 Evento cliccato:", info.event);
            openAttendanceModal(info.event.id, info.event.startStr);
        }
    });

    calendar.render();
});

function openAttendanceModal(courseId, date) {
    var modal = new bootstrap.Modal(document.getElementById('attendanceModal'));
    modal.show();

    document.getElementById('course_id').value = courseId;
    document.getElementById('attendance_date').value = date;

    // 🔄 Recupero delle presenze dal server
    fetch(`/api-attendances/${courseId}?date=${date}`)
        .then(response => response.json())
        .then(data => {
            console.log("📊 Dati ricevuti:", data);

            let studentsList = document.getElementById('studentsList');
            studentsList.innerHTML = '';

            if (!data.students || data.students.length === 0) {
                studentsList.innerHTML = '<p>Nessuno studente iscritto a questo corso.</p>';
                return;
            }

            // 🚀 Mostra gli studenti con il loro stato (✅ presente / ❌ assente)
            data.students.forEach(student => {
                let color;
                if(student.status == 'present'){
                  color = '🟢';
                }else{
                  color = '🔴';

                }
                studentsList.innerHTML += `
                    <div class="d-flex align-items-center">
                        <span class="me-2">${color}</span>
                        <span>${student.name}</span>
                    </div>
                `;
            });
        })
        .catch(error => console.error("⚠️ Errore nel recupero dei dati:", error));
}

</script>
@endsection
