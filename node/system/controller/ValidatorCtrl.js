const validator = require('validator');

module.exports = class ValidatorCtrl{
	
	constructor() {	

		this.validInfo = {
			'errorSt': false,
			'rawVal': {},
			'val': {},
			'error': {},
		};
		this.schema;
		this.reqParams;
	}

	getObjValidator(){
		return validator;
	}

	valid(schema, reqParams){

		// console.log(schema);
		// console.log(reqParams);
		
		this.schema = schema;
		this.reqParams = reqParams;

		for(let key in this.schema){

			if(reqParams[key] === undefined)
				throw new Error(`${reqParams[key]} : this parameter is not defined`);
			else
				this.runValid(key);
		}

		/*console.log('VALIDINFO ERRORST: ', this.validInfo['errorSt']);
		console.log('VALIDINFO RAW: ', this.validInfo['rawVal']);
		console.log('VALIDINFO VAL: ', this.validInfo['val']);
		console.log('VALIDINFO ERROR: ', this.validInfo['error']);*/

		return this.validInfo;
	}

	runValid(key){

		let error;
		let valid = this.schema[key]['valid'];
		let params = this.schema[key]['params'];
		let sanitizer = this.schema[key]['sanitizer'] || null;

		if(!(params instanceof Array) && params != null)
			throw new Error(`The params of ${key} must be type Array`);

		if( params == null ){
			error = validator[valid](this.reqParams[key]);
		}else{
			error = validator[valid](this.reqParams[key], ...params);
		}

		if(error){
			
			let filtVal = this.filterInput(key, this.reqParams[key], sanitizer);

			this.validInfo['rawVal'][key] = this.reqParams[key];
			this.validInfo['val'][key] = filtVal;

		}else{

			this.validInfo['errorSt'] = true;
			this.validInfo['error'][key] = this.schema[key]['error'];
			this.validInfo['rawVal'][key] = this.reqParams[key];
		}
	}

	filterInput(key, inputVal, sanitizer = null){
		
		if(!(sanitizer instanceof Array) && sanitizer != null){

			throw new Error(`The sanitizer of ${key} must be type Array`);

		}else if(sanitizer === null){

			inputVal = validator.trim(inputVal);
			inputVal = validator.escape(inputVal);
		}else{
			for(let sanitVal of sanitizer){

				if (sanitVal.params instanceof Array)
					inputVal = validator[sanitVal.func](inputVal, ...sanitVal.params);
				else
					inputVal = validator[sanitVal.func](inputVal);
			}
		}
		
		return inputVal;
	}


}