{{ Form::open() }}

<div class="row">
                  @foreach($programs as $index => $program)
                  <div class="col-md-6">
                     <label>
                     {{ Form::checkbox('program[]', $program->id, in_array($program->id, $array) ? 'true' : '') }}
                     <span style="font-weight: lighter;">{{ $program->program_name }}</span>
                     </label>		
                  </div>
                  @endforeach
               </div>

{{ Form::close() }}