<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		.body{
			width: 80%;
			margin: auto;
		}
		.txtCenter{
			text-align: center;
		}
		.clm{
			width: 100%;
		}
		.left-clm{
			float: left;
			width: 35%;
		}
		.right-clm{
			float: left;
			width: 65%;
		}
		hr
		{ 
		  display: block;
		  margin-top: 0.5em;
		  margin-bottom: 0.5em;
		  margin-left: auto;
		  margin-right: auto;
		  border-style: inset;
		  border-width: 1px;
		}
		.rx{
			font-weight: bold;
			font-size: 40px;
		}
		.prescribe{
			margin-left: 30%;
			font-family: cursive;
		}
		.advice{
			margin-top:70px;
			margin-left: 10%;
			font-family: cursive;
		}
		.sign{
			margin-top: 50px;
			float: right;
		}
	</style>
</head>
<body>
	<div class="body"> 

		<div class="txtCenter">
			<h3>AamarLab VirtualDoctor </h3>
			<p> Dhaka, Bangladesh</p>
		</div>
		<p><span style="font-weight:bold;font-size:20px" >{{ $appointment->doctors->name }}</span><span style="float:right"><strong> Date: </strong>{{ date('j F Y', strtotime($appointment->visit_date)) }}</span></p>
		<hr>
		<div class="clm">
			<p><strong>Patient Name : </strong>{{ $appointment->users->name }}</p>
			<p><span><strong>Age:</strong>{{ $appointment->users->age }} </span>
			<span><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sex: </strong>{{ $appointment->users->gender }}</span>
			<span style="float:right;"><strong>Follow-up Visit Date: <span style="color:red">{{ date('j F Y', strtotime($appointment->follow_up_visit_date)) }}</span></strong></span></p>
		</div>
		
		<div class="clm">
			<div class="left-clm">
				<div class="advice">
				<strong><u>Symptoms</u></strong>
				<p> @php $patient_symptoms = explode(",",$appointment->patient_symptoms);@endphp  @foreach($patient_symptoms as $sinfo)
					<p>{{ $sinfo }}</p>
				@endforeach </p> 

				@if($appointment->cc)
					<strong><u>Investigation</u></strong>
					<p> @php $investigation = explode(",",$appointment->investigation);@endphp  @foreach($investigation as $iinfo)
					<p>{{ $iinfo }}</p>
				@endforeach </p> 
				@endif

				@if($appointment->cc)
					<strong><u>CC</u></strong><br>
					<p> @php $cc = explode(",",$appointment->cc);@endphp  @foreach($cc as $cinfo)
					<p>{{ $cinfo }}</p>
				@endforeach </p> 
				@endif
				</div>
			</div>
			<div class="right-clm">
			<p class="rx">R<span style="font-size:20px">x</span></p>
				<div class="prescribe">
				<p> @php $medicine = explode(",",$appointment->prescribe_medicines);@endphp  @foreach($medicine as $info)
					<p>{{ $info }}</p>
				@endforeach </p> 
			</div>
			</div>
			
		</div>
		
	</div>
</body>
</html>