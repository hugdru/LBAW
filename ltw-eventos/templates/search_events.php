<form action="search_events.php" method="get">
<input type="hidden" name="page" value="1">
  <div>Search By:<select name="mode">
    <option value="date" selected>Date</option>
    <option value="description">Description</option>
    <option value="name">Name</option>
    <option value="location">Location</option>
    <option value="type">Type</option>
  </select>
  <select name="dateOperator" style="display: none" disabled="true">
    <option value="<">Past events</option>
    <option value=">=">Upcoming events</option>
  </br>
  <script>
  showDateOperator = function(){
    if($("select[name='mode'] option:selected").text() != 'Date'){
      $("select[name='dateOperator'], textarea[name='searchText']").css('display', 'inline').removeAttr('disabled');
      $("input[name='searchDate']").css('display', 'none').attr('disabled', 'disabled');
    }
    else{
      $("select[name='dateOperator'], textarea[name='searchText']").css('display', 'none').attr('disabled', 'disabled');
      $("input[name='searchDate']").css('display', 'inline').removeAttr('disabled');
    }
  }
  showDateOperator();
  $("select[name='mode']").change(showDateOperator);
  </script>
  <textarea name="searchText" rows="5" cols="58" style="display:none" disabled="true"></textarea></div>
  <input type="date" name="searchDate" value="2015-01-01">
  <input type="submit" value="Search"/>
</form>
