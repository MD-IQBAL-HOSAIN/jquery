<?php
require "database2.php";
$searchStr = false;
$searchString = false;
if (isset($_GET['psearch'])) {
    $searchString = $db->real_escape_string($_GET['psearch']);
    $searchStr = "where sku like '%$searchString%' or name like '%$searchString%' ";
}
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$pageSize = 10;
$offset = ($page - 1) * $pageSize;

#total record start

#for search result
if (isset($_GET['psearch'])) {
    $totalRecordQuery = "select count(*) as total from products" . $searchStr;
} else {
    $totalRecordQuery = "select count(*) as total from products";
}

$totalRecordQueryResult = $db->query($totalRecordQuery);
$row = $totalRecordQueryResult->fetch_assoc();
$totalRecord = $row['total'];
$totalPage = ceil($totalRecord / $pageSize);

if (isset($_GET['psearch'])) {
    $selectQuery = "select * from products" . $searchStr . "limit $offset,$pageSize";
} else {
    $selectQuery = "select * from products limit $offset ,$pageSize";
}
$selectQueryResult = $db->query($selectQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="text-right">
            <form action="" method="get">
                <input class="p-2" type="search" name="psearch" id="" placeholder="search">
                <input class="btn btn-info" type="submit" value="search" name="search_btn" id="">
                <input class="btn btn-danger border-outline" type="button" value="clear" name="clear_btn" id="" onclick="refreshpage()">
                <a href="javascript:void(0)" class="btn btn-primary" id="addbtn">ADD Product</a>
            </form>
        </div>
        <!-- insert form -->
        <div id="formContainer">
            <!-- <h3>Insert and Update New product</h3> -->
            <form action="">
                <input type="hidden" name="id" id="updateid">
                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input type="text" class="form-control" id="sku" maxlength="20">
                </div>
                <div class="form-group">
                    <label for="pname">Name</label>
                    <input type="text" class="form-control" id="pname">
                </div>
                <div class="form-group">
                    <label for="pprice">Price</label>
                    <input type="number" class="form-control" id="pprice">
                </div>
                <div class="form-group">
                    <br>
                    <input type="button" value="Insert" id="insertbtn" class="btn btn-outline-success">
                    <input type="button" value="Update" id="updatebtn" class="btn btn-outline-warning">
                </div>
            </form>
        </div>
        <!-- insert form end-->

        <div class="tableContainer">
            <table class="table table-border table-striped">
                <caption>Total Products: <?php echo $row['total']; ?></caption>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>

                </thead>
                <?php
                if ($selectQueryResult->num_rows > 0) {
                    while ($row = $selectQueryResult->fetch_assoc()) {
                        echo "<tbody><tr>
                   <td class='pid'>" . $row['id'] . "</td>
                   <td class='psku'>" . $row['sku'] . "</td>
                   <td class='pname'>" . $row['name'] . "</td>
                   <td class='pprice'>" . $row['price'] . "</td>
                   <td> 
                   <a href='javascript:void(0)' data-id='{$row['id']}'>  <img class='editIcon' src='' alt=''> </a> 
                   <a href='javascript:void(0)' data-id='{$row['id']}'>  <img class='deleteIcon' src='' alt=''> </a> 
                   </td>                   
                   </tr></tbody>";
                    }
                }
                $selectQueryResult->free();
                $db->close();
                ?>
            </table>
        </div>
        <hr>
        <nav aria-label="page navigation example">
            <ul class="pagination">
                <?php
                $p = (($page - 1) > 0) ? ($page - 1) : 1;
                $prev_disabled = ($page == 1) ? "disabled" : "";
                echo '<li class="page-iteam ' . $prev_disabled . '"><a class="page-link" href="?page=1' . '&psearch=' . ($searchString ? $searchString : '') . '">First</a></li>';

                echo '<li class="page-iteam ' . $prev_disabled . '"><a class="page-link" href="?page=' . $p . '&psearch=' . ($searchString ? $searchString : '') . '">Previous</a></li>';

                for ($i = 1; $i <= $totalPage; $i++) {
                    $active = ($page == $i) ? "active" : '';

                    if (abs($page - $i) < 6) {
                        echo '<li class="page-iteam ' . $active . '"><a class="page-link" href="?page=' . $i . '&psearch=' . ($searchString ? $searchString : '') . '">' . $i . '</a></li>';
                    }
                }

                $n = (($page + 1) < $totalPage) ? ($page + 1) : $totalPage;
                $next_disabled = ($page == $totalPage) ? 'disabled' : '';
                echo '<li class="page-iteam ' . $next_disabled . '"><a class="page-link" href="?page=' . $n . '&psearch=' . ($searchString ? $searchString : '') . '">Next</a></li>';

                echo '<li class="page-iteam ' . $next_disabled . '"><a class="page-link" href="?page=' . $totalPage . '&psearch=' . ($searchString ? $searchString : '') . '">Last</a></li>';
                ?>
            </ul>
        </nav>
    </div>

    <script src="../../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function refreshpage() {
            window.location.href = "select2.php";
        }
        $(document).ready(function() {
            $('#formContaniner').hide();

            //show-hide form
            $('#addbtn').click(function() {
                clearform();
                $('#formContainer').toggle(500);
                $('#updatebtn').hide(200);
                $('#insertbtn').show(200);
            });
            //clear form fx.
            function clearform() {
                $('#updateid').val("");
                $('#sku').val("");
                $('#pname').val("");
                $('#pprice').val("");
            }
            //insert
            $("#inserbtn").click(function() {
                let sku = $("#sku").val('');
                let pname = $("#pname").val('');
                let pprice = $("#pprice").val('');
                if (sku.length && pname.length && pprice.length) {
                    //ajax request
                    $.post("insert.php", {
                        psku: sku,
                        productname: pname,
                        productprice: pprice
                    }, function(d) {
                        console.log(d);
                        if (d.success) {
                            //sweet aleart
                            swal.fire({
                                position: "top-end",
                                icon: "success",
                                tittle: d.message,
                                showConfirmButton: false,
                                timer: 1000
                            });
                            //sweet aleart end
                            clearform();
                            $('#formContainer').hide(500);
                        } else {
                            swal.fire({
                                position: "top-end",
                                icon: "Error",
                                tittle: d.message,
                                showConfirmButton: false,
                                timer: 1000
                            });
                        }

                    }, "json");
                } else {
                    alert("all required")
                }
            });

            //edit
            $("#tableContainer").on("click", "editIcon", function() {
                let t = $(this);
                let id = t.parent().data("id");
                let fid = t.closest().find(".pid").html();
                let psku = t.closest().find(".psku").html();
                let pname = t.closest().find(".pname").html();
                let pprice = t.closest().find(".pprice").html();

                $("#updateid").val(id);
                $("#sku").val(psku);
                $("#pname").val(pname);
                $("#pprice").val(pprice);

                $("#insertbtn").hide(200);
                $("#updatebtn").show(200);
                $("#formContainer").show('200');
            });

            //update
            $("#updatebtn").click(function() {
                let id = $("#updateid").val();
                let sku = $("#sku").val();
                let pname = $("#pname").val();
                let pprice = $("#pprice").val();
                if (sku.length && pname.length && pprice.length) {
                    //ajax request
                    $.post("update.php", {
                        id: id,
                        psku: sku,
                        productname: pname,
                        productprice: pprice
                    }, function(d) {
                        console.log(d);
                        if (d.success) {
                            //sweet aleart
                            swal.fire({
                                position: "top-end",
                                icon: "success",
                                tittle: d.message,
                                showConfirmButton: false,
                                timer: 1000
                            }).then(e => location.reload());
                            //sweet aleart end
                            clearform();
                            $('#formContainer').hide(500);
                        } else {
                            swal.fire({
                                position: "top-end",
                                icon: "Error",
                                tittle: d.message,
                                showConfirmButton: false,
                                timer: 1000
                            });
                        }

                    }, "json");
                } else {
                    alert("all required")
                }
            });

            //delete
            $("#tableContainer").on("click", "deleteIcon", function() {
                //swal delete start
                swal.fire({
                    title: "Are you sure want to delete ?",
                    text: "You won't be able to revert this !!",
                    icon: "Warning",
                    showCancelButton: "Yes, delete it !!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        //swal delete end
                        let t = $(this);
                        let id = t.parent().data("id");
                        $.post("delete.php", {
                            did: id
                        }, function(d) {
                            if (d.success) {
                                swal.fire({
                                    position: "top-end",
                                    icon: "Error",
                                    title: d.message,
                                    showConfirmButton: false,
                                    timer: 1000
                                });
                            }
                        }, "json")
                    }
                })
            });

        });
    </script>
</body>

</html>