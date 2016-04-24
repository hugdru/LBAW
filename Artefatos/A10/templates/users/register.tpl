<!DOCTYPE html>
<html>
    <head>
        {include file='common/head.tpl'}
        <link rel="stylesheet" href="../../css/home.css">
    </head>
    <body>
	{include file='common/navbar.tpl'}
	{include file='common/content-top.tpl'}     
        <!-- Content Start -->
        

        <div class="accountfield">
            <h1>Register Account</h1>           

            <form role="form" action="{$BASE_URL}actions/users/register.php" method="post" enctype="multipart/form-data">
		        <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name">
                </div>
		
                <div class="form-group">
                    <label for="usr">Username</label>
                    <input type="text" class="form-control" id="username">
                </div>
                
                <div class="form-group">
                    <label for="eml">Email Address</label>
                    <input type="email" class="form-control" id="email">
                </div>
                
                <div class="form-group">
                    <label for="pwd1">Password</label>
                    <input type="password" class="form-control" id="password">
                </div>
                
                <div class="form-group">
                    <label for="pwd2">Password (Repeat)</label>
                    <input type="password" class="form-control" id="passwordRepeat">
                </div>
		
		<div class="form-group">
                    <label for="file">Profile photo</label>
                    <input type="file" name="file" placeholder="Optional">
                </div>

		<div class="form-group">
  			<label for="country">Your country:</label>
  			<select class="form-control" id="pais">
    				<option>1</option>
    				<option>2</option>
				<option>3</option>
    				<option>4</option>
  			</select>
		</div>
                
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>

        <!-- Content Finish -->
	{include file = 'common/content-bottom.tpl'}
    </body>
</html>


