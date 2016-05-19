<?php
include_once("pagination.class.php");

$uid=$_REQUEST['uid'];
/*..................Select Update Text ................................*/
if(isset($_REQUEST['uid']))
    {
        global $wpdb;
        $table = $wpdb->prefix."crud";   
        $sql="SELECT * FROM $table where id='$uid'";
        $row= $wpdb->get_row($sql);
        //$fetch ->Title;
    }
/*...................Select Update Query ..............................*/   
if(isset($_REQUEST['uid']) && (isset($_REQUEST['submit'])))
    {
        global $wpdb;
        $title=$_REQUEST['Title'];
        $description =$_REQUEST['Description'];   
        $table=$wpdb->prefix."crud";
        $sql="update $table set Title ='$title',Description='$description' where id='$uid'";
        $result = $wpdb->query($sql);  
        header("Location:".$_SERVER['HTTP_REFERER']);
}   
/*..........................Insert Query ...........................*/   
else if (isset($_POST['submit'])&&(!isset($_REQUEST['uid'])))
    {
      if(($_POST['Title']!="") && ($_POST['Description']!="")) {
        global $wpdb;
        $table = $wpdb->prefix."crud";       
        $title= $_POST['Title'];
        $desc= $_POST['Description'];       
        $sql="INSERT INTO $table(id ,Title ,Description,Status)VALUES(NULL , '$title', '$desc','Active')";
        $wpdb->query($sql);
				
        if($wpdb) { ?>
<script type="text/javascript"> alert("1 record Inserted");</script>
<?php } }else { ?>
<script type="text/javascript">  alert("Please enter Title and Description"); </script>
<?php   }
 }
/*........................Status Update Query ..........................*/   
if(isset($_REQUEST['id'])&&(isset($_REQUEST['status'])))
    {
                $id=$_REQUEST['id'];
                global $wpdb;                   
                $table = $wpdb->prefix."crud";   
                if($_REQUEST['status']=='Active')
                {   
                    $sql="update $table set Status='Deactivate' where id='$id'";
                    //$result = mysql_query($sql);
                    $result = $wpdb->query($sql);
					///;
                }
                else if($_REQUEST['status']=='Deactivate')
                {
                    $sql="update $table set Status='Active' where id='$id'";
                    $result = $wpdb->query($sql);
                } }   
/*..........................Delete Query ................................*/
if(isset($_REQUEST['did']))    {           
        global $wpdb;
        $did=$_REQUEST['did'];
        $table = $wpdb->prefix."crud";   
        $sql="delete from $table where id='$did'";
        $result = $wpdb->query($sql) or(die(mysql_error()));
    }
?>
<script language=JavaScript>
    function check_length(form1)
    {
            maxLen = 500; // max number of characters allowed
            if (form1.Description.value.length >= maxLen)
            {
                var msg = "You have reached your maximum limit of characters allowed";
                alert(msg);form1.Description.value = form1.mesaj.value.substring(0, maxLen);
            } else {
                form1.text_num.value = maxLen - form1.Description.value.length;
            }
    }
</script>
<div class="wrap">   
<form name="abc" id="abc" method="post" action="">
  <table style="border:#000000 solid 1px; width:825px;">
    <tr>
      <td align="right" >&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="130" align="right" >&nbsp;&nbsp;&nbsp;<b>Name :</b></td>
      <td width="4">&nbsp;</td>
      <td width="633"><input type="text" name="Title" id="Title" value="<?php if(isset($_REQUEST['uid'])){ echo $row->Title;} ?>" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;&nbsp;&nbsp;<b>Description :</b></td>
      <td>&nbsp;</td>
      <td><textarea name="Description"  id="Description" cols="35" rows="5" onKeyPress="check_length(this.form);" onKeyDown="check_length(this.form);" onFocus="this.className='inputs-focus';" onBlur="this.className='inputs';"><?php if(isset($_REQUEST['uid'])){ echo $row->Description; }?>
</textarea>
        <br />
        <input size="1" disabled="disabled" value="500" name="text_num">
        Characters Left</td>
    </tr>
    <tr>
      <td width="130">&nbsp;</td>
      <td width="4">&nbsp;</td>
      <td align="left"><input type="submit" name="submit" id="submit" value="<?php if (isset($_REQUEST['uid'])){ echo "Update Data"; } else { echo "Add Data"; } ?>" class="button-primary"/></td>
  </table>
</form>
</div>
<br />
<?php
global $wpdb;
$table = $wpdb->prefix."crud";   
$counter = $wpdb->get_var("SELECT count(*) FROM $table");   
?>   
<div style="width:825px;">
<?php if($counter > 0) {
            $p = new listing_pagination;
            $p->items($counter);
            $p->limit(2); // Limit entries per page
            $p->target("admin.php?page=crud/crud-audq.php");
            $p->currentPage($_GET[$p->paging]);
            $p->calculate();
            $p->parameterName('paging');
            $p->adjacents(1);
            if(!isset($_GET['paging'])) {
                $p->page = 1;
            } else {
                $p->page = $_GET['paging'];
            }
            $limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
?>           
            <div class="tablenav">
                <div class='tablenav-pages'>
                    <?php echo $p->show(); ?>
                </div>
            </div>               
<?php           
    } ?>       
<table class="widefat" style="border:#000000 solid 1px; width:825px;">
<thead>
    <th>Name</th>
    <th>Description</th>
    <th>Status</th>
    <th>Update</th>
    <th>Delete</th>
</thead>
<tfoot>
  <tr>
   <th>Name</th>
    <th>Description</th>
    <th>Status</th>
    <th>Update</th>
    <th>Delete</th>
</tr>
</tfoot>
  <tbody>
<?php   
    global $wpdb;
    $table = $wpdb->prefix."crud";   
    $sql="SELECT * FROM $table order by id DESC $limit";       
    $result = $wpdb->get_results($sql);    
    if($counter > 0) :
    ?>
<?php    foreach( $result as $row )  {?>  
  <tr>
    <td><?php echo $row->Title;?></td>
    <td><?php echo substr($row->Description,0,25);?></td>
    <td><a href="<?php $_SERVER['HTTP_REFERER']; ?>?page=crud/crud-audq.php&id=<?php echo $row->id;?>&status=<?php echo $row->Status;?>"><?php echo $row->Status;?></a></td>
    <td><a href="<?php $_SERVER['HTTP_REFERER']; ?>?page=crud/crud-audq.php&uid=<?php echo $row->id;?>">Update</a></td>
    <td><a href="<?php $_SERVER['HTTP_REFERER']; ?>?page=crud/crud-audq.php&did=<?php echo $row->id;?>" onClick="return confirm('Are you sure you want to delete?');">Delete</a></td>  
  </tr>
  <?php } ?>
     <?php else : ?> 
          <tr>
                  <td colspan="5">No Records Found!</td>
          </tr>
          <?php endif; ?>
   </tbody>
</table>
</div>