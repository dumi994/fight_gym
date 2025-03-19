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
  
  <table id="example" class="display mt-5" style="width:100%">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Numero presenze ultimo mese</th>
        <th>Stato pagamento</th>  
        <th style="width:8% !important;">Azioni</th>
      </tr>
    </thead>
    
    <tbody>
      @foreach ($students as $student)
    <tr>
        <td>{{ $student->first_name . " " . $student->last_name }}</td>
        <td>
          {{ $student->email }}
        </td>
        <td>
            <!-- Verifica se ha almeno una presenza -->
            @if ($student->attendances->count() > 0)
                {{ $student->attendances->count() }} presenze
            @else
                ❌ 0 presenze
            @endif
            @php
               
              $lastMembership = $student->memberships()->orderBy('year', 'desc')->orderBy('month', 'desc')->first();

               // dd($lastMembership);
            @endphp
        </td>
        <td id="status-{{$student->id}}-{{$lastMembership->month}}-{{$lastMembership->year}}">
          @if ($lastMembership && $lastMembership->status == 'paid')
            🟢 {{ getMonthName($lastMembership->month)}} {{ $lastMembership->year }} pagato
          @else
            🔴
          @endif
        </td>
        <td>
        <x-switch-button 
          :checked="$lastMembership && $lastMembership->status == 'paid'"
          :data_student_id="$student->id ?? ''"
          :data_month="$lastMembership->month ?? ''"
          :data_year="$lastMembership->year ?? ''"
          :student_name="$student->first_name . ' ' . $student->last_name"
        />
        </td>
    </tr>
    @endforeach
    </tbody>

    <tfoot>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Numero presenze ultimo mese</th>
        <th>Stato pagamento</th>
        <th style="width:8% !important;">Azioni</th>
      </tr>
    </tfoot>
  </table>
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

            // 🔥 **Aggiorna dinamicamente il pallino**
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
            checkbox.checked = !checkbox.checked; // 🔥 Se fallisce, ripristina lo stato
        }
    })
    .catch(error => {
        console.error("Errore AJAX:", error);
        Swal.fire("Errore!", "Si è verificato un errore durante l'aggiornamento.", "error");
        checkbox.checked = !checkbox.checked; // 🔥 Ripristina lo stato se l'AJAX fallisce
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
