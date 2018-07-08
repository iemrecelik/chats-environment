<div id="fr{{ userName }}" title="Private(Özel) {{ userName | replace(" ", "_") | capitalize }}">

    <div class="panel panel-primary">
        
        <div class="panel-body">
            <ul class="chat">
                
            </ul>
        </div>
        <div class="panel-footer">
            <div class="input-group">
                <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." 
                onkeypress="sendPrivateMsg('{{ userName }}', this, event)"/>
                <span class="input-group-btn">
                    <button class="btn btn-warning btn-sm" id="btn-chat">
                    Send</button>
                </span>
            </div>
        </div>
    </div>
</div>