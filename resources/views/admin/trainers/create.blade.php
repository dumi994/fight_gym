@extends('layouts.tpl')

@section('content')
@php
$users = App\Models\User::all(); // Ottieni tutti gli utenti
$courses = getSidebarData('courses'); // Ottieni tutti i corsi
@endphp
<div class="d-flex justify-content-center" style="padding-top:100px">
  <div>
    <h1 class="text-center">Assegna Corsi agli Utenti</h1>

    <form action="{{ route('assign.courses') }}" method="POST">
      @csrf
      <div class="form-row">
        <div class="form-group col-md-12">
          <label for="user">Utente</label>
          <select id="user" class="form-control @error('user') is-invalid @enderror" name="user" required>
            <option value="" disabled selected>Seleziona un utente</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </select>
          @error('user')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="form-group col-md-12">
          <label for="courses">Corsi</label>
          <select id="courses" class="form-control @error('courses') is-invalid @enderror" name="courses[]" multiple required>
            <option value="" disabled>Seleziona uno o più corsi</option>
            @foreach($courses as $course)
            <option value="{{ $course->id }}">{{ $course->name }}</option>
            @endforeach
          </select>
          @error('courses')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Assegna Corsi</button>
    </form>
  </div>
</div>
@endsection