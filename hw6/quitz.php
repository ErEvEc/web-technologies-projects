<!DOCTYPE>
<HTML>
    <HEAD>
    <meta charset="UTF-8">
    <style type="text/css">
        label{
            color: red;
            width: 1000px;
            text-align: center;
        }
        #rstable td {text-align:center;
            width: 24%;
            }
        #rstable2 td {text-align:left;
            width:20%;
            padding-left: 20%;
            }
		th{
			font-weight:bold;
		}
        h2{
            text-align: center;
        }
    </style>
    <title>Forecast</title>
    <script type="text/javascript">
    </script>
    </HEAD>
    
    <BODY onload="changeKw(); copyForm()">
        
        <h1 align="center" style="font-size:40px">Congress Information Search</h1>
        
        <center>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return checkInput();" method="post">
            <table style="border:1px solid black">
            <tr align="center">
                <td>Congress Database</td>
                <td>
                    <select id="database" name="dbName" onchange="changeKw();" >
                    <option value="slo">Select your option</option>
                    <option value="legislators">Legislators</option>
                    <option value="committees">Committees</option>
                    <option value="bills">Bills</option>
                    <option value="amendments">Amendments</option>
                    </select>
                </td>
            </tr>

            <tr align="center">
                <td>Chamber</td>
                <td>  
                    <input type= "radio" name="chamber" id="snchecked" value="Senate" checked>Senate
                    <input type= "radio" name="chamber" id="hochecked "value="House">House
                </td>
            </tr>

            <tr align="center">
                <td id='kwcontent'>Keyword*</td>
                <td>
                    <input type="text" name="kwName" id="keyword" value="<?php if(isset($_POST['kwName'])) echo $_POST['kwName']; ?>">
                </td>
            </tr>
            <tr align="center">
                <td></td>
                <td align="center">
                    <input type="submit" name="tijiao" value="search">
                    <input type="button" value="clear" onclick="rsForm()">
                </td>
            </tr>
            <tr align="center">
                <td colspan="2" align="center">
                    <p align="center"><a href="http://sunlightfoundation.com/" target="_blank">Powered by Sunlight Foundation</a></p>
                </td>
            </tr>
            </table>

        </form>

        <?php
			ini_set('date.timezone','UTC');
            $cgapi = "http://congress.api.sunlightfoundation.com/";
            //$apikey = "a77bef19e5eb48a89e0343e89be7e228";
            $apikey = "cbbc9c162edd449b8a071588ad6fa82b";
            
            
            $state_list = array( 
                    'AL'=>"Alabama", 
                    'AK'=>"Alaska",   
                    'AZ'=>"Arizona",   
                    'AR'=>"Arkansas",   
                    'CA'=>"California",   
                    'CO'=>"Colorado",   
                    'CT'=>"Connecticut",   
                    'DE'=>"Delaware",   
                    'DC'=>"District Of Columbia",   
                    'FL'=>"Florida",   
                    'GA'=>"Georgia",   
                    'HI'=>"Hawaii",   
                    'ID'=>"Idaho",   
                    'IL'=>"Illinois",   
                    'IN'=>"Indiana",   
                    'IA'=>"Iowa",   
                    'KS'=>"Kansas",   
                    'KY'=>"Kentucky",   
                    'LA'=>"Louisiana",   
                    'ME'=>"Maine",   
                    'MD'=>"Maryland",   
                    'MA'=>"Massachusetts",   
                    'MI'=>"Michigan",   
                    'MN'=>"Minnesota",   
                    'MS'=>"Mississippi",   
                    'MO'=>"Missouri",   
                    'MT'=>"Montana", 
                    'NE'=>"Nebraska", 
                    'NV'=>"Nevada", 
                    'NH'=>"New Hampshire", 
                    'NJ'=>"New Jersey", 
                    'NM'=>"New Mexico", 
                    'NY'=>"New York", 
                    'NC'=>"North Carolina", 
                    'ND'=>"North Dakota", 
                    'OH'=>"Ohio",   
                    'OK'=>"Oklahoma",   
                    'OR'=>"Oregon",   
                    'PA'=>"Pennsylvania",   
                    'RI'=>"Rhode Island",   
                    'SC'=>"South Carolina",   
                    'SD'=>"South Dakota", 
                    'TN'=>"Tennessee",   
                    'TX'=>"Texas",   
                    'UT'=>"Utah",   
                    'VT'=>"Vermont",   
                    'VA'=>"Virginia",   
                    'WA'=>"Washington",   
                    'WV'=>"West Virginia",   
                    'WI'=>"Wisconsin",   
                    'WY'=>"Wyoming" 
                );
            
            $state_list = array_flip($state_list);
            $state_list = array_change_key_case($state_list,CASE_UPPER);
            $picurl = "http://theunitedstates.io/images/congress/225x275/";
            $det = array();
            $idx = 0;
            function validstring($str){
                if($str == '')
                    $str = 'NA';
                return $str;
            }
            function std_class_object_to_array($stdclassobject){
                $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
             
                foreach ($_array as $key => $value) {
                    $value = (is_array($value) || is_object($value)) ? std_class_object_to_array($value) : $value;
                    $array[$key] = $value;
                }
             
                return $array;
            }
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $chamber = strtolower($_POST['chamber']);
                $keyword1 = $_POST['kwName'];
				$keyword=str_replace(" ","%20",$keyword1);
                if($_POST['dbName'] == 'legislators'){
                    $temp = strtoupper($keyword);
                    $flag;
                    if($state_list[$temp]!='')
                        $flag = 1;
                    else if(strpos($keyword,'%20')==false)
                        $flag = 0;
                    else
                        $flag = 2;
					
                    $url = '';
                    if($flag == 1)
                        $url = $cgapi.'legislators?chamber='.$chamber.'&state='.$state_list[$temp].'&apikey='.$apikey;
                    else if($flag == 0)
                        $url = $cgapi.'legislators?chamber='.$chamber.'&query='.$keyword.'&apikey='.$apikey;
                    else{
                        $fn = substr($keyword,0,strpos($keyword, '%20'));
                        $ln = substr($keyword,strpos($keyword, '%20')+3);
                        $url = $cgapi.'legislators?chamber='.$chamber.'&first_name='.$fn.'&last_name='.$ln.'&apikey='.$apikey;
                    }
                    $durl = $cgapi.'legislators?chamber='.$chamber.'&state='.$keyword;
                    
					
                    $rejson = file_get_contents($url);
                    
                    $dejson1 = json_decode($rejson)->results;
                    
                    if(count($dejson1) == 0){
                        echo '<h2>The API return zero results for the request</h2>';
                    }
                    else{
                    echo '<div id="rstable" style="text-align:center;width:100%"><table border="1px" cellspacing="0" cellpadding="5" align="center" width="70%"><tr><th>Name</th><th>State</th><th>Chamber</th><th>Details</th></tr>';
                    for($i=0;$i<count($dejson1);$i++){
                        $dejson = std_class_object_to_array($dejson1[$i]);
                        $name = validstring($dejson['first_name']." ".$dejson['last_name']);
                        $state = validstring($dejson['state_name']);
                        $chamber = validstring($dejson['chamber']);
                        $vdt1 = validstring($dejson['bioguide_id']);
                        $stateU = validstring($dejson['state']);
                        $vdt2 = '';
                        if($flag == 1)
                            $vdt2 = $cgapi.'legislators?chamber='.$chamber.'&state='.$state_list[$temp].'&bioguide_id='.$vdt1.'&apikey='.$apikey;
                        else
                            $vdt2 = $cgapi.'legislators?chamber='.$chamber.'&state='.$stateU.'&bioguide_id='.$vdt1.'&apikey='.$apikey;
                        
                        $detail_info2 = file_get_contents($vdt2);
                        $detail_info1 = json_decode($detail_info2)->results;
                        $detail_info = std_class_object_to_array($detail_info1[0]);
                        
                        //print_r($detail_info);
                        
                        /*name = <?php echo "'".$det[7*$idx+0]."'" ?>;
                        term = <?php echo "'".$det[7*$idx+1]."'" ?>;
                        website = <?php echo "'".$det[7*$idx+2]."'" ?>;
                        office = <?php echo "'".$det[7*$idx+3]."'" ?>;
                        fb1 = <?php echo "'".$det[7*$idx+4]."'" ?>;
                        fb = "https://www.facebook.com/" + fb1;
                        twi1 = <?php echo "'".$det[7*$idx+5]."'" ?>;
                        twi = "https://twitter.com/" + twi1;
                        fname = <?php echo "'".$det[7*$idx+6]."'" ?>;
                        */
                        $name = $detail_info['title'].' '.$detail_info['first_name'].' '.$detail_info['last_name'];
                        //echo 'url:'.$vdt2.'NO.'.$i.' '.$det[0];
                        //echo '<br/>';
                        $term = validstring($detail_info['term_end']);
                        $website = validstring($detail_info['website']);
                        $office = validstring($detail_info['office']);
                        $fb = validstring($detail_info['facebook_id']);
                        $twi = validstring($detail_info['twitter_id']);
                        $fname = validstring($detail_info['first_name'].' '.$detail_info['last_name']);
                        $idx = $i;
                        //print_r($det);
                        $para = "'".$name."',"."'".$term."',"."'".$website."',"."'".$office."',"."'".$fb."',"."'".$twi."',"."'".$fname."'";
                        //echo $para;
                        $vdt = '<a href="javascript:void(0);" onclick="showDetails('."'".$idx."', '".$vdt1."',".$para.');">View Details</a>';
                        //echo substr($vdt, 1);
                        echo '<tr><td>'.$fname.'</td><td>'.$state.'</td><td>'.$chamber.'</td><td>'.$vdt.'</td></tr>';
                    }
                    
                    echo '</table></div>';
                }
                    
                }
                else if($_POST['dbName'] == 'committees'){
					$keyword = strtoupper($keyword);
                    $url = $cgapi.'committees?committee_id='.$keyword.'&chamber='.$chamber.'&apikey='.$apikey;
                    $rejson = file_get_contents($url);
                    //echo $rejson;
                    $dejson1 = json_decode($rejson)->results;
                    if(count($dejson1) == 0){
                        echo '<h2>The API return zero results for the request</h2>';
                    }
                    else{
                    echo '<div id="rstable" style="text-align:center;width:100%"><table border="1px" cellspacing="0" cellpadding="5" align="center" width="70%"><tr><th>Committee</th><th>Company Name</th><th>Chamber</th></tr>';
                    for($i=0;$i<count($dejson1);$i++){
                        $dejson = std_class_object_to_array($dejson1[$i]);
                        $cid = validstring($dejson['committee_id']);
                        $cname = validstring($dejson['name']);
                        $chamber = validstring($dejson['chamber']);
                        echo '<tr><td>'.$cid.'</td><td>'.$cname.'</td><td>'.$chamber.'</td></tr>';
                    }
                    echo '</table></div>';
                    }                    
                }
                else if($_POST['dbName'] == 'bills'){
					$keyword = strtolower($keyword);
                    $url = $cgapi.'bills?bill_id='.$keyword.'&chamber='.$chamber.'&apikey='.$apikey;
                    $rejson = file_get_contents($url);
                    //echo $rejson;
                    $dejson1 = json_decode($rejson)->results;
                    $t = array();
                    if(count($dejson1) == 0){
                        echo '<h2>The API return zero results for the request</h2>';
                        
                    }
                    else{
                    echo '<div id="rstable" style="text-align:center;width:100%"><table border="1px" cellpadding="5" cellspacing="0" align="center" width="70%"><tr><th>Bill ID</th><th>Short Title</th><th>Chamber</th><th>Details</th></tr>';
                    for($i=0;$i<count($dejson1);$i++){
                        $dejson = std_class_object_to_array($dejson1[$i]);
                        $bid = validstring($dejson['bill_id']);
                        $sht = validstring($dejson['short_title']);
                        $chamber = validstring($dejson['chamber']);
                        $sponsor = validstring($dejson['sponsor']['title'].' '.$dejson['sponsor']['first_name'].' '.$dejson['sponsor']['last_name']);
                        $intro = validstring($dejson['introduced_on']);
                        $lawd = validstring($dejson['last_version']['version_name'].','.$dejson['last_action_at']);
                        $billUrl = validstring($dejson['last_version']['urls']['pdf']);
                        
                        /*$t[] = validstring($bid);
                        $t[] = validstring($sht);
                        $t[] = validstring($sponsor);
                        $t[] = validstring($intro);
                        $t[] = validstring($lawd);
                        $t[] = validstring($billUrl);
                        $t[] = validstring($chamber);*/
                        $para = "'".$bid."',"."'".$sht."',"."'".$sponsor."',"."'".$intro."',"."'".$lawd."',"."'".$billUrl."'";
                        $vdt = '<a href="javascript:void(0);" onclick="showBillDetails('.$para.');">View Details</a>';
                        
                        echo '<tr><td>'.$bid.'</td><td>'.$sht.'</td><td>'.$chamber.'</td><td>'.$vdt.'</td></tr>';
                    }
                    echo '</table></div>';       
                    }            
                }
                else if($_POST['dbName'] == 'amendments'){
					$keyword = strtolower($keyword);
                    $url = $cgapi.'amendments?amendment_id='.$keyword.'&chamber='.$chamber.'&apikey='.$apikey;
                    
                    $rejson = file_get_contents($url);
                    //echo $rejson;
                    $dejson1 = json_decode($rejson)->results;
                    
                    if(count($dejson1) == 0){
                        echo '<h2>The API return zero results for the request</h2>';
                    
                    }
                    else{
                    echo '<div id="rstable" style="text-align:center;width:100%"><table border="1px" cellspacing="0" cellpadding="5"  align="center" width="70%"><tr><th>Amendment ID</th><th>Amendment Type</th><th>Chamber</th><th>Introduced on</th></tr>';
                    for($i=0;$i<count($dejson1);$i++){
                        $dejson = std_class_object_to_array($dejson1[$i]);
                        $bid = validstring($dejson['amendment_id']);
                        $sht = validstring($dejson['amendment_type']);
                        $chamber = validstring($dejson['chamber']);
                        $vdt = validstring($dejson['introduced_on']);
                        echo '<tr><td>'.$bid.'</td><td>'.$sht.'</td><td>'.$chamber.'</td><td>'.$vdt.'</td></tr>';
                    }
                    echo '</table></div>'; 
                    }                  
                }
            }
        ?>

        </center>

        
        <script type="text/javascript">
                function changeKw(){
                    var value = document.getElementById('database').value;
                    var kwId = document.getElementById('kwcontent');
                    var cvalue = document.getElementById('chamber');
                    
                    var nwcontent = "";
                    if(value == "slo")
                        nwcontent = "Keyword*";
                    else if(value == "legislators")
                        nwcontent = "State/Represetative";
                    else if(value == "committees")
                        nwcontent = "Committee ID*";  
                    else if(value == "bills")
                        nwcontent = "Bill ID*";
                    else if(value == "amendments")
                        nwcontent = "Amendment ID*";
                    kwId.innerText = nwcontent;
                    return;
                }
                function copyForm(){
                    var dv ='slo';
                    var dv1 = <?php 
                        if(isset($_POST['dbName'])) 
                            echo "'".$_POST['dbName']."';"; 
                        else
                            echo "'".'slo'."'";
                        ?>;
                    
                    document.getElementById('database').value = dv1;
                    
                    var idx = <?php
                        $r = 2; 
                        if(isset($_POST['chamber'])){
                            if($_POST['chamber'] == "Senate"){
                                $r=0;
                            }
                            else{
                                $r=1;
                            }
                        }
                        echo $r; 
                    ?>;
                    var rd = document.getElementsByName('chamber');
                    if(idx==0)
                        rd[0].checked = true;
                    else if(idx==1)
                        rd[1].checked = true;
                    changeKw();
                }
                function rsForm(){
                    document.getElementById('database').value = 'slo';
                    var cvalue = document.getElementsByName('chamber');
                    cvalue[0].checked = false;
                    cvalue[1].checked = false;
                    document.getElementById('keyword').value = '';
                    document.getElementsByName('chamber')[0].checked = 'true';
                    document.getElementById('kwcontent').innerText = 'Keyword*';
                    rs = document.getElementById('rstable');
                    if(rs!=null)
                        rs.remove();
                }
                function checkInput(){
                    var dvalue = document.getElementById('database').value;
                    var cvalue = document.getElementsByName('chamber');
                    var kwvalue = document.getElementById('keyword').value;
                    var kvalue = document.getElementById('kwcontent').textContent;
                    var info = "";
                    var flag = 0;
                    if(dvalue == "slo"){
                        info += "Congress database, ";
                        flag ++;
                    }   
                    if(cvalue[0].checked == false && cvalue[1].checked == false){
                        info += "Chamber, ";
                        flag ++;
                    }
                    if(kwvalue == ""){
                        info += kvalue;
                        flag ++;
                    }
                    if(flag!=0){
                        if(info[info.length-1] ==' '){
                            //alert(info[info.length-1]);
                            info = info.substring(0,info.length-2);
                        }
                        else if(info[info.length-1] =='*')
                            info = info.substring(0,info.length-1);   
                        //alert(info);
                        alert('Please enter the following missing information: ' + info);
                        return false;
                    }
                    else
                        return true;
                }
                function showDetails(value1,value2,name,term,website,office,fb,twi,fname){
                    
                    var imgurl = "https://theunitedstates.io/images/congress/225x275/"+value2+".jpg";
                    
                    //var idx = value1;
                    /*var url = value1;
                    var rstable = document.getElementById('rstable');
                    rstable.removeChild(rstable.firstChild);
                    
                    xmlhttp = new XMLHttpRequest();
                    
                    xmlhttp.open("GET",url,false);
                    xmlhttp.send();
                    if(xmlhttp.status==404){
                        print("<h2>The API return zero results for the request</h2>");
                        exit();
                    }
                    s = xmlhttp.responseText;
                    
                    var sj = eval('(' + s + ')');
                    dres = sj['results'][0];
                    fname = dres['first_name'] + ' ' + dres['last_name'];
                    name = dres['title'] + ' ' + dres['first_name'] + ' ' + dres['last_name'];
                    term = dres['term_end'];
                    website = dres['website'];
                    office = dres['office'];
                    fb = "https://www.facebook.com/" + dres['facebook_id'];
                    twi = "https://twitter.com/" + dres['twitter_id'];*/
                    /*name = <?php echo "'".$det[7*$idx+0]."'" ?>;
                    term = <?php echo "'".$det[7*$idx+1]."'" ?>;
                    website = <?php echo "'".$det[7*$idx+2]."'" ?>;
                    office = <?php echo "'".$det[7*$idx+3]."'" ?>;
                    fb1 = <?php echo "'".$det[7*$idx+4]."'" ?>;
                    fb = "https://www.facebook.com/" + fb1;
                    twi1 = <?php echo "'".$det[7*$idx+5]."'" ?>;
                    twi = "https://twitter.com/" + twi1;
                    fname = <?php echo "'".$det[7*$idx+6]."'" ?>;*/
                    pictd = "<div id='rstable2' style='width:100%;text-align:center;margin: 0 auto;'><center><table style='align:center;border:1px solid black' width='70%'><tr><td colspan='2' style='text-align:center;padding-left:0'><img src='"+imgurl+"'></td></tr>";
                    content = pictd + '<tr><td>Full Name</td><td>'+name+'</td></tr>';
                    content += '<tr><td>Term Ends on</td><td>'+term+'</td></tr>';
                    if(website == 'NA')
                        content += '<tr><td>Website</td><td>'+website+'</td></tr>';
                    else
                        content += '<tr><td>Website</td><td><a href="'+website+'" target="_blank">'+website+'</td></tr>';
                    content += '<tr><td>Office</td><td>'+office+'</td></tr>';
                    if(fb == 'NA')
                        content += '<tr><td>Facebook</td><td>'+fb+'</td></tr>';
                    else
                        content += '<tr><td>Facebook</td><td><a href="https://www.facebook.com/'+fb+'" target="_blank">'+fname+'</td></tr>';
                    //content += '<tr><td>Facebook</td><td><a href="'+fb+'" target="_blank">'+fname+'</td></tr>';
                    
                    if(twi == 'NA')
                        content += '<tr><td>Twitter</td><td>'+twi+'</td></tr>';
                    else
                        content += '<tr><td>Twitter</td><td><a href="https://twitter.com/'+twi+'" target="_blank">'+fname+'</td></tr>';
                    //content += '<tr><td>Twitter</td><td><a href="'+twi+'" target="_blank">'+fname+'</td></tr></table></center></div>';
                    rstable.innerHTML = content;
                    //alert('here');
                    
                }
                function showBillDetails(bid,bti,spo,ino,lawd,burl){
                    /*bid = <?php echo "'".$t[0]."'" ?>;
                    bti = <?php echo "'".$t[1]."'" ?>;
                    spo = <?php echo "'".$t[2]."'" ?>;
                    ino = <?php echo "'".$t[3]."'" ?>;
                    lawd = <?php echo "'".$t[4]."'" ?>;
                    burl = <?php echo "'".$t[5]."'" ?>;*/
					var t = bti;
                    if(bti =='NA')
                        bti = bid;
                    var rstable = document.getElementById('rstable');
                    rstable.removeChild(rstable.firstChild);
                    content = "<div id='rstable2' style='width:100%;text-align:center;margin: 0 auto;'><center><table style='align:center;border:1px solid black' width='70%'>";
                    content += "<tr><td>Bill ID</td><td>"+bid+"</td></tr>";
                    content += "<tr><td>Bill Title</td><td>"+t+"</td></tr>";
                    content += "<tr><td>Sponsor</td><td>"+spo+"</td></tr>";
                    content += "<tr><td>Introduced on</td><td>"+ino+"</td></tr>";
                    content += "<tr><td>Last action with date</td><td>"+lawd+"</td></tr>";
                    content += '<tr><td>Bill URL</td><td><a href="'+burl+'" target="_blank">'+bti+'</td></tr></table></center></div>';
                    rstable.innerHTML = content;
                }
        </script>
		<noscript>
    </BODY>
</HTML>