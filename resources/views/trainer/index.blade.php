@extends('layouts.tpl')

@section('content')

<div class="p-2">

  <div class="row">
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{count(getSidebarData('courseStudents'))}}</h3>
          <p>I miei allievi</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="/trainer-dashboard/students" class="small-box-footer">Gestione allievi <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{count(getSidebarData('courseTrainer'))}}</h3>

          <p>I miei corsi</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>

        </div>
        <a href="/trainer-dashboard/courses" class="small-box-footer">Gestione corsi <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
 
    
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>&nbsp;</h3>

          <p>Calendario</p>
        </div>
        <div class="icon">
          <i class="ion ion-calendar"></i>
        </div>
        <a href="/trainer-dashboard/attendances" class="small-box-footer">Registro presenze <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>
</div>

@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    new DataTable('#example');

    document.querySelectorAll('form[method="POST"]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
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

    setTimeout(function () {
      $('#call-mess').fadeOut('slow');
    }, 3000);
  });
</script>
@endsection