@extends('layouts.tpl')

@section('content')
<div class="d-flex justify-content-center" style="padding-top:100px">
  <div>
    <h1 class="text-center">Modifica utente</h1>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="form-row">
        <div class="form-group col-md-12">
          <label for="nomeUtente">Nome</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="nomeUtente" value="{{ old('name', $user->name) }}" required>
          @error('name')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="form-group col-md-12">
          <label for="email">Email</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" value="{{ old('email', $user->email) }}" required>
          @error('email')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="form-group col-md-12">
          <label for="password">Password</label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password">
          @error('password')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
          <small>Lascia il campo vuoto se non vuoi cambiare la password</small>
        </div>
        <div class="form-group col-md-12">
          <label for="role">Ruolo</label>
          <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required>
            <option value="" disabled>Scegli ruolo...</option>
            <option value="admin" 
              {{ old('role', optional($user->roles->first())->name) == 'admin' ? 'selected' : '' }}>
              Amministratore
            </option>
            <option value="trainer" 
              {{ old('role', optional($user->roles->first())->name) == 'trainer' ? 'selected' : '' }}>
              Trainer
            </option>
          </select>
          @error('role')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Salva</button>
    </form>
  </div>
</div>
@endsection