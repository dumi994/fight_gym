@extends('layouts.tpl')

@section('content')
<div class="d-flex justify-content-center" style="padding-top:100px">
  <div>
    <h1 class="text-center">Aggiungi nuovo utente</h1>

    <form action="{{ route('users.store') }}" method="POST">
      @csrf
      <div class="form-row">
        <div class="form-group col-md-12">
          <label for="nomeUtente">Nome</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="nomeUtente" value="{{ old('name') }}" required>
          @error('name')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="form-group col-md-12">
          <label for="email">Email</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
          @error('email')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="form-group col-md-12">
          <label for="password">Password</label>
          <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password" required>
          @error('password')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
         <div class="form-group col-md-12">
          <label for="password_confirmation">Conferma Password</label>
          <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
          @error('password')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        
        <div class="form-group col-md-12">
          <label for="role">Ruolo</label>
          <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required>
            <option value="" selected disabled>Scegli ruolo...</option>
            @foreach($roles as $role)
              <option value="{{ $role->name }}" style="text-transform:capitalize">{{$role->name}}</option>
            @endforeach
        <!--     <option value="admin">Amministratore</option>
            <option value="instructor">Instructor</option> -->
          </select>
          @error('role')
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