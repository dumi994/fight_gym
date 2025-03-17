@extends('layouts.tpl')

@section('content')
<div class="mt-5 p-5">
  <form action="{{ route('trainers.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
      <div class="col-sm-12 col-md-7">
        <div class="form-group">
          <label for="name">Nome</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" readonly>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" readonly>
        </div>
      </div>
      <div class="col-sm-12 col-md-5">
        <div class="form-group">
          <label for="courses">Corsi</label>
          <select multiple class="form-control" id="courses" name="courses[]">
            @foreach($courses as $course)
            <option value="{{ $course->id }}" {{ $user->courses->contains($course->id) ? 'selected' : '' }}>
              {{ $course->name }}
            </option>
            @endforeach
          </select>
        </div>
      </div>

    </div>


    <button type="submit" class="btn btn-primary w-100">Aggiorna Corsi</button>
  </form>
</div>
@endsection