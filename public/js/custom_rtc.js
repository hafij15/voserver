var connection = new RTCMultiConnection();
var designer = new CanvasDesigner();
var chkRecordConference = true;
var baseUrl = window.location.href.split("/chat/");
var chatArray = [];
var allMsg = "";
var muteState;
var videoState = true;
var vitalSign;
var complains;
var diagnosis;
var investigation;
var instruction;
var medicine;
var followUpDate;

// ajax setup for laravel
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    // first step, ignore default STUN+TURN servers
    connection.iceServers = [];
    connection.iceServers = iceServers;
    // hide doctors field to patient
    if (isPatients == true) {
        $(".doctor-field").css("display", "none");
        $("#chatBox").css("height", "calc(100vh - 143px");
        $("#radioInterrup").hide();
        $("#radioCall").hide();
    } else {
        $(".patient-field").css("display", "none");
        $("#radioInterrup").show();
        $("#radioCall").show();
    }
    // disable the back button
    history.pushState(null, null, location.href);
    history.back();
    history.forward();
    window.onpopstate = function () {
        history.go(1);
    };
});

// this line is VERY_important
connection.socketURL = "https://rtcmulticonnection.herokuapp.com:443/";

// connect to socket on disconnect and join to the existing room
connection.onSocketDisconnect = function (event) {
    $("#videoContainer").append(
        "<div class='connection-lost-msg'><b>Connection lost</b><br><i class='fas fa-wave-square'></i><br>reconnecting...</div>"
    );
    // parameter passed 0 since no user conneceted as sockect disconnected
    setVideoElementStyle(0);
    // remove all remote video element from UI as socket disconnected
    $("#videoContainer").children().filter("video").each(function () {
        // console.log(sessionStorage.getItem("localVideoId"), this.id)
        if (sessionStorage.getItem("localVideoId") != this.id) {
            this.pause(); // can't hurt
            delete this; // @sparkey reports that this did the trick (even though it makes no sense!)
            $(this).remove(); // this is probably what actually does the trick
        }
    });
    // console.log("socket.io connection is closed in custom...");

    // set meeting min in session when connection get lost.
    sessionStorage.setItem("meeting_min", $("#txt_meeting_mins").val());

    // reconnect to socket and generate video element again
    connection.connectSocket(function () {
        // console.log('Successfully connected to socket.io server...');
        setTimeout(function () {
            if (sessionStorage.getItem("roomId") != null) {
                roomSessionExist();
                $("#videoContainer").children().filter(".connection-lost-msg").each(function () {
                    $(this).remove();
                });
            }
        }, 1000)
    });
}

// if you want audio+video+chat conferencing
connection.session = {
    audio: true,
    video: true,
    data: true,
};

connection.bandwidth = {
    audio: 6,  // 50 kbps
    video: 100 // 256 kbps
};

connection.codecs = {
    video: 'VP9',
    audio: 'G722'
};

// alert('is current user has low bandwidth? ' + connection.isLowBandwidth === true);

// set getUserMedia for different browser --- (under develoment)
navigator.getUserMedia = navigator.getUserMedia ||
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia;

// check user media is available or not --- (under develoment)
// function getMedia() {
//     if (navigator.getUserMedia) {
//         navigator.getUserMedia(connection.session,
//             function (stream) {
//                 // console.log("get user media: ", stream);
//             },
//             function (err) {
//                 // console.log("The following error occurred: " + err);
//                 alert("There is problem with microphone and camera or used by other application!");
//             }
//         );
//     } else {
//         console.log("getUserMedia not supported");
//     }
// }
function getMedia() {
    // console.log(navigator.getUserMedia);
    if (navigator.getUserMedia) {
        navigator.getUserMedia(connection.session,
            function (stream) {
                console.log("get user media: ", stream);
            },
            function (err) {
                // console.log("The following error occurred: " + err);
                $("#videoContainer").append('<div class="alert alert-danger">' +
                    'Apparently, your webcam is being used or blocked by another application. To start your webcam, you must temporarily close that application.' +
                    '</div >'
                )
                // alert("Apparently, your webcam is being used or blocked by another application. To start your webcam, you must temporarily close that application.");
            }
        );
    } else {
        console.log("getUserMedia not supported");
    }
}

connection.sdpConstraints.mandatory = {
    OfferToReceiveAudio: true,
    OfferToReceiveVideo: true,
    VoiceActivityDetection: false
};

// required canvas page here
designer.widgetHtmlURL = baseUrl[0] + '/public/js/widget.html';
designer.widgetJsURL = "widget.min.js";

// default selected tool in whiteboard
designer.setSelected("pencil");

// list of tools in whiteboard
designer.setTools({
    pencil: true,
    text: true,
    image: true,
    pdf: true,
    eraser: true,
    line: true,
    arrow: true,
    dragSingle: true,
    dragMultiple: true,
    arc: true,
    rectangle: true,
    quadratic: false,
    bezier: true,
    marker: true,
    zoom: false,
    lineWidth: false,
    colorsPicker: false,
    extraOptions: false,
    code: false,
    undo: true,
});

// get time function
function getTime(timeFor) {
    if (timeFor == "chat") {
        var date = new Date();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? "pm" : "am";
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? "0" + minutes : minutes;
        var time = hours + ":" + minutes + " " + ampm;
        return time;
    } else if (timeFor == "meeting") {
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth();
        var year = date.getFullYear();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
        var currentTime = month + "/" + day + "/" + year + " " + hours + ":" + minutes + ":" + seconds;
        return currentTime;
    }
}

// return number of user connected and enable the spme input/button on more than one connected user
function numberOfUsers() {
    // console.log(connection.getAllParticipants());
    var usersConnectedlist = connection.getAllParticipants();
    var usersConnected = usersConnectedlist.length;
    userList(usersConnectedlist, usersConnected);
    // do some task based on user connected
    if (usersConnected > 0) {
        $("#complains").prop("disabled", false);
        $("#diagnosis").prop("disabled", false);
        $("#investigation").prop("disabled", false);
        $("#instruction").prop("disabled", false);
        $("#medicine").prop("disabled", false);
        $("#followUpDate").prop("disabled", false);
        $("#complainsRes").prop("disabled", false);
        $("#diagnosisRes").prop("disabled", false);
        $("#investigationRes").prop("disabled", false);
        $("#medicineRes").prop("disabled", false);
        $("#instructionRes").prop("disabled", false);
        $("#followUpDateRes").prop("disabled", false);
        $("#sendMessageBtn").prop("disabled", false);
        $("#message").prop("disabled", false);
        $("#sendMessageBtnRes").prop("disabled", false);
        $("#messageRes").prop("disabled", false);
        $("#sendVitalBtn").prop("disabled", false);
        $("#sendVitalBtnRes").prop("disabled", false);
        $("#sendPrescriptionBtn").prop("disabled", false);
        $("#sendPrescriptionBtnRes").prop("disabled", false);
        $("#saveVitalDataBtn").prop("disabled", false);
        $("#txt_temp").prop("disabled", false);
        $("#txt_weight").prop("disabled", false);
        $("#txt_pulse").prop("disabled", false);
        $("#txt_bp").prop("disabled", false);
        $("#txt_oxy").prop("disabled", false);
    } else {
        $("#complains").prop("disabled", true);
        $("#diagnosis").prop("disabled", true);
        $("#investigation").prop("disabled", true);
        $("#instruction").prop("disabled", true);
        $("#medicine").prop("disabled", true);
        $("#followUpDate").prop("disabled", true);
        $("#complainsRes").prop("disabled", true);
        $("#diagnosisRes").prop("disabled", true);
        $("#investigationRes").prop("disabled", true);
        $("#medicineRes").prop("disabled", true);
        $("#instructionRes").prop("disabled", true);
        $("#followUpDateRes").prop("disabled", true);
        $("#sendMessageBtn").prop("disabled", true);
        $("#message").prop("disabled", true);
        $("#sendMessageBtnRes").prop("disabled", true);
        $("#messageRes").prop("disabled", true);
        $("#sendVitalBtn").prop("disabled", true);
        $("#sendVitalBtnRes").prop("disabled", true);
        $("#sendPrescriptionBtn").prop("disabled", true);
        $("#sendPrescriptionBtnRes").prop("disabled", true);
        $("#saveVitalDataBtn").prop("disabled", true);
        $("#txt_temp").prop("disabled", true);
        $("#txt_weight").prop("disabled", true);
        $("#txt_pulse").prop("disabled", true);
        $("#txt_bp").prop("disabled", true);
        $("#txt_oxy").prop("disabled", true);
    }
    return usersConnected;
}

// list out all connected user
function userList(usersConnectedlist, usersConnected) {
    if (usersConnected == 0) {
        $(".user-list").empty();
        $(".user-list").append(
            "Nobody joined the room."
        );
        $("#userConnectedModalBtn").css("display", "none");
    } else if (usersConnected > 0) {
        $(".user-list").empty();
        // list all connected user
        usersConnectedlist.forEach(function (element) {
            // console.log(connection.peers[element]);
            var userExtraInfo = connection.peers[element].extra;
            $(".user-list").append(
                '<div class="media mb-3">' +
                '<div class="media-left media-middle">' +
                '<img class="mr-3" src="https://www.enigmatixmedia.com/pics/demo.png" alt="Generic placeholder image">' +
                '</div>' +
                '<div class="media-body">' +
                userExtraInfo.fullName +
                '</div>' +
                '</div>'
            );
        });
        $("#userConnectedModalBtn").css("display", "inline");
        $("#userConnectedModalBtn").html(
            '<i class="fas fa-network-wired"></i> <span>' +
            usersConnected +
            '</span> <i class="fas fa-angle-down"></i>'
        );
    }
}

// input value saved in session for doctors field with input field ID
function storeInSession(e) {
    var inputId = e.srcElement.id;
    sessionStorage.setItem(e.srcElement.id, $("#" + inputId).val());
    // console.log(sessionStorage);
}

// show meeting duration
setInterval(function () {
    if (sessionStorage.getItem("roomId") != null) {
        var currentTime = getTime("meeting");
        var startTime = sessionStorage.getItem("meetingStartTime");
        var diff = moment.duration(moment(currentTime).diff(moment(startTime)));
        var formatedData = [diff.asHours().toFixed(0), diff.minutes(), diff.seconds()].join(':');
        // console.log(startTime, currentTime, diff);
        $("#duration").css("display", "inline");
        $("#duration").html('<i class="fas fa-stopwatch"></i> ' + formatedData);
    }
}, 1000);

// user on leave
connection.onleave = function (event) {
    // console.log("who leave: ", event, connection.isInitiator)
    var remoteUserFullName = event.extra.fullName;
    customAlert(remoteUserFullName)
    setTimeout(function () {
        var usersConnected = numberOfUsers();
        setVideoElementStyle(usersConnected);
        // console.log("after -> participent after leave someone: ", connection.getAllParticipants());
    }, 1000)
}

// alert of which user leave
function customAlert(msg) {
    $("body").append(
        "<div class='custom-alert-active'><b>" + msg + "</b> left the session</div>"
    )
    setTimeout(function () {
        $(".custom-alert-active").addClass("custom-alert-inactive");
        $(".custom-alert-active").removeClass("custom-alert-active");
    }, 10000)
}

$("#RoomInfo").click(function () {
    if ($(".room-id-div .custom-tooltip").css("display") == "none") {
        $(".room-id-div .custom-tooltip").css("display", "block");
        $(".room-id-div .fa-caret-up").css("display", "block");
    } else {
        $(".room-id-div .custom-tooltip").css("display", "none");
        $(".room-id-div .fa-caret-up").css("display", "none");
    }
})

// check room id in session storage and join room
function roomSessionExist() {
    $("#chatBox")
        .children()
        .filter(".single-chat-div")
        .each(function () {
            $(this).remove();
        });
    var roomId = sessionStorage.getItem("roomId");
    // insert extra info of user
    connection.extra = {
        fullName: nickname,
    };
    connection.openOrJoin(roomId);
    document.getElementById("roomIdDiv").innerText = roomId;
    
    $("#txt_meeting_mins").val(sessionStorage.getItem("meeting_min"));

    chatArray = JSON.parse(sessionStorage.getItem("chatArray")) == null ? [] : JSON.parse(sessionStorage.getItem("chatArray"));
    // console.log(JSON.parse(sessionStorage.getItem("chatArray")));
    if (chatArray != null) {
        chatArray.forEach(function (params) {
            $("#chatBox").append(params);
        });
    }
    // $("#createRoomModalBtn").prop("disabled", true);
    // $("#joinRoomModalBtn").prop("disabled", true);
}

$(document).ready(function () {
    getMedia();
    // if session exxist then join room
    if (sessionStorage.getItem("roomId") != null) {
        roomSessionExist();
    } else {
        // console.log(appointmentId, roomId, nickname);
        connection.openOrJoin(roomId);
        sessionStorage.setItem("roomId", roomId);
        sessionStorage.setItem("meetingStartTime", getTime("meeting"));
        $("#roomIdDiv").text(roomId);
        // insert extra info of user
        connection.extra = {
            fullName: nickname,
        };
    }
});

// append video element on streaming video client
connection.onstream = function (event) {
    // console.log(event);
    $(document).ready(function () {
        // video container selector
        var video_container = document.getElementById("videoContainer");
        var video = event.mediaElement;
        // get connected user number
        var usersConnected = numberOfUsers();
        $(".bottom-call-bar > button").css("display", "inline-block");
        if (usersConnected == 0 && sessionStorage.getItem("joinedOnce") == null) {
            $(".session-wellcome-msg").css("display", "block");
        } else {
            $(".session-wellcome-msg").css("display", "none");
            sessionStorage.setItem("joinedOnce", true)
        }
        // console.log(connection.extra);
        if (event.type == "local") {
            sessionStorage.setItem("localVideoId", event.streamid);
            $(".local-video").append(video);
            var vids = $(".local-video>video");
            // hide video controls
            $.each(vids, function () {
                this.controls = false;
            });
            if (confirm("would you like to join with your microphone on?\nPlease use headphone if there is echo!")) {
                muteState = false;
                muteUnmute();
            } else {
                muteState = true;
                muteUnmute();
            }
        } else {
            video_container.appendChild(video);
            setVideoElementStyle(usersConnected);
        }
        // recording rtc
        if (chkRecordConference === true) {
            var recorder = connection.recorder;
            if (!recorder) {
                recorder = RecordRTC([event.stream], {
                    type: 'video'
                });
                recorder.startRecording();
                connection.recorder = recorder;
            } else {
                recorder.getInternalRecorder().addStreams([event.stream]);
            }
            if (!connection.recorder.streams) {
                connection.recorder.streams = [];
            }
            connection.recorder.streams.push(event.stream);
            //   recordingStatus.innerHTML = 'Recording ' + connection.recorder.streams.length + ' streams';
        }
    })
};

// session record end method
function AddZero(num) {
    return num >= 0 && num < 10 ? "0" + num : num + "";
}
function stopRTCrecord() {
    //console.log(event);
    var recorder = connection.recorder;
    if (!recorder) return alert('No recorder found.');
    recorder.stopRecording(function () {
        var blob_ = recorder.getBlob();
        var now = new Date();
        var strDate = [
            [
                AddZero(now.getDate()),
                AddZero(now.getMonth() + 1),
                now.getFullYear(),
            ].join("-")];
        var time = [[AddZero(now.getHours()), AddZero(now.getMinutes())].join("-")];
            var strDateTime = strDate + "_" + time;;
            // console.log("strDateTime: " + strDateTime);

        //subtask_id and date time 
        var name = (appiontmantData.subtask_id != null?appiontmantData.subtask_id:0) + "_session_recording_" + strDateTime;
        var blob = new Blob([blob_], {
            type: "video/mp4",
        });
        // var mp4Name = encodeURIComponent(
        //     "session_recording_" + new Date().getTime() + ".mp4"
        // );
        // save to disk this "blob"
        var data = new FormData();
        data.append("video", blob); //Correct: sending the Blob itself   enctype: 'multipart/form-data',
        data.append("name", name);
        data.append("subtask_id", appiontmantData.subtask_id);

        console.log("video data: ", appiontmantData.subtask_id);
        $.ajax({
            type: "POST",
            url: baseUrl[0] + "/send_session_record",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                console.log(data);
            },
            error: function (e) {
                console.log("ERROR : ", e);
            },
        });
    });

// invokeSaveAsDialog(blob);
connection.recorder = null;

    
};

// set video elemet style
function setVideoElementStyle(usersConnected) {
    var w = $("#videoContainer").innerWidth();
    var h = $("#videoContainer").innerHeight();
    // var containerDimension = Math.min(w, h) * Math.min(w, h);
    // var numberOfTiles = containerDimension / (usersConnected);
    // var videoDimension = Math.floor(Math.sqrt(numberOfTiles));
    // console.log(usersConnected, w, h, containerDimension, numberOfTiles, videoDimension);
    // $("#videoContainer>video").css({
    //     height: videoDimension,
    //     width: videoDimension
    // });
    if (usersConnected == 1) {
        $("#videoContainer>video").css({
            height: "auto",
            width: "auto"
        });
    } else if (usersConnected == 2) {
        $("#videoContainer>video").css({
            height: "auto",
            width: w / 2
        });
    } else if (usersConnected == 3) {
        $("#videoContainer>video").css({
            height: "auto",
            width: w / 3
        });
    } else if (usersConnected == 4) {
        $("#videoContainer>video").css({
            height: h / 2,
            width: w / 2
        });
    }
    var vids = $("video");
    // console.log(vids);
    // hide controls for remote video
    $.each(vids, function () {
        this.controls = false;
    });
}

// leave the chat room function 
function leaveChat() {
    // disconnect with all users
    connection.getAllParticipants().forEach(function (pid) {
        connection.disconnectWith(pid);
    });
    // stop all local cameras
    connection.attachStreams.forEach(function (localStream) {
        // console.log(localStream);
        localStream.stop();
    });
    connection.closeSocket();
    $("#videoContainer").children().filter("video").each(function () {
        this.pause(); // can't hurt
        delete this; // @sparkey reports that this did the trick (even though it makes no sense!)
        $(this).remove(); // this is probably what actually does the trick
    });
    $("#roomIdDiv").text("NO ROOM JOINED");
    $("#createRoomModalBtn").prop("disabled", false);
    $("#joinRoomModalBtn").prop("disabled", false);
    $(".bottom-call-bar > button").css("display", "none");
    $("#userConnectedModalBtn").css("display", "none");
    $("#duration").css("display", "none");
    $("#sendMessageBtn").prop("disabled", true);
    $("#message").prop("disabled", true);
    $("#sendMessageBtnRes").prop("disabled", true);
    $("#messageRes").prop("disabled", true);
    $("#videoContainer").children().filter(".connection-lost-msg").each(function () {
        $(this).remove();
    })
    var spentHour = $("#duration").text();
    var sessionConfirm = $("input[name='endSessionStatus']:checked").val();
    saveMeetingMinutes();
    stopRTCrecord();
    // if (isPatients == false) {
    //     vitalSign = "Temperature: " + $("#txt_temp").val() + "째F" +
    //         ",Pulse: " + $("#txt_pulse").val() + "bpm," +
    //         "Weight: " + $("#txt_weight").val() + "Kg," +
    //         "Blood Pressure: " + $("#txt_bp").val() + "mmHg," +
    //         "Oxygen Rate: " + $("#txt_oxy").val() + "%";
    //     $.ajax({
    //         method: "POST",
    //         dataType: 'JSON',
    //         url: baseUrl[0] + "/send_session_data",
    //         data: {
    //             'appointmentId': appointmentId,
    //             'spentHour': spentHour,
    //             'vitalSign': vitalSign,
    //             'complains': complains,
    //             'diagnosis': diagnosis,
    //             'investigation': investigation,
    //             'instruction': instruction,
    //             'medicine': medicine,
    //             'followUpDate': followUpDate,
    //             'vrSessionStatus': sessionConfirm
    //         },
    //         success: function (result) {
    //             // alert(result);
    //         },
    //     });
    // } else {
    //     console.log("temp:" + $("#temp").val());
    //     $.ajax({
    //         method: "POST",
    //         dataType: 'JSON',
    //         url: baseUrl[0] + "/send_session_status_patient",
    //         data: {
    //             'appointmentId': appointmentId,
    //             'vrSessionStatus': sessionConfirm
    //         },
    //         success: function (result) {
    //             // alert(result);
    //         },
    //     });
    // }
    sessionStorage.clear();
    $('<div class="page-loader-wrapper text-center">' +
        '<div class = "loader">' +
        '<div class = "preloader">' +
        '<div class = "spinner-layer pl-red">' +
        '<div class = "circle-clipper left">' +
        '<div class = "circle">' +
        '</div>' +
        '</div>' +
        '<div class = "circle-clipper right">' +
        '<div class = "circle">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<p> Processing... <br> <small> Storing data and redirecting to home page. </small></p>' +
        '</div>' +
        '</div>').appendTo("body");
    setTimeout(() => {
        window.location.href = baseUrl[0] + '/dashboard';
    }, 3000);
};

// enable end session btn
function enableEndSessionBtn() {
    $("#endSessionBtn").prop("disabled", false);
}

// Reset Radio btn (close modal)
$('#endSessionModal').on('hidden.bs.modal', function (e) {
    $('#endSessionModal input').prop("checked", false);
    $("#endSessionBtn").prop("disabled", true);
});

$('#endSessionModal').on('shown.bs.modal', function () {
    if (sessionStorage.getItem("sessionSuccess") == "true") {
        $("#inlineRadio").prop("checked", true);
        $("#endSessionBtn").prop("disabled", false);
    }
})

// enable Input when give service over phone
function enablePresOnCall() {
    $("#complains").prop("disabled", false);
    $("#diagnosis").prop("disabled", false);
    $("#investigation").prop("disabled", false);
    $("#instruction").prop("disabled", false);
    $("#medicine").prop("disabled", false);
    $("#followUpDate").prop("disabled", false);
    $("#complainsRes").prop("disabled", false);
    $("#diagnosisRes").prop("disabled", false);
    $("#investigationRes").prop("disabled", false);
    $("#medicineRes").prop("disabled", false);
    $("#instructionRes").prop("disabled", false);
    $("#followUpDateRes").prop("disabled", false);
    $("#sendPrescriptionBtn").prop("disabled", false);
    $("#sendPrescriptionBtnRes").prop("disabled", false);
    $("#saveVitalDataBtn").prop("disabled", false);
    $("#txt_temp").prop("disabled", false);
    $("#txt_weight").prop("disabled", false);
    $("#txt_pulse").prop("disabled", false);
    $("#txt_bp").prop("disabled", false);
    $("#txt_oxy").prop("disabled", false);
}

// send an initial message on open data channel
connection.onopen = function (event) {
    connection.send('1stmsgonopendatachannel');
};

// send message function
function sendMessage(e) {
    if (e.type == "click" || e.keyCode == 13) {
        // console.log(nickname)
        var textmsg = document.getElementById("message");
        var textmsgRes = document.getElementById("messageRes");
        var messageArray = {};
        // console.log(textmsg.value)
        var time = getTime("chat");
        if (textmsg.value != "") {
            var chatMsg = "<div class='clearfix single-chat-div'><p class='sent-msg-para text-right float-right'><small>" +
                time +
                "</small><br>" +
                textmsg.value +
                "</p></div>";
            // console.log(chatMsg);
            chatArray.push(chatMsg);
            sessionStorage.setItem("chatArray", JSON.stringify(chatArray));
            $("#chatBox").append(chatMsg);
            $("#chatBox").animate({
                scrollTop: $("#chatBox").get(0).scrollHeight,
            },
                1000
            );
            messageArray = {
                msg: textmsg.value,
                username: nickname,
                // msgRes: textmsgRes.value,
            };
            // console.log(messageArray);
            connection.send(messageArray);
            textmsg.value = null;
        }
        if (textmsgRes.value != "") {
            var chatMsg = "<div class='clearfix single-chat-div'><p class='sent-msg-para text-right float-right'><small>" +
                time +
                "</small><br>" +
                textmsgRes.value +
                "</p></div>";
            chatArray.push(chatMsg);
            sessionStorage.setItem("chatArray", JSON.stringify(chatArray));
            // console.log(chatArray);
            $("#chatBoxResponsive").append(chatMsg);
            $("#chatBoxResponsive").animate({
                scrollTop: $("#chatBoxResponsive").get(0).scrollHeight,
            },
                1000
            );
            messageArray = {
                msg: textmsgRes.value,
                username: nickname,
                // msgRes: textmsgRes.value,
            };
            // console.log(messageArray);
            connection.send(messageArray);
            textmsgRes.value = null;
        }
    }
}

function saveMeetingMinutes() {
    console.log($("#txt_meeting_mins").val());
    var spentHour = $("#duration").text();
    var meetingMin = $("#txt_meeting_mins").val();
   
    // if (meetingMin != "" && meetingMin != undefined && meetingMin != null) {

    // } else {
    //     console.log('Nothing to update');
    // }
    $.ajax({
        method: "POST",
        dataType: "JSON",
        url: baseUrl[0] + "/send_meeting_mins",
        data: {
            meetingId: appointmentId,
            spentHour: spentHour,
            meetingMin: meetingMin,
        },
        success: function (result) {
            alert("Meeting minutes has been saved successfully!");
        },
    });
    
    // closeHistory();
    // alert("Vital signs has been saved successfully!");
}

//send vital data
function sendVitalData(e) {
    var texttemp = document.getElementById("temp");
    var textweight = document.getElementById("weight");
    var textpulse = document.getElementById("pulse");
    var textbp = document.getElementById("bp");
    var textoxygen = document.getElementById("oxygen");
    var messageArray = {};
    if (texttemp.value != "") {
        messageArray = {
            data_temp: texttemp.value,
            data_pulse: (textpulse.value != "" ? textpulse.value : "Not Applicable"),
            data_weight: (textweight.value != "" ? textweight.value : "Not Applicable"),
            data_bp: (textbp.value != "" ? textbp.value : "Not Applicable"),
            data_oxygen: (textoxygen.value != "" ? textoxygen.value : "Not Applicable")
        };
        // console.log("messageArray: " + messageArray);
        connection.send(messageArray);
        alert("Vital signs has been sent to Doctor!")
    }
}

//send vital data Responsive
function sendVitalDataRes(e) {
    var texttemp = document.getElementById("tempRes");
    var textpulse = document.getElementById("pulseRes");
    var textweight = document.getElementById("weightRes");
    var textbp = document.getElementById("bpRes");
    var textoxygen = document.getElementById("oxygenRes");
    var messageArray = {};
    if (texttemp.value != "") {
        messageArray = {
            data_temp: texttemp.value,
            data_pulse: (textpulse.value != "" ? textpulse.value : "Not Applicable"),
            data_weight: (textweight.value != "" ? textweight.value : "Not Applicable"),
            data_bp: (textbp.value != "" ? textbp.value : "Not Applicable"),
            data_oxygen: (textoxygen.value != "" ? textoxygen.value : "Not Applicable")
        };
        // console.log("messageArray: " + messageArray);
        connection.send(messageArray);
        closeHistory();
        alert("Vital signs has been sent to Doctor!");
    }
}

// send prescription
function sendPrescriptionData() {
    var spentHour = $("#duration").text();
    complains = $("#complains").val();
    diagnosis = $("#diagnosis").val();
    investigation = $("#investigation").val();
    instruction = $("#instruction").val();
    medicine = $("#medicine").val();
    followUpDate = $("#followUpDate").val();
    var messageArray = {};
    if (complains != "") {
        messageArray = {
            data_complain: complains,
            data_diagnosis: (diagnosis != "" ? diagnosis : "Not Applicable"),
            data_investigation: (investigation != "" ? investigation : "Not Applicable"),
            data_instruction: (instruction != "" ? instruction : "Not Applicable"),
            data_medicine: (medicine != "" ? medicine : "Not Applicable"),
            data_followUpDate: (followUpDate != "" ? followUpDate : "Not Applicable")
        };
        // console.log("messageArray: " + messageArray);
        connection.send(messageArray);
    }
    // store prescription to DB
    $.ajax({
        method: "POST",
        dataType: 'JSON',
        url: baseUrl[0] + "/send_session_data",
        data: {
            'appointmentId': appointmentId,
            'spentHour': spentHour,
            'vitalSign': vitalSign,
            'complains': complains,
            'diagnosis': diagnosis,
            'investigation': investigation,
            'instruction': instruction,
            'medicine': medicine,
            'followUpDate': followUpDate
        },
        success: function (result) {
            //result
        },
    });
    alert("Prescription has been saved successfully!");
    sessionStorage.setItem("sessionSuccess", true);
}

// send prescription Responsive
function sendPrescriptionDataRes() {
    var spentHour = $("#duration").text();
    complains = $("#complainsRes").val();
    diagnosis = $("#diagnosisRes").val();
    investigation = $("#investigationRes").val();
    instruction = $("#instructionRes").val();
    medicine = $("#medicineRes").val();
    followUpDate = $("#followUpDateRes").val();
    var messageArray = {};
    if (complains != "") {
        messageArray = {
            data_complain: complains,
            data_diagnosis: (diagnosis != "" ? diagnosis : "Not Applicable"),
            data_investigation: (investigation != "" ? investigation : "Not Applicable"),
            data_instruction: (instruction != "" ? instruction : "Not Applicable"),
            data_medicine: (medicine != "" ? medicine : "Not Applicable"),
            data_followUpDate: (followUpDate != "" ? followUpDate : "Not Applicable")
        };
        // console.log("messageArray: " + messageArray);
        connection.send(messageArray);
    }
    // store prescription to DB
    $.ajax({
        method: "POST",
        dataType: 'JSON',
        url: baseUrl[0] + "/send_session_data",
        data: {
            'appointmentId': appointmentId,
            'spentHour': spentHour,
            'vitalSign': vitalSign,
            'complains': complains,
            'diagnosis': diagnosis,
            'investigation': investigation,
            'instruction': instruction,
            'medicine': medicine,
            'followUpDate': followUpDate
        },
        success: function (result) {
            //result
        },
    });
    closeRx();
    alert("Prescription has been saved successfully!");
    sessionStorage.setItem("sessionSuccess", true);
}

// on message arrival append the message to chat box
connection.onmessage = function (event) {
    // console.log(event.data.data_temp);
    // sync data for canvas
    designer.syncData(event.data);
    if (event.data.msg != undefined) {
        var msg = event.data.msg;
        var time = getTime("chat");
        var chatMsg = "<div class='clearfix single-chat-div'><p class='received-msg-para text-left float-left'><small>" +
            time +
            " <b class='text-uppercase'>" +
            event.data.username +
            "</b></small><br>" +
            msg +
            "</p></div>";
        chatArray.push(chatMsg);
        sessionStorage.setItem("chatArray", JSON.stringify(chatArray));
        // console.log(chatArray);
        $("#chatBox").append(chatMsg);
        $("#chatBox").animate({
            scrollTop: $("#chatBox").get(0).scrollHeight,
        },
            1000
        );
        $("#chatBoxResponsive").append(
            "<div class='clearfix single-chat-div'><p class='received-msg-para text-left float-left'><small>" +
            time +
            " <b class='text-uppercase'>" +
            event.data.username +
            "</b></small><br>" +
            msg +
            "</p></div>"
        );
        $("#chatBoxResponsive").animate({
            scrollTop: $("#chatBoxResponsive").get(0).scrollHeight,
        },
            1000
        );
        // var msgToStore = time + ": " + event.data.username + "\n" + event.data.msg + "\n";
    }
    // show vital data for doctor after send by patient
    if (event.data.data_temp != "" && event.data.data_temp != undefined && event.data.data_temp != null) {
        $("#txt_temp").val(event.data.data_temp);
        $("#txt_pulse").val(event.data.data_pulse);
        $("#txt_weight").val(event.data.data_weight);
        $("#txt_bp").val(event.data.data_bp);
        $("#txt_oxy").val(event.data.data_oxygen);
        sessionStorage.setItem("txt_temp", event.data.data_temp);
        sessionStorage.setItem("txt_pulse", event.data.data_pulse);
        sessionStorage.setItem("txt_weight", event.data.data_weight);
        sessionStorage.setItem("txt_bp", event.data.data_bp);
        sessionStorage.setItem("txt_oxy", event.data.data_oxygen);
    }
    // append prescribed data to the patient end
    if (event.data.data_complain != "" && event.data.data_complain != undefined) {
        alert("The doctor has written a prescription for you.");
        $("#getPrescription").empty();
        $("#getPrescription").append(
            "<hr>" +
            "<h4><i class='fas fa-prescription'></i> Prescription</h4>" +
            "<p><b>C/C:</b><pre>" + event.data.data_complain + "</pre></p>" +
            "<p><b>Diagnosis:</b><pre>" + event.data.data_diagnosis + "</pre></p>" +
            "<p><b>Investigation:</b><pre>" + event.data.data_investigation + "</pre></p>" +
            "<p><b>Medicine:</b><pre>" + event.data.data_medicine + "</pre></p>" +
            "<p><b>Instruction:</b><pre>" + event.data.data_instruction + "</pre></p>" +
            "<p><b>Follow-up date:</b><pre>" + event.data.data_followUpDate + "</pre></p>"
        );
        sessionStorage.setItem("sessionSuccess", true);
    }
};

// open chat on mobile view
function openChatModal() {
    $(".chat-box-responsive").css("display", "block");
    var urlReplace = "#" + $(this).attr("id"); // make the hash the id of the modal shown
    history.pushState(null, null, urlReplace); // push state that hash into the url
};

// close chat on mobile view
$("#chatClose").click(function () {
    $(".chat-box-responsive").css("display", "none");
});

// close modal on back button click for mobile view
$(".modal").on("shown.bs.modal", function () {
    // any time a modal is shown
    var urlReplace = "#" + $(this).attr("id"); // make the hash the id of the modal shown
    history.pushState(null, null, urlReplace); // push state that hash into the url
});

// If a pushstate has previously happened and the back button is clicked, hide any modals.
$(window).on("popstate", function () {
    $(".modal").modal("hide");
    $(".chat-box-responsive").css("display", "none");
});

// mute or unmute own microphone
function muteUnmute() {
    // alert(muteState);
    if (muteState === true) {
        var localStream = connection.attachStreams[0];
        // localStream.unmute("audio");
        localStream.getAudioTracks()[0].enabled = false;
        muteState = false;
        $("#mute-btn > i").addClass("fa-microphone-slash");
        $("#mute-btn > i").removeClass("fa-microphone");
        $("#mute-btn").attr('title', "unmute");
    } else if (muteState === false) {
        var localStream = connection.attachStreams[0];
        // localStream.mute("audio");
        localStream.getAudioTracks()[0].enabled = true;
        muteState = true;
        $("#mute-btn > i").addClass("fa-microphone");
        $("#mute-btn > i").removeClass("fa-microphone-slash");
        $("#mute-btn").attr('title', "mute");
    }
}

// video on/off
function videoOnOff() {
    // alert(videoState);
    if (videoState === true) {
        // console.log(connection.attachStreams)
        var localStream = connection.attachStreams[0];
        localStream.getVideoTracks()[0].enabled = false;
        // localStream.mute("video");
        videoState = false;
        $("#video-btn > i").addClass("fa-video-slash");
        $("#video-btn > i").removeClass("fa-video");
        $("#video-btn").attr('title', "turn on video");
    } else if (videoState === false) {
        // console.log(connection.attachStreams)
        var localStream = connection.attachStreams[0];
        localStream.getVideoTracks()[0].enabled = true;
        // localStream.unmute("video");
        videoState = true;
        $("#video-btn > i").addClass("fa-video");
        $("#video-btn > i").removeClass("fa-video-slash");
        $("#video-btn").attr('title', "turn off video");
    }
}

// append whiteboard/cxanvas in the view
designer.appendTo(document.getElementById("widget-container"));

// send canvas data to remote user
designer.addSyncListener(function (data) {
    // console.log(data);
    connection.send(data);
});

// open canvas function
function openCanvas() {
    $("#widget-container").css({
        "visibility": "visible",
        "z-index": "999999999"
    });
    $("#widget-container>iframe").css({
        "top": "50%",
        "opacity": "1"
    });
    $("#widget-container>button").css({
        "top": "15px"
    })
}

// hide canvas function
function closeCanvas() {
    $("#widget-container>iframe").css({
        "top": "-50%",
        "opacity": "0"
    });
    $("#widget-container>button").css({
        "top": "-35px"
    })
    setTimeout(function () {
        $("#widget-container").css({
            "visibility": "hidden",
            "z-index": "-999999999"
        });
    }, 300);
}

// open end user history to doctor/patient for mobile view
function openHistory() {
    $("#paitentHistory").css("left", "0");
    $("#vitalSign").css("left", "0");
}

// close end user history for doctor/patient for mobile view
function closeHistory() {
    $("#paitentHistory").css("left", "-300px");
    $("#vitalSign").css("left", "-300px");
}

// open end user history to doctor/patient for mobile view
function openRx() {
    $("#prescription").css("left", "0");
    $("#doctorHistoryPrescription").css("left", "0");
}

// close end user history for doctor/patient for mobile view
function closeRx() {
    $("#prescription").css("left", "-300px");
    $("#doctorHistoryPrescription").css("left", "-300px");
}

// disable previous date selection in follow up data field
$(document).ready(function () {
    var dtToday = new Date();
    var month = dtToday.getMonth() + 1;
    var day1 = dtToday.getDate();
    var day = day1 + 1;
    var year = dtToday.getFullYear();
    if (month < 10)
        month = '0' + month.toString();
    if (day < 10)
        day = '0' + day.toString();
    var maxDate = year + '-' + month + '-' + day;
    $('#followUpDate').attr('min', maxDate);
})

// function backbtn() {
//     leaveChat();
// }

// disable/enable console output for RTCMultiConnection() console.log()
connection.enableLogs = false;
