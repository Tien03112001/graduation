function myOption() {
    document.getElementById("sendOption").classList.toggle("showhd");
}

function myEmoji() {
    document.getElementById("emojiOption").classList.toggle("showln");
}

window.onclick = function (event) {
    if (!event.target.matches('.send-option-btn')) {
        var dropdowns = document.getElementsByClassName("send-option");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('showhd')) {
                openDropdown.classList.remove('showhd');
            }
        }
    }

    if (!event.target.matches('.emoji-option-btn')) {
        var dropdowns = document.getElementsByClassName("emoji-option");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('showln')) {
                openDropdown.classList.remove('showln');
            }
        }
    }
}
