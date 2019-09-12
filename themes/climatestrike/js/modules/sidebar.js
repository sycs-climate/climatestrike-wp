function toggleSidebar() {
    var sidebar = document.getElementById("sidebar");
    var sidebar_button = document.getElementById("sidebar-button");
    var pos = sidebar.style.top;

    if(pos == "0px") {
        sidebar.style.top = "-100%";
    } else {
        sidebar.style.top = "0px";
    }
}
