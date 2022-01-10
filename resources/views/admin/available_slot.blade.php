<div class="form-group">
            <select class="form-control schedule" onkeyup="createSchedule()" onchange="createSchedule()" name="slot_id" id="slot_id" required >
                <option value="">Select Time Schedule</option>
                <option value="create_schedule">Create Time Schedule</option>
                @foreach($slot as $data)            
                <option value="{{ $data->id }}">{{ date('h:i:s A', strtotime($data->start_time)) }} - {{ date('h:i:s A', strtotime($data->end_time)) }}</option>
                @endforeach                                            
            </select>
        </div>