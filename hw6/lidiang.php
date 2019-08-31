<html>
	<head>
		<meta charset="UTF-8" http-equiv="Access-Control-Allow-Origin" content="*">
		<style>
			div#mainbox{
				width: 40%;
				margin: 0 auto;				 	
				height: 30%;
				background-color: #f3f3f3;
			}
			h2#header{
				margin-top: 10px;
				margin-bottom: 10px;
				text-align:center;
			}
			hr#line{
				height:1px;
				background-color:black;
			}
			p#mandatory_fields{
				text-align:left;
			}
			div#inputButton{
				margin-top: 1%;
				margin-left: 37%;
			}
			input.Button{
				-moz-border-radius:5px;
   				-webkit-border-radius:5px;
    			border-radius:5px; 
    			background-color: white;
    			border-color: grey;
			}
			div#Table{
				margin-top: 0.5%;
			}
			table#stockTable{
				border-collapse:collapse; 
				border-color:lightgrey;
				
			}
			td.first_column{
				text-align:left;
				background-color: #f3f3f3; 
				border-color: lightgrey;
			}
			td.second_column{
				text-align:center;
				background-color: #FbFbFb;
				border-color: lightgrey;
			}
			div#news_area{
				margin-top: 0.5%;
			}
			table#newsTable{
				border-collapse:collapse; 
				border-color:lightgrey;
			}
			a#news{
				text-decoration:none;
				color: blue;
			}
			a#news:hover{
				text-decoration:none;
				color: black;
			}
			a.link_chart{
				text-decoration:none;
				color:blue;
			}
			a.link_chart:hover{
				color:black;
			}
		</style>
		
		
	</head>
	<?php
			$json_string="";
			$companyname="";
			$urlname="";
			$apiurl="";
			$key="B1FXC27WA1HL6WAO";
		?>
	<body>
		
		<div id="mainbox">
			<form id="myform" method="post" onsubmit="return checksubmit();" action="<?php echo $_SERVER["PHP_SELF"];?>" >
				<fieldset>
        			<h2 id="header"> <i>Stock Search</i></h2><hr id="line"/>
        			Enter Stock Ticker Symbol:* <input type ="text" name ="company" id ="inputText" value="<?php if(isset($_POST['company'])) { echo htmlentities ($_POST['company']); }?>">
        			<div id="inputButton">
        				<input type ="submit"  name="Submit" value="search" class="Button" >
        				<input type ="button" name="Clear" value ="clear" class="Button" onclick="data_clear();"><br>
        			</div>
        		<p id="mandatory_fields"><i> *- Mandatory fields.</i></p>
    			</fieldset>
   			</form>
		</div>
		<div id="tablename" ></div>
		</body>
		<script type="text/javascript">
			var obj = document.getElementById('myform'); 
		</script>
		
		<script type="text/javascript">
	 	function checksubmit(){
	 		var obj = document.getElementById('myform'); 
	 		var input=document.getElementById('inputText').value;
	 		if(input==""){
	 			alert("Please enter a symbol");
	 			return false;
	 		}
	 	}
	 	function data_clear(){
	 		//var text_input = document.getElementById('inputText').value;
	 		document.getElementById('inputText').value="";
	 		var datatable = document.getElementById('stockTable');
	 		if(datatable){
	 			datatable.parentNode.removeChild(datatable);
	 		}
	 		var newTable = document.getElementById('newsTable');
            if(newTable){
	 			newTable.parentNode.removeChild(newTable);
	 		}
	 		var click_button = document.getElementById("news_area");
            if(click_button){
	 			click_button.parentNode.removeChild(click_button);
	 		}
	 		var graph = document.getElementById("volume_graph");
	 		if(graph){
	 			graph.parentNode.removeChild(graph);
	 		}
	 	}
	 	 	
	 	</script>
	
	

    <?php
    	$xml_to_json=array();
    	$price_and_volume=array();
    	$SMA = array();
    	$EMA = array();
    	$SlowK = array();$SlowD = array();	
    	$RSI = array();
    	$ADX = array();
    	$CCI = array();$time_stamp=array();
    	$Real_Middle_Band = array();$Real_Upper_Band = array();$Real_Lower_Band = array();
    	$MACD = array();$MACD_Hist = array();$MACD_Signal = array();
    	function get_json($xml_url){
			@$json = json_decode(json_encode(simplexml_load_string(file_get_contents($xml_url))),true);
			return $json;
		}
		if (isset($_POST["Submit"])) {
			$companyname = $_POST['company'];
			$urlname = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=";
			$apiurl = $urlname.$companyname."&apikey=".$key."&outputsize=full";
			@$fileContent = file_get_contents($apiurl) or die("Out of time, try again!");
			$jsonObj = json_decode($fileContent,true);
			
			if (count($jsonObj)>1) {
				echo "<div id='Table'>";
				echo '<table  id="stockTable" width="80%" cellpadding="0" align="center" border="2">';
			
				$close_array=array();
    			$volume_array=array();
				$My_array=array();				
				
				$xml_news_title=array();
				$xml_news_time=array();
				$xml_news_link=array();
				$xml_news_link_article=array();
				$xml_news_time_omit=array();
				foreach ($jsonObj as $key => $value) {
					$a=0;
					if($key=="Meta Data"){
						echo "<tr>";
						echo "<td width='35%' class='first_column'>Symbol</td>";
						echo "<td width='65%' class='second_column'>".$jsonObj['Meta Data']['2. Symbol']."</td>";
						echo "</tr>";
					}
					elseif ($key=="Time Series (Daily)") {
						foreach ($value as $Daily => $value_daily) {
							$time_stamp[]=$Daily;
                        	$close_array[] =  $value_daily['4. close']; 
                        	$volume_array[] = $value_daily['5. volume'];
                        	$a++; if($a==130) break;
							foreach ($value_daily as $item => $value_item) {
								$My_array[]=$value_item;
							}
						}
					}
				}
					
				$change=number_format(($My_array[3]-$close_array[1]),2);
				$percent=number_format(($change/$close_array[1]*100),2);
				$range=$My_array[2]."-".$My_array[1];
				echo "<tr>";
				echo "<td class='first_column'>Close</td>";
				echo "<td class='second_column'>".$My_array[3]."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='first_column'>Open</td>";
				echo "<td class='second_column'>".$My_array[0]."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='first_column'>Previous Close</td>";
				echo "<td class='second_column'>".$close_array[1]."</td>";
				echo "</tr>";
						
				if($change>0){
		    		echo "<tr>";
			    	echo "<td class='first_column'>Change</td>";
					echo "<td class='second_column'>".$change.'<img src="http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png" width="10px" height="12px">'."</td>";
					echo "</tr>";
				}
				else{
					echo "<tr>";
					echo "<td class='first_column'>Change</td>";
					echo "<td class='second_column'>".$change.'<img src="http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png" width="10px" height="12px">'."</td>";
					echo "</tr>";
				}
						
				if($percent>0){
					echo "<tr>";
					echo "<td class='first_column'>Change Percent</td>";
					echo "<td class='second_column'>".$percent."%".'<img src="http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png" width="10px" height="12px">'."</td>";
					echo "</tr>";
				}
				else{
					echo "<tr>";
					echo "<td class='first_column'>Change Percent</td>";
					echo "<td class='second_column'>".$percent."%".'<img src="http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png" width="10px" height="12px">'."</td>";
					echo "</tr>";
				}	
				echo "<tr>";
				echo "<td class='first_column'>Day’s Range</td>";
				echo "<td class='second_column'>".$range."</td>";
				echo "</tr>";	
				echo "<tr>";
				echo "<td class='first_column'>Volume</td>";
				echo "<td class='second_column'>".$My_array[4]."</td>";
				echo "</tr>";	
				echo "<tr>";
				echo "<td class='first_column'>Timestamp</td>";
				echo "<td class='second_column'>".$time_stamp[0]."</td>";
				echo "</tr>";	
				echo "<tr>";
				echo "<td class='first_column'>Indicators</td>";
				echo "<td class='second_column'>"."<a class='link_chart' href='javascript:volume_chart();'>Price</a>"
				."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."<a class='link_chart' href='javascript:loadJSON(\"SMA\",\"$companyname\");'>"."SMA</a>"
				."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."<a class='link_chart' href='javascript:loadJSON(\"EMA\",\"$companyname\");'>"."EMA</a>"
				."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."<a class='link_chart' href='javascript:loadJSON(\"STOCH\",\"$companyname\");'>"."STOCH</a>"
				."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."<a class='link_chart' href='javascript:loadJSON(\"RSI\",\"$companyname\");'>"."RSI</a>"
				."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."<a class='link_chart' href='javascript:loadJSON(\"ADX\",\"$companyname\");'>"."ADX</a>"
				."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."<a class='link_chart' href='javascript:loadJSON(\"CCI\",\"$companyname\");'>"."CCI</a>"
				."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."<a class='link_chart' href='javascript:loadJSON(\"BBANDS\",\"$companyname\");'>"."BBANDS</a>"
				."&nbsp"."&nbsp"."&nbsp"."&nbsp"."&nbsp"."<a class='link_chart' href='javascript:loadJSON(\"MACD\",\"$companyname\");'>"."MACD</a>"."</td>";
				echo "</tr>";
				echo "</table></div>";	
				echo "<center><div align='center' id='volume_graph'  style='height: 70%; width: 80%; margin-top: 0.5%;'>"; 
				echo "</div></center>";
				   
				 echo "<div align='center' id='news_area' >";	
				 echo "<button onclick='getProfile()' style='background-color:inherit; border:none; border-color:white;'>click to show stock news"."<br/>".'<img src="http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png" width="30px" height="15px">'."</button>";
				 echo "</div>";	
					
				$xml_url= "https://seekingalpha.com/api/sa/combined/".$_POST['company'].".xml";
					
				$json_from_xml=get_json($xml_url);	
				if($json_from_xml==null) echo '<script type="text/javascript"> document.getElementById("news_area").innerHTML="No news!";</script>';
				else{
				foreach ($json_from_xml as $key_firstloop => $value_firstloop){
					if($key_firstloop=="channel"){
						foreach($value_firstloop as $key_secondloop => $value_secondloop){
							if($key_secondloop=="item"){
								foreach($value_secondloop as $value_thirdloop){
									
									$xml_news_title[]=$value_thirdloop["title"];
									$xml_news_time[]=$value_thirdloop["pubDate"];
									$xml_news_link[]=$value_thirdloop["link"];
									
								}
							}
						}
					}
				}
			}
				foreach ($xml_news_link as $value_link) {
					if(strpos($value_link,'article')!=false){
						$xml_news_link_article[]=$value_link;
					}
				}
				foreach($xml_news_time as $value_time){
					$length=strlen($value_time);
					$value_time_new=substr($value_time, 0,$length-5);
					$xml_news_time_omit[]=$value_time_new;
				}
				$xml_to_json[]=$xml_news_title;
				$xml_to_json[]=$xml_news_time_omit;
				$xml_to_json[]=$xml_news_link_article;
				$price_and_volume[]=$volume_array;
				$price_and_volume[]=$close_array;
			}
			else{
				echo "<div id='Table'>";
				echo '<table  id="stockTable" width="60%" cellpadding="0" align="center" border="2">';
				echo "<tr>";
				echo "<td width='35%' class='first_column'>Error</td>";
				echo "<td width='65%' class='second_column'>Error:No record has been found, please enter a valid symbol</td>";
				echo "</tr>";
				echo "</table>";
			}
		}		
	?>
 	<script type="text/javascript"> 
 	var chart_parameters = [];
 	var url = "";
	function loadJSON(item,name_company) {
		if(item=="BBANDS")
			url = "https://www.alphavantage.co/query?function="+item+"&symbol="+name_company+"&interval=daily&time_period=5&series_type=close&apikey=B1FXC27WA1HL6WAO&outputsize=full&nbdevup=3&nbdevdn=3";
		else if(item == "MACD")
			url = "https://www.alphavantage.co/query?function="+item+"&symbol="+name_company+"&interval=daily&time_period=10&series_type=close&apikey=B1FXC27WA1HL6WAO&outputsize=full&slowkmatype=1&slowdmatype=1";
		else url = "https://www.alphavantage.co/query?function="+item+"&symbol="+name_company+"&interval=daily&time_period=10&series_type=close&apikey=B1FXC27WA1HL6WAO&outputsize=full";
  		var xhttp;
  		if (window.XMLHttpRequest)
 			{
 				xhttp=new XMLHttpRequest();
 			}
 			else
 			{
 				xhttp=new ActiveXObject("Microsoft.XMLHTTP");
 			}
  		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
      			 var jsonStr = this.responseText;
      			 if(item==="SMA"||item==="EMA"||item==="RSI"||item==="ADX"||item==="CCI"){
      			 	chart_parameters = get_Line_Data(jsonStr,item);
      			 	//console.log(chart_parameters); 
      			 	if(item === "SMA")   SMA_chart(chart_parameters);
      			 	else if(item === "EMA")   EMA_chart(chart_parameters);	
      			 	else if(item === "RSI")	  RSI_chart(chart_parameters);	 
      			 	else if(item === "ADX")	  ADX_chart(chart_parameters);
      			 	else CCI_chart(chart_parameters);
      			 }
      			  else if(item==="BBANDS"||item==="MACD"){
      			 	chart_parameters = get_Three_Line_Data(jsonStr,item);
      			 	if(item === "BBANDS")	BBANDS_chart(chart_parameters);
      			 	else MACD_chart(chart_parameters);
      			 } 
      			  else {
      			  	chart_parameters = get_Two_Line_Data(jsonStr,item);
      			  	STOCH_chart(chart_parameters);
      			  }
      			    			     			 
    		}
  		};
  		xhttp.open("GET", url, true);
  		xhttp.send();
  	}
	function get_Line_Data(json_string,indicator){
		var jsonObj= eval("("+json_string+")");
		var Obj_index =  "Technical Analysis: "+indicator;
		var value = Object.keys(jsonObj[Obj_index]);
		var Datas = [];
		for(var i=0; i<121; i++){
			Datas.push(parseFloat(jsonObj[Obj_index][value[i]][indicator]));
		}
		return Datas;
	}
	function get_Two_Line_Data(json_string,indicator){
		var jsonObj= eval("("+json_string+")");
		var Obj_index =  "Technical Analysis: "+indicator;
		var value = Object.keys(jsonObj[Obj_index]);
		var name = Object.keys(jsonObj[Obj_index][value[0]]).sort();
		var Datas1 = [];
		var Datas2 = [];
		var Datas = new Array(); Datas[0] = new Array();  Datas[1] = new Array();
		for(var i=0; i<121; i++){
			Datas1.push(parseFloat(jsonObj[Obj_index][value[i]][name[0]]));
			Datas2.push(parseFloat(jsonObj[Obj_index][value[i]][name[1]]));
		}
		Datas[0] = Datas1; Datas[1] = Datas2; 
		return Datas;
	}
	function get_Three_Line_Data(json_string,indicator){
		var jsonObj= eval("("+json_string+")");
		var Obj_index =  "Technical Analysis: "+indicator;
		var value = Object.keys(jsonObj[Obj_index]);
		var name = Object.keys(jsonObj[Obj_index][value[0]]).sort();
		var Datas1 = [];
		var Datas2 = [];
		var Datas3 = [];
		var Datas = new Array(); Datas[0] = new Array();  Datas[1] = new Array();  Datas[2] = new Array();
		for(var i=0; i<121; i++){
			Datas1.push(parseFloat(jsonObj[Obj_index][value[i]][name[0]]));
			Datas2.push(parseFloat(jsonObj[Obj_index][value[i]][name[1]]));
			Datas3.push(parseFloat(jsonObj[Obj_index][value[i]][name[2]]));
		}
		Datas[0] = Datas1; Datas[1] = Datas2; Datas[2] = Datas3;
		return Datas;
	}
	</script>

 
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script type="text/javascript">
		var price_graph=<?php echo json_encode($close_array);?>;
		var vol_graph = <?php echo json_encode($volume_array);?>;
		var first_graph_volume = [];
		var first_graph_volume_reverse = [];
		var first_graph_price = [];
		var num =0;
		
		for(var j=0;j<121;j++)
			first_graph_price.push(+price_graph[j]);
		var min_price = 0;
		var max_price = 0;
		var min=0;var max=0;var numline = 0;var price_interval = 0;
		min_price=Math.min.apply(null, first_graph_price);
		max_price=Math.max.apply(null, first_graph_price);
		if(max_price>=15){
			min_price_graph = min_price-10;
			max_price_graph = max_price+5;
			num = (max_price_graph-min_price_graph)/5;
			price_interval = 5;
		}
		else if(max_price<10&&max_price>=1.5){
			min_price_graph = min_price-1;
			max_price_graph = max_price+0.5;
			num = (max_price_graph-min_price_graph)/0.5;
			price_interval = 0.5;
		}
		else{
			min_price_graph = min_price-0.03;
			max_price_graph = max_price+0.03;
			num = (max_price_graph-min_price_graph)/0.03;
			price_interval = 0.03;
		}
		
		
		for(var i=0;i<121;i++)
			first_graph_volume.push(+vol_graph[i]);
		var max_volume = 0; var y_interval_volume = 0;var y_interval_max=0;var first_digit=0;var digit=0;var number=1;var index_point=0
		var max_vol=0; var vol_interval=0; var digit_before_point = 0;var digit_before_point1 = 0;
		min_volume=Math.min.apply(null, first_graph_volume);
		max_volume=Math.max.apply(null, first_graph_volume);
		first_digit = max_volume.toString().substr(0, 1);
		index_point = max_price_graph.toString().indexOf('.');
		index_point1 = min_price_graph.toString().indexOf('.');
		digit_before_point = max_price_graph.toString().substr(index_point-1,1);
		digit_before_point1 = min_price_graph.toString().substr(index_point1-1,1);
		digit = max_volume.toString().length-1;	
		for(var a=0; a<digit; a++){
			number = number*10;
		}	
		y_interval_volume = number*first_digit;
		// if((digit_before_point-digit_before_point1)>5)
		// 	numline = Math.floor(num)+1;
		// else if((digit_before_point<digit_before_point1)&&(digit_before_point+10-digit_before_point1)>5)
		// 	numline = Math.ceil(num)+1;
		// else numline = num;
		if((digit_before_point-digit_before_point1)>=5)
			numline = num;
		else if((digit_before_point-digit_before_point1)<5&&(digit_before_point-digit_before_point1)>0)
			numline = Math.floor(num)+1;
	    else if((digit_before_point1-digit_before_point)>5)
	    	numline = Math.floor(num)+1;
		else if ((digit_before_point1-digit_before_point)==5)
	    	numline = Math.floor(num)+1;
	    else if(digit_before_point1==digit_before_point)
	    	numline = num;
		else numline = Math.ceil(num)+1;
		y_interval_max = y_interval_volume*numline;
		var time_graph = <?php echo json_encode($time_stamp);?>;
		var month = []; var day = []; var date=[]; var first_graph_xAxis=[];
		for(var k=0;k<121;k++){
			month.push(time_graph[k].substr(5,2));
			day.push(time_graph[k].substr(8,2));
		}
		for(var l=0;l<month.length;l++){
			var x_axis = month[l]+"/"+day[l];
			first_graph_xAxis.push(x_axis);
		}
		var first_graph_time=time_graph[0];
		var first_graph_time_year = first_graph_time.substr(0,4);
		var first_graph_time_month = first_graph_time.substr(5,2);
		var first_graph_time_day = first_graph_time.substr(8,2);
		var company_name = <?php echo json_encode($_POST['company']); ?>;
		var first_graph_symbol = company_name;
		
		function volume_chart() {
			console.log(num);
			console.log(numline);
			console.log(max_price_graph);
			console.log(min_price_graph);
			console.log(digit_before_point);
			console.log(digit_before_point1);
			Highcharts.chart('volume_graph', {
				chart: {
            		borderColor: 'lightgrey',
            		borderWidth: 2,
            		type: 'line'
       			 },
				title: {
            		text: 'Stock Price('+first_graph_time_month+'/'+first_graph_time_day+'/'+first_graph_time_year+')'
       			},
       			subtitle: {
       				useHTML: true,
       				color: 'blue',
    				text: "<a target='_blank' style='color:blue;text-decoration:none;' onmouseover='this.style.cssText=\"color:black; text-decoration:none;\"' onmouseout='this.style.cssText=\"color:blue;text-decoration:none;\"' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>", 
       			},                          
       			exporting:{  
                    enabled: true
                },
       			legend:{
       				layout: 'vertical',
      				align: 'right',
      				verticalAlign: 'middle',
      				enabled: true,
     				borderWidth: 0,
     				itemStyle : {
        				'fontSize' : '10px'
   					}
       			},
       			
    			xAxis: {
        			categories: first_graph_xAxis,
        			reversed: true,
        			labels:{ 
        				rotation: -45 ,
        				step:5 
    				},
    				tickLength: 5,
    				style:{
    					fontSize: 3
    				}
    			},
    			yAxis: [{
       				title: { text: 'Volume' },
       				opposite: true,
       				alignTicks: false,
       				
       				tickInterval: y_interval_volume,
       				max: y_interval_max,
       				gridLineWidth: 0
   					}, {
        			title: { text: 'Stock Price' },
        			min: min_price_graph,
        			max: max_price_graph,
       				alignTicks: false,
       				tickInterval: price_interval,
    			}],
    			series: [{
        			type: 'area',
       				data: first_graph_price,
       				name: first_graph_symbol,      			
        			yAxis: 1,
        			zIndex: -1,    			
        			color: '#EF7F7E',
        			tooltip:{
       					valueDecimals:2
       				},
        			marker:{
        				enabled:true,
        				radius:1,
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	}, {
        			type: 'column',
       				data: first_graph_volume,
        			name: first_graph_symbol+' Volume',
        			color: 'white'
    			}]
			});
		}
		function SMA_chart(yData){
			var min_data=Math.min.apply(null, yData);
			var max_data=Math.max.apply(null, yData);
			var min_ydata = min_data-2.5;
			var max_ydata = max_data+2.5;
			Highcharts.chart('volume_graph', {
				chart: {
            		borderColor: 'lightgrey',
            		borderWidth: 2,
            		type:'spline'
       			 },
       			 title: {
            		text: 'Simple Moving Average (SMA)'
       			},
       			subtitle: {
       				color: 'blue',
       				useHTML:true,
    				text: "<a target='_blank' style='color:blue;text-decoration:none;' onmouseover='this.style.cssText=\"color:black; text-decoration:none;\"' onmouseout='this.style.cssText=\"color:blue;text-decoration:none;\"' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>", 
       			},      
       			legend:{
       				layout: 'vertical',
      				align: 'right',
      				verticalAlign: 'middle',
      				enabled: true,
     				borderWidth: 0,
     				itemStyle : {
        				'fontSize' : '10px'
   					}
       			},
       			xAxis: {
        			categories: first_graph_xAxis,
        			reversed: true,
        			labels:{ 
        				rotation: -45 ,
        				step:5 
    				},
    				tickLength: 5,
    				style:{
    					fontSize: 3
    				}
    			},
    			yAxis: {
        			title: { text: 'SMA' },
        			min: min_ydata,
        			max: max_ydata,
       				alignTicks: false,
       				tickInterval: 2.5,
    			},
    			series: [{
        			type: 'line',
       				data: yData,
       				name: first_graph_symbol,      			  			
        			color: '#EF7F7E',
        			tooltip:{
       					valueDecimals:2
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	}]
   			});
		}
		function EMA_chart(yData){
			var min_data=Math.min.apply(null, yData);
			var max_data=Math.max.apply(null, yData);
			var min_ydata = min_data-2.5;
			var max_ydata = max_data+2.5;
			//console.log(max_data);
			Highcharts.chart('volume_graph', {
				chart: {
            		borderColor: 'lightgrey',
            		borderWidth: 2,
            		type:'spline'
       			 },
       			 title: {
            		text: 'Expotential Moving Area (EMA)'
       			},
       			subtitle: {
       				color: 'blue',
       				useHTML:true,
    				text: "<a target='_blank' style='color:blue;text-decoration:none;' onmouseover='this.style.cssText=\"color:black; text-decoration:none;\"' onmouseout='this.style.cssText=\"color:blue;text-decoration:none;\"' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>", 
       			},      
       			legend:{
       				layout: 'vertical',
      				align: 'right',
      				verticalAlign: 'middle',
      				enabled: true,
     				borderWidth: 0,
     				itemStyle : {
        				'fontSize' : '10px'
   					}
       			},
       			xAxis: {
        			categories: first_graph_xAxis,
        			reversed: true,
        			labels:{ 
        				rotation: -45 ,
        				step:5 
    				},
    				tickLength: 5,
    				style:{
    					fontSize: 3
    				}
    			},
    			yAxis: {
        			title: { text: 'EMA' },
        			min: min_ydata,
        			max: max_ydata,
       				alignTicks: false,
       				tickInterval: 2.5,
    			},
    			series: [{
        			type: 'line',
       				data: yData,
       				name: first_graph_symbol,      			
        						
        			color: '#EF7F7E',
        			tooltip:{
       					valueDecimals:2
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	}]
   			});
		}
		function RSI_chart(yData){
			var min_data=Math.min.apply(null, yData);
			var max_data=Math.max.apply(null, yData);
			var min_ydata = min_data-10;
			var max_ydata = max_data+10;
			//console.log(max_data);
			Highcharts.chart('volume_graph', {
				chart: {
            		borderColor: 'lightgrey',
            		borderWidth: 2,
            		type:'spline'
       			 },
       			 title: {
            		text: 'Relative Strength Index (RSI)'
       			},
       			subtitle: {
       				color: 'blue',
       				useHTML:true,
    				text: "<a target='_blank' style='color:blue;text-decoration:none;' onmouseover='this.style.cssText=\"color:black; text-decoration:none;\"' onmouseout='this.style.cssText=\"color:blue;text-decoration:none;\"' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>", 
       			},      
       			legend:{
       				layout: 'vertical',
      				align: 'right',
      				verticalAlign: 'middle',
      				enabled: true,
     				borderWidth: 0,
     				itemStyle : {
        				'fontSize' : '10px'
   					}
       			},
       			xAxis: {
        			categories: first_graph_xAxis,
        			reversed: true,
        			labels:{ 
        				rotation: -45 ,
        				step:5 
    				},
    				tickLength: 5,
    				style:{
    					fontSize: 3
    				}
    			},
    			yAxis: {
        			title: { text: 'RSI' },
        			min: min_ydata,
        			max: max_ydata,
       				alignTicks: false,
       				tickInterval: 10,
    			},
    			series: [{
        			type: 'line',
       				data: yData,
       				name: first_graph_symbol,      			
     		
        			color: '#EF7F7E',
        			tooltip:{
       					valueDecimals:2
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	}]
   			});
		}
		function ADX_chart(yData){
			var min_data=Math.min.apply(null, yData);
			var max_data=Math.max.apply(null, yData);
			var min_ydata = min_data-10;
			var max_ydata = max_data+10;
			//console.log(max_data);
			Highcharts.chart('volume_graph', {
				chart: {
            		borderColor: 'lightgrey',
            		borderWidth: 2,
            		type:'spline'
       			 },
       			 title: {
            		text: 'Average Directional movement indeX (ADX)'
       			},
       			subtitle: {
       				color: 'blue',
       				useHTML:true,
    				text: "<a target='_blank' style='color:blue;text-decoration:none;' onmouseover='this.style.cssText=\"color:black; text-decoration:none;\"' onmouseout='this.style.cssText=\"color:blue;text-decoration:none;\"' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>", 
       			},      
       			legend:{
       				layout: 'vertical',
      				align: 'right',
      				verticalAlign: 'middle',
      				enabled: true,
     				borderWidth: 0,
     				itemStyle : {
        				'fontSize' : '10px'
   					}
       			},
       			xAxis: {
       				rotation: -45 ,
        			categories: first_graph_xAxis,
        			reversed: true,
        			labels:{ 
        				step:5 
    				},
    				tickLength: 5,
    				style:{
    					fontSize: 3
    				}
    			},
    			yAxis: {
        			title: { text: 'ADX' },
        			min: min_ydata,
        			max: max_ydata,
       				alignTicks: false,
       				tickInterval: 5,
    			},
    			series: [{
        			type: 'line',
       				data: yData,
       				name: first_graph_symbol,      			
        					
        			color: '#EF7F7E',
        			tooltip:{
       					valueDecimals:2
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	}]
   			});
		}
		function CCI_chart(yData){
			var min_data=Math.min.apply(null, yData);
			var max_data=Math.max.apply(null, yData);
			var min_ydata = min_data-100;
			var max_ydata = max_data+100;
			//console.log(max_data);
			Highcharts.chart('volume_graph', {
				chart: {
            		borderColor: 'lightgrey',
            		borderWidth: 2,
            		type:'spline'
       			 },
       			 title: {
            		text: 'Commodity Channel Index (CCI)'
       			},
       			subtitle: {
       				color: 'blue',
       				useHTML:true,
    				text: "<a target='_blank' style='color:blue;text-decoration:none;' onmouseover='this.style.cssText=\"color:black; text-decoration:none;\"' onmouseout='this.style.cssText=\"color:blue;text-decoration:none;\"' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>", 
       			},      
       			legend:{
       				layout: 'vertical',
      				align: 'right',
      				verticalAlign: 'middle',
      				enabled: true,
     				borderWidth: 0,
     				itemStyle : {
        				'fontSize' : '10px'
   					}
       			},
       			xAxis: {
        			categories: first_graph_xAxis,
        			reversed: true,
        			labels:{ 
        				rotation: -45 ,
        				step:5 
    				},
    				tickLength: 5,
    				style:{
    					fontSize: 3
    				}
    			},
    			yAxis: {
        			title: { text: 'CCI' },
        			min: min_ydata,
        			max: max_ydata,
       				alignTicks: false,
       				tickInterval: 100,
    			},
    			series: [{
        			type: 'line',
       				data: yData,
       				name: first_graph_symbol,      			
        					
        			color: '#EF7F7E',
        			tooltip:{
       					valueDecimals:2
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	}]
   			});
		}
		function BBANDS_chart(yData){
			var data_min = [];var data_max = [];
			var min_data1=Math.min.apply(null, yData[0]);
			var max_data1=Math.max.apply(null, yData[0]);
			var min_ydata1 = min_data1; data_min.push(min_ydata1);
			var max_ydata1 = max_data1; data_max.push(max_ydata1);
			var min_data2=Math.min.apply(null, yData[1]);
			var max_data2=Math.max.apply(null, yData[1]);
			var min_ydata2 = min_data2; data_min.push(min_ydata2);
			var max_ydata2 = max_data2; data_max.push(max_ydata2);
			var min_data3=Math.min.apply(null, yData[2]);
			var max_data3=Math.max.apply(null, yData[2]);
			var min_ydata3 = min_data3; data_min.push(min_ydata3);
			var max_ydata3 = max_data3; data_max.push(max_ydata3);
			var min_ydata = Math.min.apply(null, data_min);
			var max_ydata = Math.max.apply(null, data_max);
			//console.log(max_data);
			Highcharts.chart('volume_graph', {
				chart: {
            		borderColor: 'lightgrey',
            		borderWidth: 2,
            		type:'spline'
       			 },
       			 title: {
            		text: 'Bollinger Bands (BBANDS)'
       			},
       			subtitle: {
       				color: 'blue',
       				useHTML:true,
    				text: "<a target='_blank' style='color:blue;text-decoration:none;' onmouseover='this.style.cssText=\"color:black; text-decoration:none;\"' onmouseout='this.style.cssText=\"color:blue;text-decoration:none;\"' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>", 
       			},      
       			legend:{
       				layout: 'vertical',
      				align: 'right',
      				verticalAlign: 'middle',
      				enabled: true,
     				borderWidth: 0,
     				itemStyle : {
        				'fontSize' : '10px'
   					}
       			},
       			xAxis: {
        			categories: first_graph_xAxis,
        			reversed: true,
        			labels:{ 
        				rotation: -45 ,
        				step:5 
    				},
    				tickLength: 5,
    				style:{
    					fontSize: 3
    				}
    			},
    			yAxis: {
        			title: { text: 'BBANDS' },
        			min: min_ydata,
        			max: max_ydata,
       				alignTicks: false,
       				tickInterval: 5,
    			},
    			series: [{
        			type: 'line',
       				data: yData[0],
       				name: first_graph_symbol+' Real Lower Band',      			
        					
        			color: '#EF7F7E',
        			tooltip:{
       					valueDecimals:4
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	},{
        			type: 'line',
       				data: yData[1],
       				name: first_graph_symbol+' Real Middle Band',      			
        					
        			color: 'blue',
        			tooltip:{
       					valueDecimals:4
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	},{
        			type: 'line',
       				data: yData[2],
       				name: first_graph_symbol+' Real Upper Band',      			
        					
        			color: 'green',
        			tooltip:{
       					valueDecimals:4
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	}]
   			});
		}
		function MACD_chart(yData){
			var data_min = [];var data_max = [];
			var min_data1=Math.min.apply(null, yData[0]);
			var max_data1=Math.max.apply(null, yData[0]);
			var min_ydata1 = min_data1; data_min.push(min_ydata1);
			var max_ydata1 = max_data1; data_max.push(max_ydata1);
			var min_data2=Math.min.apply(null, yData[1]);
			var max_data2=Math.max.apply(null, yData[1]);
			var min_ydata2 = min_data2; data_min.push(min_ydata2);
			var max_ydata2 = max_data2; data_max.push(max_ydata2);
			var min_data3=Math.min.apply(null, yData[2]);
			var max_data3=Math.max.apply(null, yData[2]);
			var min_ydata3 = min_data3; data_min.push(min_ydata3);
			var max_ydata3 = max_data3; data_max.push(max_ydata3);
			var min_ydata = Math.min.apply(null, data_min);
			var max_ydata = Math.max.apply(null, data_max);
			//console.log(max_data);
			Highcharts.chart('volume_graph', {
				chart: {
            		borderColor: 'lightgrey',
            		borderWidth: 2,
            		type:'spline'
       			 },
       			 title: {
            		text: 'Moving Average Convergence/Divergence (MACD)'
       			},
       			subtitle: {
       				color: 'blue',
       				useHTML:true,
    				text: "<a target='_blank' style='color:blue;text-decoration:none;' onmouseover='this.style.cssText=\"color:black; text-decoration:none;\"' onmouseout='this.style.cssText=\"color:blue;text-decoration:none;\"' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>", 
       			},      
       			legend:{
       				layout: 'vertical',
      				align: 'right',
      				verticalAlign: 'middle',
      				enabled: true,
     				borderWidth: 0,
     				itemStyle : {
        				'fontSize' : '10px'
   					}
       			},
       			xAxis: {
        			categories: first_graph_xAxis,
        			reversed: true,
        			labels:{ 
        				rotation: -45 ,
        				step:5 
    				},
    				tickLength: 5,
    				style:{
    					fontSize: 3
    				}
    			},
    			yAxis: {
        			title: { text: 'MACD' },
        			min: min_ydata,
        			max: max_ydata,
       				alignTicks: false,
       				tickInterval: 1,
    			},
    			series: [{
        			type: 'line',
       				data: yData[0],
       				name: first_graph_symbol+' MACD',      			
        					
        			color: '#EF7F7E',
        			tooltip:{
       					valueDecimals:4
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	},{
        			type: 'line',
       				data: yData[1],
       				name: first_graph_symbol+' MACD_Hist',      			
        					
        			color: 'blue',
        			tooltip:{
       					valueDecimals:4
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	},{
        			type: 'line',
       				data: yData[2],
       				name: first_graph_symbol+' MACD_Signal',      			
        					
        			color: 'green',
        			tooltip:{
       					valueDecimals:4
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	}]
   			});
		}
		function STOCH_chart(yData){
			var data_min = [];var data_max = [];
			var min_data1=Math.min.apply(null, yData[0]);
			var max_data1=Math.max.apply(null, yData[0]);
			var min_ydata1 = min_data1; data_min.push(min_ydata1);
			var max_ydata1 = max_data1; data_max.push(max_ydata1);
			var min_data2=Math.min.apply(null, yData[1]);
			var max_data2=Math.max.apply(null, yData[1]);
			var min_ydata2 = min_data2; data_min.push(min_ydata2);
			var max_ydata2 = max_data2; data_max.push(max_ydata2);
			var min_ydata = Math.min.apply(null, data_min);
			var max_ydata = Math.max.apply(null, data_max);
			Highcharts.chart('volume_graph', {
				chart: {
            		borderColor: 'lightgrey',
            		borderWidth: 2,
            		type:'spline'
       			 },
       			 title: {
            		text: 'Stochastic Oscillator (STOCH)'
       			},
       			subtitle: {
       				color: 'blue',
       				useHTML:true,
    				text: "<a target='_blank' style='color:blue;text-decoration:none;' onmouseover='this.style.cssText=\"color:black; text-decoration:none;\"' onmouseout='this.style.cssText=\"color:blue;text-decoration:none;\"' href='https://www.alphavantage.co/'>Source: Alpha Vantage</a>", 
       			},      
       			legend:{
       				layout: 'vertical',
      				align: 'right',
      				verticalAlign: 'middle',
      				enabled: true,
     				borderWidth: 0,
     				itemStyle : {
        				'fontSize' : '10px'
   					}
       			},
       			xAxis: {
        			categories: first_graph_xAxis,
        			reversed: true,
        			labels:{ 
        				rotation: -45 ,
        				step:5 
    				},
    				tickLength: 5,
    				style:{
    					fontSize: 3
    				}
    			},
    			yAxis: {
        			title: { text: 'STOCH' },
        			min: min_ydata,
        			max: max_ydata,
       				alignTicks: false,
       				tickInterval: 10,
    			},
    			series: [{
        			type: 'line',
       				data: yData[0],
       				name: first_graph_symbol+' SlowD',      			
        					
        			color: '#EF7F7E',
        			tooltip:{
       					valueDecimals:4
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	},{
        			type: 'line',
       				data: yData[1],
       				name: first_graph_symbol+' SlowK',      			
        					
        			color: 'blue',
        			tooltip:{
       					valueDecimals:4
       				},
        			marker:{
        				enabled:true,
        				radius:2,
        				symbol: 'square',
        				states:{
        					hover:{
        						radius:5,
        					}
        				}
        			}
   			 	}]
   			});
		}
	</script>

	<?php 
		if (isset($_POST["Submit"])){
			if (count($jsonObj)>1){
				echo "<script type=text/javascript>volume_chart()</script>";
			}
		}
	?>
	<script type="text/javascript">	 		 	
	 	function getProfile() {
	 		var table_text="";
	 		var slist = <?php echo json_encode($xml_to_json);?>;
  			var json_obj = eval(slist);
	 		table_text += "<div id='hideNews' align='center' style='margin-top:0.5%'><button onclick='hideProfile()' style='background-color:white; border: none; border-color:white;'>click to hide stock news<br/>";	
	 		table_text += "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png' width='30px' height='15px'>";
	 		table_text += "</button>";
	 		table_text += "<div id='Table' align='center' style='margin-top:0.5%; margin-bottom:5%'><table  id='newsTable' width='80%' cellpadding='0'  border='2'>";  		
	 		for(var i=0;i<5;i++){
	 			table_text += "<tr><td class='first_column' width='100%'>";
	 			table_text += "<a id='news' target='_blank'href="+json_obj[2][i]+">"+json_obj[0][i]+"</a>"+"&nbsp"+"&nbsp"+"&nbsp"+"&nbsp"+"&nbsp"+"&nbsp"+"&nbsp"+"&nbsp"+"Publicated time:"+json_obj[1][i]+"</td></tr>";
	 		}
	 		table_text += "</table></div></div>"; 
	 		document.getElementById("news_area").innerHTML = table_text; 
	 	}
	 	function hideProfile(){
	 		var table_text_hide="";
	 		table_text_hide += "<div  align='center' id='tablename' style='margin-top:0.5%;'><button onclick='getProfile()' style='background-color:white; border: none; border-color:white;'>click to show stock news<br/>";	
	 		table_text_hide += "<img src='http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png' width='30px' height='15px'>";
	 		table_text_hide += "</button></div>";
	
	 		document.getElementById("news_area").innerHTML = table_text_hide; 
	 	}
	</script>
	


</html>