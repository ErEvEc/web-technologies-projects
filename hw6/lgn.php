<style>
	h1{
		font-style: italic;
		height: 20px;
		margin-top: 5px;
		text-align: center;
	}
	h4{
		font-style: italic;
		height: 20px;
		margin-top: 5px;
		text-align: left;
	}
	form{
		margin-left: 400px;
		border:1px solid rgb(220,220,220);
		width: 400px;
		display: inline-block;
		margin-top: 30px;
		padding-left: 10px;
		padding-right: 10px;
		background-color: rgb(245,245,245);
		padding-bottom: 20px;
	}
	#inputButton{
        margin-top: -13px;
        margin-left: 185px;
        margin-bottom: -13px;
     }
    input[type="submit"] {
        border-radius: 10px;
		width: 80px;
		height:30px;
		font-size: 12px;
        background-color: white;
        border:1px solid rgb(220,220,220);
     }
    input[type="button"] {
		border-radius: 10px;
		font-size:12px;
		width: 80px;
		height:30px;
		background-color: white;
		border:1px solid rgb(220,220,220);
    }
    input[type="text"] {
         width: 160px;
    }
a{
	text-decoration: underline;
	color:blue;
}
</style>

<script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">
	function show_news(){
		<?php
		error_reporting(0);
		$jsonstr = simplexml_load_file('https://seekingalpha.com/api/sa/combined/'.$_GET["symbol"].'.xml');
		$jsonout = json_encode($jsonstr);
		echo "var jsonobj = ".$jsonout.";";
		?>
		var news_l = jsonobj.channel.item;
	
		var news_list = new Array();
		for (var i = 0; i <news_l.length; i++) {
			if (news_l[i]["link"].indexOf("article")>0){
				//news_list.push(news_l[i]);
				news_list.push(news_l[i]);
			}
			
		}
		//sort article according to time
		news_list.sort(function(a,b){
			var t = new Date(a.pubDate);
			var tmp = new Date(b.pubDate);
			return tmp-t;
		});
		if (news_list.length>=5){
			
			html_text="<table border='1' style='margin-left:80px;margin-top:10px;background-color :rgb(245,245,245);'>"; 
		for (var i = 0; i < 5; i++) {
			
			html_text+="<tr><td style='min-width:950px;background-color:rgb(245,245,245);'>"+"<a href = "+news_list[i]["link"]+" target='_blank'>"+news_list[i]["title"]+ "</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Publicated Time: &nbsp;&nbsp;" + news_list[i]["pubDate"].substring(0,news_list[i]["pubDate"].length-5)+"</td></tr>";
			//html_text+="<td>"+ news_list[i]["pubDate"]+"</td></tr>";
		}
		html_text+="</table>";
		document.getElementById("show_news_table").innerHTML=html_text;
		html_button = '<div id = "show_news_button" style="margin-top:10px; margin-left:450px; width:200px; color:rgb(200,200,200);" onclick ="hide_news()">Click to hide stock news </br><img src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png" width="25px" height="22px" style="margin-left:60px;"></div>';
		document.getElementById("news_button").innerHTML=html_button;
	}
		else{
			html_text="<table border='1' style='margin-left:80px;margin-top:10px;background-color :rgb(245,245,245);'>"; 
				for (var i = 0; i < news_list.length; i++) {
			
			html_text+="<tr><td style='min-width:950px;background-color:rgb(245,245,245);'>"+"<a href = "+news_list[i]["link"]+" target='_blank'>"+news_list[i]["title"]+ "</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Publicated Time: &nbsp;&nbsp;" + news_list[i]["pubDate"].substring(0,news_list[i]["pubDate"].length-5)+"</td></tr>";
			//html_text+="<td>"+ news_list[i]["pubDate"]+"</td></tr>";
		}
		html_text+="</table>";
		document.getElementById("show_news_table").innerHTML=html_text;
		html_button = '<div id = "show_news_button" style="margin-top:10px; margin-left:450px; width:200px; color:rgb(200,200,200);" onclick ="hide_news()">Click to hide stock news </br><img src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png" width="25px" height="22px" style="margin-left:60px;"></div>';
		document.getElementById("news_button").innerHTML=html_button;
	}
		}
		
		
	
function hide_news(){
		ht = '<div id = "show_news_button" style="margin-top:10px; margin-left:450px; width:200px;color:rgb(200,200,200);" onclick ="show_news()">Click to show stock news </br><img src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png" width="25px" height="22px" style="margin-left:60px;"></div>';
		document.getElementById("news_button").innerHTML=ht;
		document.getElementById("show_news_table").innerHTML="";
}
function clearTest(){
	document.getElementById("symbol").value="";
	document.getElementById("body").removeChild(document.getElementById("content"));
}
</script>
<body id="body">

   <form class ="stockForm" method="get">
        <h1> Stock Search</h1><hr style="height:1px;border:none;background-color:black;" />
        Enter Stock Ticker Symbol:* <input type ="text" name ="symbol" id ="symbol" value="<?php echo isset($_GET['symbol']) ? $_GET['symbol'] :''?>"><br><br>
        <div id="inputButton">
        <input type ="submit"  name="Search" value="search" style ="margin-right:10px">
        <input type ="button" name="Clear" value ="clear" onclick="clearTest()"><br><br>
        </div>
		<h4>* - Mandatory fields.</h4>
    </form>
<div id = "content">

<?php if(isset($_GET["Search"])): ?>

  <?php
  if($_GET["symbol"] == ""){echo "<script LANGUAGE='javascript'>alert('Please enter a symbol!');</script>";} 
  else {
                $jsonRes = file_get_contents('https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol='.$_GET["symbol"].'&outputsize=full&apikey=9L1UZT1MNNMSY7T6');
                $jsonResponse = json_decode($jsonRes, true);
                echo '<table border="1px solid rgb(220,220,220)" id="infoTable" style="text-align: left; margin-left:80px">';
            	foreach ($jsonResponse as $key => $value) {
            		if ($key == "Error Message") {
                    echo '<tr>';
                    echo '<td style="font-weight: bold;  min-width:350px"  >' . 'Error' . '</td>';
                    echo '<td style="font-weight: bold;  min-width:600px" >' . 'This is not a valid symbol' . '</td>';
                    echo '</tr>';
                } elseif ($key == "Meta Data"){
                	
                		# code...
                		echo '<tr>';
                        echo '<td style="font-weight: bold; background-color:rgb(245,245,245);min-width: 350px; text-align: left ">' . 'Stock Ticker Symbol'. '</td>';
                        echo '<td style = "background-color: white; min-width:600px; text-align:center">' . $_GET["symbol"].'</td></tr>';
                        
                        $time_stamp = $value["3. Last Refreshed"];
                        $t = $time_stamp;
       
                }elseif ($key =="Time Series (Daily)"){
                
 
                	$time_list = array_keys($value);
                	$last_day = $time_list[0];
                	$previous_day = $time_list[1];
                	echo '<tr>';
                       echo '<td style="font-weight: bold; background-color:rgb(245,245,245); min-width: 350px; text-align: left ">' . 'Close'. '</td>';
                     $closeCurrent = $value[$last_day]["4. close"];
                        echo '<td style = "background-color: white; min-width:600px; text-align:center">' . $closeCurrent.'</td></tr>';
                    echo '<tr>';
                       echo '<td style="font-weight: bold; background-color:rgb(245,245,245);min-width: 350px; text-align: left ">' . 'Open'. '</td>';
                       $openCurrent = $value[$last_day]["1. open"];
                        echo '<td style = "background-color: white; min-width:600px; text-align:center">' . $openCurrent.'</td></tr>';
                        echo '<tr>';
                       echo '<td style="font-weight: bold; background-color:rgb(245,245,245);min-width: 350px; text-align: left ">' . 'Previous Close'. '</td>';
                       $previousClose = $value[$previous_day]["4. close"];
                        echo '<td style = "background-color: white; min-width:600px; text-align:center">' . $previousClose.'</td></tr>';
                        echo '<tr>';
                       echo '<td style="font-weight: bold; background-color:rgb(245,245,245);min-width: 350px; text-align: left ">' . 'Change'. '</td>';
                       $change = $closeCurrent - $previousClose;
                       if ($change >= 0) {
                       	# code...
                       	echo '<td style = "background-color: white; min-width:600px; text-align:center">' .number_format((float)$change, 2, '.', '').'<img src="http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png" width="10px" height="12px">'.'</td></tr>';
                       }else{
                       	echo '<td style = "background-color: white; min-width:600px; text-align:center">' .number_format((float)$change, 2, '.', '').'<img src = "http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png "width="10px" height="12px">'.'</td></tr>';
                       }
                        
                          echo '<td style="font-weight: bold; background-color:rgb(245,245,245);min-width: 350px; text-align: left ">' . 'Change Percent'. '</td>';
                          $changePercent = (float)($change*100/$previousClose);
                          if ($changePercent >= 0){
                          	 echo '<td style = "background-color: white; min-width:600px; text-align:center">' . number_format((float)$changePercent, 2, '.', '').'%'.'<img src="http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png" width="10px" height="12px">'.'</td></tr>';
                          	}else{
                          		 echo '<td style = "background-color: white; min-width:600px; text-align:center">' . number_format((float)$changePercent, 2, '.', '').'%'.'<img src = "http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png "width="10px" height="12px">'.'</td></tr>';
                          		}
                       
                          echo '<td style="font-weight: bold; background-color:rgb(245,245,245);min-width: 350px; text-align: left ">' . "Day's Range". '</td>';
                          $low = $value[$last_day]["3. low"];
                          $high = $value[$last_day]["2. high"];
                        echo '<td style = "background-color: white; min-width:600px; text-align:center">' . $low.'-'.$high.'</td></tr>';
                          echo '<td style="font-weight: bold; background-color:rgb(245,245,245);min-width: 350px; text-align: left ">' . 'Volume'. '</td>';
                          $volume = number_format($value[$last_day]["5. volume"]);
                        echo '<td style = "background-color: white; min-width:600px; text-align:center">' . $volume.'</td></tr>';
                          echo '<td style="font-weight: bold; background-color:rgb(245,245,245);min-width: 350px; text-align: left ">' . 'Timestamp'. '</td>';
                          
                        echo '<td style = "background-color: white; min-width:600px; text-align:center">' . $time_stamp .'</td></tr>';
                          echo '<td style="font-weight: bold; background-color:rgb(245,245,245);min-width: 350px; text-align: left ">' . 'Indicators'. '</td>';
                        echo '<td style = "background-color: white; min-width:600px; text-align:center">' . '<a id ="price" >Price</a> '.'&nbsp;&nbsp;&nbsp;'.'<a id ="sma">SMA</a>'.'&nbsp;&nbsp;&nbsp;'.'<a id ="ema">EMA</a>'.'&nbsp;&nbsp;&nbsp;'.'<a id = "stoch">STOCH</a>'.'&nbsp;&nbsp;&nbsp;'.'<a id = "rsi">RSI</a>'.'&nbsp;&nbsp;&nbsp;'.'<a id = "adx">ADX</a>'.'&nbsp;&nbsp;&nbsp;'.'<a id = "cci">CCI</a>'.'&nbsp;&nbsp;&nbsp;'.'<a id = "bbands">BBANDS</a>'.'&nbsp;&nbsp;&nbsp;'.'<a id = "macd">MACD</a>'.'</td></tr></table>';
                        echo '<div id = "aa"></div>';
                        $daylist = $pricelist=$volumelist='[';
                            
                            for($i=0;$i<132;$i++){
                            	date_default_timezone_set('America/New_York');
                                $daylist = $daylist."'".date('m/d',strtotime($time_list[$i]))."',";
                                $pricelist = $pricelist.$value[$time_list[$i]]['4. close'].",";
                                $volumelist = $volumelist.$value[$time_list[$i]]['5. volume'].",";
                                
                            }
                            $daylist = $daylist."]";
                            $pricelist=$pricelist."]";
                            $volumelist=$volumelist."]";
                            echo "<div id='charts' style='min-width: 250px; height: 500px; max-width:1100px; margin: 0 auto'></div>
                            <script>
                            Highcharts.chart('charts', {
                                title: {
                                    text: 'Stock Price(".date('m/d/Y',strtotime($time_stamp)).")'
                                },
                                subtitle: {
                                    text: '<a href=\"https://www.alphavantage.co/\">Source: Alpha Vantage</a>'
                                },
                                xAxis: [{
                                    categories:".$daylist.",
                                    reversed: true,
                                    		tickInterval: 5,
        							showLastLabel: true,
                                }],
                                yAxis: [{
  
                                    title: {
                                        text: 'Stock Price'
                                    },
                                    tickAmount: 8,
                                    tickInterval:5, 
                                },
                                {
                                	 
                                    title: {
                                        text: 'Volume'
                                    },
                                    tickAmount: 8,
                                    tickInterval: 50000000,
                                    opposite:true,
                                },],
                                legend: {
                                            layout:'verticle',
                                            align: 'right',
                        				verticalAlign: 'middle',
                                        },
                                        
                                        plotOptions: {
                                          area: {
                            fillColor: 'rgb(242,137,134)',
                            marker: {
                                enabled: false,
                                radius: 2,
                                fillColor: 'rgb(233,28,23)',
                            },
                            lineWidth: 1,
                            lineColor: 'rgb(233,28,23)',
                            threshold: null
                        }
                                        },
                                series: [{
                                    type:'area',
                                    yAxis: 0,
                                    color:'rgb(242,137,134)',
                                    name: '".$_GET["symbol"]."',
                                    data: ".$pricelist."
                                },
                                 {
        name: '".$_GET["symbol"]." Volume',
        type: 'column',
        yAxis: 1,
        color: 'rgb(255,255,255)',
        data: ".$volumelist."
    }
                            ],
                                
                            });
                            </script>";
                
 echo '<div id = news_button><div id = "show_news_button" style="margin-top:10px; margin-left:450px; width:200px;color:rgb(200,200,200);" onclick ="show_news()">Click to show stock news </br><img src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png" width="25px" height="22px" style="margin-left:60px;"></div></div>';
echo '<div id = "show_news_table"></div>';
               }
         
            	
}
        }
	?>
<?php endif; ?>


</div>

<script type="text/javascript">
	
if (document.getElementById("sma")!= null){
	document.getElementById("sma").addEventListener("click",show_sma); 
}
function show_sma(){
	var a = '<?php echo $_GET["symbol"] ?>';
	
	//alert(b);
	var url = 'https://www.alphavantage.co/query?function=SMA&symbol='+a +'&interval=daily&time_period=10&series_type=close&apikey=9L1UZT1MNNMSY7T6';
    var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
    var o = JSON.parse(this.responseText);
    pl = o["Meta Data"]["2: Indicator"];
	
var days = new Array();
var times = new Array();
var datas = new Array();
for (var k in o["Technical Analysis: SMA"]) {
	days.push(k);
}
for(var i = 0; i<132;i++){
	var x = "";
	x = days[i].substring(5,7)+"/"+days[i].substring(8,10);
	//x.replace(/-/,"/");
	times.push(x);
	datas.push(parseFloat(o["Technical Analysis: SMA"][days[i]]["SMA"]));
}
Highcharts.chart('charts', {
    title: {
        text: pl
    },
    subtitle: {
        text: '<a href="https://www.alphavantage.co/">Source: Alpha Vantage</a>'
    },
    xAxis: {
        categories:times,
        reversed: true,
        		tickInterval: 5,
        showLastLabel: true,
    },
    yAxis: {
        title: {
            text: 'SMA'
        },
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    plotOptions: {
        line:{
            marker: {//dot
                enabled:true,
                radius: 2,
                symbol:  'square'
            },
          },  
    },
    series: [{
        name: a,
        data: datas,
        color: 'rgb(233,33,0)',
    }],
});
    }
};
      xmlhttp.open("GET",url,true);
      xmlhttp.send();  
 
}
if (document.getElementById("ema")!= null){
	document.getElementById("ema").addEventListener("click",show_ema); 
}
function show_ema(){
	var a = '<?php echo $_GET["symbol"] ?>';
	
	//alert(b);
	var url = 'https://www.alphavantage.co/query?function=EMA&symbol='+a +'&interval=daily&time_period=10&series_type=close&apikey=9L1UZT1MNNMSY7T6';
    var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
    var o = JSON.parse(this.responseText);
    pl = o["Meta Data"]["2: Indicator"];
	
var days = new Array();
var times = new Array();
var datas = new Array();
for (var k in o["Technical Analysis: EMA"]) {
	days.push(k);
}
for(var i = 0; i<132;i++){
	var x = "";
	x = days[i].substring(5,7)+"/"+days[i].substring(8,10);
	//x.replace(/-/,"/");
	times.push(x);
	datas.push(parseFloat(o["Technical Analysis: EMA"][days[i]]["EMA"]));
}
Highcharts.chart('charts', {
    title: {
        text: pl
    },
    subtitle: {
        text: '<a href="https://www.alphavantage.co/">Source: Alpha Vantage</a>'
    },
    xAxis: {
        categories:times,
        reversed: true,
        		tickInterval: 5,
        showLastLabel: true,
    },
    yAxis: {
        title: {
            text: 'EMA'
        },
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    plotOptions: {
        line:{
            marker: {//dot
                enabled:true,
                radius: 2,
                symbol:  'square'
            },
          },  
    },
    series: [{
        name: a,
        data: datas,
        color: 'rgb(233,33,0)',
    }],
});
    }
};
      xmlhttp.open("GET",url,true);
      xmlhttp.send();  
 
}
if (document.getElementById("rsi")!= null){
document.getElementById("rsi").addEventListener("click",show_rsi); }
function show_rsi(){
	var a = '<?php echo $_GET["symbol"] ?>';
	
	//alert(b);
	var url = 'https://www.alphavantage.co/query?function=RSI&symbol='+a +'&interval=daily&time_period=10&series_type=close&apikey=9L1UZT1MNNMSY7T6';
    var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
    var o = JSON.parse(this.responseText);
    pl = o["Meta Data"]["2: Indicator"];
	
var days = new Array();
var times = new Array();
var datas = new Array();
for (var k in o["Technical Analysis: RSI"]) {
	days.push(k);
}
for(var i = 0; i<132;i++){
	var x = "";
	x = days[i].substring(5,7)+"/"+days[i].substring(8,10);
	//x.replace(/-/,"/");
	times.push(x);
	datas.push(parseFloat(o["Technical Analysis: RSI"][days[i]]["RSI"]));
}
Highcharts.chart('charts', {
    title: {
        text: pl
    },
    subtitle: {
        text: '<a href="https://www.alphavantage.co/">Source: Alpha Vantage</a>'
    },
    xAxis: {
        categories:times,
        reversed: true,
        		tickInterval: 5,
        showLastLabel: true,
    },
    yAxis: {
        title: {
            text: 'RSI'
        },
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    plotOptions: {
        line:{
            marker: {//dot
                enabled:true,
                radius: 2,
                symbol:  'square'
            },
          },  
    },
    series: [{
        name: a,
        data: datas,
        color: 'rgb(233,33,0)',
    }],
});
    }
};
      xmlhttp.open("GET",url,true);
      xmlhttp.send();  
 
}
if (document.getElementById("cci")!= null){
document.getElementById("cci").addEventListener("click",show_cci); }
function show_cci(){
	var a = '<?php echo $_GET["symbol"] ?>';
	
	//alert(b);
	var url = 'https://www.alphavantage.co/query?function=CCI&symbol='+a +'&interval=daily&time_period=10&series_type=close&apikey=9L1UZT1MNNMSY7T6';
    var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
    var o = JSON.parse(this.responseText);
    pl = o["Meta Data"]["2: Indicator"];
	
var days = new Array();
var times = new Array();
var datas = new Array();
for (var k in o["Technical Analysis: CCI"]) {
	days.push(k);
}
for(var i = 0; i<132;i++){
	var x = "";
	x = days[i].substring(5,7)+"/"+days[i].substring(8,10);
	//x.replace(/-/,"/");
	times.push(x);
	datas.push(parseFloat(o["Technical Analysis: CCI"][days[i]]["CCI"]));
}
Highcharts.chart('charts', {
    title: {
        text: pl
    },
    subtitle: {
        text: '<a href="https://www.alphavantage.co/">Source: Alpha Vantage</a>'
    },
    xAxis: {
        categories:times,
        reversed: true,
        		tickInterval: 5,
        showLastLabel: true,
    },
    yAxis: {
        title: {
            text: 'CCI'
        },
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    plotOptions: {
        line:{
            marker: {//dot
                enabled:true,
                radius: 2,
                symbol:  'square'
            },
          },  
    },
    series: [{
        name: a,
        data: datas,
        color: 'rgb(233,33,0)',
    }],
});
    }
};
      xmlhttp.open("GET",url,true);
      xmlhttp.send();  
 
}
if (document.getElementById("adx")!= null){
document.getElementById("adx").addEventListener("click",show_adx); }
function show_adx(){
	var a = '<?php echo $_GET["symbol"] ?>';
	
	//alert(b);
	var url = 'https://www.alphavantage.co/query?function=ADX&symbol='+a +'&interval=daily&time_period=10&series_type=close&apikey=9L1UZT1MNNMSY7T6';
    var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
    var o = JSON.parse(this.responseText);
    pl = o["Meta Data"]["2: Indicator"];
	
var days = new Array();
var times = new Array();
var datas = new Array();
for (var k in o["Technical Analysis: ADX"]) {
	days.push(k);
}
for(var i = 0; i<132;i++){
	var x = "";
	x = days[i].substring(5,7)+"/"+days[i].substring(8,10);
	//x.replace(/-/,"/");
	times.push(x);
	datas.push(parseFloat(o["Technical Analysis: ADX"][days[i]]["ADX"]));
}
Highcharts.chart('charts', {
    title: {
        text: pl
    },
    subtitle: {
        text: '<a href="https://www.alphavantage.co/">Source: Alpha Vantage</a>'
    },
    xAxis: {
        categories:times,
        reversed: true,
        		tickInterval: 5,
        showLastLabel: true,
    },
    yAxis: {
        title: {
            text: 'ADX'
        },
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    plotOptions: {
        line:{
            marker: {//dot
                enabled:true,
                radius: 2,
                symbol:  'square'
            },
          },  
    },
    series: [{
        name: a,
        data: datas,
        color: 'rgb(233,33,0)',
    }],
});
    }
};
      xmlhttp.open("GET",url,true);
      xmlhttp.send();  
 
}
if (document.getElementById("stoch")!= null){
document.getElementById("stoch").addEventListener("click",show_stoch); }
function show_stoch(){
	var a = '<?php echo $_GET["symbol"] ?>';
	
	//alert(b);
	var url = 'https://www.alphavantage.co/query?function=STOCH&symbol='+a +'&interval=daily&time_period=10&series_type=close&apikey=9L1UZT1MNNMSY7T6';
    var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
    var o = JSON.parse(this.responseText);
    pl = "Stochatic Oscillator (STOCH)";
	
var days = new Array();
var times = new Array();
var datasD = new Array();
var datasK = new Array();
for (var k in o["Technical Analysis: STOCH"]) {
	days.push(k);
}
for(var i = 0; i<132;i++){
	var x = "";
	x = days[i].substring(5,7)+"/"+days[i].substring(8,10);
	//x.replace(/-/,"/");
	times.push(x);
	datasD.push(parseFloat(o["Technical Analysis: STOCH"][days[i]]["SlowD"]));
	datasK.push(parseFloat(o["Technical Analysis: STOCH"][days[i]]["SlowK"]));
}
Highcharts.chart('charts', {
    title: {
        text: pl
    },
    subtitle: {
        text: '<a href="https://www.alphavantage.co/">Source: Alpha Vantage</a>'
    },
    xAxis: {
        categories:times,
        reversed: true,
        		tickInterval: 5,
        showLastLabel: true,
    },
    yAxis: {
        title: {
            text: 'STOCH'
        },
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    plotOptions: {
        line:{
            marker: {//dot
                enabled:true,
                radius: 2,
                symbol:  'square'
            },
          },  
    },
    series: [{
        name: a+' SlowK',
        data: datasD,
        color: 'rgb(233,33,0)',
    },
{
        name: a+' SlowD',
        data: datasK,
        
    },
    ],
});
    }
};
      xmlhttp.open("GET",url,true);
      xmlhttp.send();  
 
}
if (document.getElementById("bbands")!= null){
document.getElementById("bbands").addEventListener("click",show_bbands); }
function show_bbands(){
	var a = '<?php echo $_GET["symbol"] ?>';
	
	//alert(b);
	var url = 'https://www.alphavantage.co/query?function=BBANDS&symbol='+a +'&interval=daily&time_period=10&series_type=close&apikey=9L1UZT1MNNMSY7T6';
    var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
    var o = JSON.parse(this.responseText);
    pl = "Bollinger Bands (BBANDS)";
	
var days = new Array();
var times = new Array();
var datasU = new Array();
var datasL = new Array();
var datasM = new Array();
for (var k in o["Technical Analysis: BBANDS"]) {
	days.push(k);
}
for(var i = 0; i<132;i++){
	var x = "";
	x = days[i].substring(5,7)+"/"+days[i].substring(8,10);
	//x.replace(/-/,"/");
	times.push(x);
	datasU.push(parseFloat(o["Technical Analysis: BBANDS"][days[i]]["Real Upper Band"]));
	datasL.push(parseFloat(o["Technical Analysis: BBANDS"][days[i]]["Real Lower Band"]));
	datasM.push(parseFloat(o["Technical Analysis: BBANDS"][days[i]]["Real Middle Band"]));
}
Highcharts.chart('charts', {
    title: {
        text: pl
    },
    subtitle: {
        text: '<a href="https://www.alphavantage.co/">Source: Alpha Vantage</a>'
    },
    xAxis: {
        categories:times,
        reversed: true,
        tickInterval: 5,
        showLastLabel: true,
    },
    yAxis: {
        title: {
            text: 'BBANDS'
        },
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    plotOptions: {
        line:{
            marker: {//dot
                enabled:true,
                radius: 2,
                symbol:  'square'
            },
          },  
    },
    series: [{
        name: a+' Real Upper Band',
        data: datasU,
        color: 'rgb(233,33,0)',
    },
{
        name: a+' Real Lower Band',
        data: datasL,
        
    },
    {
        name: a+' Real Middle Band',
        data: datasM,
        
    },
    ],
});
    }
};
      xmlhttp.open("GET",url,true);
      xmlhttp.send();  
 
}
if (document.getElementById("macd")!= null){
document.getElementById("macd").addEventListener("click",show_macd); }
function show_macd(){
	var a = '<?php echo $_GET["symbol"] ?>';
	
	//alert(b);
	var url = 'https://www.alphavantage.co/query?function=MACD&symbol='+a +'&interval=daily&time_period=10&series_type=close&apikey=9L1UZT1MNNMSY7T6';
    var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
    var o = JSON.parse(this.responseText);
    pl = "Moving Average Convergence/Divergence (MACD)";
	
var days = new Array();
var times = new Array();
var datasS = new Array();
var datasH = new Array();
var datas = new Array();
for (var k in o["Technical Analysis: MACD"]) {
	days.push(k);
}
for(var i = 0; i<132;i++){
	var x = "";
	x = days[i].substring(5,7)+"/"+days[i].substring(8,10);
	//x.replace(/-/,"/");
	times.push(x);
	datasS.push(parseFloat(o["Technical Analysis: MACD"][days[i]]["MACD_Signal"]));
	datasH.push(parseFloat(o["Technical Analysis: MACD"][days[i]]["MACD_Hist"]));
	datas.push(parseFloat(o["Technical Analysis: MACD"][days[i]]["MACD"]));
}
Highcharts.chart('charts', {
    title: {
        text: pl
    },
    subtitle: {
        text: '<a href="https://www.alphavantage.co/">Source: Alpha Vantage</a>'
    },
    xAxis: {
        categories:times,
        reversed: true,
		tickInterval: 5,
        showLastLabel: true,
    },
    yAxis: {
        title: {
            text: 'MACD'
        }, 
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
    plotOptions: {
        line:{
            marker: {//dot
                enabled:true,
                radius: 2,
                symbol:  'square'
            },
          },  
    },
    series: [{
        name: a+' MACD_Signal',
        data: datasS,
        color: 'rgb(233,33,0)',
    },
{
        name: a+' MACD_Hist',
        data: datasH,
        
    },
    {
        name: a+' MACD',
        data: datas,
        
    },
    ],
});
    }
};
      xmlhttp.open("GET",url,true);
      xmlhttp.send();  
 
}
if (document.getElementById("price")!= null){
document.getElementById("price").addEventListener("click",show_price); }
function show_price(){
	var a = '<?php echo $_GET["symbol"] ?>';
	
	//alert(a);
	var jr =<?php echo $jsonResponse ?>;
var times = <?php echo $daylist ?>;
var datasV = <?php echo $volumelist?>;
var datas = <?php echo $pricelist ?>;
var timest = '<?php echo $t ?>';
var st = timest.substring(5,7)+'/'+timest.substring(8,10)+'/'+timest.substring(0,4);
Highcharts.chart('charts', {
    title: {
        text: "Stock Price ("+st+")"
    },
    subtitle: {
        text: '<a href="https://www.alphavantage.co/">Source: Alpha Vantage</a>'
    },
    xAxis: {
        categories:times,
        reversed: true,
		tickInterval: 5,
        showLastLabel: true,
    },
yAxis: [{
  
                                    title: {
                                        text: 'Stock Price'
                                    },
                                    tickAmount: 8,
                                    tickInterval:5,
                                    type:'linear' 
                                },
                                {
                                	 
                                    title: {
                                        text: 'Volume'
                                    },
                                    tickAmount: 8,
                                    tickInterval:50000000,
                                    opposite:true,
                                },],
                                legend: {
                                            layout:'verticle',
                                            align: 'right',
                        				verticalAlign: 'middle',
                                        },
                                        
                                        plotOptions: {
                                          area: {
                            fillColor: 'rgb(242,137,134)',
                            marker: {
                                enabled: false,
                                radius: 2,
                                fillColor: 'rgb(233,28,23)',
                            },
                            lineWidth: 1,
                            lineColor: 'rgb(233,28,23)',
                            threshold: null
                        }
                                        },
                                series: [{
                                    type:'area',
                                    yAxis: 0,
                                    color:'rgb(242,137,134)',
                                    name: a,
                                    data: datas
                                },
                                 {type:'column',
                                    yAxis: 1,
                                    color:'rgb(255,255,255)',
                                    name: a +' Volume',
                                    data: datasV
    }
                            ],
                                
});
    
};
</script>

</body>