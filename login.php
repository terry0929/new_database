<?php 
session_start();
include 'common/header.php'; ?>

<div class="login-container">
    <h2>🔐 登入系統</h2>
    <form action="login_check.php" method="post">
        <label>帳號：
            <input type="text" name="username" class="input-field">
        </label><br><br>
        <label>密碼：
            <input type="password" name="password" class="input-field">
        </label><br><br>
        <input type="submit" value="登入" class="submit-button">
    </form>
</div>

<?php include 'common/footer.php'; ?>

<style>
  /* 整個登入頁面居中顯示 */
  .login-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100vh;
    text-align: center;
    background-color: #f4f7fc; /* 頁面背景色 */
    padding: 20px;
  }

  h2 {
    font-size: 24px;
    margin-bottom: 20px;
    font-weight: bold;
    color: #333;
  }

  /* 輸入框樣式 */
  .input-field {
    width: 300px;
    padding: 12px;
    font-size: 16px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: inset 0 1px 5px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }

  .input-field:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 8px rgba(0, 255, 0, 0.2);
    outline: none;
  }

  /* 登入按鈕樣式 */
  .submit-button {
    width: 50%;
    padding: 12px;
    font-size: 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: block;
    margin: 20px auto; /* 置中對齊 */
  }

  .submit-button:hover {
    background-color: #45a049;
    transform: scale(1.05);
  }

  .submit-button:active {
    background-color: #3e8e41;
    transform: scale(1);
  }
</style>
