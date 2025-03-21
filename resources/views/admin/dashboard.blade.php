@extends('layouts.tpl')

@section('content')

<div class="p-2">
 
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
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{count(getSidebarData('users'))}}</h3>

            <p>Utenti</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>

          </div>
          <a href="/dashboard/users" class="small-box-footer">Tutti gli utenti <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{count(getSidebarData('courses'))}}</h3>

            <p>Corsi</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="/dashboard/courses" class="small-box-footer">Tutti i corsi <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{count(getSidebarData('students'))}}</h3>

            <p>Atleti</p>
          </div>
          <div class="icon">
            <i class="material-symbols-outlined" style="font-size:60px">sensor_occupied</i>

          </div>
          <a href="/dashboard/students" class="small-box-footer">Tutti gli atleti <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>&nbsp;</h3>

            <p>Calendario corsi</p>
          </div>
          <div class="icon">
            <i class="ion ion-calendar"></i>
          </div>
          <a href="/dashboard/attendances" class="small-box-footer">Guarda calendario <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
       <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-primary">
          <div class="inner">
            <h3>&nbsp;</h3>

            <p>Stato pagamenti</p>
          </div>
          <div class="icon">
            <i style="color:white" class="ion ion-checkmark"></i>
          </div>
          <a href="/dashboard/check-payments" class="small-box-footer">Controlla stato pagamenti <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
     
  </div><!-- /.container-fluid -->
</section>
</div>

@endsection

@section('scripts')
<script>
 
  document.addEventListener('DOMContentLoaded', function() {
    let dataTable = new DataTable('#example', {
      retrieve: true,  // Usa la tabella esistente senza ricaricarla
      paging: false,    // Evita il cambio pagina automatico
      searching: false, // Evita il filtro automatico
      ordering: false   // Evita che venga riodinata automaticamente
    });

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
    /*  */
   
  document.querySelectorAll(".membership-checkbox").forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
        let studentId = this.getAttribute("data-student-id") || null;
        let month = this.getAttribute("data-month") || null;
        let year = this.getAttribute("data-year") || null;
        let studentName = this.getAttribute("data-student-name") || "Lo studente";
        let newStatus = this.checked ? "paid" : "unpaid";

        if (!studentId || !month || !year || !studentName) {
            console.error("Dati mancanti:", { studentId, month, year, studentName });
            alert("Errore: dati mancanti!");
            this.checked = !this.checked; // Ripristina lo stato originale
            return;
        }

        //  Blocca il cambio se già pagato
        if (newStatus === "unpaid") {
            Swal.fire({
                title: "Annullare il pagamento?",
                text: `${studentName} risulta già pagato. Vuoi davvero annullare il pagamento?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sì, annulla!",
                cancelButtonText: "No, mantieni pagato"
            }).then((result) => {
                if (!result.isConfirmed) {
                    checkbox.checked = true; // Ripristina se l'utente annulla
                    return;
                }
                updateMembership(studentId, month, year, newStatus, checkbox);
            });
        } else {
            // Conferma pagamento
            Swal.fire({
                title: "Conferma pagamento",
                text: `Sei sicuro che ${studentName} ha pagato?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sì, confermo!",
                cancelButtonText: "Annulla"
            }).then((result) => {
                if (!result.isConfirmed) {
                    checkbox.checked = false; // Ripristina se l'utente annulla
                    return;
                }
                updateMembership(studentId, month, year, newStatus, checkbox);
            });
        }
    });
});

// Funzione AJAX per aggiornare il database
function updateMembership(studentId, month, year, status, checkbox) {
    fetch("/update-membership", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({
            student_id: studentId,
            month: month,
            year: year,
            status: status,
            
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire("Successo!", `Lo stato del pagamento è stato aggiornato a ${status}.`, "success");

            // **Aggiorna dinamicamente il pallino**
            let statusCell = document.getElementById(`status-${studentId}-${month}-${year}`);
            if (statusCell) {
                if (status === "paid") {
                    statusCell.innerHTML = `🟢 ${getMonthName(month)} ${year} pagato`;
                } else {
                    statusCell.innerHTML = "🔴 Non pagato";
                }
            }

        } else {
            Swal.fire("Errore!", "Qualcosa è andato storto.", "error");
            checkbox.checked = !checkbox.checked; // Se fallisce, ripristina lo stato
        }
    })
    .catch(error => {
        console.error("Errore AJAX:", error);
        Swal.fire("Errore!", "Si è verificato un errore durante l'aggiornamento.", "error");
        checkbox.checked = !checkbox.checked; // Ripristina lo stato se l'AJAX fallisce
    });
}
function getMonthName(month) {
  const mesi = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", 
    "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];
  return mesi[month - 1] || "Mese sconosciuto";
}
});
</script>
@endsection
