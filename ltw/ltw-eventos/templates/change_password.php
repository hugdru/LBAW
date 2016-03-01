<form action="action_change_password.php" method="post">
  <label>Old password:
    <input type="password" name="password" required="required" value="">
  </label>
  <label>New password:
    <input type="password" name="passwordNew" onInput="validatePassword(this)" required="required" value="">
  </label>
  <label>Confirm new password:
    <input type="password" name="passwordNewConfirm" onInput="validatePassword(this)" required="required" value="">
  </label>
  <input type="submit" value="Change password">
</form>
