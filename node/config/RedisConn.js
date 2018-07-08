const redisConf = require('./configParameters').redis
	, bluebird = require("bluebird")
	, redis = require("redis")
	, client = redis.createClient(redisConf);

bluebird.promisifyAll(redis.RedisClient.prototype);
bluebird.promisifyAll(redis.Multi.prototype);

module.exports = client;