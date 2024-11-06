<?php
include("dbcon.php");
session_start();

   $tenbaiviet = $_POST['tenbaiviet'];
    //xulyhinhanh
   $hinhanh = $_FILES['hinhanh']['name'];
   $hinhanh_tmp = $_FILES['hinhanh']['tmp_name'];
   $hinhanh_time = time().'_'.$hinhanh;
   $id_admin =$_SESSION['auth_user_id'];
   

  

   if(isset($_POST['thembaiviet'])){
   //them
    $sql_them = "INSERT INTO baiviet(tenbaiviet,hinhanh,id_users) VALUE('".$tenbaiviet."
      ','".$hinhanh_time."','".$id_admin."')";
   mysqli_query($conn,$sql_them);
   move_uploaded_file($hinhanh_tmp,'uploads/'.$hinhanh_time);
   header('location:new.php');
   
   
   }
   elseif (isset($_POST['suabaiviet'])){
      //sua
      if($hinhanh !=''){
         move_uploaded_file($hinhanh_tmp,'uploads/'.$hinhanh_time);       
         $sql_update = "UPDATE baiviet SET tenbaiviet='". $tenbaiviet."', hinhanh='". $hinhanh_time."' WHERE id_baiviet='$_GET[idbaiviet]'";
         $sql = "SELECT * FROM baiviet WHERE id_baiviet = '$_GET[idbaiviet]' LIMIT 1";
         $query = mysqli_query($conn,$sql);
         while($row = mysqli_fetch_array($query)){
            unlink('uploads/'.$row['hinhanh']);
         }
      }else{
         $sql_update = "UPDATE baiviet SET tenbaiviet='". $tenbaiviet."'
         WHERE id_baiviet='$_GET[idbaiviet]'";
      }
      mysqli_query($conn,$sql_update);
      header('location:new.php');
   
   
   }else{
      $id = $_GET['idbaiviet'];
      $sql = "SELECT * FROM baiviet WHERE id_baiviet = '$id' LIMIT 1";
      $query = mysqli_query($conn,$sql);
      while($row = mysqli_fetch_array($query)){
         unlink('uploads/'.$row['hinhanh']);
      }
      $sql_xoa = "DELETE FROM baiviet WHERE id_baiviet='".$id."'";
      mysqli_query($conn,$sql_xoa);
      header('location:new.php');
   }
?>