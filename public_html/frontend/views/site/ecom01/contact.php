<div class="container">
<div class="row f12e">
   
 
  <form action="" data-action="sajax" class="sys_contact_01" onsubmit="return ajaxSubmitForm(this);" method="POST">
    <div class="col-lg-6">
<?php 
echo '<input type="hidden" name="_csrf-frontend" value="'. Yii::$app->request->csrfToken.'" /> ';
echo '<input type="hidden" name="action" value="send_contact_request" /> ';
echo '<div class="form-group">
        <label class="aleft fl100" for="InputName">Họ và tên</label>
         
          <input type="text" class="form-control required" name="f[fname]" id="InputName" placeholder="Enter Name" required>
           
      </div>';
echo '<div class="form-group">
        <label class="aleft fl100" for="InputName">Email</label>
     
          <input type="email" class="form-control required" name="f[email]" id="InputEmail" placeholder="Enter Email" required>
      
      </div>';
echo '<div class="form-group">
        <label class="aleft fl100" for="InputName">Số điện thoại</label>
   
          <input type="text" class="form-control" name="f[phone]" id="InputPhone" placeholder="Enter Phone number" />

      </div>';
echo '<div class="form-group">
        <label class="fll100 aleft" for="InputMessage">Nội dung liên hệ</label>
         
          <textarea name="InputMessage" id="InputMessage" class="form-control required" rows="5" required></textarea>
           
      </div>';
?> 
 
 
      <button type="submit" name="submit" id="submit" class="btn btn-info pull-right"><i class="glyphicon glyphicon-send"></i> Gửi liên hệ</button>
    </div>
  </form>
  <hr class="featurette-divider hidden-lg">
  <div class="col-lg-5 col-md-push-1">
    <address>
     <?php 
     $b = Yii::$app->zii->getBox('text-contact-form');
     if(!empty($b)){
     	echo uh($b['text'],2);
     }
     ?>
    </address>
  </div>
</div>

</div>