@extends('layouts.tpl')

@section('content')
<div class="d-flex justify-content-center px-2" style="padding-top:100px">
  <div>
    <h1 class="text-center">Aggiungi nuovo studente</h1>

    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-row">
        <!-- DATI PERSONALI -->
        <div class="col-md-6 col-sm-12 px-5">
          <div class="form-row">
            <!-- Campo Nome -->
            <div class="form-group col-md-6">
              <label for="first_name">Nome</label>
              <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" id="first_name" value="{{ old('first_name') }}" required>
              @error('first_name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

            <!-- Campo Cognome -->
            <div class="form-group col-md-6">
              <label for="last_name">Cognome</label>
              <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="last_name" value="{{ old('last_name') }}" required>
              @error('last_name')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <!-- Campo Sesso -->
            <div class="form-group col-md-6">
              <label for="gender">Sesso</label>
              <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                <option value="" selected disabled>Scegli sesso...</option>
                <option value="m" {{ old('gender') == 'm' ? 'selected' : '' }}>Maschio</option>
                <option value="f" {{ old('gender') == 'f' ? 'selected' : '' }}>Femmina</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Altro</option>
              </select>
              @error('gender')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <!-- Campo Data di Nascita -->
            <div class="form-group col-md-6">
              <label for="birth_date">Data di Nascita</label>
              <input type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required>
              @error('birth_date')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

            <!-- Campo Indirizzo -->
            <div class="form-group col-md-12">
              <label for="address">Indirizzo</label>
              <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address" value="{{ old('address') }}">
              @error('address')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <!-- Campo Email -->
            <div class="form-group col-md-6">
              <label for="email">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}">
              @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <!-- Campo Numero di Telefono -->
            <div class="form-group col-md-6">
              <label for="phone_number">Numero di Telefono</label>
              <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" id="phone_number" value="{{ old('phone_number') }}">
              @error('phone_number')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

          </div>

        </div>
        <!-- DATI PER LA PALESTRA -->
        <div class="col-md-6 col-sm-12 px-5">

          <!-- Campo Allenatore (user_id) -->
         <!--  <div class="form-group col-md-12">
            <label for="user_id">Allenatore</label>
            <select id="user_id" class="form-control @error('user_id') is-invalid @enderror" name="user_id">
              <option value="" selected disabled>Scegli allenatore...</option>
              @foreach($users as $user)
              <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
              @endforeach
            </select>
            @error('user_id')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div> -->
          <!-- Campo Corso -->
          <div class="form-group col-md-12">
              <label for="courses">Corsi</label>
              <select id="courses" class="form-control @error('courses') is-invalid @enderror" name="courses[]" multiple>
                  @foreach($courses as $course)
                      <option value="{{ $course->id }}" {{ collect(old('courses'))->contains($course->id) ? 'selected' : '' }}>
                          {{ $course->title }}
                      </option>
                  @endforeach
              </select>
              @error('courses')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>

          <!-- Campo Certificato Medico -->
          <div class="form-group col-md-12">
            <label for="medical_certificate_path">Certificato Medico</label>
            <input type="file" class="form-control-file @error('medical_certificate_path') is-invalid @enderror" name="medical_certificate_path" id="medical_certificate_path">
            @error('medical_certificate_path')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <!-- Campo Data di Iscrizione -->
          <div class="form-group col-md-6">
            <label for="enrollment_date">Data di Iscrizione</label>
            <input type="date" class="form-control @error('enrollment_date') is-invalid @enderror" name="enrollment_date" id="enrollment_date" value="{{ old('enrollment_date') }}">
            @error('enrollment_date')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <!-- Campo Stato dell'Iscrizione -->
          <div class="form-group col-md-6">
            <label for="membership_status">Stato dell'Iscrizione</label>
            <select id="membership_status" class="form-control @error('membership_status') is-invalid @enderror" name="membership_status" required>
              <option value="active" {{ old('membership_status') == 'active' ? 'selected' : '' }}>Attivo</option>
              <option value="inactive" {{ old('membership_status') == 'inactive' ? 'selected' : '' }}>Inattivo</option>
              <option value="pending" {{ old('membership_status') == 'pending' ? 'selected' : '' }}>In sospeso</option>
            </select>
            @error('membership_status')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="p-5">

        <button type="submit" class="btn btn-primary w-100">Aggiungi</button>
      </div>

    </form>
  </div>
</div>
@endsection