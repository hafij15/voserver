@extends('layouts.backend.app')

@section('title','Chat')

@push('css')
<style>
    h4 {
        color: #ab47bc;
    }

    button {
        box-shadow: 0 0 5px gray;
    }

    section.content {
        margin: 0 0 0 300px;
    }

    .ls-closed section.content {
        margin-left: 0px;
    }

    .docVitalsignInput p {
        margin: 0 0 10px 0 !important;
    }

    .docVitalsignInput p input {
        width: 75px;
        border-radius: 4px;
    }

    .modal .modal-header .modal-title {
        display: contents;
    }

    .modal-header .close {
        box-shadow: none;
    }

    #paitentHistory h4 a {
        width: 34px;
        height: 34px;
        padding: 6px;
        border-radius: 50%;
    }

    .media-body {
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="container-fluid bg-dark">
    <div class="row">
        <div class="col-md-9">
            <section class="call-bar pt-3 pb-3 clearfix">
                <!-- <button class="btn btn-primary btn-outline-primary" type="button" id="record-btn" onclick="record();">start record</button>
                <button class="btn btn-primary btn-outline-primary" type="button" id="stop-btn" onclick="stop();">stop record</button> -->
                <!-- <button class="btn call-btn" data-toggle="modal" data-target="#createRoomModal" id="createRoomModalBtn">Create Room</button>
                <button class="btn call-btn" data-toggle="modal" data-target="#joinRoomModal" id="joinRoomModalBtn">Join</button> -->
                <div class="local-video d-inline">
                    <!-- local video here -->
                </div>
                <button class="btn" data-toggle="modal" data-target="#userConnectedModal"
                    id="userConnectedModalBtn"></button>
                <div id="duration">
                    <!-- duration shows up here -->
                </div>
                <!-- <button id="testSocket">testSocket</button> -->
                <div class="float-right room-id-div">
                    <button class="btn btn-link float-right" id="RoomInfo" data-toggle="tooltip" data-placement="bottom"
                        title="Paitent's History"><i class="fas fa-qrcode"></i></button>
                    <div class="custom-tooltip">Room ID: <span id="roomIdDiv"></span></div>
                    <i class="fas fa-caret-up"></i>
                </div>
                <button class="btn btn-link float-right" data-toggle="tooltip" onclick="openCanvas();"
                    data-placement="bottom" title="Whiteboard"><i class="fas fa-chalkboard"></i></button>
            </section>
            <!-- video container -->
            <section id="videoContainer">
                <!-- video elements here -->
                <div class="bottom-call-bar">
                    <button class="btn btn-info" id="mute-btn" data-toggle="tooltip" onclick="muteUnmute();"
                        data-placement="top"><i class="fas fa-microphone"></i></button>
                    <button class="btn btn-danger" id="leave-btn" data-toggle="modal" data-target="#endSessionModal"><i
                            class="fas fa-phone-slash"></i></button>
                    <button class="btn btn-info" id="video-btn" onclick="videoOnOff();"><i
                            class="fas fa-video"></i></button>
                    <button class="btn btn-info" id="chat-btn" onclick="openChatModal();"><i
                            class="far fa-comments"></i></button>
                </div>
                <div class="session-wellcome-msg">
                    <h4>Welcome to the virtual session!</h4>
                </div>
            </section>
        </div>
        <div class="col-md-3 chat-box-holder">
            <section id="chatBox" class="mt-3 clearfix">
                <p class="text-center"><b>Messages</b></p>
                <!-- chat here -->
            </section>
            <section class="in-group mt-3 mb-3">
                <input disabled type="text" id="message" class="d-inline" placeholder="Message here..."
                    onkeyup="sendMessage(event);">
                <div class="d-inline">
                    <button disabled class="" type="button" id="sendMessageBtn"
                        onclick="sendMessage(event);">Send</button>
                </div>
            </section>
        </div>
    </div>

    <!-- create room Modal -->
    {{-- <div class="modal fade" id="createRoomModal" tabindex="-1" role="dialog" aria-labelledby="createRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoomModalLabel">Create a Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="createRoomCheckBtn">
                        <label class="form-check-label" for="createRoomCheckBtn">Create room with a random ID</label>
                    </div>
                    <div class="form-group" id="roomIdInputDiv">
                        <label for="roomId">Enter room ID</label>
                        <input type="text" class="form-control" id="roomId" name="roomId" placeholder="E.g. 'Chat Room' or '7thrg34t3ujg'">
                    </div>
                    <div class="form-group">
                        <label for="nicknameInput">Nickname</label>
                        <input type="text" class="form-control" id="nicknameInput" placeholder="Enter a nickname to show in chat">
                    </div>
                    <button type="button" class="btn btn-primary" id="createRoomBtn">Submit</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- join room Modal -->
    {{-- <div class="modal fade" id="joinRoomModal" tabindex="-1" role="dialog" aria-labelledby="joinRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="joinRoomModalLabel">Join a Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="roomIdToJoin">Enter room ID</label>
                        <input type="text" class="form-control" id="roomIdToJoin" name="roomIdToJoin" placeholder="E.g. 'Chat Room' or '7thrg34t3ujg'">
                    </div>
                    <div class="form-group">
                        <label for="nicknameInputToJoin">Nickname</label>
                        <input type="text" class="form-control" id="nicknameInputToJoin" placeholder="Enter a nickname to show in chat">
                    </div>
                    <button type="button" class="btn btn-primary" id="joinRoomBtn">Submit</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- chat box visible in small screen -->
    <div class="chat-box-responsive">
        <button class="btn btn-outline-primary btn-primary" type="button" id="chatClose"><i
                class="fas fa-times"></i></button>
        <section id="chatBoxResponsive" class="clearfix">
            <!-- chat here -->
        </section>
        <section class="in-group mt-3">
            <input disabled type="text" id="messageRes" class="d-inline" placeholder="Comments..."
                onkeyup="sendMessage(event);">
            <div class="d-inline">
                <button disabled class="" type="button" id="sendMessageBtnRes"
                    onclick="sendMessage(event);">Send</button>
            </div>
        </section>
    </div>

    <!-- connected user modal -->
    <div class="modal fade" id="userConnectedModal" tabindex="-1" role="dialog" aria-labelledby="userConnectedLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userConnectedLabel">Connected user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="user-list">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- canvas modal -->
    <div id="widget-container">
        <!-- canvas shows up here     -->
        <button type="button" class="close" onclick="closeCanvas();">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    {{-- end session modal --}}
    <div class="modal" id="endSessionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Really want to end the session?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Was this session...</p>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="endSessionStatus"
                            onclick="enableEndSessionBtn();" id="inlineRadio" value="1">
                        <label class="form-check-label" for="inlineRadio">Successful (Service completed)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="endSessionStatus"
                            onclick="enableEndSessionBtn();" id="inlineRadio1" value="2">
                        <label class="form-check-label" for="inlineRadio1">Incomplete (Service not completed)</label>
                    </div>

                    <div class="form-check form-check-inline" id="radioInterrup">
                        <input class="form-check-input" type="radio" name="endSessionStatus"
                            onclick="enableEndSessionBtn();" id="inlineRadio2" value="3">
                        <label class="form-check-label" for="inlineRadio2">Interrupted (Any inconveniences)</label>
                    </div>
                    {{-- <div class="form-check form-check-inline" id="radioCall">
                        <input class="form-check-input" type="radio" name="endSessionStatus"
                            onclick="enableEndSessionBtn();" id="inlineRadio3" value="4">
                        <label class="form-check-label" for="inlineRadio3">Called patient (Over phone)</label>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="endSessionBtn" disabled onclick="leaveChat();">End
                        Session</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- left side navbar info for doctor and patient -->
    @if($ispatients == false)
    {{-- doctor end view --}}
    <div id="paitentHistory">
        {{-- PM meeting minutes --}}
        {{-- <textarea disabled type="text" id="meeting_mins" class="d-inline" placeholder="Comments..." onkeyup="sendMessage(event);"> --}}
        <textarea name="meeting_mins" id="txt_meeting_mins" rows="10" style="border-radius: 5px;width: 100%;
    padding: 5px 15px 15px;
    overflow-y: auto;"></textarea>
        <div class="d-inline" style="width: 100% !important;">
            <button style="width: 100% !important;" class="" type="button" id="meeting_mins_BtnRes" onclick="saveMeetingMinutes();">Save</button>
        </div>
    </div>
    @endif

    @if($ispatients == true)
    <div id="doctorHistoryPrescription">
        {{-- Employee meeting minutes --}}
    </div>
    @endif
</div>
{{-- @if(!auth()->user()->api_token)
<button class="btn btn-primary backbtn" data-toggle="modal" data-target="#endSessionModal"><i class="fa fa-caret-left"
        aria-hidden="true"></i>
    Back</button>
@endif
@if(auth()->user()->api_token)
<a class="homebtn" data-toggle="modal" data-target="#endSessionModal">
    <span class="material-icons"
        style="font-weight: bold;font-size: 18px !important; color: #fff;">
        home
    </span>
</a>
@endif --}}
<a class="homebtn" data-toggle="modal" data-target="#endSessionModal">
    <span class="material-icons" style="font-weight: bold;font-size: 18px !important; color: #fff;">
        home
    </span>
</a>
@endsection

@push('js')
<script type="text/javascript">
    // set some global variable for custom_rtc.js
    @php
    echo "var iceServers = $result;";
    echo "var appointmentId = '$id';";
    echo "var roomId = '$room';";
    echo "var nickname = '$userName';";
    echo "var isPatients = '$ispatients';";
    // echo "var subTaskId = $virtualSessionStatus;";
    echo "var appiontmantData = $appointment;";
    @endphp

</script>
<!-- webRTC script -->
<script src="{{ asset('public/js/RTCMultiConnection.min.js')}}"></script>
<script src="{{ asset('public/js/socket.io.js')}}"></script>
<!-- Record  -->
{{-- <script src="https://xoma.co/nacional/consultasweb/dist/RecordRTC.js"></script> --}}
<script src="{{ asset('public/js/RecordRTC_new.js')}}"></script>
<script src="{{ asset('public/js/webrtc-handler.js')}}"></script>
<script src="{{ asset('public/js/canvas-designer-widget.js')}}"></script>
<script language="JavaScript" type="text/javascript" src="{{ asset('public/js/custom_rtc.js')}}"></script>
<!-- fontawesome kit -->
<script src="https://kit.fontawesome.com/a516280526.js" crossorigin="anonymous"></script>
@endpush
