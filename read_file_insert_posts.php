<?php
    require_once('configuration.php');

    $lu_name = './lines_updated.txt';
    $fr_name = './nyt2_ru.json';
    $lines_updated = file($lu_name)[0];
    $file_read = file($fr_name);

    $time = time();
    $date = date('Y-m-d H:i:s',$time);
    $gmdate = gmdate('Y-m-d H:i:s',$time);

    for ($page = $lines_updated; $page < count($file_read); $page++){
        $decoded_json_object = (json_decode($file_read[$page],true));
        $bestsellers_date = date('Y-m-d',(get_bestsellers_date($decoded_json_object)/1000));
        $published_date = date('Y-m-d',(get_published_date($decoded_json_object)/1000));
        $url = get_amazon_product_url($decoded_json_object);
        $author = get_author($decoded_json_object);
        
        $title = mysqli_real_escape_string($con,get_title($decoded_json_object));
        $description = get_description($decoded_json_object);
        $price = get_price($decoded_json_object);
        $publisher = get_publisher($decoded_json_object);

        print("page № $page"."\n");
        if($description==""){
            $html = mysqli_real_escape_string($con,
            "<h1>Автор: $author\nНазвание: $title</h1>\nДата публикации: $published_date\nСтал бестселлером: $bestsellers_date\nИздатель: $publisher\n<a href=".'\"https://www.w3schools.com\"'.">Ссылка на книгу на AMAZON</a>\nЦена: $price\$"
            );
        }
        else{
            $html = mysqli_real_escape_string($con,
            "<h1>Автор: $author\nНазвание: $title</h1>$description\nДата публикации: $published_date\nСтал бестселлером: $bestsellers_date\nИздатель: $publisher\n<a href=".'\"https://www.w3schools.com\"'.">Ссылка на книгу на AMAZON</a>\nЦена: $price\$"
            );
        }


        $sql = 'INSERT INTO wp_posts
        (
            post_author,
            post_date,
            post_date_gmt,
            post_content,
            post_title,
            post_excerpt,
            post_status,
            comment_status,
            ping_status,
            post_password,
            post_name,
            to_ping,
            pinged,
            post_modified,
            post_modified_gmt,
            post_content_filtered,
            post_parent,
            guid,
            menu_order,
            post_type,
            post_mime_type,
            comment_count
        )
        VALUES (
                    1, # post_author bigint
                    "'.$date.'", # post_date datetime
                    "'.$gmdate.'", # post_date_gmt datetime
                    "'.$html.'", # post_content longtext
                    "'.$title.'", # post_title text
                    "", # post_excerpt text
                    "publish", # post_status varchar(20)
                    "open", # comment_status varchar(20)
                    "open", # ping_status varchar(20)
                    "", # post_password varchar(255)
                    "'.$title.'", # post_name varchar(200)
                    "", # to_ping text
                    "", # pinged text
                    "'.$date.'", # post_modified datetime
                    "'.$gmdate.'", # post_modified_gmt datetime
                    "", # post_content_filtered longtext
                    0, # post_parent bigint
                    "https://liaten.ru/?p='.$page.'", # guid varchar(255)
                    "0", # menu_order int
                    "post", # post_type varchar(20)
                    "", # post_mime_type varchar(100)
                    0  # comment_count bigint
                )';
        $response = array();
        if(mysqli_query($con, $sql)){
            $response['success']=true;
            $response['type']='create_user';
        }
        else{
            $response['success']=false;
            $response['sql'] = $sql;
            $response['error_message'] = mysqli_error($con);
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    function get_bestsellers_date($json){
        return $json["bestsellers_date"]["\$date"]["\$numberLong"];
    }

    function get_published_date($json){
        return $json["published_date"]["\$date"]["\$numberLong"];
    }

    function get_amazon_product_url($json){
        return $json["amazon_product_url"];
    }

    function get_author($json){
        return $json["author"];
    }

    function get_description($json){
        return $json["description"];
    }

    function get_price($json){
        return $json["price"]["\$numberInt"]??0;
    }

    function get_publisher($json){
        return $json["publisher"];
    }

    function get_title($json){
        return $json["title"];
    }
?>