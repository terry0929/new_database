<?php session_start(); include 'common/header.php'; ?>

<div class="page-content">
  <div class="login-container">
      <h2>🔒 登入系統</h2>

      <div class="tab-buttons">
          <button class="tab-button active" onclick="switchTab('student')">🧑‍🎓 學生登入</button>
          <button class="tab-button" onclick="switchTab('teacher')">👨‍🏫 教師登入</button>
      </div>

      <form id="login-form" action="login_check.php" method="post">
          <input type="hidden" name="role" id="role" value="student">

          <label>帳號：
              <input type="text" name="username" required>
          </label><br><br>
          <label>密碼：
              <input type="password" name="password" required>
          </label><br><br>
          <input type="submit" value="登入" class="submit-button">
      </form>
  </div>
</div>

<?php include 'common/footer.php'; ?>

<style>

  .login-container {
      width: 360px;
      margin: 0 auto;
      padding: 40x 30px;
      background: #f5faff; /* ✅ 淡藍色區塊，比 #f0f6ff 更淡 */
      text-align: center;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
  }

  .tab-buttons {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
  }
  .tab-button {
      border: none;
      background: none;
      font-size: 16px;
      padding: 8px 20px;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.3s;
  }
  .tab-button.active {
      border-bottom: 2px solid green;
      color: green;
      font-weight: bold;
  }
  input[type="text"], input[type="password"] {
      width: 80%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 6px;
      border: 1px solid #ccc;
  }
  .submit-button {
      margin-top: 15px;
      padding: 10px 40px;
      background-color: #4CAF50;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
  }
</style>

<script>
function switchTab(role) {
    document.getElementById('role').value = role;

    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(btn => btn.classList.remove('active'));

    if (role === 'student') {
        buttons[0].classList.add('active');
    } else {
        buttons[1].classList.add('active');
    }
}
</script>
