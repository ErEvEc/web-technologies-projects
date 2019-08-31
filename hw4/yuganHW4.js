

function viewJSON(what){

var URL =what.URL.value;

function loadJSON(url) {
    if (window.XMLHttpRequest) {
        xmlhttp=new XMLHttpRequest(); 
    } else {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
    }

    xmlhttp.open("GET",url,false);
    xmlhttp.send();

    jsonDoc=JSON.parse(xmlhttp.responseText); //#1step
    return jsonDoc;
}
jsonObj = loadJSON(URL);

if (window.ActiveXObject){
    if (jsonObj.parseError.errorCode != 0) {
        var myErr = jsonObj.parseError;
        generateError(jsonObj);
        hWin = window.open("", "Error", "height=300,width=340"); 
        hWin.document.write(html_text);
    } else {
        generateHTML(jsonObj);
        hWin = window.open("", "Assignment4", "height=950,width=1060"); 
        hWin.document.write(html_text); 
    }
}else {
    jsonObj.onload=generateHTML(jsonObj);
    hWin = window.open("", "Assignment4", "height=4000,width=1060"); 
    hWin.document.write(html_text); 
} 
hWin.document.close();

function generateHTML(jsonObj) {

    root=jsonObj.DocumentElement;
    html_text="<html><head><title>JSON Parse Result</title></head><body>";
    html_text+="<table border='2'>";

    head = jsonObj.Mainline.Table.Header.Data;
    movies=jsonObj.Mainline.Table.Row; 

    html_text+="<thead><tr>"; 

    for(i=0;i<head.length;i++) {
        header=head[i];
        html_text+="<th>"+header+"</th>";
    }
    html_text+="</tr></thead>";
    
    html_text+="<tbody>";
    for(i=0;i<movies.length;i++) {
        movieNodeList = movies[i];
        html_text+="<tr>";
        movie_keys = Object.keys(movieNodeList);
        for(j=0;j<movie_keys.length;j++){
            prop=movie_keys[j];
            if(prop=="Hubs"){
                
                html_text+="<td><li><b>"+ movieNodeList[prop].Hub[0]+"</b></li><li>"+ movieNodeList[prop].Hub[1]+ "</li><td>";

            }else if(prop=="HomePage"){
                
                html_text+="<td><a href="+ movieNodeList[prop] +">" +movieNodeList[prop] +"</a></td>";

            }else if(prop=="Logo"){
                html_text+="<td><img src='"+ movieNodeList[prop] + "' height='"+133+"'></td>";
            }else if(prop!=undefined && prop!=null && prop!=""){
                html_text+="<td>"+ movieNodeList[prop] +"</td>";
            }

        }
        
        html_text+="</tr>";
    }

    html_text+="</tbody>"; 
    html_text+="</table>";
    html_text+="</body></html>"; 
}
}