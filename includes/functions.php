<?php

function fetch_all($mysqli, $sql, $types = null, $params = [])
{
    $stmt = mysqli_prepare($mysqli, $sql);
    if ($types) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQL_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

function fetch_one($mysqli, $sql, $types = null, $params = [])
{
    $rows = fetch_all($mysqli, $sql, $types, $params);
    return $rows[0] ?? null;
}


?>