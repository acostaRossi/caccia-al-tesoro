
function bindEvents() {

    let flowerbeds = document.getElementsByClassName("flowerbed");

    for(let flowerbed of flowerbeds) {
        flowerbed.addEventListener("click", () => {

            document.getElementById("flowerbed-selected").value = flowerbed.getAttribute("number");

            let form = document.getElementById("main-form");

            form.submit();
        });
    }
}

function youWin() {

    setTimeout(() => {
        window.alert("You WIN !!!");

        document.getElementById("you-win-gif").style.display = "block";

        setTimeout(() => {
            document.getElementById("you-win-gif").style.display = "none";
        }, 2000);

        showNewGameButton();
    }, 700);
}

function gameOver() {

    setTimeout(() => {
        window.alert("GAME OVER");

        document.getElementById("game-over-gif").style.display = "block";

        setTimeout(() => {
            document.getElementById("game-over-gif").style.display = "none";
        }, 2000);

        showNewGameButton();

    }, 700);
}

function showNewGameButton() {
    document.getElementById("new-game-form").style.display = "block";
}


