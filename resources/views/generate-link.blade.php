<!DOCTYPE html>
<html>
<head>
    <title>Generated Link</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center ">Generated Link</h1>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="{{ route('admin-panel.index') }}" class="btn btn-info ">Admin</a>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(Auth::check())
        <p class="fw-bold">Your unique link: <input type="text" value="{{ route('generate-link') . '/' . Auth::user()->uniqueLinks->first()->link }}" id="uniqueLinkInput" class="form-control" readonly></p>
        <button onclick="copyToClipboard()" class="btn btn-primary">Copy Link</button>
        <a href="{{ route('generate-link') }}" class="btn btn-success">Generate New Link</a>
        @if (Auth::user()->uniqueLinks->count() > 0)
            <a href="{{ route('deactivate-link', ['uniqueLink' => $uniqueLink]) }}" onclick="return confirmDeactivation()">
                <button class="btn btn-danger">Deactivate Link</button>
            </a>
        @endif

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">Feeling Lucky Game</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center h4 mt-0">Rolling number:</p>
                    <p id="rolledNumber" class="text-center display-1 mb-0"></p>
                    <p id="gameResult" class="text-center h4 mt-0"></p>
                    <p id="winAmount" class="text-center h4"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-center">
                    <button onclick="playGame()" class="btn btn-primary">I'm feeling lucky</button>
                </div>
            </div>
            @else
                <p>Please <a href="{{ route('process-registration') }}">login</a> or <a href="{{ route('process-registration') }}">register</a> to access your unique link.</p>
            @endif
            <div class="text-center">
                <h2>Game History</h2>
                <button onclick="showHistory()" class="btn btn-secondary">History</button>
            </div>
            <div class="col-md-12 d-flex justify-content-center">
                <ul id="historyList" class="list-group mt-3"></ul>
            </div>
        </div>


        <script>
            function copyToClipboard() {
                var copyText = document.getElementById("uniqueLinkInput");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                alert("Copied the link: " + copyText.value);
            }

            function animateNumber(target, duration, callback) {
                var start = 0;
                var startTime = performance.now();

                function step(timestamp) {
                    var progress = timestamp - startTime;
                    var percentage = Math.min(progress / duration, 1);
                    var currentValue = Math.floor(start + percentage * (target - start));
                    document.getElementById("rolledNumber").textContent = currentValue;

                    if (progress < duration) {
                        requestAnimationFrame(step);
                    } else {
                        if (callback) {
                            callback();
                        }
                    }
                }

                requestAnimationFrame(step);
            }

            function playGame() {

                document.getElementById("gameResult").textContent = "";
                document.getElementById("winAmount").textContent = "";
                document.getElementById("rolledNumber").textContent = "";

                var randomNumber = Math.floor(Math.random() * 1000) + 1;
                var gameResult = randomNumber % 2 === 0 ? "Win" : "Lose";
                var winAmount = 0;

                if (randomNumber > 900) {
                    winAmount = Math.floor(randomNumber * 0.7);
                } else if (randomNumber > 600) {
                    winAmount = Math.floor(randomNumber * 0.5);
                } else if (randomNumber > 300) {
                    winAmount = Math.floor(randomNumber * 0.3);
                } else {
                    winAmount = Math.floor(randomNumber * 0.1);
                }

                var gameCompleteCallback = function () {
                    if (gameResult === "Lose") {
                        document.getElementById("gameResult").textContent = "Result: " + gameResult;
                        document.getElementById("rolledNumber").textContent =  randomNumber;
                    } else {
                        document.getElementById("gameResult").textContent = "Result: " + gameResult;
                        document.getElementById("winAmount").textContent = "Win Amount: " + winAmount;
                        document.getElementById("rolledNumber").textContent =  randomNumber;
                    }

                    saveGameResult(gameResult, randomNumber,winAmount);
                };

                animateNumber(randomNumber, 2000, gameCompleteCallback);
            }


            function saveGameResult(result, rolledNumber, winAmount) {
                fetch('/save-game-result', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        result: result,
                        rolled_number: rolledNumber,
                        winAmount: winAmount
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                    })
                    .catch(error => {
                        console.error('Error saving game result:', error);
                    });
            }

            function showHistory() {
                var historyList = document.getElementById("historyList");
                historyList.innerHTML = "";

                fetch('/get-game-history')
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(result => {
                            var historyItem = document.createElement("li");
                            historyItem.classList.add("list-group-item");

                            if (result.result === "Lose") {
                                historyItem.textContent = result.result + " | Rolled Number: " + result.rolled_number;
                            } else {
                                historyItem.textContent = result.result + " | Win Amount: " + result.winAmount + " | Rolled Number: " + result.rolled_number;
                            }
                            historyList.appendChild(historyItem);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching game history:', error);
                    });
            }
            function confirmDeactivation() {
                return confirm("The link will be deactivated. Are you sure you want to proceed?");
            }
        </script>
</div>
</body>
</html>
