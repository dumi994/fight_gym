@extends('layouts.tpl')

@section('content')
<div class="d-flex justify-content-center" style="padding-top:100px">
  <div class="p-5">
    <h1 class="text-center">{{ isset($course) ? 'Modifica Corso' : 'Aggiungi Nuovo Corso' }}</h1>

    <form action="{{ isset($course) ? route('courses.update', $course->id) : route('courses.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @if(isset($course))
      @method('PUT')
      @endif

      <div class="form-row">

        <!-- Nome -->
        <div class="form-group col-md-12">
          <label for="name">Nome</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $course->name ?? '') }}" required>
          @error('name')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

        <!-- Durata -->
        <div class="form-group col-md-2 col-sm-6">
          <label for="durability">Durata</label>
          <input type="time" class="form-control @error('durability') is-invalid @enderror" name="durability" id="durability" value="{{ old('durability', $course->durability ?? '') }}" required>
          @error('durability')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

        <!-- Inizio -->
        <div class="form-group col-md-2 col-sm-6">
          <label for="course_start">Inizio</label>
          <input type="time" class="form-control @error('course_start') is-invalid @enderror" name="course_start" id="course_start" value="{{ old('course_start', $course->course_start ?? '') }}">
          @error('course_start')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

        <!-- Fine -->
        <div class="form-group col-md-2 col-sm-6">
          <label for="course_end">Fine</label>
          <input type="time" class="form-control @error('course_end') is-invalid @enderror" name="course_end" id="course_end" value="{{ old('course_end', $course->course_end ?? '') }}">
          @error('course_end')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

        <!-- Prezzo -->
        <div class="form-group col-md-2 col-sm-6">
          <label for="price">Prezzo</label>
          <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" placeholder="€" name="price" id="price" value="{{ old('price', $course->price ?? '') }}">
          @error('price')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

        <!-- Livello -->
        <div class="col-md-2 col-sm-6">
          <div class="form-group">
            <label for="level">Livello</label>
            <select id="level" class="form-control @error('level') is-invalid @enderror" name="level">
              <option value="" selected disabled>Scegli livello...</option>
              <option value="amatoriale" {{ old('level', $course->level ?? '') == 'amatoriale' ? 'selected' : '' }}>Amatoriale</option>
              <option value="agonistico" {{ old('level', $course->level ?? '') == 'agonistico' ? 'selected' : '' }}>Agonistico</option>
            </select>
            @error('level')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>

        <!-- Stato -->
        <div class="col-md-2 col-sm-6">
          <div class="form-group">
            <label for="state">Stato</label>
            <select id="state" class="form-control @error('state') is-invalid @enderror" name="state">
              <option value="active" {{ old('state', $course->state ?? 'active') == 'active' ? 'selected' : '' }}>Attivo</option>
              <option value="inactive" {{ old('state', $course->state ?? 'active') == 'inactive' ? 'selected' : '' }}>Inattivo</option>
            </select>
            @error('state')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>

        <!-- Giorni -->
        <div class="col-6">
          <div class="form-group">
            <label for="days">Giorni</label>
            <textarea class="form-control @error('days') is-invalid @enderror" name="days" id="days" placeholder="Inserisci i giorni senza spazi e seguiti da virgola">{{ old('days', $course->days ?? '') }}</textarea>
            @error('days')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>

      </div>

      <button type="submit" class="btn btn-primary">{{ isset($course) ? 'Aggiorna' : 'Aggiungi' }}</button>
    </form>
  </div>
</div>
@endsection