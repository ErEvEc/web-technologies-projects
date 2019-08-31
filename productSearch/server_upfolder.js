var express = require('express');
var bodyParser = require('body-parser');
var cors = require('cors');
var https = require('https');
var geohash = require('ngeohash');
var path = require('path');

var app = express();
const port = 3000;

app.use(express.static(__dirname + '/public'));

var router = require('./backend/app');
app.use('/api', router);

app.listen(port, () => {
	console.log("Server started at port " + port);
});