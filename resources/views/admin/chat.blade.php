<!DOCTYPE html>
<html direction="rtl" dir="rtl" style="direction: rtl">

<head>
    <base href="">
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <meta name="description"
          content="مترونیک admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services و widgets." />
    <meta name="keywords"
          content="مترونیک, bootstrap, bootstrap 5, Angular 11, VueJs, React, Laravel, admin themes, web design, figma, web development, ree admin themes, bootstrap admin, bootstrap dashboard" />
    <link rel="canonical" href="Https://preview.keenthemes.com/metronic8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ url('assets/media/logos/favicon.ico') }}" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

    <link href="{{ url('assets/plugins/global/plugins.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/css/style.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/plugins/floatlabel/bootstrap-float-label.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/plugins/lobibox-master/dist/css/lobibox.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .userTable tbody tr td:nth-last-child(n+5) {
            padding-top: 18px;
        }
    </style>
    @yield('css')
</head>
{{--<iframe src="/chatSection" frameborder="0" style="width: 100%">--}}
{{--    --}}
{{--</iframe>--}}
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
<body id="kt_body"
      class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed"
      style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js"
        integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/wavesurfer.js"></script>

<script v-if="showMe" type="module">
    import { io } from "https://cdn.socket.io/4.3.2/socket.io.esm.min.js";
    var header_token = document.cookie.split(";")[0].split("=")[1];
    var convert_date = t => new Date(t).toLocaleDateString("fa-IR",{timeZone:"Asia/Tehran",hour:"2-digit",minute:"2-digit"})
    axios.post("https://chat.applygermany.net/auth",{},{headers:{"authorization":header_token}}).then(function(re){
        console.log(re,'re is here guys')
        var token = re.data.token;
        var sign = re.data.sign;
        var options = {
            transports:["websocket","polling"],path:"/socket",
            withCredentials:true,auth:{sign:sign,token:token}
        }
        const socket = io("https://chat.applygermany.net",options);
        window.socket = socket;
        document.addEventListener("dblclick",(e)=>{
            try {
                if (e.target.previousSibling.id.split("_")[1] == "message-id"){
                    $("#kt_drawer_chat_messenger_body").children().remove()
                    socket.emit("5w8oFWiTadbYDGTvFJZc",room_id,myid,e.target.previousSibling.id.split("_")[0]);
                }
            } catch (err){
                console.log(err,'error is here guys')
            }
        });
        function return_notif(title,body,timestamp){
            return '<div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">'+
                '<div class="scroll-y mh-325px my-5 px-8">'+
                '<div class="d-flex flex-stack py-4">'+
                '<div class="d-flex align-items-center">'+
                '<div class="mb-0 me-2">'+
                '<a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bolder">'+title+'</a>'+
                '<div class="text-gray-400 fs-7">'+body+'</div>'+
                '</div>'+
                '</div>'+
                '<span class="badge badge-light fs-8">'+convert_date(timestamp)+'</span>'+
                '</div>'+
                '</div>'+
                '</div>';
        }
        function create_voice(me,data,read,timestamp,id,data1,image = 0){
            read ? read = '<i style="color:blue" class="bi bi-check-all"></i>' : read = '<i class="bi bi-check"></i>';
            me == window.myid || me == "admin" ? me = "rtl" : me = "ltr";
            image != 0 ? '<div class="symbol symbol-35px symbol-circle"><img alt="Pic" id="messagePicUser" src="{{ url("uploads/avatar/'+image+'") }}"></div>' : image = '';
            duration_voice(data1).then((duration)=>{
                $("#kt_drawer_chat_messenger_body").append('<div class="d-flex justify-content-start mb-10" style="direction:'+me+'">'+
                    '<div id="'+id+'_message-id" class="d-flex flex-column align-items-start"'+
                    '<div class="d-flex align-items-center mb-2">'+
                    image+
                    '<div class="ms-3">'+
                    // <!--<a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1" id="messageUser"></a>-->
                    '<span id="messageTimeUser" class="text-muted fs-7 mb-1">'+convert_date(timestamp)+'</span>'+
                    '</div>'+
                    '</div>'+
                    '<div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start"data-kt-element="message-text">'+duration+'  <div id="current_time'+data+'">'+"0".toHHMMSS()+'</div>'+read+'<i id='+data+' class="bi bi-play"></i>'+
                    '</div>'+
                    '</div>');
                $("#"+data).click(()=>{
                    playaudio(data1,data);
                });
            }).catch((err)=>{""});
        }
        function create_image(me,data,read,timestamp,id,image = 0){
            var img = new Image();
            read ? read = '<i style="color:blue" class="bi bi-check-all"></i>' : read = '<i class="bi bi-check"></i>';
            me == window.myid || me == "admin" ? me = "rtl" : me = "ltr";
            image != 0 ? '<div class="symbol symbol-35px symbol-circle"><img alt="Pic" id="messagePicUser" src="{{ url("uploads/avatar/'+image+'") }}"></div>' : image = '';
            img.onload = function(){
                $("#kt_drawer_chat_messenger_body").append('<div class="d-flex justify-content-start mb-10" style="direction:'+me+'">'+
                    '<div id="'+id+'_message-id" class="d-flex flex-column align-items-start"'+
                    '<div class="d-flex align-items-center mb-2">'+
                    image+
                    '<div class="ms-3">'+
                    // <!--<a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1" id="messageUser"></a>-->
                    '<span id="messageTimeUser" class="text-muted fs-7 mb-1">'+convert_date(timestamp)+'</span>'+
                    '</div>'+
                    '</div>'+
                    '<div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start"data-kt-element="message-text" id="'+id+'"></div>'+read+
                    '</div>'+
                    '</div>');
                $("#"+id).append(img);
            }
            img.src = data;
        }
        function create_text(me,text,read,timestamp,id,image = 0){
            read ? read = '<i style="color:blue" class="bi bi-check-all"></i>' : read = '<i class="bi bi-check"></i>';
            me == window.myid || me == "admin" ? me = "rtl" : me = "ltr";
            image != 0 ? '<div class="symbol symbol-35px symbol-circle"><img alt="Pic" id="messagePicUser" src="{{ url("uploads/avatar/'+image+'") }}"></div>' : image = '';
            return '<div class="d-flex justify-content-start mb-10" style="direction:'+me+'">'+
                '<div id="'+id+'_message-id" class="d-flex flex-column align-items-start"'+
                '<div class="d-flex align-items-center mb-2">'+
                image+
                '<div class="ms-3">'+
                // <!--<a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1" id="messageUser"></a>-->
                '<span id="messageTimeUser" class="text-muted fs-7 mb-1">'+convert_date(timestamp)+'</span>'+
                '</div>'+
                '</div>'+
                '<div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start"data-kt-element="message-text">'+text+'</div>'+read+
                '</div>'+
                '</div>';
        }
        window.onbeforeunload = function(e) {
            socket.disconnect();
        };
        socket.on("last_notification",(data)=>{
            data.forEach((notif)=>{
                $(".tab-content").eq(0).append(return_notif(notif.title,notif.body,notif.timestamp));
            });
        });
        socket.on("user_chat_id",(data)=>{
            window.myid = data;
        });
        socket.on("new_room_"+window.myid,(data,name,lastname,[],active,user_id)=>{
            active = active == 0 ? "<div style='float:left'>غیر فعال</div>":"<i style='color:red;float:left;cursor:pointer;font-size:18px' id='delete_"+data+"'class='bi bi-trash'></i> ";
            active += '<i style="color:red;float:left;cursor:pointer;font-size:20px" id="delete_all_'+data+'" class="bi bi-x"></i>';
            // var img_user = 'https://api.agdevelop.ir/image/user/'+user_id+'/1650720666';
            // <img src='"+img_user+"' style='height:50px;width:50px;border-radius:50%'>
            $("#rooms ul").append("<li class='list-group-item'><a href='#' style='color:black' id='room_"+data+"'>  5"+name+"  "+lastname+"  </div> </a> "+active+"</li>")
            $("#delete_"+data).click(()=>{
                socket.emit("sSIMqKlNOe9fX52ZxgPV",data,myid);
            });
            $("#delete_all_"+data).click(()=>{
                socket.emit("SXX16Q6G4hwWSU4tF3uJh52s0",data,myid);
            });
            $("#room_"+data).click(()=>{change(data);})
        });
        socket.on("new_notification",(notif)=>{
            $(".tab-content").eq(0).append(return_notif(notif.title,notif.body,notif.timestamp));
        });
        socket.on("chat_room_id",(data)=>{
            data.forEach((value)=>{
                value.active = value.active == 0 ? "<div style='float:left'>غیر فعال</div>":"<i style='color:red;float:left;cursor:pointer;font-size:18px' id='delete_"+value.id+"'class='bi bi-trash'></i> ";
                value.active += '<i style="color:red;float:left;cursor:pointer;font-size:20px" id="delete_all_'+value.id+'" class="bi bi-x"></i>';
                // var img_user = 'https://api.agdevelop.ir/image/user/'+user_id+'/1650720666';
                // <img src='"+img_user+"' style='height:50px;width:50px;border-radius:50%'>
                $("#rooms ul").append("<li class='list-group-item'><a href='#' style='color:black' id='room_"+value.id+"'>  "+value.firstname+"  "+value.lastname+" </div> </a> "+value.active+"</li>")
                $("#delete_"+value.id).click(()=>{
                    socket.emit("sSIMqKlNOe9fX52ZxgPV",value.id,myid);
                });
                $("#delete_all_"+value.id).click(()=>{
                    socket.emit("SXX16Q6G4hwWSU4tF3uJh52s0",value.id,myid);
                });
                $("#room_"+value.id).click(()=>{change(value.id);})
            });
        });
        socket.on("admin_event",(msg,data)=>{
            if (data == 0){
                Lobibox.notify('error', {
                    title: " عملیات نا موفق ",
                    msg: msg == "deactive_room" ? "روم غیرفعال نشد" : "پیام حذف نشد",
                    icon: 'fa fa-warning',
                    position: 'bottom left',
                    sound: false,
                    mouse_over: "pause"
                });
            } else {
                Lobibox.notify('success', {
                    title: " عملیات موفق ",
                    msg:  msg == "deactive_room" ? "روم غیرفعال شد" : "پیام حذف شد",
                    icon: 'fa fa-check',
                    position: 'bottom left',
                    sound: false,
                    mouse_over: "pause"
                });
            }
            if (msg == "deactive_room"){
                $("#delete_"+data[0]).remove();
                $("#room_"+data[0]).append("<div style='float:left'>غیر فعال</div>");
            }
        });
        socket.on("message_buffer",(data,format,argument)=>{
            var result = argument[0];
            if (format == "audio"){
                data = String(data).split(",")[1];
                create_voice(result.from,result.msg,result.read,result.timestamp,result.id,data);
            } else if (format == "image"){
                create_image(result.from,data,result.read,result.timestamp,result.id,result.msg);
            } else if(format == "pdf"){
                result.msg = "<a download='pdf' href='"+data+"' title='PDF'>PDF FILE</a>";
                $("#kt_drawer_chat_messenger_body").append(create_text(result.from,result.msg,result.read,result.timestamp,result.id));
            }
        });
        socket.on("event_message",(success,error)=>{
            if (!success){
                console.log(error)
            }
        });
        socket.on("new_rooms",(data)=>{
            data.forEach((value)=>{
                // var img_user = 'https://api.agdevelop.ir/image/user/no/1';
                // <img src='"+img_user+"' style='height:50px;width:50px;border-radius:50%'>
                value.active = value.active == 0 ? "<div style='float:left'>غیر فعال</div>":"<i style='color:red;float:left;cursor:pointer;font-size:18px' id='delete_"+value.id+"'class='bi bi-trash'></i> ";
                value.active += '<i style="color:red;float:left;cursor:pointer;font-size:20px" id="delete_all_'+value.id+'" class="bi bi-x"></i>';
                $("#rooms ul").append("<li class='list-group-item'><a href='#' style='color:black' id='room_"+value.id+"'>  "+value.firstname+"  "+value.lastname+" - "+value.by[0]+" </div> </a> "+value.active+"</li>")
                $("#delete_"+value.id).click(()=>{
                    socket.emit("sSIMqKlNOe9fX52ZxgPV",value.id,myid);
                });
                $("#delete_all_"+value.id).click(()=>{
                    socket.emit("SXX16Q6G4hwWSU4tF3uJh52s0",value.id,myid);
                });
                $("#room_"+value.id).click(()=>{change(value.id);})
            });
        });
        socket.on("send_private_message",(data)=>{
            data.forEach((result)=>{
                if (result["from"] == myid){ result["from"] = "me"}
                if (result.type == "text"){
                    $("#kt_drawer_chat_messenger_body").append(create_text(result["from"],result["msg"],result["read"],result["timestamp"],result.id));
                }
                if (result.type == "audio" || result.type == "file"){
                    var messages_id = result["msg"];
                    socket.emit("message_buffer",result["msg"],result.room_id,myid,[result]);
                }
            });
        });
        socket.on("new_message",(data)=>{
            console.log(data);
        });
        socket.on("last_message",(data)=>{
            data.forEach((result)=>{
                if (result.type == "text"){
                    $("#kt_drawer_chat_messenger_body").append(create_text(result["from"],result["msg"],result["read"],result["timestamp"],result.id));
                }
                else if (result.type == "audio" || result.type == "file"){
                    var messages_id = result["msg"];
                    socket.emit("message_buffer",result["msg"],result.room_id,myid,[result]);
                }
            });
        });
        document.getElementById("backspace").addEventListener("click",()=>{
            window.room_id = "";
            document.getElementById("record").removeEventListener("click",voice_func);
            document.getElementById("send_text_message").removeEventListener("click",handler_send_message);
            $("#rooms,#kt_drawer_chat_close").show();
            $("#kt_drawer_chat_messenger_body").children().remove();
            $("#text_message").val("");
            $("#kt_drawer_chat_messenger_body,#kt_drawer_chat_messenger_footer,#backspace").hide();
        });
        var handler_send_message = function(){
            var msg = document.getElementById("text_message").value;
            socket.emit("private_message",window.room_id,window.myid,msg,"text");
            $("#text_message").val("");
        }
        var voice_func = function(){
            if (window.is_recording == 0){
                var audio = {audio:1,video:0};
                navigator.mediaDevices.getUserMedia(audio).then((stream)=>{
                    window.stream = stream;
                    window.record1 = new MediaRecorder(window.stream);
                    window.record1.start();
                    window.is_recording = 1;
                    $("#icon-record").css("color","red");
                    var data = [];
                    window.record1.onstop = ()=>{
                        var blob = new Blob(data,{"type":"audio/ogg; codec=opus"});
                        var reader = new FileReader();
                        reader.readAsDataURL(blob);
                        reader.onloadend = ()=>{
                            var base64_encoded = reader.result;
                            socket.emit("private_message",window.room_id,window.myid,base64_encoded,"audio");
                        }
                    }
                    window.record1.ondataavailable = (event)=>{
                        data.push(event.data);
                    }
                });
            } else if (window.is_recording){
                window.is_recording = 0;
                window.record1.stop();
                window.stream.getTracks().forEach(function(track) {
                    track.stop();
                });
                $("#icon-record").css("color","#A1A5B7");
            }
        }
        function change(id){
            window.room_id = id;
            $("#rooms,#kt_drawer_chat_close").hide();
            $("#kt_drawer_chat_messenger_body,#kt_drawer_chat_messenger_footer,#backspace").show();
            socket.emit("last_message",id,window.myid);
            document.getElementById("send_text_message").addEventListener("click",handler_send_message);
            window.is_recording = 0;
            document.getElementById("record").addEventListener("click",voice_func);
        }
    });
    function b64toBlob(dataURI) {
        var byteString = atob(dataURI);
        var ab = new ArrayBuffer(byteString.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], { type: 'audio/ogg' });
    }
    document.getElementById("file_upload").onchange = function(e){
        var reader = new FileReader();
        reader.readAsDataURL(this.files[0]);
        reader.onload = () => {
            var file = reader.result;
            socket.emit("private_message",window.room_id,window.myid,file,"file");
        }
    }
    String.prototype.toHHMMSS = function () {
        var sec_num = parseInt(this, 10);
        var hours   = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        return hours+':'+minutes+':'+seconds;
    }
    function duration_voice(blob){
        return new Promise((resolve,reject)=>{
            try {
                blob = b64toBlob(String(blob));
                var audioCtx = new (AudioContext || webkitAudioContext)();
                var sources = audioCtx.createBufferSource();
                const fileReader = new FileReader();
                fileReader.onloadend = () => {
                    const arrayBuffer = fileReader.result;
                    audioCtx.decodeAudioData(arrayBuffer, (audioBuffer) => {
                        sources.buffer = audioBuffer;
                        resolve(String(sources.buffer.duration).toHHMMSS());
                    });
                }
                fileReader.readAsArrayBuffer(blob);
            } catch (err){
                reject(err);
            }
        });
    }
    function playaudio(blob,id){
        blob = b64toBlob(blob);
        window.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        window.sources = window.audioCtx.createBufferSource();
        const fileReader = new FileReader();
        fileReader.onloadend = () => {
            const arrayBuffer = fileReader.result;
            window.audioCtx.decodeAudioData(arrayBuffer, (audioBuffer) => {
                window.sources.buffer = audioBuffer;
                window.sources.connect(window.audioCtx.destination);
                window.sources.addEventListener("ended",function(){
                    clearInterval(set_time);
                    $("#current_time"+id).html("00:00:00");
                    audioCtx.close();
                })
                window.sources.start();
                var set_time = setInterval(()=>{
                    $("#current_time"+id).html(String(audioCtx.currentTime).toHHMMSS());
                });
            });
        }
        fileReader.readAsArrayBuffer(blob);
    }
</script>
{{--    <iframe src="/chatSection" style="width:100%" frameborder="0">--}}
{{--        --}}
{{--    </iframe>--}}
<div class="d-flex flex-column flex-root">
    <div class="page d-flex flex-row flex-column-fluid">
        @include('admin.aside')
        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
            {{--                @include('admin.header')--}}
            <div id="mainPart">
                <div id="kt_header" style="" class="header align-items-stretch">
                    <div class="container-fluid d-flex align-items-stretch justify-content-between">
                        <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
                            <div class="btn btn-icon btn-active-color-white" id="kt_aside_mobile_toggle">
                                <i class="bi bi-list fs-1"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                            <a href="index.html" class="d-lg-none">
                                <img alt="Logo" src="{{ url('assets/media/logos/logo-default.jpeg') }}" class="h-15px" />
                            </a>
                        </div>
                        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1 ">
                            <div class="d-flex align-items-stretch" id="kt_header_nav">
                            </div>
                            <div class="d-flex align-items-stretch flex-shrink-0">
                                <div class="topbar d-flex align-items-stretch flex-shrink-0">
                                    <div class="d-flex align-items-stretch @if (!isset(auth()->user()->admin_permissions->chat)) d-none @endif">
                                        <div class="topbar-item position-relative px-3 px-lg-5" id="kt_drawer_chat_toggle" @click="showMe=!showMe">
                                            <i  class="bi bi-chat-left-text fs-3"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-stretch">
                                        <div class="topbar-item position-relative px-3 px-lg-5" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom">
                                            <i class="bi bi-app-indicator fs-3"></i>
                                        </div>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true">
                                            <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('{{ url('assets/media/misc/pattern-1.jpg') }}')">
                                                <h3 class="text-white fw-bold px-9 mt-10 mb-6">اعلان ها
                                                    <!--<span class="fs-8 opacity-75 ps-3">24 گزارش</span>-->
                                                </h3>
                                                <!--<ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-bold px-9">-->
                                                <!--    <li class="nav-item">-->
                                                <!--        <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#kt_topbar_notifications_1">هشدارها</a>-->
                                                <!--    </li>-->
                                                <!--    <li class="nav-item">-->
                                                <!--        <a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_3">گزارش</a>-->
                                                <!--    </li>-->
                                                <!--</ul>-->
                                            </div>
                                            <div class="tab-content">
                                                <!--<div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">-->
                                                <!--    <div class="scroll-y mh-325px my-5 px-8">-->
                                                <!--        <div class="d-flex flex-stack py-4">-->
                                                <!--            <div class="d-flex align-items-center">-->
                                                <!--                <div class="symbol symbol-35px me-4">-->
                                                <!--                    <span class="symbol-label bg-light-primary">-->
                                                <!--                        <span class="svg-icon svg-icon-2 svg-icon-primary">-->
                                                <!--                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">-->
                                                <!--                                <path d="M11.2600599,5.81393408 L2,16 L22,16 L12.7399401,5.81393408 C12.3684331,5.40527646 11.7359848,5.37515988 11.3273272,5.7466668 C11.3038503,5.7680094 11.2814025,5.79045722 11.2600599,5.81393408 Z" fill="#000000" opacity="0.3" />-->
                                                <!--                                <path d="M12.0056789,15.7116802 L20.2805786,6.85290308 C20.6575758,6.44930487 21.2903735,6.42774054 21.6939717,6.8047378 C21.8964274,6.9938498 22.0113578,7.25847607 22.0113578,7.535517 L22.0113578,20 L16.0113578,20 L2,20 L2,7.535517 C2,7.25847607 2.11493033,6.9938498 2.31738608,6.8047378 C2.72098429,6.42774054 3.35378194,6.44930487 3.7307792,6.85290308 L12.0056789,15.7116802 Z" fill="#000000" />-->
                                                <!--                            </svg>-->
                                                <!--                        </span>-->
                                                <!--                    </span>-->
                                                <!--                </div>-->
                                                <!--                <div class="mb-0 me-2">-->
                                                <!--                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bolder">پروژه آلیس</a>-->
                                                <!--                    <div class="text-gray-400 fs-7">توسعه فاز 1</div>-->
                                                <!--                </div>-->
                                                <!--            </div>-->
                                                <!--            <span class="badge badge-light fs-8">1 ساعت</span>-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--<div class="tab-pane fade" id="kt_topbar_notifications_3" role="tabpanel">-->
                                                <!--    <div class="scroll-y mh-325px my-5 px-8">-->
                                                <!--        <div class="d-flex flex-stack py-4">-->
                                                <!--            <div class="d-flex align-items-center me-2">-->
                                                <!--                <span class="w-70px badge badge-light-success me-4">200</span>-->
                                                <!--                <a href="#" class="text-gray-800 text-hover-primary fw-bold">سفارش جدید</a>-->
                                                <!--            </div>-->
                                                <!--            <span class="badge badge-light fs-8">فقط</span>-->
                                                <!--        </div>-->
                                                <!--        <div class="d-flex flex-stack py-4">-->
                                                <!--            <div class="d-flex align-items-center me-2">-->
                                                <!--                <span class="w-70px badge badge-light-danger me-4">500</span>-->
                                                <!--                <a href="#" class="text-gray-800 text-hover-primary fw-bold">مشتری جدید</a>-->
                                                <!--            </div>-->
                                                <!--            <span class="badge badge-light fs-8">2 ساعت</span>-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--    <div class="py-3 text-center border-top">-->
                                                <!--        <a href="pages/profile/activity.html" class="btn btn-color-gray-600 btn-active-color-primary">نمایش همه-->
                                                <!--            <span class="svg-icon svg-icon-5">-->
                                                <!--                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">-->
                                                <!--                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">-->
                                                <!--                            <polygon points="0 0 24 0 24 24 0 24" />-->
                                                <!--                            <rect fill="#000000" opacity="0.5" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)" x="7.5" y="7.5" width="2" height="9" rx="1" />-->
                                                <!--                            <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />-->
                                                <!--                        </g>-->
                                                <!--                    </svg>-->
                                                <!--                </span>-->
                                                <!--           </a>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-stretch" id="kt_header_user_menu_toggle">
                                        <div class="topbar-item cursor-pointer symbol px-3 px-lg-5 me-n3 me-lg-n5 symbol-30px symbol-md-35px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom">
                                            <img src="{{ route("imageUser", ["id" =>  auth()->user()->id, "ua" => time()])  }}" alt="metronic" />
                                        </div>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <div class="menu-content d-flex align-items-center px-3">
                                                    <div class="symbol symbol-50px me-5">
                                                        <img alt="Logo" src="{{ route("imageUser", ["id" =>  auth()->user()->id, "ua" => time()]) }}" />
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <div class="fw-bolder d-flex align-items-center fs-5">{{auth()->user()->firstname. " " . auth()->user()->lastname}}
                                                            <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">Admin</span></div>
                                                        <a href="#" class="fw-bold text-muted text-hover-primary fs-7">{{auth()->user()->email}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="separator my-2"></div>
                                            <div class="menu-item px-5">
                                                <a href="account/overview.html" class="menu-link px-5">پروفایل من</a>
                                            </div>
                                            <div class="menu-item px-5">
                                                <a href="pages/projects/list.html" class="menu-link px-5">
                                                    <span class="menu-text">پروژه ها من</span>
                                                    <span class="menu-badge">
                                        <span class="badge badge-light-danger badge-circle fw-bolder fs-7">3</span>
                                    </span>
                                                </a>
                                            </div>
                                            <div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start" data-kt-menu-flip="bottom">
                                                <a href="#" class="menu-link px-5">
                                                    <span class="menu-title">اشتراک من</span>
                                                    <span class="menu-arrow"></span>
                                                </a>
                                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                    <div class="menu-item px-3">
                                                        <a href="account/referrals.html" class="menu-link px-5">مراجعات</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="account/billing.html" class="menu-link px-5">صورتحساب</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="account/statements.html" class="menu-link px-5">درگاه ها</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="account/statements.html" class="menu-link d-flex flex-stack px-5">بیانه ها
                                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="اظهارات خود را نمایش دهید"></i>
                                                        </a>
                                                    </div>
                                                    <div class="separator my-2"></div>
                                                    <div class="menu-item px-3">
                                                        <div class="menu-content px-3">
                                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                                <input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
                                                                <span class="form-check-label text-muted fs-7">اعلان ها</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="menu-item px-5">
                                                <a href="account/statements.html" class="menu-link px-5">من بیانه ها</a>
                                            </div>
                                            <div class="separator my-2"></div>
                                            <div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start" data-kt-menu-flip="bottom">
                                                <a href="#" class="menu-link px-5">
                                    <span class="menu-title position-relative">زبان
                                    <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">انگلیسی
                                    <img class="w-15px h-15px rounded-1 ms-2" src="{{ url('assets/media/flags/united-states.svg') }}" alt="metronic" /></span></span>
                                                </a>
                                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                    <div class="menu-item px-3">
                                                        <a href="account/settings.html" class="menu-link d-flex px-5 active">
                                            <span class="symbol symbol-20px me-4">
                                                <img class="rounded-1" src="{{ url('assets/media/flags/united-states.svg') }}" alt="metronic" />
                                            </span>انگلیسی
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="menu-item px-5 my-1">
                                                <a href="account/settings.html" class="menu-link px-5">اکانت تنظیمات</a>
                                            </div>
                                            <div class="menu-item px-5">
                                                <a href="{{route('admin.logout')}}" class="menu-link px-5">خروج</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-stretch d-lg-none px-3 me-n3" title="Show header menu">
                                        <div class="topbar-item" id="kt_header_menu_mobile_toggle">
                                            <i class="bi bi-text-left fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @yield('content')
                @include('admin.footer')
            </div>
        </div>
    </div>
</div>

<div v-if="showMe" id="kt_drawer_chat" class="bg-white @if (!isset(auth()->user()->admin_permissions->chat)) d-none @endif" data-kt-drawer="true"
     data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
     data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
     data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close"
     style="width: 500px !important;">
    <div class="card w-100" id="kt_drawer_chat_messenger">
        <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
            <div class="card-title">
                <div class="d-flex justify-content-center flex-column me-3">
                    <a href="#" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 mb-2 lh-1">پیام
                        ها</a>
                    <!--<div class="mb-0 lh-1">-->
                    <!--    <span class="badge badge-success badge-circle w-10px h-10px me-1"></span>-->
                    <!--    <span class="fs-7 fw-bold text-gray-400">فعال</span>-->
                    <!--</div>-->
                </div>
            </div>
            <div class="card-toolbar">
                <div class="me-2">
                    <!--<button class="btn btn-sm btn-icon btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">-->
                    <!--    <i class="bi bi-three-dots fs-3"></i>-->
                    <!--</button>-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3"
                         data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">مخاطبین</div>
                        </div>
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                               data-bs-target="#kt_modal_users_search">افزودن مخاطب</a>
                        </div>
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link flex-stack px-3" data-bs-toggle="modal"
                               data-bs-target="#kt_modal_invite_friends">دعوت از مخاطبین
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                   title=""
                                   data-bs-original-title="برای ارسال دعوت نامه یک ایمیل تماس مشخص کنید"
                                   aria-label="برای ارسال دعوت نامه یک ایمیل تماس مشخص کنید"></i></a>
                        </div>
                        <div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start"
                             data-kt-menu-flip="left, center, top">
                            <a href="#" class="menu-link px-3">
                                <span class="menu-title">گروه ها</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-bs-toggle="tooltip"
                                       title="" data-bs-original-title="بزودی">ساختن گروه</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-bs-toggle="tooltip"
                                       title="" data-bs-original-title="بزودی">دعوت کاربران</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-bs-toggle="tooltip"
                                       title="" data-bs-original-title="بزودی">تنظیمات</a>
                                </div>
                            </div>
                        </div>
                        <div class="menu-item px-3 my-1">
                            <a href="#" class="menu-link px-3" data-bs-toggle="tooltip" title=""
                               data-bs-original-title="بزودی">تنظیمات</a>
                        </div>
                    </div>
                </div>
                <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_chat_close">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                   fill="#000000">
                                    <rect fill="#000000" x="0" y="7" width="16"
                                          height="2" rx="1"></rect>
                                    <rect fill="#000000" opacity="0.5"
                                          transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                          x="0" y="7" width="16" height="2" rx="1">
                                    </rect>
                                </g>
                            </svg>
                        </span>
                </div>
                <div class="btn btn-sm btn-icon btn-active-light-primary" id="backspace" style="display:none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         class="bi bi-arrow-return-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                              d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="card-body" id="rooms">
            <div class="card" style="width: 35rem;">
                <ul class="list-group list-group-flush">
                </ul>
            </div>
        </div>
        <div class="card-body" id="kt_drawer_chat_messenger_body" style="display:none;">
            <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true"
                 data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                 data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
                 data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px"
                 style="height: 493px;">
            </div>
        </div>
        <div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer" style="display:block;">
                <textarea id="text_message" class="form-control form-control-flush mb-3" rows="1" data-kt-element="input"
                          placeholder="نوشتن پیام"></textarea>
            <div class="d-flex flex-stack">
                <div class="d-flex align-items-center me-2">
                    <button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button"
                            id="record" data-bs-toggle="tooltip" title="">
                        <i class="bi bi-mic fs-3" id="icon-record"></i>
                    </button>
                    <input type="file" accept='application/pdf, image/*' id="file_upload"
                           style="display:none" />
                    <button class="btn btn-sm btn-icon btn-active-light-primary me-1"
                            onclick="document.getElementById('file_upload').click();" type="button"
                            data-bs-toggle="tooltip" title="">
                        <i class="bi bi-upload fs-3"></i>
                    </button>
                </div>
                <button class="btn btn-primary" id="send_text_message" type="button"
                        data-kt-element="send">ارسال</button>
            </div>
        </div>
    </div>
</div>

<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                 height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24" />
                    <rect fill="#000000" opacity="0.5" x="11" y="10" width="2"
                          height="10" rx="1" />
                    <path
                            d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
                            fill="#000000" fill-rule="nonzero" />
                </g>
            </svg>
        </span>
</div>
<script>
    new Vue({
        el: "#mainPart",
        data: {
            showMe:false,
        },

    });


</script>
<script src="{{ url('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ url('assets/js/scripts.bundle.js') }}"></script>
<script src="{{ url('assets/plugins/floatlabel/bootstrap-float-label.js') }}"></script>
<script src="{{ url('assets/plugins/lobibox-master/dist/js/lobibox.min.js') }}"></script>
<script src="{{ url('assets/plugins/lobibox-master/dist/js/messageboxes.min.js') }}"></script>
<script src="{{ url('assets/plugins/lobibox-master/dist/js/notifications.min.js') }}"></script>

<script>
    @if (session()->get('error'))
    $(document).ready(function() {
        Lobibox.notify('error', {
            title: " عملیات نا موفق : ",
            msg: "{{ session()->get('error') }}",
            icon: 'fa fa-warning',
            position: 'bottom left',
            sound: false,
            mouse_over: "pause"
        });
    });
    @endif
    @if (session()->get('success'))
    $(document).ready(function() {
        Lobibox.notify('success', {
            title: " عملیات موفق : ",
            msg: "{{ session()->get('success') }}",
            icon: 'fa fa-check',
            position: 'bottom left',
            sound: false,
            mouse_over: "pause"
        });
    });
    @endif
    $.bootstrapFloatLabel();
</script>
@yield('script')
</body>

</html>
