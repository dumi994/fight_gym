<style>
  .switch {
  display: inline-block;
  height: 17px;
  position: relative;
  width: 30px;
}

.switch input {
  display:none;
}

.slider {
  background-color: #ccc;
  bottom: 0;
  cursor: pointer;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
  transition: .4s;
}

.slider:before {
  background-color: #fff;
  bottom: 4px;
  content: "";
  height: 26px;
  left: 4px;
  position: absolute;
  transition: .4s;
  width: 26px;
}

input:checked + .slider {
  background-color: #66bb6a;
}

input:checked + .slider:before {
  transform: translateX(26px);
}

.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.switch {
  display: inline-block;
  height: 34px;
  position: relative;
  width: 60px;
}

.switch input {
  display:none;
}

.slider {
  background-color: #ccc;
  bottom: 0;
  cursor: pointer;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
  transition: .4s;
}

.slider:before {
  background-color: #fff;
  bottom: 4px;
  content: "";
  height: 26px;
  left: 4px;
  position: absolute;
  transition: .4s;
  width: 26px;
}

input:checked + .slider {
  background-color: #66bb6a;
}

input:checked + .slider:before {
  transform: translateX(26px);
}

.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

/* general styling */
 
.container {
  margin: 0 auto;
}
</style>
@props(['checked' => false, 'data_student_id' => null, 'data_month' => null, 'data_year' => null, 'student_name' => null])

<label class="switch">
    <input type="checkbox"
      class="membership-checkbox"
      data-student-name="{{ $student_name }}"
      data-student-id="{{ $data_student_id ?? '' }}"
      data-month="{{ $data_month ?? '' }}"  
      data-year="{{ $data_year ?? '' }}"    
      {{ $checked ? 'checked' : '' }}>
    <span class="slider round"></span>
</label>