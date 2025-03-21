@extends('layouts.tpl')

@section('content')
<div class="d-flex justify-content-center" style="padding-top:100px">
  <div class="p-5">
    <h1 class="text-center">Aggiungi Nuovo Corso</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-row">

        <!-- Nome -->
        <div class="form-group col-md-6">
         <div class="col-md-12">
            <label for="title">Nome</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" value="{{ old('title') }}" required>
            @error('title')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
         </div>

          <div class="col-md-12 row">
             
            <!-- Durata -->
            <div class="form-group col-md-4 col-sm-6">
              <label for="duration">Durata</label>
              <input type="time" class="form-control @error('duration') is-invalid @enderror" name="duration" id="duration" value="{{ old('duration') }}" required>
              @error('duration')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

            <!-- Inizio -->
            <div class="form-group col-md-4 col-sm-6">
              <label for="course_start">Inizio</label>
              <input type="time" class="form-control @error('course_start') is-invalid @enderror" name="course_start" id="course_start" value="{{ old('course_start') }}">
              @error('course_start')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

              <!-- Fine -->
            <div class="form-group col-md-4 col-sm-6">
              <label for="course_end">Fine</label>
              <input type="time" class="form-control @error('course_end') is-invalid @enderror" name="course_end" id="course_end" value="{{ old('course_end') }}">
              @error('course_end')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

          </div>
          <div class="col-md-12 row">
            <!-- Prezzo -->
            <div class="form-group col-md-4 col-sm-6">
              <label for="price">Prezzo</label>
              <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" name="price" id="price" value="{{ old('price') }}">
              @error('price')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

            <!-- Livello -->
            <div class="form-group col-md-4 col-sm-6">
              <label for="level">Livello</label>
              <select id="level" class="form-control @error('level') is-invalid @enderror" name="level">
                <option value="" selected disabled>Scegli livello...</option>
                <option value="amatoriale" {{ old('level') == 'amatoriale' ? 'selected' : '' }}>Amatoriale</option>
                <option value="agonistico" {{ old('level') == 'agonistico' ? 'selected' : '' }}>Agonistico</option>
              </select>
              @error('level')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

            <!-- Stato -->
            <div class="form-group col-md-4 col-sm-6">
              <label for="state">Stato</label>
              <select id="state" class="form-control @error('state') is-invalid @enderror" name="state">
                <option value="active" {{ old('state', 'active') == 'active' ? 'selected' : '' }}>Attivo</option>
                <option value="inactive" {{ old('state', 'active') == 'inactive' ? 'selected' : '' }}>Inattivo</option>
              </select>
              @error('state')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
          </div>
        </div>

      <!-- Trainer -->
      <div class="form-row">
        <div class="form-group col-md-12">
          <label for="teacher_id">Trainer principale:</label>
          <select name="teacher_id" id="teacher_id" class="form-control">
              <option value="">-- Seleziona un trainer principale --</option>
              @foreach($trainers as $trainer)
                  <option value="{{ $trainer->id }}" {{ old('teacher_id') == $trainer->id ? 'selected' : '' }}>
                      {{ $trainer->name }}
                  </option>
              @endforeach
          </select>

          @error('teacher_id')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group col-md-12">
          <label for="trainers">Trainer aggiuntivi:</label>
          <select name="trainers[]" id="trainers" class="form-control" multiple>
              @foreach($trainers as $trainer)
                  <option value="{{ $trainer->id }}" 
                      {{ collect(old('trainers', []))->contains($trainer->id) ? 'selected' : '' }}>
                      {{ $trainer->name }}
                  </option>
              @endforeach
          </select>

          @error('trainers')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
      </div>

        <!-- Giorni -->
        <div class="form-group col-6">
          <label for="days">Giorni</label>
          <textarea class="form-control @error('days') is-invalid @enderror" name="days" id="days" placeholder="Inserisci i giorni senza spazi e separati da |">{{ old('days') }}</textarea>
          @error('days')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

      </div>

      <button type="submit" class="btn btn-primary w-100">Aggiungi</button>
    </form>
  </div>
</div>
@endsection
