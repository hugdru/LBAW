  <script>
    disableButtons = function(){
      this.form.submit();
      this.disabled = true;
    }
    $("input[type='submit']").click(disableButtons);
  </script>
  <script src="lightbox/js/lightbox.js"></script>
 </body>
</html>
