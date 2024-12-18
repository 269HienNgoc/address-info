</br>
</br>
<div class="noti"><p><?php echo $response ?? '' ?></p></div>
<form class="form-container" action="<?php echo admin_url('admin.php?page=hd-add-address') ?>" method="post">
  <div class="form-header">NHẬP THÔNG TIN ĐỊA CHỈ CHI NHÁNH</div>
  <div class="form-group">
    <label for="avatarAddress">Ảnh chinh nhánh</label>
    <img id="preview_avatarAddress" class="preview_avatarAddress" src="#" alt="">
    <input type="text" id="avatarAddress" name="avatarAddress" readonly>
    <button class="hd-btn" type="button" id="btn-upload-avatarAddress">Chọn ảnh...</button>
  </div>
  <div class="form-group">
    <label for="nameAddress">Tên chi nhánh</label>
    <input type="text" id="nameAddress" name="nameAddress" placeholder="Nhập tên chi nhánh..." required>
  </div>
  <div class="form-group">
    <label for="address_">Nhập địa chỉ</label>
    <input type="text" id="address_" name="address_" placeholder="Nhập địa chỉ..." required>
  </div>
  <div class="form-group">
    <label for="province">Thành phố</label>
    <select id="province" name="province" required>
      <option>Chọn thành phố</option>
      <?php foreach($province as $val){ ?>
      <option value="<?= $val->id ?>"><?= $val->province_name ?></option>
      <?php } ?>
    </select>
  </div>
  <div class="form-group">
    <label for="maps">Link Google Maps</label>
    <input type="text" id="maps" name="maps" placeholder="Nhập link Google Maps..." required>
  </div>
  <div class="form-group">
    <label for="phone">Điện thoại</label>
    <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại...." required>
  </div>
  <button type="submit" name="submit-btn" class="submit-btn">Lưu</button>
</form>