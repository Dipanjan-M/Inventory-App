function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

setInterval(function() {
    if (confirm("Want to extend session ? ")) {
        $.get("extend_session.php", function(data, status) {
            alert(data);
        });
    }
}, 15 * 60 * 1000);