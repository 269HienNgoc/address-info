</br>
</br>
<div class="noti"><p><?php echo $response ?? '' ?></p></div>
<form class="form-container" action="<?php echo admin_url('admin.php?page=hd-add-province') ?>" method="POST">
  <div class="form-header">NHẬP TỈNH/THÀNH PHỐ</div>
  <div class="form-group">
    <label for="nameProvince">Tên thành phố</label>
    <input type="text" id="nameProvince" name="nameProvince" placeholder="Nhập tên tỉnh thành..." required>
  </div>
  <button type="submit" name="submit-btn-province" class="submit-btn-province">Lưu</button>
</form>