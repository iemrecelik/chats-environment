export default {
  methods: {
    getQueryParameters: function(name){
      name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
      var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
      var results = regex.exec(location.search);
      return results === null ? 
      '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    },

    isActiveTab: function(tabName){
      return tabName === this.activeTab?'active show':'';
    },

    getImageFiltUrl: function(url = '', filt = 'raw'){
      
      if(url.indexOf('img/') < 0 && url) 
        url = `/storage/upload/imgs/${filt}/${url}`;

      return url;
    },

    convertTime(loginTime){
      if(!loginTime) return '';

      let nowTime = new Date().getTime();
      let onlineTime = nowTime - loginTime;

      if (onlineTime < 60000) {
        return 'NEW';

      } else if(onlineTime < 3600000 ) {

        return 'before '+Math.floor( (onlineTime/60000) )+' minute';

      }else if(onlineTime < 86400000){

        return 'before '+Math.floor( (onlineTime/3600000) )+' hour';
      }else
        return 'before '+Math.floor( (onlineTime/86400000) )+' day';

    }
  },

  computed: {
    activeTab: function () {
      return this.$store.state.chats.activeTab;
    }
  },

  filters: {
    capitalize: function (value) {
      if (!value) return ''
        console.log(value);
      value = value.toString()
      return value.charAt(0).toUpperCase() + value.slice(1)
    }
  }
}