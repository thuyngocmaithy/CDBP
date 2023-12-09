<?php
require_once('../DataProvider.php');
if (isset($_POST['start']))
    $start = $_POST['start'];
if (isset($_POST['end']))
    $end = $_POST['end'];
$count = 0;
// $valueFilter = $_POST['valueFilter'];
$tungay = date_format(date_create($_POST['tungay']), "Y-m-d");
$denngay = date_format(date_create($_POST['denngay']), "Y-m-d");
// echo $valueFilter;
// LẤY SỐ LƯỢNG
$sqlLaySoLuong;
$sqlBangdiem;
// if ($valueFilter == 'filter') {
$sqlLaySoLuong = "SELECT COUNT(*) AS 'Số lượng'
                    FROM bangchamdiem bd
                    WHERE bd.`Thời gian` >= '$tungay'
                    AND bd.`Thời gian` <= '$denngay'";
$sqlBangdiem = "SELECT *
                    FROM bangchamdiem bd
                    WHERE bd.`Thời gian` >= '$tungay'
                    AND bd.`Thời gian` <= '$denngay'";
// echo  $sqlBangdiem;
// } else {
//     $sqlLaySoLuong = "SELECT COUNT(*) AS 'Số lượng'
//             FROM bangchamdiem
//             WHERE `Trạng thái` like '$valueFilter'";

//     $sqlBangdiem = "SELECT *
//                     FROM bangchamdiem bd, thongbao tb
//                     WHERE bd.`Thời gian` >= tb.`Ngày thực hiện`
//                     AND bd.`Thời gian` <= tb.`Ngày hết hạn`
//                     AND tb.`Hiển thị` = 0
//                     AND tb.`Phân loại` = 'Chấm điểm'
//                     AND `Trạng thái` like '$valueFilter'";
// }
// echo $sqlBangdiem;
// $queryLaySoLuong = DataProvider::executeQuery($sqlLaySoLuong);

// $row = mysqli_fetch_array($queryLaySoLuong);
// $countData = $row['Số lượng'];
// echo "<div style='display:none' id='countindex-TVBCH'>$countData</div>";


// XỬ LÝ
$data = "";
$queryBangdiem = DataProvider::executeQuery($sqlBangdiem);

while ($rowBangdiem = mysqli_fetch_array($queryBangdiem)) {
    // MÃ CDBP
    $sqlTenCDBP = "SELECT cd.`Tên CDBP`
                FROM cdbp cd
                WHERE cd.`Mã CDBP` = '" . $rowBangdiem['Mã CDBP'] . "'";
    $queryTenCDBP = DataProvider::executeQuery($sqlTenCDBP);
    // MÃ THÀNH VIÊN BCH
    if ($rowBangdiem['Mã BCH chấm'] != '') {
        $sqlMaBCH = "SELECT `Tên thành viên BCH CDBP`
        FROM thanhvienbch
        WHERE `Mã thành viên BCH CDBP` = '" . $rowBangdiem['Mã BCH chấm'] . "'";
        $queryMaBCH = DataProvider::executeQuery($sqlMaBCH);
    }
    // MÃ THÀNH VIÊN BCH EDIT
    if ($rowBangdiem['Mã BCH chỉnh sửa'] != '') {

        $sqlMaBCHEdit = "SELECT `Tên thành viên BCH CDBP`
            FROM thanhvienbch
            WHERE `Mã thành viên BCH CDBP` = '" . $rowBangdiem['Mã BCH chỉnh sửa'] . "'";
        $queryMaBCHEdit = DataProvider::executeQuery($sqlMaBCHEdit);
    }
    
    while ($rowTenCDBP = mysqli_fetch_array($queryTenCDBP)) {
        if (isset($queryMaBCH) && !isset($queryMaBCHEdit)) {
            while ($rowMaBCH = mysqli_fetch_array($queryMaBCH)) {
                // while ($rowMaBCHEdit = mysqli_fetch_array($queryMaBCHEdit)) {
                $count++;
                $tenCDBP = $rowTenCDBP['Tên CDBP'];
                // if ($count > $start && $count <= $end) {
                    $data = "
                        <div class='col'>
            <div class='card'>
                <div class='card-body'>
                    <h5 class='card-title'>$tenCDBP</h5>
                    <p class='card-text'>Thời gian: " . $rowBangdiem['Thời gian'] . "</p>
                    <p class='card-text'>Trạng thái: " . $rowBangdiem['Trạng thái'] . "</p>
                    <p class='card-text'>Người chấm: " . $rowMaBCH['Tên thành viên BCH CDBP'] . "</p>
                    <p class='card-text'>Người chỉnh sửa: </p>
                    <button value='" . $rowBangdiem['Mã bảng chấm điểm'] . "' class='btn btn-primary action-bang-diem'>Xử lý</button>
                    <button value='" . $rowBangdiem['Mã bảng chấm điểm'] . "' class='btn btn-secondary khoa-bang-diem'>Khóa</button>
                    <button style='display: none' value='" . $rowBangdiem['Mã bảng chấm điểm'] . "' class='btn btn-secondary mo-khoa-bang-diem'>Mở khóa</button>
                </div>
            </div>
            </div>
            ";
                // }
                // }
            }
            if ($data != "")
                echo $data;
        } else if (isset($queryMaBCH) && isset($queryMaBCHEdit)) {
            while ($rowMaBCH = mysqli_fetch_array($queryMaBCH)) {
                while ($rowMaBCHEdit = mysqli_fetch_array($queryMaBCHEdit)) {
                    $count++;
                    $tenCDBP = $rowTenCDBP['Tên CDBP'];
                    // if ($count > $start && $count <= $end) {
                        $data = "
                        <div class='col'>
            <div class='card'>
                <div class='card-body'>
                    <h5 class='card-title'>$tenCDBP</h5>
                    <p class='card-text'>Thời gian: " . $rowBangdiem['Thời gian'] . "</p>
                    <p class='card-text'>Trạng thái: " . $rowBangdiem['Trạng thái'] . "</p>
                    <p class='card-text'>Người chấm: " . $rowMaBCH['Tên thành viên BCH CDBP'] . "</p>
                    <p class='card-text'>Người chỉnh sửa: " . $rowMaBCHEdit['Tên thành viên BCH CDBP'] . "</p>
                    <button value='" . $rowBangdiem['Mã bảng chấm điểm'] . "' class='btn btn-primary action-bang-diem'>Xử lý</button>
                    <button value='" . $rowBangdiem['Mã bảng chấm điểm'] . "' class='btn btn-secondary khoa-bang-diem'>Khóa</button>
                    <button style='display: none' value='" . $rowBangdiem['Mã bảng chấm điểm'] . "' class='btn btn-secondary mo-khoa-bang-diem'>Mở khóa</button>
                </div>
            </div>
            </div>
            ";
                    // }
                }
            }
            if ($data != "")
                echo $data;
        } else {
            // while ($rowMaBCH = mysqli_fetch_array($queryMaBCH)) {
            //     while ($rowMaBCHEdit = mysqli_fetch_array($queryMaBCHEdit)) {
            $count++;
            $tenCDBP = $rowTenCDBP['Tên CDBP'];
            // if ($count > $start && $count <= $end) {
            $data = "
                        <div class='col'>
            <div class='card'>
                <div class='card-body'>
                    <h5 class='card-title'>$tenCDBP</h5>
                    <p class='card-text'>Thời gian: " . $rowBangdiem['Thời gian'] . "</p>
                    <p class='card-text'>Trạng thái: " . $rowBangdiem['Trạng thái'] . "</p>
                    <p class='card-text'>Người chấm: </p>
                    <p class='card-text'>Người chỉnh sửa: </p>
                    <button value='" . $rowBangdiem['Mã bảng chấm điểm'] . "' class='btn btn-primary action-bang-diem'>Xử lý</button>
                    <button value='" . $rowBangdiem['Mã bảng chấm điểm'] . "' class='btn btn-secondary khoa-bang-diem'>Khóa</button>
                    <button style='display: none' value='" . $rowBangdiem['Mã bảng chấm điểm'] . "' class='btn btn-secondary mo-khoa-bang-diem'>Mở khóa</button>
                </div>
            </div>
            </div>
            ";
            if ($data != "")
                echo $data;
            // }
            //     }
            // }
        }
    }
  
}
if ($data == "")
{
    echo "<div class='none-bangchamdiem-theongay'>Không có bảng chấm điểm trong thời gian này</div>";
}
