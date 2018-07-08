const configPar = require('./configParameters');
const mysql      = require('mysql');

/*connection.config.queryFormat = function (query, values) {
  if (!values) return query;
  return query.replace(/\:(\w+)/g, function (txt, key) {
    if (values.hasOwnProperty(key)) {
      return this.escape(values[key]);
    }
    return txt;
  }.bind(this));
};*/

/*connection.connect(function(err) {
  if (err) {
    console.error('error connecting: ' + err.stack);
    return;
  }
  
  console.log('connected as id ' + connection.threadId);
});*/

module.exports = class mysqlConn{

  constructor(){
    this.connection;
  }

  connect(){
    
    this.connection = mysql.createConnection(configPar.mysqlDB);
    this.config();

    this.connection.connect((err) => {
      if (err) {
        console.error('error connecting: ' + err.stack);
        return;
      }
    });    
  }

  config(){
    this.connection.config.queryFormat = function (query, values) {
      if (!values) return query;
      return query.replace(/\:(\w+)/g, function (txt, key) {
        if (values.hasOwnProperty(key)) {
          return this.escape(values[key]);
        }
        return txt;
      }.bind(this));
    };
  }

  disconnect(){
    this.connection.end();
  }

  queryOn(query, data = null){
    this.connect();
    let res = this.connection.query(query, data);
    this.disconnect();
    return res;
  }

  async query(query, data = null){
    
    this.connect();
    
    let res = await new Promise( (resolve, reject) => {
      
      this.connection.query(query, data, (error, results, fields) => {
        resolve({error, results, fields});
      });
    });
    
    this.disconnect();

    return res;
  }
}