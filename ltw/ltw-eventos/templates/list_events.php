<section class="event-list">
<h2 id="created" class="event-header">Search Result</h2>
<ul class="search_results">
  <?
  $userId = 0;
  if(isset($_SESSION['userId'])){
    $userId = $_SESSION['userId'];
  }
  $limit = 5;
  if($_GET['mode'] == 'date'){
    //uses limit + 1 to retrieve an extra result if there is to be a Next page
    $result = searchEventByDate($_GET['searchDate'], $userId, $limit, $_GET['page']);
    $hrefString = "&mode=date&searchDate=" . $_GET['searchDate'];
  }
  else{
    $result = searchEventBySearchMode($_GET['searchText'], $_GET['mode'], $_GET['dateOperator'], $userId, $limit, $_GET['page']);
    $hrefString = "&mode=" . $_GET['mode'] . "&dateOperator=" . $_GET['dateOperator'] . "&searchText=" . $_GET['searchText'];
  }
  for($i = 0; $i < min([$limit, count($result)]); $i++){
    ?>
    <li>
      <a href="view_event.php?id=<?=$result[$i]['idEvent']?>">
        <img name="<?=$result[$i]['name']?>" src="images/icons/<?=$result[$i]['idCover']?>">
        <h4><span><?=$result[$i]['name']?></span></h4>
      </a>
    </li>
    <?
  }
  ?>
</ul>
</section>

<nav>
  <ul class="available_pages"><?
  $pageIndex = $_GET['page'];
  //Show Previous index if not on first page
  if(count($result)){
    if($pageIndex > 1){?>
      <li><a href="search_events.php?page=<?=($pageIndex - 1) . $hrefString?>">&lt</a></li>
      <?}
      //Show Next index if not on last page
      if(count($result) == $limit + 1){?>
        <li><a href="search_events.php?page=<?=($pageIndex + 1) . $hrefString?>">&gt</a></li>
        <?}
      }
      else{
        //if not on page 1 and there are no results, go to page 1 and display No results error
        if($pageIndex != 1){
          add_error_message("No results for this search on page " . $pageIndex);
          header("Location: search_events.php?page=1" . $hrefString);
        }
        else{
          //if on page 1 and there are no results, clear search from GET and display No results error
          add_error_message("No results for this search");
          header("Location: search_events.php");
        }
        exit();
      }?>
    </ul>
  </nav>
