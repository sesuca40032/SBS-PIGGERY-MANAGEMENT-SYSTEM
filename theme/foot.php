
<p class="text-center" style="margin-top: 5%">
	Developed by <a href="https://www.facebook.com/profile.php?id=100084915082290">Jade Technology INC.</a>
</p>

<script>

var mySidebar = document.getElementById("mySidebar");

var overlayBg = document.getElementById("myOverlay");


function w3_open() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
        overlayBg.style.display = "none";
    } else {
        mySidebar.style.display = 'block';
        overlayBg.style.display = "block";
    }
}

// Close the sidebar with the close button
function w3_close() {
    mySidebar.style.display = "none";
    overlayBg.style.display = "none";
}
</script>
</body>
</html>