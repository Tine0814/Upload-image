<?php

include('./connection.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Upload Picture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous" />
</head>
<style>
body {
    background: #f4f4f4;
}

.banner {
    background: #a770ef;
    background: -webkit-linear-gradient(to right, #a770ef, #cf8bf3, #fdb99b);
    background: linear-gradient(to right, #a770ef, #cf8bf3, #fdb99b);
}
</style>

<body>

    <?php
                    $phpFileUploadErrors = array(
                        0 => 'Successfully uploaded',
                        1 => 'the upload file exceed the  upload_max_filesize directive in php.ini',
                        2 => 'the upload file exceed the MAX_FILE_SIZE directive that was specified in the HTML form',
                        3 => 'the upload file was only partially uploaded',
                        4 => 'no file was uploaded',
                        5 => 'missing a temporary folder',
                        6 => 'failed to write file to disc',
                        7 => 'a php extension stopped the file upload',
                    );

                    if (isset($_FILES['file'])) {

                        $file_array = reArrayFiles($_FILES['file']);
                        //pre_r($file_array);
                        for ($i = 0; $i < count($file_array); $i++) {
                            if ($file_array[$i]['error']) {
                                ?><div class="alert alert-danger">
        <?php echo $file_array[$i]['name'] . ' - ' . $phpFileUploadErrors[$file_array[$i]['error']];
                                ?></div><?php
                            } else {
                                $extensions = array('jpeg', 'jpg', 'png', 'gif');
                                $file_ext = explode('.', $file_array[$i]['name']);
                                $file_ext = end($file_ext);
                                $name = $_POST['name'];
                                $details = $_POST['details'];

                                if (!in_array($file_ext, $extensions)) {
                                    ?>
    <div class="alert alert-danger">

        <?php echo "{$file_array[$i]['name']} -File upload failed, please try again. Only JPG, JPEG, PNG, & GIF files are allowed to upload." ?>

    </div>

    <?php
                                } else {
                                    if (move_uploaded_file($file_array[$i]['tmp_name'], "./picture/" . $file_array[$i]['name'])) {
                                        $mysqli->query("INSERT INTO file(image,name,details) 
                                        VALUES ('" . $file_array[$i]['name'] ."', '$name', '$details')");

                                        // header('refresh:1;url= approval.php');

                                    }
                                    ?>
    <div class="alert alert-success">
        <?php echo $file_array[$i]['name'] . ' - ' . $phpFileUploadErrors[$file_array[$i]['error']] ?>
    </div>

    <?php
                                }
                            }
                        }
                    }

                    function reArrayFiles($file_post) {

                        $file_ary = array();
                        $file_count = count($file_post['name']);
                        $file_keys = array_keys($file_post);

                        for ($i = 0; $i < $file_count; $i++) {
                            foreach ($file_keys as $key) {
                                $file_ary[$i][$key] = $file_post[$key][$i];
                            }
                        }

                        return $file_ary;
                    }

                    function pre_r($array) {
                        echo '<pre>';
                        print_r($array);
                        echo '</pre>';
                    }

                    //end multi upload-->
                    ?>
    <div class="d-flex justify-content-center align-self-center">
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <label for="formFileMultiple" class="form-label">Multiple files input example</label>
            <input class="form-control mt-2" type="file" name="file[]" id="formFileMultiple" multiple />
            <input class="form-control mt-2" type="text" name="name" placeholder="Name" />
            <input class="form-control mt-2" type="text" name="details" placeholder="Details" />
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>

        <?php 
                $result = $mysqli->query("SELECT * FROM file ORDER BY name");
                ?>
    </div>

    <div class="container-fluid">
        <div class="px-lg-5">

            <!-- For demo purpose -->
            <div class="row py-5">
                <div class="col-lg-12 mx-auto">
                    <div class="text-white p-2 shadow-sm rounded banner">
                        <h1 class="display-4 text-center text-uppercase fs-3">Simple photo gallery</h1>
                    </div>
                </div>
            </div>

            <!-- End -->

            <div class="row">
                <?php while($row = $result->fetch_assoc()): ?>
                <!-- Gallery item -->
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="bg-white rounded shadow-sm"><img src="./picture/<?php echo $row['image']; ?>" alt=""
                            class="img-fluid card-img-top">
                        <div class="p-4">
                            <h5> <a href="#" class="text-dark"><?php echo $row['name']; ?></a></h5>
                            <p class="small text-muted mb-0"><?php echo $row['details']; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>

                <!-- End -->


            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
            integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
            integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
        </script>
</body>

</html>