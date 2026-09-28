<!DOCTYPE html>
<html>
<body>
  <form id="my-form">
    <input type="text" id="myInputID">
    <button type="submit" onclick="submit()">Submit</button>
  </form>

  <script>
    var el = document.getElementById("myInputID");
    el.addEventListener("keypress", function(event) {
      if (event.key === "Enter") {
        location.href("search?search");
        event.preventDefault();
      }
    });

  </script>
</body>
</html>