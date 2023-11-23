<?php
require_once 'src/Models/CarsModel.php';
require_once 'src/Models/MakeModel.php';
$allFieldsFilled = true;
$input_id = $_POST['edit_id'];

if (!isset($_POST['model']) || empty($_POST['model'])) {
    $allFieldsFilled = false;
}

if (!isset($_POST['make']) || empty($_POST['make'])) {
    $allFieldsFilled = false;
}

if (!isset($_POST['bodytype']) || empty($_POST['bodytype'])) {
    $allFieldsFilled = false;
}

if (!isset($_POST['year']) || empty($_POST['year'])) {
    $allFieldsFilled = false;
}

if (!isset($_POST['image_link']) || empty($_POST['image_link'])) {
    $allFieldsFilled = false;
}

if (!$allFieldsFilled) {
    header("Location: edit.php?edit_id={$input_id}&error=fields not filled");
    exit();
}

$model = $_POST['model'];
$make = $_POST['make'];
$bodytype = $_POST['bodytype'];
$year = $_POST['year'];
$image_link = $_POST['image_link'];

if (strlen($model) > 100) {
    header("Location: edit.php?edit_id={$input_id}&error=model name too long");
    exit();
} elseif (strlen($image_link) > 500) {
    header("Location: edit.php?edit_id={$input_id}&error=image link too long");
    exit();
} elseif ($year < 1900 || $year > 2024) {
    header("Location: edit.php?edit_id={$input_id}&error=incorrect year");
    exit();
} elseif (strlen($make) > 100) {
    header("Location: edit.php?edit_id={$input_id}&error=make name too long");
    exit();
} else {
    $db = new PDO('mysql:host=db; dbname=Cars', 'root', 'password');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $cars_model = new CarsModel($db);
    $make_model = new MakeModel($db);

    $id = $cars_model->getIdByModel($model);

    if ($make_model->checkDistinct('name', $make)) {
        if ($make_model->addMake($make) != true) {
            header("Location: edit.php?edit_id={$input_id}&error=could not add make");
            exit();
        }
    }

    $make_id = $make_model->getIdByName($make);
    if (!$make_id) {
        header("Location: edit.php?edit_id={$input_id}&error=could not find make");
    }

    if ($cars_model->editCarAll($id, $model, $image_link, $make_id, $bodytype, $year) == true) {
        header('Location: index.php');
        exit();
    } else {
        header("Location: edit.php?edit_id={$input_id}&error=failed to insert to database");
        exit();
    }
    header('Location: index.php');
}
?>
