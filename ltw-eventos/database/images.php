<?
function getImageDesc($id){
  $stmt = Database::getInstance()->getConnection()->prepare('SELECT description FROM Image WHERE idImage = ?');
  $stmt->execute(array($id));
  $result = $stmt->fetchColumn();
  return $result;
}

function createImage($imgDesc){
  $stmt = Database::getInstance()->getConnection()->prepare('INSERT INTO Image VALUES(NULL, NULL, NULL, ?)');
  $stmt->execute(array($imgDesc));
  $idImg = Database::getInstance()->getConnection()->lastInsertId();

  $originalFileName = "images/originals/$idImg";
  $smallFileName = "images/icons/$idImg";

  move_uploaded_file($_FILES['image']['tmp_name'], $originalFileName);

  $imageType = exif_imagetype($originalFileName);
  imageCreateSwitch($imageType,$original,$originalFileName);

  $width = imagesx($original);
  $height = imagesy($original);
  $square = min($width, $height);

  // Create small square thumbnail
  $small = imagecreatetruecolor(200, 200);
  imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
  imageTypeSwitch($imageType,$small,$smallFileName);

  return $idImg;
}


function createAlbum($idAlbum,$idEvent,$albumDesc){

  if(count($_FILES['album']['name']) > 0){

    for($i = 0; $i < count($_FILES['album']['name']); $i++) {

      $imgDesc = $idAlbum . '-' . $i;

      $stmt = Database::getInstance()->getConnection()->prepare('INSERT INTO Image VALUES(NULL, ?, ?, ?)');
      $stmt->execute(array($idEvent,$idAlbum,$imgDesc));
      $idImg = Database::getInstance()->getConnection()->lastInsertId();

      $originalFileName = "images/originals/$idImg";
      $smallFileName = "images/icons/$idImg";

      move_uploaded_file($_FILES['album']['tmp_name'][$i], $originalFileName);

      $imageType = exif_imagetype($originalFileName);
      imageCreateSwitch($imageType,$original,$originalFileName);

      $width = imagesx($original);
      $height = imagesy($original);
      $square = min($width, $height);

      // Create small square thumbnail
      $small = imagecreatetruecolor(200, 200);
      imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
      imageTypeSwitch($imageType,$small,$smallFileName);
    }
  }
}



function createCoverImage($imgDesc){
  $stmt = Database::getInstance()->getConnection()->prepare('INSERT INTO Image VALUES(NULL, NULL, NULL, ?)');
  $stmt->execute(array($imgDesc));
  $idImg = Database::getInstance()->getConnection()->lastInsertId();

  $originalFileName = "images/originals/$idImg";
  $smallFileName = "images/icons/$idImg";
  $coverFileName = "images/covers/$idImg";

  move_uploaded_file($_FILES['image']['tmp_name'], $originalFileName);

  $imageType = exif_imagetype($originalFileName);
  imageCreateSwitch($imageType,$original,$originalFileName);

  $width = imagesx($original);
  $height = imagesy($original);
  $square = min($width, $height);

  // Create icon square thumbnail
  $small = imagecreatetruecolor(200, 200);
  imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);

  imageTypeSwitch($imageType,$small,$smallFileName);

  // Create cover
  $cover = imagecreatetruecolor(600, ($height>200)?200:$height);
  imagecopyresized($cover, $original, 0, 0, 0, 0, 600, ($height>200)?200:$height, $width, ($height>200)?200:$height);
  imagejpeg($cover, $coverFileName);

  imageTypeSwitch($imageType,$cover,$coverFileName);

  return $idImg;
}

function imageTypeSwitch($imageType,&$image,&$fileName){
  switch($imageType){
    case IMAGETYPE_GIF:
    imagegif($image, $fileName);
    break;
    case  IMAGETYPE_JPEG:
    imagejpeg($image, $fileName);
    break;
    case IMAGETYPE_PNG:
    imagepng($image, $fileName);
    break;
    case IMAGETYPE_BMP:
    imagebmp($image, $fileName);
    break;
  }
}

function imageCreateSwitch($imageType,&$original,&$originalFileName){
  switch($imageType){
    case IMAGETYPE_GIF:
    $original = imagecreatefromgif($originalFileName);
    break;
    case  IMAGETYPE_JPEG:
    $original = imagecreatefromjpeg($originalFileName);
    break;
    case IMAGETYPE_PNG:
    $original = imagecreatefrompng($originalFileName);
    break;
    case IMAGETYPE_BMP:
    $original = imagecreatefrombmp($originalFileName);
    break;
    default:
    $stmt = Database::getInstance()->getConnection()->prepare('DELETE FROM Image WHERE idImage=?');
    $stmt->execute(array($idImg));
      unlink($originalFileName);
      add_error_message('Image File Invalid');
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit();
      break;
  }
}
?>
