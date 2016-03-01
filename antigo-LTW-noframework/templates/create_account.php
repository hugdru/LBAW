<section class="header-form animated-form">
  <h2 class="form-title"><a href="#create-form">Create a New Account</a></h2>
  <form id="create-form" action="action_create_account.php" method="post" >
    <label>Name
      <input type="text" name="name" value="" placeholder="Full Name">
    </label>
    <label>Username
      <input type="text" name="username" value="" placeholder="Username">
    </label>
    <label>Password
      <input id="password" type="password" name="password" value="" placeholder="Password">
    </label>
    <label>Confirm Password
      <input id="confirm_password" type="password" name="confirm_password" value="" placeholder="Password">
    </label>
    <input class="button" type="submit" value="Create Account">
  </form>
</section>
<section class="header-form animated-form">
  <h2 class="form-title"><a href="#login-form">Login with an existing Account</a></h2>
  <form id="login-form" action="action_login.php" method="post" >
    <label>Username
      <input type="text" name="username" required="required" value="" placeholder="Username">
    </label>
    <label>Password
      <input type="password" name="password" required="required" value="" placeholder="Password">
    </label>
    <input class="button" type="submit" value="Login">
  </form>
</section>
