<?PHP
/** XMLPHPForm - Buld's applications forms from XML configurations.
  * <code>
  *
  * </code>
  */

$file = 'demoform.xml';

define (ATTR_INPUT,"TYPE,NAME,CLASS,SIZE,MAXLENGTH,SELECTED,CHECKED,DIR,STYLE,MAXLENGTH,ID,ROWS,COLS");
define (ACCEPTED_TAGS,"INPUT,INPUT2");
define (SUB_TAGS,"LABEL,LABEL2");
define (HTML_TAGS,"");

/** XMLPHPForm - Buld's applications forms from XML configurations files.<br><br>
  * <b>EXAMPLE:</b>
  *<code>
  *<?xml version='1.0' /?/>
  *<form name="eeee" method="post" db_table="menu_data" db_primary_id="id" action="show_menus.php">
  *  <input name="name"	  type="text"  required="text" size="50" maxlength="128">Name of menu</input>
  *  <input name="parent"	  type="text" required="text" size="5" maxlength="5">Tipa parent</input>
  *  <input name="id_menu"  type="text" required="text" size="5" maxlength="5">Id_Menu</input>
  *  <input name="link_y_n" type="checkbox" value="1">Is link?</input>
  *  <input name="idp"	  type="text"  size="5" maxlength="5">ID PAGE</input>
  *  <input name="idp_var"  type="text"  size="5" maxlength="5">Parameter ID</input>
  *  <input name="link"	  type="text"  required="text" size="50" maxlength="128">
  *  URL link, if not ID Page  *  </input>
  *  <select name="select">
  *    <option value="1">1</option>
  *    <option value="2">2</option>
  *  </select>
  *  <input type="submit" value="{INSERT_UPDATE}" style="margin-top:10"/>
  *</form>
  *</code>
  */
class FormXML
{ var $stack,
	  $html_form,
	  $required_fields;

var $DELETE_IMAGE_STR='Delete Image';

	  /**  Files Variable containt details of Uploaded files.
	    */
var  $files;

var	  $showm,
	  $req_elements,
	  $req_message,
	  $input_elements,
	  $form_method,$form_tofile,$form_name,
	  $xml_data,$from_encoding,
	  $db_table,					// Table name
	  $db_primary_id,				// Table primary id name, if "name"=23, only edit 
	  $db_values,					// DB Values if editing form
	  $out='';						// Output variable
var	  $Get_Out_type="variable";		// "screen" OR "variable" 
	  /**  Redirect is enable after action. For disable it, set value = false
	    */
var	  $redirect_after_action = true;
	  /**  Redirect URL after action. Value can be update manually, from code, before
		*  $this->CheckPost() medthod.<br>
		*<b>EXAMPLE 1:</b>
		*$template->assign_var_from_handle('FORM', 'form'); 
		*$formxml=new FormXML;
		*$formxml->html_form[1][0]=	"<table border=0 align=center>\n";
		*$formxml->html_form[1][1]=	"  <tr>\n". //[1][1]
		*					"    <td class=\"text_12\" >\n"."          ";
		*$formxml->ParseStr($template->_tpldata['.'][0][FORM]);
		*$formxml->CheckPost();
		*/
var	  $redirect_url_after_action="";

var	  $action="insert";				// /Default action is insert/ insert, edit, delete

var	  $insert_id;					// Insert last id 


// In future :
# $remember_get	 = true,		// Remember previous _GET[REUEStS]
# $remember_get_disable = array ("action"); // Disable parameter's for _GET requies

function GenInsertSQL(&$db_table, &$to_DB)
{ if (!is_array($to_DB)){die ("GenInsertSQL function error, '$to_DB' is not array"); }
  if (count($to_DB)==0) {die ("GenInsertSQL function error, '$to_DB' is a empty"); }
  $s1=$s2='';$first=true;
  foreach ($to_DB AS $key=>$val)
  { if (!$first){$s1.=",";$s2.=",";}// Comma after older fileds
	$s1.="`".mysql_escape_string($key)."`";$s2.="'".mysql_escape_string($val)."'";$first=false;
  }
  return "INSERT INTO ".mysql_escape_string($db_table)."($s1) VALUES ($s2)";
}

function GenUpdateSQL (&$db_table, &$to_DB, $where)
{
  if (!is_array($to_DB)){die ("GenUpdateSQL function error, '$to_DB' is not array"); }
  if (count($to_DB)==0) {die ("GenUpdateSQL function error, '$to_DB' is a empty"); }


  $s1='';$first=true;
  foreach ($to_DB AS $key=>$val)
  { if (!$first){$s1.=",";$s2.=",";}// Comma after older fileds
    $s1.="`".mysql_escape_string(stripslashes($key))."`='".mysql_escape_string(stripslashes($val))."'";
	$first=false;
  }
  return "UPDATE ".mysql_escape_string($db_table)." SET $s1 ".$where;
}


  function InitVars()
  {
	$this->html_form=array ( 1=> array ("<div onClick=\"return fromxml_checkform();\">Table</div><br><table border=1>\n",   //[1][0]
										"  <tr>\n". //[1][1]
										"    <td>\n".
										"          ",
										// Here name of element
										"    </td>\n". //[1][2]
										"    <td>\n",
										// Here input element
										"    </td>\n". //[1][3]
										"  </tr>\n",
										"</table>"),

							 2=> array ("", "      ","","      <br \>\n",""));
    $this->show		=true;
	$this->to_encoding		="utf-8";
	$this->from_encoding	="utf-8";
	$this->stack=array();
	$this->db_primary_id="id";
    
	//if ($_REQUEST[$this->db_primary_id]>0 ){die "ddd";}
  }

  function Get_Out($outstr)
  {
     if ($this->Get_Out_type=='variable')
	   {$this->out.= $outstr;}
	 else
	   { echo $outstr;}
  }
  
  function FormXML()
  { 
     $this->InitVars();
  }

  function ParseStr($str)
  {

	$this->xml_data=$str;
	$this->Parse();
  }

  function ParseFile($file)
  {
	$this->xml_data=file_get_contents($file);
	$this->Parse();
  }

  function AddGet()
  {
	 $string='';
	 $first=true;
     foreach ($_GET AS $key=>$val)
	 {
	   $string.=($first==false?"&":"").$key."=".urlencode($val);
	   $first=false;
	 }
	 return $string;
  }

  function Parse()
  { global $db;

    $this->CustomParse();

	foreach ($this->stack AS $key=>$value)
	  {	if (strtolower($value[name])=="form")
	 	 { // Found form
		   //echo "<pre>";print_r($value[attrs]);die;

		   $this->form_tofile	=($value[attrs][NAME]!=''?$value[attrs][NAME]:"form1");
		   $this->form_method	=($value[attrs][METHOD]!=''?$value[attrs][METHOD]:"post");
		   $this->form_name		= $value[attrs][NAME];
   		   $this->db_table		= $value[attrs][DB_TABLE];
		   $this->db_primary_id	= $value[attrs][DB_PRIMARY_ID];
		   if ($value[attrs][ACTION]!=''){$this->Redirect_url_after_action=$value[attrs][ACTION];}

	       //////////// GET FROM DB ///////////////////////////////////////////////
		   // If edititing, get from DB data
		   if ($_REQUEST[$this->db_primary_id]>0 )
			{ $this->action="edit";  
			  $SQL="SELECT * FROM ".mysql_escape_string($this->db_table)." WHERE ".
			  mysql_escape_string($this->db_primary_id)."=".mysql_escape_string($_REQUEST[$this->db_primary_id]). " LIMIT 0,1";
			  $row = $db->Execute($SQL);
			  $row=$row->GetRows(1);
			  $this->db_values=$row[0];
			}

		   $this->Get_Out ("<form name=\"$this->form_name\" method=\"$this->form_method\" action=\"".$_SERVER["PHP_SELF"]."?".$this->AddGet()."\" onSubmit=\"return fromxml_checkform()\" enctype=\"multipart/form-data\">");
		   $this->ParseForm($value);
		   $this->Get_Out ("</form>");
	      }
	 }  
  }

  function Redirect ($addr)
  {
     if (!headers_sent())
	{ header("location:$addr"); } 
	  else
	{ echo "<script>document.location='$addr'</script>"; }
	 exit();
  }

  function CheckPost()
  {

	 
	 global $db;
     $all_posted=(count($this->input_elements)>0?true:false);
	
	 foreach ($this->req_elements AS $key=>$value)
       if (!isset($_POST[$key]))$all_posted=false; 

     foreach( $this->input_elements AS $key)
	   $to_DB[strtolower($key)]=$_POST[$key];

    /////// INSERT /////////////////////////////////////////////////////////////////
	if ($all_posted&&count($to_DB)>0&&$this->action=='insert')
	 { $SQL=GenInsertSQL($this->db_table, $to_DB);
	   $predres=$db->Execute($SQL);
	   
	   if ($db->Insert_ID()>0) 
	   {
		 /////////// IF UPLOAD FILES
		 if (count($_FILES)>0)
		 foreach($_FILES as $key=>$value)
		 { if (isset($this->files[$key]))
		   { if ($this->files[$key][TYPE]=='image'&&isset($this->files[$key]['MAX_WIDTH'])&&isset($this->files[$key]['MAX_HEIGHT']))
			{ // Here must be image
			  image_createThumb($_FILES[$key]['tmp_name'],
								$this->files[$key]['UPLOAD_DIR'].$this->files[$key]['PREFIX'].
								$db->Insert_ID().".jpg",
								$this->files[$key]['MAX_WIDTH'],
								$this->files[$key]['MAX_HEIGHT'],
								80);
			  $myfile=$this->files[$key]['PREFIX'].$db->Insert_ID().".jpg";

			  $SQL="UPDATE ".$this->db_table." SET `".$this->files[$key][NAME]."`='".$myfile."' WHERE `".$this->db_primary_id."`='".$db->Insert_ID()."'";
			  
			  $db->Execute($SQL);
			  //die($SQL);

			}
            // Elseif is not image or not require image resize will be added in future
		   }
		 }

	   }
	   $this->insert_id=$db->Insert_ID();
	   if ($predres===false) 
	     {	print 'Error inserting: '.$db->ErrorMsg().'<BR>';}
	   elseif ($this->Redirect_url_after_action!=''&&$this->redirect_after_action==true) 
		 {  
		   $this->Redirect ($this->Redirect_url_after_action);
		 }
 	  $this->action='@insert';
	 }

     /////// EDIT /////////////////////////////////////////////////////////////////
     if ($all_posted&&count($to_DB)>0&&$this->action=='edit')
     {
		 

		 /////////// IF UPLOAD FILES
		 if (count($_FILES)>0)
		 foreach($_FILES as $key=>$value)
		 {
		   if (isset($this->files[$key]))
		   { 
			if ($this->files[$key][TYPE]=='image'&&isset($this->files[$key]['MAX_WIDTH'])&&isset($this->files[$key]['MAX_HEIGHT']))
			{ // Here must be image
			  image_createThumb($_FILES[$key]['tmp_name'],
								$this->files[$key]['UPLOAD_DIR'].$this->files[$key]['PREFIX'].
								intval($_REQUEST[$this->db_primary_id]).".jpg",
								$this->files[$key]['MAX_WIDTH'],
								$this->files[$key]['MAX_HEIGHT'],
								80);
			  $to_DB[$this->files[$key][NAME]]=$this->files[$key]['PREFIX'].intval($_REQUEST[$this->db_primary_id]).".jpg";
			}
            // Elseif is not image or not require image resize will be added in future

		   }
		 }

		/////////// IF DELETING FILES
		if (is_array($this->files))
		foreach ($this->files AS $key2=>$value2)
		{ 
		  if ($_POST[$key2]=='delete')
		  { // here deleting file
			$myimage= $value2[UPLOAD_DIR].$value2[PREFIX].intval($_REQUEST[$this->db_primary_id]).".jpg";
			$to_DB[$key2]='';unlink($myimage);
		  }
		}

		$SQL=GenUpdateSQL($this->db_table, $to_DB, "WHERE ".		mysql_escape_string($this->db_primary_id)."='".mysql_escape_string($_REQUEST[$this->db_primary_id])."'");


		//echo "<pre>";print_r ($to_DB);
		//die_r ($to_DB);
         
		if ($db->Execute($SQL) === false) 
		  {	 print 'Error inserting: '.$db->ErrorMsg().'<BR>';}
		elseif ($this->Redirect_url_after_action!=''&&$this->redirect_after_action==true) 
		  { $this->Redirect ($this->Redirect_url_after_action); }
		$this->action='@edit';
      }

	  /////// DELETE /////////////////////////////////////////////////////////////////
	  if ($_GET['action']=='delete'&&$_REQUEST[$this->db_primary_id]>0)
	  {
		
		foreach ($this->files as $key=>$val)
		{
		  @unlink ($myfile=$this->files[$key]['UPLOAD_DIR'].$this->files[$key]['PREFIX'].intval($_REQUEST[$this->db_primary_id]).".jpg");
		}


	    $SQL="DELETE FROM `".mysql_escape_string($this->db_table)."` WHERE ".
			 mysql_escape_string($this->db_primary_id)."=".mysql_escape_string($_REQUEST[$this->db_primary_id]);
		if ($db->Execute($SQL) === false) 
		  {	print 'Error inserting: '.$db->ErrorMsg().'<BR>';}
		elseif ($this->Redirect_url_after_action!=''&&$this->redirect_after_action==true) 
		  { $this->Redirect ($this->Redirect_url_after_action); }
		$this->action='@delete';
	  }
  }

  function ParseForm($myarr)
  {  //var $form_attr;
    $form_attr=$myarr[attrs];
    //foreach ($myarr AS $key=>$value) {   //$this->Get_Out $key.":".$value."<br>"; }
    $this->ParseInput($myarr[children],1); // Started with first Level
	$this->ParseJS(); // Started with first Level
  }

  Function ParseAttr($myarr)
  { $optionsstr="";
	foreach (explode(",",ATTR_INPUT) AS $key3=>$value3){ 
	  if  ($myarr[$value3]){$optionsstr.=" ".strtolower($value3)."=\"".$myarr[$value3]."\"";} 
	}
    return $optionsstr;
  }

  /////// SELECT BOX ///////////////////////////////////////////////////////////////////////
  Function ParseSelectBox($value)
  { global $db;
  
    if(isset($value[attrs][DB_TABLE]))
    {
	  if ($value[attrs][NAME]==''){ die("Require name for XML selectbox"); }
      $value2="     <select name=\"".$value[attrs][NAME]."\">\n";
	  $SQL="SELECT ".$value[attrs][DB_PRIMARY_ID].",".$value[attrs][DB_PRIMARY_NAME]." FROM ".$value[attrs][DB_TABLE];
	  $db2 = $db->Execute($SQL);
	  while ($row = $db2->FetchRow()) 
	  { // proveryaem, ili edit
	    $selected='';
		// IF EDIT, CHECK DEFAULT VALUE
	    if ($this->action=="edit")
		{ if (stripslashes($this->db_values[$value[attrs][NAME]])==stripslashes($row[$value[attrs][DB_PRIMARY_ID]]))
		   {$selected=" selected ";}
		}
		$value2.="         <option value=\"".htmlspecialchars($row[$value[attrs][DB_PRIMARY_ID]]).
		"\"$selected>".htmlspecialchars($row[$value[attrs][DB_PRIMARY_NAME]])."</option>\n";
	  }
      $value2.="     </select>\n";
	  return $value2;
	}
	elseif (isset($value[attrs][VALUES])) // Here only for values 1;2;3;4 where value is name
	{ $value2="     <select name=\"".$value[attrs][NAME]."\">\n";
	  foreach (explode(";", $value[attrs][VALUES]) AS $myval)
	  { $selected='';
		// IF EDIT, CHECK DEFAULT VALUE
	    if ($this->action=="edit")
		{ if (stripslashes($this->db_values[$value[attrs][NAME]])==stripslashes($myval))
		   {$selected=" selected ";}
		}
		  
	  $value2.="         <option value=\"".htmlspecialchars($myval)."\"$selected>".htmlspecialchars($myval)."</option>\n"; }
      $value2.="     </select>\n";
	  return $value2;
	}
  }
  /////// END SELECT BOX ///////////////////////////////////////////////////////////////////////

  //////// PARSING IMAGE ////////////////////////////////////////////////////////////////////////
  Function ParseImage($value)
  {
	/*Array
	(
		[name] => INPUT
		[attrs] => Array
			(
				[NAME] => image
				[TYPE] => image
				[UPLOAD_DIR] => ../../tmp
				[PREFIX]	=> product-
				[MAX_WIDTH] => 150
				[MAX_HEIGHT] => 175
			)
		[cdata] => ъоерд
	)
	*/    
    $value2="       <input name=\"".htmlspecialchars($value[attrs][NAME])."\" type=\"file\" />";

    if ($this->action=='edit')
	{
       $image= $value[attrs][UPLOAD_DIR].$value[attrs][PREFIX].intval($_REQUEST[$this->db_primary_id]).".jpg";
	   if (is_file($image))
	   {  $value2="       <img src=\"$image\"><br>\n";
		  $value2.="      <input name=\"".htmlspecialchars($value[attrs][NAME])."\" type=\"checkbox\" value=\"delete\"/>".$this->DELETE_IMAGE_STR;
	   }
	}
	// Remeber values for inser/update
	foreach ($value[attrs] AS $key2=>$val2)
    {
	   $this->files[$value[attrs][NAME]][$key2]=$val2;
	}
	return $value2;
  }
  //////// END PARSING IMAGE ////////////////////////////////////////////////////////////////////


  //////// PARSING TEXT AREA ////////////////////////////////////////////////////////////////////
  Function ParseTextArea($value)
  {
      $myval='';
	  if ($this->action=='edit')
	  {
	    $this->input_elements[]=$value[attrs][NAME];
		$myval=htmlspecialchars($this->db_values[$value[attrs][NAME]]);
	  }
	  
	  $value2="<textarea".$this->ParseAttr($value[attrs]).">$myval</textarea>";
	  return $value2;
  }
  //////// END PARSING TEXT AREA  ///////////////////////////////////////////////////////////////


  /////// MAIN PARSING //////////////////////////////////////////////////////////////////////////
	function ParseInput($myarr,$level)
	{ if ($this->show){$this->Get_Out ($this->html_form[$level][0]);}
	  if (is_array($myarr))
	  foreach ($myarr AS $key=>$value)
	  {	 $name		=iconv('utf-8',$this->to_encoding,trim(($value[cdata]==true?$value[cdata]:$value[attrs][NAME]))."\n");
       // HERE FILL WITCH VALUE         
		 if (in_array($value[name],explode(",",ACCEPTED_TAGS)))
		 { // Calculate value field
		   $inp_value="";
		   if (isset($value[attrs][VALUE]))
		   { // Value getting from 
		      $inp_value='value="'.$value[attrs][VALUE].'" ';
			  // if checkbox, value, and checked is different
			  if ($value[attrs][TYPE]=='checkbox'&& $value[attrs][VALUE]>0&&$value[attrs][VALUE]==stripslashes($this->db_values[$value[attrs][NAME]]))
			  { $inp_value.='checked="true" ';   }
				  
		   }
		   else
		   {  if ($this->action=="edit")
			   {
		        $inp_value.='value="'.htmlspecialchars (stripslashes($this->db_values[$value[attrs][NAME]])).'" ';
			   }
			  elseif (isset($value[attrs]["DEFAULT"] ))
		        $inp_value.='value="'.$value[attrs]["DEFAULT"].'" ';
			  elseif (isset($value[attrs][DEFAULT_FROM_GET]))
			   { // Retriving value from get
			     $inp_value.='value="'.htmlspecialchars(urldecode($_GET[$value[attrs][DEFAULT_FROM_GET]])) .'" ';
			   }
		   }

		   // HERE GENERATING INPUT ELEMENT
		   switch($value[attrs][TYPE])
			{
			case "selectbox": $inputstr	= $this->ParseSelectBox($value);
							  $this->input_elements[]=$value[attrs][NAME];
							  break;

			case "image"	: $inputstr	= $this->ParseImage($value);
							  $this->input_elements[]=$value[attrs][NAME];
							  break;

			case "submit"	: $inputstr	=iconv('utf-8',$this->to_encoding,
							  "      <input".$this->ParseAttr($value[attrs])."$optionsstr $inp_value\>\n"); 
							  break;

					default	: $inputstr	=iconv('utf-8',$this->to_encoding,
							  "      <input".$this->ParseAttr($value[attrs])."$optionsstr $inp_value\>\n"); 
							  $this->input_elements[]=$value[attrs][NAME];
							  break;
		     }
  


		   if ($value[attrs][REQUIRED]){

		     $this->req_message[$value[attrs][NAME]]="Required ".$value[attrs][NAME];
			 if (is_array($value[children]))
			 foreach ($value[children] as $key4=>$value4)
			 { if ($value4[name]=="REQUIRED")
			   {$this->req_message[$value[attrs][NAME]]=$value4[cdata];}
			 } 
			 $this->req_elements[$value[attrs][NAME]]=$value[attrs][REQUIRED];
           }
		

		 }
		 else
		 {
			 switch ($value[name])
			 { case "TEXTAREA"	: $inputstr = $this->ParseTextArea($value);
								  break;

			   default			: $inputstr	="";
								  break;
			 }
		  }

		 $reverse=$value[attrs][REVERSE];
		 if ($this->show)
		 { $this->Get_Out ($this->html_form[$level][1].($reverse==true?$inputstr:$name).$this->html_form[$level][2]); }
		   if ($this->show) { $this->Get_Out ($reverse==true?$name:$inputstr);}
		 if (in_array($value[name],explode(",",SUB_TAGS))){$this->ParseInput($value[children],$level+1); }
 		 if ($this->show){ $this->Get_Out ( $this->html_form[$level][3]);}
	  }
	  if ($this->show){ $this->Get_Out ($this->html_form[$level][4]);}
	}
	function ParseJS()
	{
	  $this->Get_Out ("
<SCRIPT LANGUAGE=\"JavaScript\">
<!--
function fromxml_checkform()
{ fromxmlerror=false;fromxmlerror_str='';	
");

foreach ($this->req_elements AS $key => $val)
{ $myerr=$this->req_message[$key];
	
  $this->Get_Out ("  if (document.$this->form_name.$key.value==\"\"){fromxmlerror_str=fromxmlerror_str+\"$myerr\\n\";fromxmlerror=true; };\n");
}
  $this->Get_Out ("  if (fromxmlerror){alert(fromxmlerror_str);return false;}\n");
  $this->Get_Out("
}
//-->
</SCRIPT>\n");
	}
/////// END MAIN PARSING /////////////////////////////////////////////////////////////////////

function CustomParse()
{ 
   global $stack; $stack=array();

   function startTag($parser, $name, $attrs) 
   {  global $stack;
      $tag=array("name"=>$name,"attrs"=>$attrs); array_push($stack,$tag);}

   function cdata($parser, $cdata)
   {  global $stack;
	  if(trim($cdata)){ $stack[count($stack)-1]['cdata']=$cdata;}}

  function endTag($parser, $name) 
  {  global $stack;
	$stack[count($stack)-2]['children'][] = $stack[count($stack)-1];array_pop($stack);}

	$xml_parser = xml_parser_create();
	xml_set_element_handler($xml_parser, "startTag", "endTag");
	xml_set_character_data_handler($xml_parser, "cdata");
	$data = xml_parse($xml_parser,iconv($this->from_encoding,'utf-8',$this->xml_data));
	if(!$data) {
	die(sprintf("XML error: %s at line %d",
	xml_error_string(xml_get_error_code($xml_parser)),xml_get_current_line_number($xml_parser)));
	}
	xml_parser_free($xml_parser);
	$this->stack=&$stack;
 }
}

/// HERE FUNCTIONS FOR PROCESS IMAGES

 function ImageCopyResampleBicubic ($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
   {
       /*
       port to PHP by John Jensen July 10 2001 -- original code (in C, for the PHP GD Module) by jernberg@fairytale.se
       */
       for ($i = 0; $i < imagecolorstotal($src_img); $i++)
       {
           // get pallete. Is this algoritm correct?
           $colors = ImageColorsForIndex ($src_img, $i);
           ImageColorAllocate ($dst_img, $colors['red'], $colors['green'], $colors['blue']);
       }

       $scaleX = ($src_w - 1) / $dst_w;
       $scaleY = ($src_h - 1) / $dst_h;

       $scaleX2 = $scaleX / 2.0;
       $scaleY2 = $scaleY / 2.0;

       for ($j = $src_y; $j < $dst_h; $j++)
       {   $sY = $j * $scaleY;
           for ($i = $src_x; $i < $dst_w; $i++)
           {   $sX = $i * $scaleX;
               $c1 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, (int) $sX, (int) $sY + $scaleY2));
               $c2 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, (int) $sX, (int) $sY));
               $c3 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, (int) $sX + $scaleX2, (int) $sY + $scaleY2));
               $c4 = ImageColorsForIndex ($src_img, ImageColorAt ($src_img, (int) $sX + $scaleX2, (int) $sY));
               $red = (int) (($c1['red'] + $c2['red'] + $c3['red'] + $c4['red']) / 4);
               $green = (int) (($c1['green'] + $c2['green'] + $c3['green'] + $c4['green']) / 4);
               $blue = (int) (($c1['blue'] + $c2['blue'] + $c3['blue'] + $c4['blue']) / 4);
               $color = ImageColorClosest ($dst_img, $red, $green, $blue);
               ImageSetPixel ($dst_img, $i + $dst_x, $j + $dst_y, $color);
           }
       }
   } 

function image_createThumbToFullBox($src,$dest,$maxWidth,$maxHeight,$quality=100) { 
  
   if (file_exists($src)  && isset($dest)) { 
       // path info 
       $destInfo  = pathInfo($dest); 
       
       // image src size 
       $srcSize  = getImageSize($src); 
       
       // image dest size $destSize[0] = width, $destSize[1] = height 
       $srcRatio  = $srcSize[0]/$srcSize[1]; // width/height ratio 
       $destRatio = $maxWidth/$maxHeight; 
       if ($destRatio < $srcRatio) { 
           $destSize[1] = $maxHeight; 
           $destSize[0] = $maxHeight*$srcRatio; 
       } 
       else { 
           $destSize[0] = $maxWidth; 
           $destSize[1] = $maxWidth/$srcRatio; 
       } 
       
       // path rectification 
       if ($destInfo['extension'] == "gif") { 
           $dest = substr_replace($dest, 'jpg', -3); 
       } 
       
       // true color image, with anti-aliasing 
       $destImage = imageCreateTrueColor($destSize[0],$destSize[1]); 
       imageAntiAlias($destImage,true); 
       
       // src image 
       switch ($srcSize[2]) { 
           case 1: //GIF 
           $srcImage = imageCreateFromGif($src); 
           break; 
           
           case 2: //JPEG 
           $srcImage = imageCreateFromJpeg($src); 
           break; 
           
           case 3: //PNG 
           $srcImage = imageCreateFromPng($src); 
           break; 
           
		   case 6: //BMP
           $srcImage = imagecreatefromwbmp($src); 
           break; 
           
           default: 
           return false; 
           break; 
       } 
       
       // resampling 
       ImageCopyResampleBicubic ($destImage, $srcImage, 0, 0, 0, 0,$destSize[0],$destSize[1],$srcSize[0],$srcSize[1]); 
       
       // generating image 
       switch ($srcSize[2]) { 
           case 1: 
           case 2: 
           imageJpeg($destImage,$dest,$quality); 
           break; 
           
           case 3: 
           imagePng($destImage,$dest); 
           break; 
       } 
       return true; 
   } 
   else { 
       return false; 
   } 
} 

function image_createThumb($src,$dest,$maxWidth,$maxHeight,$quality=100) { 
   if (file_exists($src)  && isset($dest)) { 
       // path info 
       $destInfo  = pathInfo($dest); 
       
       // image src size 
       $srcSize  = getImageSize($src); 
       
       // image dest size $destSize[0] = width, $destSize[1] = height 
       $srcRatio  = $srcSize[0]/$srcSize[1]; // width/height ratio 
       $destRatio = $maxWidth/$maxHeight; 
       if ($destRatio > $srcRatio) { 
           $destSize[1] = $maxHeight; 
           $destSize[0] = $maxHeight*$srcRatio; 
       } 
       else { 
           $destSize[0] = $maxWidth; 
           $destSize[1] = $maxWidth/$srcRatio; 
       } 
       
       // path rectification 
       if ($destInfo['extension'] == "gif") { 
           $dest = substr_replace($dest, 'jpg', -3); 
       } 
       
       // true color image, with anti-aliasing 
       $destImage = imageCreateTrueColor($destSize[0],$destSize[1]); 
       imageAntiAlias($destImage,true); 
       
       // src image 
       switch ($srcSize[2]) { 
           case 1: //GIF 
           $srcImage = imageCreateFromGif($src); 
           break; 
           
           case 2: //JPEG 
           $srcImage = imageCreateFromJpeg($src); 
           break; 
           
           case 3: //PNG 
           $srcImage = imageCreateFromPng($src); 
           break; 
           
           default: 
           return false; 
           break; 
       } 
       
       // resampling 
       ImageCopyResampleBicubic ($destImage, $srcImage, 0, 0, 0, 0,$destSize[0],$destSize[1],$srcSize[0],$srcSize[1]); 
       
       // generating image 
       switch ($srcSize[2]) { 
           case 1: 
           case 2: 
           imageJpeg($destImage,$dest,$quality); 
           break; 
           
           case 3: 
           imagePng($destImage,$dest); 
           break; 
       } 
       return true; 
   } 
   else { 
       return false; 
   } 
} 

function GenInsertSQL($db_table, &$to_DB)
{
  if (!is_array($to_DB)){die ("GenInsertSQL function error, '$to_DB' is not array"); }
  if (count($to_DB)==0) {die ("GenInsertSQL function error, '$to_DB' is a empty"); }
  $s1=$s2='';$first=true;
  foreach ($to_DB AS $key=>$val)
  { if (!$first){$s1.=",";$s2.=",";}// Comma after older fileds
	$s1.="`".mysql_escape_string($key)."`";$s2.="'".mysql_escape_string($val)."'";$first=false;
  }
  return "INSERT INTO ".mysql_escape_string($db_table)."($s1) VALUES ($s2)";
}

function GenUpdateSQL ($db_table, &$to_DB, $where)
{
  if (!is_array($to_DB)){die ("GenUpdateSQL function error, '$to_DB' is not array"); }
  if (count($to_DB)==0) {die ("GenUpdateSQL function error, '$to_DB' is a empty"); }


  $s1='';$first=true;
  foreach ($to_DB AS $key=>$val)
  { if (!$first){$s1.=",";$s2.=",";}// Comma after older fileds
    $s1.="`".mysql_escape_string(stripslashes($key))."`='".mysql_escape_string(stripslashes($val))."'";
	$first=false;
  }
  return "UPDATE ".mysql_escape_string($db_table)." SET $s1 ".$where;
}

?>