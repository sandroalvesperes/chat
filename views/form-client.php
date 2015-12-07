<?
    $this->layout = 'chat';
    $this->js     = 'pages/form-client.js';
    $this->widget->enable('radio');
    $this->widget->enable('toast');
?>

<div class="form-client">
    
    <p>
        <span>E-mail:</span>
        <input type="text" name="email" id="email" maxlength="120" class="form-input" autofocus="" />
    </p>     
    
    <p>
        <span>Name:</span>
        <input type="text" name="name" id="name" maxlength="60" class="form-input" />
    </p>
    
    <p unselectable="on" class="unselectable">
        <span>Sex:</span>
        <input type="radio" name="sex" id="male" value="M" />
        <label for="male">Male</label> &nbsp; &nbsp; 
        
        <input type="radio" name="sex" id="female" value="F" />     
        <label for="female">Female</label>
    </p>
    
    <p>
        <span>Subject:</span>
        <input type="text" name="subject" id="subject" maxlength="45" class="form-input" />
    </p>
    
    <p>
        <button name="sign_in" id="sign_in" class="form-button"> Sign In </button>
    </p>       
    
</div>