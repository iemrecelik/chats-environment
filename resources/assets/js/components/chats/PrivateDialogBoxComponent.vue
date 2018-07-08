<template>
<div :id="'fr' + idNickname" 
:title="`Private(Özel) ${titleNickname(user.nickname)}`">

  <div class="panel panel-primary">

    <div class="panel-body">
      <ul class="chat">
        
        <li 
          :class="`${position(message.selfMsg)} clearfix`"
          v-for="message in messages"
        >
          <div v-if="message.selfMsg">
            <span class="chat-img pull-right">
              <img alt="User Avatar" class="img-circle" 
                :src="getImageFiltUrl(message.user.path, '_4')"
              />
            </span>
            <div class="chat-body clearfix">
              <div class="header">
                  <small class=" text-muted">
                    <span class="glyphicon glyphicon-time"></span>
                    {{ message.time }}</small>
                  <strong class="pull-right primary-font">
                    {{ message.user.nickname }}
                  </strong>
              </div>
              <div class="pull-right">
                  <p>{{ message.msg }}</p>
              </div>
            </div>
          </div>

          <div v-else>
            <span class="chat-img pull-left">
              <img alt="User Avatar" class="img-circle"
                :src="getImageFiltUrl(message.user.path, '_4')"  
              />
            </span>
            <div class="chat-body clearfix">
              <div class="header">
                <strong class="primary-font">
                  {{ message.user.nickname }}
                </strong> 
                <small class="pull-right text-muted">
                    <span class="glyphicon glyphicon-time"></span>
                    {{ message.time }}
                </small>
              </div>
              <p>{{ message.msg }}</p>
            </div>
          </div>
          
        </li>
      </ul>
    </div>
    
    <div class="panel-footer">
      <div class="input-group input-group-sm">
        <input id="send-private-msg" type="text" class="form-control" 
          placeholder="Type your message here..."
          @keypress.enter="sendPrivateMsg(user.chatID, $event)"
        />
        
        <span class="input-group-btn ml-1">
          <button class="btn btn-primary" id="btn-chat"
            @click="sendPrivateMsg(user.chatID, $event, 'send-private-msg')"
          >
            Send
          </button>
        </span>
      </div>
    </div>

  </div>
</div>
</template>

<script>
import { createNamespacedHelpers } from 'vuex'

const { mapState, mapMutations, mapActions } = createNamespacedHelpers('chats')

export default {
  name: 'PrivateDialogBoxComponent',
  data () {
    return {
      user: this.ppuser,
    };
  },
  props: {
    ppuser: {
      type: Object, // String, Number, Boolean, Function, Object, Array
      required: true,
    }
  },
  methods: {
    ...mapActions([
      'delUsersInPrivateChats',
    ]),
    ...mapMutations([
      'addPrivateMessagesList',
    ]),
    sendPrivateMsg: function (chatID, event, takeMesInElement = null) {

      let element;
      if(takeMesInElement)
        element = document.getElementById(takeMesInElement);
      else
        element = event.target;

      this.socket.emit('sendPrivateMsg', {msg: element.value, chatID});

      element.value = '';
    },
    titleNickname: function(nickname){
      return _.capitalize(nickname);
    },
    position: function(selfMsg){
      return selfMsg ? 'right' : 'left';
    },
  },
  computed: {
    ...mapState([
      'socket',
      'privateMessagesList',
    ]),
    idNickname: function () {
      return this.user.nickname.replace(/[\s.]/, '-');
    },
    messages: function() {
      return this.privateMessagesList.filter((message) => {
        return message.chatID == this.user.chatID;
      });
    },
  },
  mounted(){
    console.log('idNickname', this.idNickname);
    $('#fr'+this.idNickname).dialog({
      minWidth: 415,
      minHeight: 302,
      dialogClass: 'auto-height chat-style',
      resize: function( event, ui ) {

          let hg = $(this).height();

          $(this).find('.panel-body').css('max-height', (hg-58)+'px');
         
      },
      create: function( event, ui ) {

          $(this).parent('.chat-style').prepend(`
              <div class="camera-preview" style="display:none">
                  <div class="camera-title-btns">
                      <button type="button" onclick="sendReqCamera()"
                      class="btn btn-primary btn-xs">
                          Send request camera
                      </button>
                      <button type="button" onclick="closeCamera(this)"
                      class="btn btn-primary btn-xs pull-right">
                          <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                      </button>
                  </div>
                  <div class="myself-camera">
                      <video autoplay></video>
                  </div> 
                  <div class="itself-camera">
                      <video autoplay></video>
                  </div> 
              </div>
          `);

          $(this).find('div.panel-body').bind("DOMSubtreeModified",function(){

              $(this).animate({scrollTop: $(this).prop("scrollHeight")}, 500);        
          });

          $(this).parent('.chat-style')
              .find('.ui-dialog-titlebar')
              .prepend('<span class="pull-left"><i class="icon ion-chatboxes"></i></span> ');
              /*.prepend('<span class="glyphicon glyphicon-comment pull-left"></span> ');*/

          $(this).parent('.chat-style').find('.ui-dialog-titlebar .ui-dialog-title').addClass('pull-left');

          let dropdownHtml = `
              <div class="btn-group chat-minimal-menu pull-right">
                  <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                      <span class="glyphicon glyphicon-chevron-down"></span>
                  </button>
                  <ul class="dropdown-menu slidedown">
                      <li onclick="showCamera(this)">
                          <a href="javascript:void(0)"><span class="glyphicon glyphicon-facetime-video">
                          </span> Camera</a>
                      </li>
                      <li onclick="refreshConn()">
                          <a href="javascript:void(0)"><span class="glyphicon glyphicon-refresh">
                          </span> Refresh</a>
                      </li>
                      <li class="divider"></li>
                      <li onclick="">
                          <a href="javascript:void(0)"><span class="glyphicon glyphicon-off">
                          </span> Sign Out</a>
                      </li>
                  </ul>
              </div>`

          /*$(this).parent('.chat-style').find('.ui-dialog-titlebar .ui-dialog-titlebar-close').before(dropdownHtml);*/
      },
      close: ( event, ui ) => {
          // $(this).dialog("destroy");
          $(event.target).dialog("destroy");
          this.delUsersInPrivateChats(this.user);
          // $(this).remove();
          
          /*if(peer[userName] !== undefined)
              peer[userName].peer.destroy();*/
      }
    });
  }
}
</script>