<?php
    require_once('configuration.php');

    $sql = "SELECT * FROM `wp_posts` order by post_date desc";

    $result = $con->query($sql);
    $response = array();

    if(mysqli_num_rows($result)>0){
    $response['success'] = 1;
    $posts = array();
    
    while($row = $result->fetch_assoc()) {
        array_push($posts, $row);
    }
    $response['posts'] = $posts;
    }
    else{
    $response['success'] = 0;
    $response['message'] = 'No data';
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    mysqli_close($con);

?>