<?php
	session_start();
	if(!isset($_SESSION['dangnhap'])){
		header('Location: login.php');
	} 
	if(isset($_GET['login'])){
 	$dangxuat = $_GET['login'];
	 }else{
	 	$dangxuat = '';
	 }
	 if($dangxuat=='dangxuat'){
	 	session_destroy();
	 	header('Location: login.php');
	 }
?>
<?php
	if(isset($_POST['logout'])){
		unset($_SESSION['dangnhap']);
		header('location:login.php');
	}
?>
<?php
	include('../db/connect.php');
?>
<?php
	if(isset($_POST['thembaiviet'])){
		$tenbaiviet = $_POST['tenbaiviet'];
		$hinhanh = $_FILES['hinhanh']['name'];
		$danhmuc = $_POST['danhmuc'];
		$chitiet = $_POST['chitiet'];
		$mota = $_POST['mota'];
		$path = '../uploads/';
		
		$hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
		$sql_insert_product = mysqli_query($con,"INSERT INTO tbl_baiviet(tenbaiviet,tomtat,noidung,danhmuctin_id,baiviet_image) values ('$tenbaiviet','$mota','$chitiet','$danhmuc','$hinhanh')");
		move_uploaded_file($hinhanh_tmp,$path.$hinhanh);
	}elseif(isset($_POST['capnhatbaiviet'])) {
		$id_update = $_POST['id_update'];
		$tenbaiviet = $_POST['tenbaiviet'];
		$hinhanh = $_FILES['hinhanh']['name'];
		$hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
	
		$danhmuc = $_POST['danhmuc'];
		$chitiet = $_POST['chitiet'];
		$mota = $_POST['mota'];
		$path = '../uploads/';
		if($hinhanh==''){
			$sql_update_image = "UPDATE tbl_baiviet SET tenbaiviet='$tenbaiviet',noidung='$chitiet',tomtat='$mota',danhmuctin_id='$danhmuc' WHERE baiviet_id='$id_update'";
		}else{
			move_uploaded_file($hinhanh_tmp,$path.$hinhanh);
			$sql_update_image = "UPDATE tbl_baiviet SET tenbaiviet='$tenbaiviet',noidung='$chitiet',tomtat='$mota',danhmuctin_id='$danhmuc',baiviet_image='$hinhanh' WHERE baiviet_id='$id_update'";
		}
		mysqli_query($con,$sql_update_image);
	}
	
?> 
<?php
	if(isset($_GET['xoa'])){
		$id= $_GET['xoa'];
		$sql_xoa = mysqli_query($con,"DELETE FROM tbl_baiviet WHERE baiviet_id='$id'");
	} 
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="../css/admin.css" />
<head>
	<meta charset="UTF-8">
	<title>Welcome Admin</title>
	<link href="../css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>
	<div class="header">
    	<h1>Welcome to account admin:  <?php echo $_SESSION['dangnhap'] ?></h1></br>
    </div>
	<!--<nav class="navbar navbar-expand-lg navbar-light bg-light">-->
	  <div class="menu" >
	    <ul >   
	      <li >
	        <a href="xulydanhmuc.php">Lo???i s???n ph???m</a>
	      </li>
	      <li >
	        <a  href="xulysanpham.php">S???n ph???m</a>
	      </li>
	       <li >
	        <a  href="xulydanhmucbaiviet.php">Ch??? ????? b??i vi???t</a>
	      </li>
	         <li >
	        <a  href="xulybaiviet.php">B??i vi???t</a>
	      </li>
		  <li >
	        <a  href="xulydonhang.php">????n h??ng <span class="sr-only">(current)</span></a>
	      </li>
	       <li >
	         <a  href="xulykhachhang.php">Kh??ch h??ng</a>
	      </li>
		  <li>
		  <form action="" method="post" enctype="multipart/form-data">
            <input type="submit" name="logout" value="????ng xu???t" style="background:#06F;color:#fff;width:190px;height:50px;" />
            </form>
		  </li>
	      
	    </ul>
	  </div>
</br></br>
	<div class="container">
		<div class="row">
		<?php
			if(isset($_GET['quanly'])=='capnhat'){
				$id_capnhat = $_GET['capnhat_id'];
				$sql_capnhat = mysqli_query($con,"SELECT * FROM tbl_baiviet WHERE baiviet_id='$id_capnhat'");
				$row_capnhat = mysqli_fetch_array($sql_capnhat);
				$id_category_1 = $row_capnhat['danhmuctin_id'];
				?>
				<div class="col-md-4">
				<h4>C???p nh???t b??i vi???t</h4>
				
				<form action="" method="POST" enctype="multipart/form-data">
					<label>T??n b??i vi???t</label>
					<input type="text" class="form-control" name="tenbaiviet" value="<?php echo $row_capnhat['tenbaiviet'] ?>"><br>
					<input type="hidden" class="form-control" name="id_update" value="<?php echo $row_capnhat['baiviet_id'] ?>">
					<label>H??nh ???nh</label>
					<input type="file" class="form-control" name="hinhanh"><br>
					<img src="../uploads/<?php echo $row_capnhat['baiviet_image'] ?>" height="80" width="80"><br>
					
				
					<label>M?? t???</label>
					<textarea class="form-control" rows="10" name="mota"><?php echo $row_capnhat['tomtat'] ?></textarea><br>
					<label>Chi ti???t</label>
					<textarea class="form-control" rows="10" name="chitiet"><?php echo $row_capnhat['noidung'] ?></textarea><br>
					<label>Ch??? ?????</label>
					<?php
					$sql_danhmuc = mysqli_query($con,"SELECT * FROM tbl_danhmuc_tin ORDER BY danhmuctin_id DESC"); 
					?>
					<select name="danhmuc" class="form-control">
						<option value="0">-----Ch???n ch??? ?????-----</option>
						<?php
						while($row_danhmuc = mysqli_fetch_array($sql_danhmuc)){
							if($id_category_1==$row_danhmuc['danhmuctin_id']){
						?>
						<option selected value="<?php echo $row_danhmuc['danhmuctin_id'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></option>
						<?php 
							}else{
						?>
						<option value="<?php echo $row_danhmuc['danhmuctin_id'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></option>
						<?php
							}
						}
						?>
					</select><br>
					<input type="submit" name="capnhatbaiviet" value="C???p nh???t" class="btn btn-default" style="background:#07F;color:#fff;width:120px;height:35px;">
				</form>
				</div>
			<?php
			}else{
				?> 
				<div class="col-md-4">
				<h4>Th??m b??i vi???t</h4>
				
				<form action="" method="POST" enctype="multipart/form-data">
					<label>T??n b??i vi???t</label>
					<input type="text" class="form-control" name="tenbaiviet" placeholder="T??n b??i vi???t"><br>
					<label>H??nh ???nh</label>
					<input type="file" class="form-control" name="hinhanh"><br>

					<label>M?? t???</label>
					<textarea class="form-control" name="mota"></textarea><br>
					<label>Chi ti???t</label>
					<textarea class="form-control" name="chitiet"></textarea><br>
					<label>Ch??? ?????</label>
					<?php
					$sql_danhmuc = mysqli_query($con,"SELECT * FROM tbl_danhmuc_tin ORDER BY danhmuctin_id DESC"); 
					?>
					<select name="danhmuc" class="form-control">
						<option value="0">-----Ch???n ch??? ?????-----</option>
						<?php
						while($row_danhmuc = mysqli_fetch_array($sql_danhmuc)){
						?>
						<option value="<?php echo $row_danhmuc['danhmuctin_id'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></option>
						<?php 
						}
						?>
					</select><br>
					<input type="submit" name="thembaiviet" value="Th??m" class="btn btn-default"style="background:#07F;color:#fff;width:100px;height:35px;" >
				</form>
				</div>
				<?php
			} 
			
				?>
			<div class="col-md-8">
				<h4>T???t c??? b??i vi???t</h4>
				<?php
				$sql_select_bv = mysqli_query($con,"SELECT * FROM tbl_baiviet,tbl_danhmuc_tin WHERE tbl_baiviet.danhmuctin_id=tbl_danhmuc_tin.danhmuctin_id ORDER BY tbl_baiviet.baiviet_id DESC"); 
				?> 
				<table class="table table-bordered ">
					<tr>
						<th>STT</th>
						<th>T??n b??i vi???t</th>
						<th>H??nh ???nh</th>
					
						<th>Ch??? ?????</th>
						
						<th colspan="2">Qu???n l??</th>
					</tr>
					<?php
					$i = 0;
					while($row_bv = mysqli_fetch_array($sql_select_bv)){ 
						$i++;
					?> 
					<tr>
						<td><?php echo $i ?></td>
						<td><?php echo $row_bv['tenbaiviet'] ?></td>
						<td><img src="../uploads/<?php echo $row_bv['baiviet_image'] ?>" height="100" width="80"></td>

						<td><?php echo $row_bv['tendanhmuc'] ?></td>
						
						<td><a href="?xoa=<?php echo $row_bv['baiviet_id'] ?>"><img src="../images/delete.png" width="30" height="30"   /></a> </td>
						<td> <a href="xulybaiviet.php?quanly=capnhat&capnhat_id=<?php echo $row_bv['baiviet_id'] ?>"><img src="../images/edit.png" width="30" height="30" /></a></td>
					</tr>
				<?php
					} 
					?> 
				</table>
			</div>
		</div>
	</div>
	
</body>
</html>