<?php
session_start();
require('firstimport.php');

$tbl_name="interlist";

mysqli_select_db($conn,"$db_name") or die("cannot select db");
$k=0;
if(isset($_POST['byname']) && ($_POST['bynum']==""))
{
	$name1=$_POST['byname'];
	$k=2;
	$name1=strtoupper($name1);
	
	$tbl_name="train_list";
	$sql="SELECT * FROM $tbl_name WHERE Number='$name1' or Name='$name1' ";
	$result=mysqli_query($conn,$sql);
}
else if(isset($_POST['byname']) && isset($_POST['bynum']))
{
	$k=1;
	$from=$_POST['byname'];
	$to=$_POST['bynum'];
	$from=strtoupper($from);
	$to=strtoupper($to);
	$sql="SELECT * FROM $tbl_name WHERE (Ori='$from' or st1='$from' or st2='$from' or st3='$from' or st4='$from' or st5='$from') and (st1='$to' or st2='$to' or st3='$to' or st4='$to' or st5='$to' or Dest='$to')";
	$result=mysqli_query($conn,$sql);
}
else if((!isset($_POST['byname'])) && (!isset($_POST['bynum'])))
{
	$k=0;
	$from="";
	$to="";
}

?>
<!DOCTYPE html>
<html>
<head>
	<title> Deccan Express </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<link href="css/bootstrap.min.css" rel="stylesheet" ></link>
	<link href="css/bootstrap.css" rel="stylesheet" ></link>
	<link href="css/Default.css" rel="stylesheet" >	</link>
	<script type="text/javascript" src="js/jquery.js"></script>

	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script type="text/javascript" src="js/man.js"></script>

</head>
<body>

	<div class="wrap" style="background-image: linear-gradient(rgba(172,182,229,0.9), rgba(134,253,232,0.9));">
		<!-- Header -->
		<div class="header">
			<div style="float:left;width:100px;margin:30px 0px 0px 30px;">
				<img src="images/logo.jpg" style="border-radius: 50%;" />
			</div>		
			<div>
			<div id="heading">
				<a href="index.php"><span style="font-family: 'Audiowide', cursive;margin:30px 0px 0px 30px;">Deccan Express</span></a>
			</div>
			</div>
		</div>
		
<!-- Navigation bar -->

				<a class="brand" href="index.php" title="Home"><img src="images/home.png" style="width:50px;height:30px;margin:0px 40px 10px 20px;"/></a>
				<a class="brand" href="train.php" title="Find train" style="font-size:25px;font-family:  cursive;margin:0px 40px 10px 20px;"> TrainSearch</a>
				<a class="brand" href="reservation.php" title="Reservation"style="font-size:25px;font-family:  cursive;margin:0px 40px 10px 20px;">Reservation</a>
				<a class="brand" href="profile.php" title="Profile" style = "font-size:25px;font-family:  cursive;margin:0px 40px 10px 20px;"/>Profile</a>
				<a class="brand" href="display.php" title="Booking History" style = "font-size:25px;font-family:  cursive;margin:0px 40px 10px 20px;">YourBooking</a>
				<?php
			 if(isset($_SESSION['name']))	
			 {
			 // echo "<span style=\"float:right;font-size:15px;margin:0px 30px 10px 10px;\">"."Welcome,".$_SESSION['name']."</span>&nbsp;&nbsp;&nbsp;<a href=\"logout.php\" class=\"btn btn-danger\" style=\"float:right;font-size:15px;margin:0px 10px 10px 10px;\">Logout</a>";

			 echo "<a href=\"logout.php\" class=\"btn btn-danger\" style=\"float:right;font-size:15px;margin:0px 10px 10px 10px;\">Logout</a><span style=\"float:right;font-size:15px;margin:0px 10px 10px 10px;\">"."Welcome,".$_SESSION['name']."</span>";

			 }
			 else
			 {
			 ?>
				<a href="login1.php" class="btn btn-danger" style="font-family: 'Audiowide', cursive;float:right;font-size:15px;margin:0px 10px 10px 10px;">Login</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="signup.php?value=0" class="btn btn-danger" style="font-family: 'Audiowide', cursive;float:right; font-size:15px;margin:0px auto;">Signup</a>
			<?php } ?>
		<div class="span12" id="boxh">
		
			<form style="margin:0 auto;" method="post" >
			<table class="table" style="margin-bottom:0px;">
				<tr>
					<th style="border-top:0px;"><label><b>Search Train</label></th>
					<td style="border-top:0px;">
						<select id="myselect" onchange="clicker()">
						<option value="plf">By Station</option>
						<option value="name" >By Name</option>
						<option value="num" >By Number</option>
						</select>
					</td>
					<td id="mbox" style="border-top:0px;"> <label>From </label></td>
					<td style="border-top:0px;"><input  type="text" class="input-block-level" name="byname" id="byn"></td>
					<td id="xbox" style="border-top:0px;"><label> To </label></td>
					<td style="border-top:0px;"><input id="xbox1" type="text" class="input-block-level" name="bynum" ></td>
					<td style="border-top:0px;"><input class="btn btn-danger" type="submit" value="Find"> </td>
					<td style="border-top:0px;"><a href="train.php" class="btn btn-danger" type="reset" value="Reset">Reset</a></td>
				</tr>
			</table>
			</form>
		</div>
<!-- display result -->
		<div class="span12">
			<div class="display" style="height:30px;">
				<table class="table" border="5px">
				<tr>
					<th style="width:60px;border-top:0px;"> Train No.</th>
					<th style="width:210px;border-top:0px;"> Train Name </th>
					<th style="width:65px;border-top:0px;"> Orig. </th>
					<th style="width:55px;border-top:0px;"> Des. </th>
					<th style="width:60px;border-top:0px;"> Arr. </th>
					<th style="width:65px;border-top:0px;"> Dep. </th>
					<th style="width:20px;border-top:0px;"> M </th>
					<th style="width:25px;border-top:0px;"> T </th>
					<th style="width:29px;border-top:0px;"> W </th>
					<th style="width:25px;border-top:0px;"> T </th>
					<th style="width:25px;border-top:0px;"> F </th>
					<th style="width:25px;border-top:0px;"> S </th>
					<th style="border-top:0px;"><font color=red> S </font></th>
				</tr>
				</table>
			</div>
			<div class="display" style="margin-top:0px;overflow:auto;">
				<table class="table" >
				<?php
				if($k==1)
				{	echo "<script> document.getElementById(\"byn\").value=\"$from\";
									   document.getElementById(\"xbox1\").value=\"$to\";
							</script>";
					$n=0;
					while($row=mysqli_fetch_array($result)){
					//$q="from: ".$from;
						if($from==$row['st1'])
						{	$q=$row['st1arri'];
							//echo $q;
						}
						else
						if($from==$row['st2'])
						{	$q=$row['st2arri']; }
						else if($from==$row['st3'])
						{	$q=$row['st3arri']; }
						else if($from==$row['st4'])
						{	$q=$row['st4arri']; }
						else if($from==$row['st5'])
						{	$q=$row['st5arri']; }
						else if($from==$row['Ori'])
						{	$q=$row['Oriarri']; }
						else if($from==$row['Dest'])
						{	$q=$row['Destarri'];}
						
						$p1=substr($q,0,2);
						$p2=substr($q,3,2);
						$p2=$p2+5;
						if($p2<10)
						{$p2="0".$p2;}
						$d=$p1.":".$p2;
					if($n%2==0)
					{
				?>
				<tr class="text-error">
					<td style="width:60px;"><?php echo $row['Number']; ?> </td>
					<td style="width:210px;"> <?php echo $row['Name']; ?> </td>
					<td style="width:65px;"> <?php echo $row['Ori']; ?> </td>
					<td style="width:55px;"> <?php echo $row['Dest']; ?> </td>
					<td style="width:60px;"> <?php echo $q; ?> </td>
					<td style="width:65px;"> <?php echo $d; ?> </td>
					<td style="width:20px;"><?php echo $row['Mon']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Tue']; ?> </td>
					<td style="width:29px;"> <?php echo $row['Wed']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Thu']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Fri']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Sat']; ?> </td>
					<td> <?php echo $row['Sun']; ?> </td>
				</tr>
				<?php
					}
					else
					{
				?>
				<tr class="text-info">
					<td style="width:80px;"> <?php echo $row['Number']; ?> </td>
					<td style="width:210px;"> <?php echo $row['Name']; ?> </td>
					<td style="width:65px;"> <?php echo $row['Ori']; ?> </td>
					<td style="width:60px;"> <?php echo $row['Dest']; ?> </td>
					<td style="width:70px;"> <?php echo $q; ?> </td>
					<td style="width:55px;"> <?php echo $d; ?> </td>
					<td style="width:20px;"> <?php echo $row['Mon']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Tue']; ?> </td>
					<td style="width:29px;"> <?php echo $row['Wed']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Thu']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Fri']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Sat']; ?> </td>
					<td> <?php echo $row['Sun']; ?> </td>				</tr>
				<?php
					}
					$n++;
					}
				}
				else if($k==2)
				{	$n=0;
					while($row=mysqli_fetch_array($result)){
					if($n%2==0)
					{
				?>
				<tr class="text-error">
					<td style="width:80px;"> <?php echo $row['Number']; ?> </td>
					<td style="width:210px;"> <?php echo $row['Name']; ?> </td>
					<td style="width:65px;"> <?php echo $row['Origin']; ?> </td>
					<td style="width:60px;"> <?php echo $row['Destination']; ?> </td>
					<td style="width:70px;"> <?php echo $row['Arrival']; ?> </td>
					<td style="width:55px;"> <?php echo $row['Departure']; ?> </td>
					<td style="width:20px;"> <?php echo $row['Mon']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Tue']; ?> </td>
					<td style="width:29px;"> <?php echo $row['Wed']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Thu']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Fri']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Sat']; ?> </td>
					<td> <?php echo $row['Sun']; ?> </td>
				</tr>
				<?php
					}	
					else
					{
				?>
				<tr class="text-info">
					<td style="width:80px;"> <?php echo $row['Number']; ?> </td>
					<td style="width:210px;"> <?php echo $row['Name']; ?> </td>
					<td style="width:65px;"> <?php echo $row['Origin']; ?> </td>
					<td style="width:60px;"> <?php echo $row['Destination']; ?> </td>
					<td style="width:70px;"> <?php echo $row['Arrival']; ?> </td>
					<td style="width:55px;"> <?php echo $row['Departure']; ?> </td>
					<td style="width:20px;"> <?php echo $row['Mon']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Tue']; ?> </td>
					<td style="width:29px;"> <?php echo $row['Wed']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Thu']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Fri']; ?> </td>
					<td style="width:25px;"> <?php echo $row['Sat']; ?> </td>
					<td> <?php echo $row['Sun']; ?> </td>		
				</tr>
				<?php
					}
					$n++;
					}
				}
				else
				{
				    echo "<div class=\"alert alert-error\"  style=\"margin:0 auto;\"> please search the train.. </div>";
				}
				mysqli_close($conn);
				?>
				</table>
			</div>
		</div>
		</div>

</body>
</html>