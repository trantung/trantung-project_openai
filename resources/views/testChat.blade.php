<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ptutor Chat</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700&amp;display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" />

    <!-- Mordernizer -->
    <link href="{{ URL::asset('css/chat.css') }}" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <div class="mobile-header d-lg-none">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="row g-0">
            <div class="col-xl-3 col-lg-3">
                <div class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="offcanvas">
                    <button type="button" class="btn-close btn-close-white" data-bs-toggle="offcanvas" aria-label="Close" data-bs-target="#offcanvas"></button>
                    <div class="lds-ring-4" style="display: none;">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <div id="sidebar-logo" class="sidebar-logo">
                        <button class="newChat" hx-swap="afterbegin">+ New chat</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-9">
                <div class="chat-wrapper">
                    <div class="chat-body form" id="chatMessages">

                    </div>
                    <div class="chat-bottom">
                        <div class="chat-input">
                            <textarea id="userInput" tabindex="0" rows="1" placeholder="Send a message" class="m-0 w-full resize-none border-0 bg-transparent py-[10px] pr-10 focus:ring-0 focus-visible:ring-0 dark:bg-transparent md:py-4 md:pr-12 gizmo:md:py-3.5 pl-3 md:pl-4" style="max-height: 200px; height: 56px;background-color: #fff !important;" oninput="autoExpand(this)"></textarea>
                            <button type="submit" id="send-btn">
                                <i class="fa-regular fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="preloader">
        <div id="loader"></div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.1/purify.js"></script>
<script src="{{ URL::asset('js/chat.js') }}"></script>
<script>
    $(window).on("load", function() {
        $("#loader").fadeOut(100, function() {
            $("#preloader").fadeOut(200);
            $("body").removeClass("loading");
        });
    });
</script>

</html>